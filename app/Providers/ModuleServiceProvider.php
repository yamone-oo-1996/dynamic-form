<?php

namespace App\Providers;

use App\Services\FormService;
use App\Services\ContractService;
use App\Services\SalesOrderService;
use Illuminate\Support\ServiceProvider;
use App\Repositories\FormTypeRepository;
use App\Repositories\FormGroupRepository;
use App\Repositories\FormConfigRepository;
use App\Repositories\ServiceDataRepository;
use App\Repositories\FormElementRepository;
use App\Repositories\DataTransferTrackingRepository;
use App\Contracts\Services\FormService as FormServiceContract;
use App\Contracts\Services\ContractService as ContractServiceContract;
use App\Contracts\Services\SalesOrderService as SalesOrderServiceContract;
use App\Contracts\Repositories\FormTypeRepository as FormTypeRepositoryContract;
use App\Contracts\Repositories\FormGroupRepository as FormGroupRepositoryContract;
use App\Contracts\Repositories\FormConfigRepository as FormConfigRepositoryContract;
use App\Contracts\Repositories\ServiceDataRepository as ServiceDataRepositoryContract;
use App\Contracts\Repositories\FormElementRepository as FormElementRepositoryContract;
use App\Contracts\Repositories\DataTransferTrackingRepository as DataTransferTrackingRepositoryContract;

class ModuleServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadRoutesFrom(base_path('routes/dynamic-form.php'));
        $this->loadRoutesFrom(base_path('routes/service.php'));
    }

    public function register(): void
    {
        $this->loadServices();
        $this->loadRepositories();
    }

    public function loadServices(): void
    {
        $this->app->bind(FormServiceContract::class, FormService::class);
        $this->app->bind(ContractServiceContract::class, ContractService::class);
        $this->app->bind(SalesOrderServiceContract::class, SalesOrderService::class);
    }

    public function loadRepositories(): void
    {
        $this->app->bind(FormElementRepositoryContract::class, FormElementRepository::class);
        $this->app->bind(FormTypeRepositoryContract::class, FormTypeRepository::class);
        $this->app->bind(FormGroupRepositoryContract::class, FormGroupRepository::class);
        $this->app->bind(FormConfigRepositoryContract::class, FormConfigRepository::class);
        $this->app->bind(DataTransferTrackingRepositoryContract::class, DataTransferTrackingRepository::class);
        $this->app->bind(ServiceDataRepositoryContract::class, ServiceDataRepository::class);
    }
}
