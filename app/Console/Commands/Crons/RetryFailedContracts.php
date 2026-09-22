<?php

namespace App\Console\Commands\Crons;

use App\Enums\ServiceReference;
use Illuminate\Console\Command;
use App\Contracts\Services\ContractService;
use App\Contracts\Repositories\DataTransferTrackingRepository;

class RetryFailedContracts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'retry:failed-contracts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Handles retry logic for contracts that failed during earlier execution.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        private ContractService $contractService,
        private DataTransferTrackingRepository $dataTransferTrackingRepository,
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $serviceRefId = ServiceReference::CONTRACT->value;

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
                $this->info("Successfully processed failed contract for POI NUMBER : {$tracking->reference_id}");
            } catch (\Exception $e) {
                $this->error("Failed to process contract ID: {$tracking->id}. Error: {$e->getMessage()}");
            }
        }

        $this->info('Retry process completed.');
        return Command::SUCCESS;
    }
}
