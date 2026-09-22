<?php

namespace App\Console\Commands\Crons;

use Exception;
use App\Enums\ServiceReference;
use Illuminate\Console\Command;
use App\Contracts\Repositories\DataTransferTrackingRepository;
use App\Contracts\Services\ContractService;

class RetryFailedLanVoucher extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'retry:failed-lan-vouchers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process or retry failed LAN Voucher cases.';

    public function __construct(
        private ContractService $contractService,
        private DataTransferTrackingRepository $dataTransferTrackingRepository,
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $serviceRefId = ServiceReference::LAN_VOUCHER->value;

        // Get Process Failed Data Transfer Tracking
        $dataTransferTracking = $this->dataTransferTrackingRepository->getFailedListByServiceRef($serviceRefId, false);

        if ($dataTransferTracking->isEmpty()) {
            // Display a warning message in the command line
            $this->warn("No failed contracts found for service reference ID: $serviceRefId.");
            return Command::SUCCESS;
        }

        // Process the failed contracts
        foreach ($dataTransferTracking as $tracking) {
            try {
                $data = json_decode($tracking['data'], true);
                $this->contractService->recreateContractProcess($data, $tracking);
                $this->info("Successfully processed failed LAN voucher for id : {$tracking->reference_id}");
            } catch (Exception $e) {
                $this->error("Failed to process LAN voucher ID: {$tracking->reference_id}. Error: {$e->getMessage()}");
            }
        }

        $this->info('Retry process completed.');
        return Command::SUCCESS;
    }
}
