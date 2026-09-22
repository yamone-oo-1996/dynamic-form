<?php

namespace Tests\Unit;

use Tests\CreatesApplication;
use App\Contracts\Services\FormService;
use App\Exceptions\DataNotFoundException;
use Illuminate\Foundation\Testing\{TestCase, DatabaseTransactions};

class FormServiceTest extends TestCase
{
    use CreatesApplication;
    use DatabaseTransactions;

    protected $service = null;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = app(FormService::class);
    }

    public function testGetFormReturnsValidResult()
    {
        $formTypeId = 1;
        $result = $this->service->getForm($formTypeId);
        $this->assertNotEmpty($result);
    }

    public function testGetFormThrowsExceptionWhenFormNotFound()
    {
        $this->expectException(DataNotFoundException::class);
        $formTypeId = 123;
        $this->service->getForm($formTypeId);
    }

    public function testGetFormDiffReturnsValidResult()
    {
        $formTypeId = 7;
        $diffFormType = 21;
        $result = $this->service->getFormDiff($formTypeId, $diffFormType);
        $this->assertNotEmpty($result);
    }

    public function testGetFormDiffThrowsExceptionWhenFormNotFound()
    {
        $this->expectException(DataNotFoundException::class);
        $formTypeId = 123;
        $diffFormType = 456;
        $this->service->getFormDiff($formTypeId, $diffFormType);
    }
}
