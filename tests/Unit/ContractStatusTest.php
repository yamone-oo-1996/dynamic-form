<?php

namespace Tests\Unit;

use App\Contracts\Repositories\DataTransferTrackingRepository;
use App\Exceptions\DataNotFoundException;
use App\Services\ContractService;
use App\Services\FormService;
use App\Services\SalesOrderService;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ContractStatusTest extends TestCase
{
    private function makeService($repository): ContractService
    {
        $formService = $this->createMock(FormService::class);
        $formService->expects($this->once())->method('setLang')->with('mm');
        return new ContractService($repository, $formService, $this->createMock(SalesOrderService::class));
    }

    public static function statuses(): array
    {
        return [
            ['CONTRACT', 1, '0', 0],
            ['CONTRACT', 1, '1', 1],
            ['LAN_VOUCHER', 2, '0', 0],
            ['LAN_VOUCHER', 2, '1', 1],
        ];
    }

    #[DataProvider('statuses')]
    public function testReturnsOnlyReferenceAndProcessingInformation(
        $refType,
        $serviceId,
        $storedFlag,
        $expectedFlag
    ): void {
        $repository = $this->createMock(DataTransferTrackingRepository::class);
        $repository->expects($this->once())->method('getByRef')->with('123', $serviceId)->willReturn([
            'reference_id' => '123',
            'is_processed' => $storedFlag,
            'data' => '{"digital_sign":"private","full_name":"Customer"}',
            'status' => 0,
        ]);
        $this->assertSame([
            'ref_id' => '123',
            'ref_type' => $refType,
            'is_processed' => $expectedFlag,
        ], $this->makeService($repository)->getContractStatus('123', $refType));
    }

    public function testMissingRecordThrowsNotFound(): void
    {
        $repository = $this->createMock(DataTransferTrackingRepository::class);
        $repository->expects($this->once())->method('getByRef')->with('missing', 1)->willReturn(null);
        $this->expectException(DataNotFoundException::class);
        $this->makeService($repository)->getContractStatus('missing', 'CONTRACT');
    }

    public function testUnknownReferenceTypeDoesNotQueryRepository(): void
    {
        $repository = $this->createMock(DataTransferTrackingRepository::class);
        $repository->expects($this->never())->method('getByRef');
        $this->expectException(DataNotFoundException::class);
        $this->makeService($repository)->getContractStatus('123', 'UNKNOWN');
    }
}
