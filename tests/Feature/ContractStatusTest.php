<?php

namespace Tests\Feature;

use App\Contracts\Repositories\DataTransferTrackingRepository;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class ContractStatusTest extends TestCase
{
    private const ENDPOINT = '/dynamic_form/api/v1/service/contracts/status';

    protected function setUp(): void
    {
        parent::setUp();
        config(['setting.api.client-header' => 'status-test-client']);
        $this->withHeaders(['X-FRONTIIR-CLIENT' => 'status-test-client']);
    }

    private function bindRepository($refId = null, $serviceId = null, $result = null): void
    {
        $repository = $this->createMock(DataTransferTrackingRepository::class);
        if ($refId === null) {
            $repository->expects($this->never())->method('getByRef');
        } else {
            $repository->expects($this->once())->method('getByRef')->with($refId, $serviceId)->willReturn($result);
        }
        $this->app->instance(DataTransferTrackingRepository::class, $repository);
    }

    public function testReturnsStatusWithoutCustomerData(): void
    {
        $this->bindRepository('voucher-123', 2, [
            'reference_id' => 'voucher-123',
            'is_processed' => '1',
            'data' => '{"digital_sign":"private"}',
        ]);
        $this->getJson(self::ENDPOINT . '?ref_id=voucher-123&ref_type=LAN_VOUCHER')
            ->assertOk()->assertExactJson([
                'status' => 200,
                'data' => ['ref_id' => 'voucher-123', 'ref_type' => 'LAN_VOUCHER', 'is_processed' => 1],
                'error' => ['message' => ''],
            ]);
    }

    public static function invalidQueries(): array
    {
        return [
            [''],
            ['?ref_id=123'],
            ['?ref_type=CONTRACT'],
            ['?ref_id=&ref_type=CONTRACT'],
            ['?ref_id[]=123&ref_type=CONTRACT'],
            ['?ref_id=123&ref_type[]=CONTRACT'],
        ];
    }

    #[DataProvider('invalidQueries')]
    public function testRejectsMissingOrMalformedParameters($query): void
    {
        $this->bindRepository();
        $this->getJson(self::ENDPOINT . $query)->assertForbidden()
            ->assertJsonPath('error.message', 'Missing or invalid parameters');
    }

    public function testReturnsNotFoundForUnknownType(): void
    {
        $this->bindRepository();
        $this->getJson(self::ENDPOINT . '?ref_id=123&ref_type=UNKNOWN')->assertNotFound()
            ->assertJsonPath('error.message', 'Invalid reference type: UNKNOWN');
    }

    public function testReturnsNotFoundForMissingRecord(): void
    {
        $this->bindRepository('missing', 1);
        $this->getJson(self::ENDPOINT . '?ref_id=missing&ref_type=CONTRACT')->assertNotFound()
            ->assertJsonPath('error.message', 'Contract data not found!');
    }

    public function testRequiresClientAuthentication(): void
    {
        $this->bindRepository();
        $this->withHeaders(['X-FRONTIIR-CLIENT' => 'wrong-client'])
            ->getJson(self::ENDPOINT . '?ref_id=123&ref_type=CONTRACT')->assertUnauthorized();
    }
}
