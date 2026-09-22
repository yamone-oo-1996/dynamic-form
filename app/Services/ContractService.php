<?php

namespace App\Services;

use Exception;
use Carbon\Carbon;
use App\Enums\RecordStatus;
use App\Services\Kafka\Publisher;
use Illuminate\Support\Facades\Log;
use App\Exceptions\DataNotFoundException;
use App\Exceptions\DataSaveFailedException;
use App\Exceptions\DataAlreadyCreatedException;
use App\Contracts\Repositories\DataTransferTrackingRepository;
use App\Contracts\Services\ContractService as ContractServiceContract;
use App\Enums\ServiceReference;

class ContractService implements ContractServiceContract
{
    protected $lang = 'en';
    protected $formCache = [];

    public function __construct(
        private DataTransferTrackingRepository $dataTransferTrackingRepository,
        private FormService $formService,
        private SalesOrderService $salesOrderService,
    ) {
        $this->formService->setLang("mm");
    }

    const PDF_GENERATOR_KAFKA_CONFIG = 'miko_pdf_generator_topic';

    public function processDigitalContract($formTypeId, $data)
    {
        try {
            $formDetail = $this->formService->getFormTypeDetail($formTypeId);
            if (empty($formDetail)) {
                throw new Exception("Form type not found!");
            }
            $form = $this->formService->getForm($formTypeId);
            $data['today_date'] = Carbon::now()->format("d/m/Y");
            $data['form_type_id'] = $formTypeId;

            # Create Logo
            $data['logo'] = $this->getLogo($formTypeId);
            $data['contact_footer'] = $this->getCustomerServiceContact($formTypeId);

            # Create Data Tracking
            $tracking = $this->dataTransferTrackingRepository->create($data, $formDetail['service_reference_id']);
            if (!$tracking) {
                throw new DataAlreadyCreatedException("Data already created for reference_id: {$data['ref_id']}");
            }

            # Contract Process
            $this->contractProcess($formDetail, $data, $form, $tracking);

            # Define Sales Orders
            if ($formDetail['service_reference_id'] == ServiceReference::LAN_VOUCHER->value) {
                $this->saveSalesOrderServiceData($data, $formDetail['service_reference_id']);
            }
        } catch (Exception | DataAlreadyCreatedException $e) {
            throw $e;
        }
    }

    protected function contractProcess($formDetail, &$data, &$form, $tracking)
    {
        # Generate HTML String
        $htmlString = $this->renderHtmlView($formDetail, $data, $form);

        # Send to Kafka
        $refType = $this->getReferenceType($formDetail['service_reference_id']);
        $this->createKafkaMessage($htmlString, $data, $refType, $formDetail['system_name']);

        # Update transfer tracking
        $this->dataTransferTrackingRepository->updateProcessStatus($tracking['id'], 1);
    }

    protected function renderHtmlView($formDetail, &$data, $form)
    {
        Log::debug("Rendering HTML View ...");
        try {
            # Create check box icon
            $this->createCheckBoxIcon($data);

            # Render the Blade view with the data and capture the output as an HTML string
            $htmlFile = ($formDetail['service_reference_id'] == ServiceReference::LAN_VOUCHER->value) ? 'lanVoucherHtml' : 'contractHtml';
            $htmlString = view($htmlFile, ['form' => $form, 'data' => $data])->render();

            if (empty($htmlString)) {
                throw new DataSaveFailedException("Can't create html string!");
            }
            return $htmlString;
        } catch (Exception $e) {
            throw new DataSaveFailedException("Failed to create HTML view! Message >>> " . $e->getMessage());
        }
    }

    protected function createKafkaMessage($htmlString, $data, $refType, $systemName)
    {
        try {
            # Send html string to kafka topic
            $kafkaHeader = [
                "ref_id" => $data['ref_id'],
                "ref_type" => $refType,
                "service" => $systemName,
                "voucher_id" => $data['voucher_id'] ?? ''
            ];
            $kafkaMessage = ['html' => $htmlString];
            (new Publisher())->sendToKafka($kafkaMessage, self::PDF_GENERATOR_KAFKA_CONFIG, $kafkaHeader);
        } catch (Exception $e) {
            throw new DataSaveFailedException("Failed to send kafka! Message >>> " . $e->getMessage());
        }
    }

    protected function getLogo($formTypeId)
    {
        $filePath = '';
        $logo = config('dynamicform.contract.logo');
        if (!empty($logo)) {
            $file = $logo[$formTypeId] ?? '';
            if ($file) {
                $filePath = public_path($file);
            }
        }
        return $filePath;
    }

    protected function getCustomerServiceContact($formTypeId)
    {
        $formConfigs = $this->formService->getFormConfigs($formTypeId)->pluck('value', 'attribute_name')->all();
        $contactFooter = $formConfigs['customer_service_contact'] ?? '';
        return $contactFooter;
    }

    protected function createCheckBoxIcon(&$data)
    {
        $data['checked_icon'] = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAYAAACqaXHeAAAAAXNSR0IArs4c6QAAAvxJREFUeF7tms+nVkEYxz9XFFFEi9QiorRoESmKKEURLVpU9INEKpGoSPolkhKJfhCJKInWRSlatEit0x/Qrk1tWkTd55o3rzHnzDMz57135rxzeF3ve+Y85/v9zDNzzp15JhjzY2LM/VMB1AwYcwJ1CIx5AtRJsA6BiCEwD1gDbIq4dpSXvAc+A79CbhKaAUeBC8DikJtMY9vvwFXggfaeIQC2AG+0gWe43VbgrUaDFsB84BOwQhM0gzbfgLXAT58WLYBtwCtfsMzObwde+zRpAVwGLlnBrviCB5z/C1OP5OG/g8sHGgfnBr/b3136RHfrkQrAewOfgI7ON3WQV18FoOyBaMLK+KnNovXVDFCijyasjJ/aLFpfzQAl+mjCyvipzaL1lZABq4DZwJcWSr0FsHry9fupAXAI+NAAoZcA5F/uZ8ByY/oHsL/hlbx3ANYZ88usHv8D7AFeWr/3CsAGk/ZLHen+HNjr+L03ADaanl/iMClzwb4+zwGbTc8vcph8Ahws7SlwAPgNvFA85GUFRya8hY62jwF5ArQd2Q2B48Bdo3i3B4Istoj5BQ6Hj4DDCoBZATgD3LBEN0HYYdJeltzs4yFwRGFemmQDwCVk4MGGsNP0/FyHSVnVPaY0nxWAJjE2hF2m5+c4TMrQORFgPjsAGggy5mc5TN4BTgaazxKAD4LL423gVIT5bAGEQLgFnI40nzUADYSbwNkE89kDaINwHTiXaL4IAC6R14DzHZgvBsCwUNnBvdiR+aIAiFjZwupyW604AB12/P9Q2bwKj8KcJmYF0LB7XTdHfelTwr6Az0OdBCcJ1DlgpuYATXq62tjlLbFxhq8baYmMFEW+60LlNMaQVWYpnmw9tJOgVId+zbhA0jYpBZMrNVWjWgByA6kSve8jmsl5WU9UVYuGABBvUi16L+OCSSmQlCV5VZWoGAoFINfIEvZ688mkw6dkfDQfb3XosOgYADmZTtZSASQjLDxAzYDCOzBZfs2AZISFB6gZUHgHJsv/B6eq4UEqpTlWAAAAAElFTkSuQmCC';
        $data['unchecked_icon'] = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAYAAACqaXHeAAAAAXNSR0IArs4c6QAAAiBJREFUeF7tmkEuBFEYhGsOwAEcQOzFwo6QcBTcQRB3wFFIyNhZiL04gANwALzJdDJpZl69+mfG63R1MhG6Xr16X1ePzT9Az69Bz88PA3ADek7Ar0DPC+AvQb8CwiuwAmATwI6wdpFLHgG8APgs2aS0AUcATgGslWyyRO07gEsAN+yeJQD2ANyzxv+s2wfwwGRgAawCeAawzphWoHkDsAXgI5eFBXAA4DZnVtn9QwB3uUwsgHMAZy2zi5x5wf0vYPQvefJns7zJ2Nxr/t7+/a98KffMKwogu0EuwJzuT3tA2XwGQD4BmTDpH5XJ+dwAEr1MmPSPyuR8bgCJXiZM+kdlcj43gEQvEyb9ozI5nxtAopcJk/5RmZzPDSDRy4RJ/6hMzucGkOhlwqR/VCbncwNI9DJh0j8qk/O5ASR6mTDpH5XJ+dwAEr1MmPSPyuR8bgCJXiZM+kdlcj43gEQvEyb9ozI5nxtAopcJk/5RmZzPDSDRy4RJ/6hMzucGkOhlwqR/VCbncwNI9DJh0j8qk/O5AST6aYTJ5b9k7fEW1Wdy3UJHZNJQ5HAeKZfosfszzJmGJ2de7CuQpkNfKx6QbB8yDUxuMFOjLIC0QZoSvc4RreT+MTstWgIgnS1Ni15VPDCZBiRP2CnRdKBSAGlNmhrdHn8qeeCjGE/jT3Y6dDK0AqCmQ4ezGEAYYccN3ICOP8BwfDcgjLDjBm5Axx9gOP43UCCuQTT/VUgAAAAASUVORK5CYII=';
    }

    protected function saveSalesOrderServiceData(&$data, $serviceRefId)
    {
        $voucherId = data_get($data, 'voucher_id');
        if (empty($voucherId)) {
            Log::warning("Voucher ID is missing in the data. Skipping Sales Order creation.");
            return;
        }
        Log::info("Creating Sales Order for Ref ID: " . $voucherId);
        if (!empty($data['service_items'])) {
            $filteredItems = array_map(function ($item) {
                return [
                    'product_code' => $item['product_code'],
                    'qty' => $item['qty'],
                ];
            }, data_get($data, 'service_items.items', []));
            $serviceData['sales_order']['items'] = $filteredItems;
            $serviceData['sales_order']['sales_notes'] = $data['ref_id'];
            $serviceData['sales_order']['sales_date'] = $data['today_date'];
            $this->salesOrderService->createSalesOrder($voucherId, $serviceRefId, $serviceData);
        }
    }

    public function getFailedContracts($refType)
    {
        try {
            $serviceRefId = null;
            if (!empty($refType)) {
                # Get Service Ref Id from refType
                $serviceRefId = $this->getServiceRefId($refType);
            }

            # Get Process Failed Data Transfer Tracking
            $dataTransferTracking = $this->dataTransferTrackingRepository->getFailedListByServiceRef($serviceRefId)->all();
            if (empty($dataTransferTracking)) {
                throw new DataNotFoundException("There is no failed contracts...");
            }
            return $dataTransferTracking;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function recreateContract($refId, $refType)
    {
        try {
            # Get Service Ref Id from refType
            $serviceRefId = $this->getServiceRefId($refType);

            # Get Data Transfer Tracking By Ref
            $dataTransferTracking = $this->dataTransferTrackingRepository->getByRef($refId, $serviceRefId);
            if (empty($dataTransferTracking)) {
                throw new DataNotFoundException("Contract not exists!");
            }
            $data = json_decode($dataTransferTracking['data'], true);

            # Contract Process
            $this->recreateContractProcess($data, $dataTransferTracking);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function recreateContractProcess($data, $dataTransferTracking)
    {
        $formTypeId = $data['form_type_id'] ?? '';
        # Check if form data is already in cache
        if (!isset($formCache[$formTypeId])) {
            # Get form detail
            $formDetail = $this->formService->getFormTypeDetail($formTypeId);
            if (empty($formDetail)) {
                throw new Exception("Form type not found for ID: $formTypeId");
            }

            # Fetch form data once and store it in the cache
            $this->formCache[$formTypeId] = $this->formService->getForm($formTypeId);
        }

        # Use cached form data
        $form = $this->formCache[$formTypeId];

        # Contract Process
        $this->contractProcess($formDetail, $data, $form, $dataTransferTracking);
    }


    public function updateContractProcess($refId, $refType)
    {
        try {
            # Get Service Ref Id from refType
            $serviceRefId = $this->getServiceRefId($refType);

            # Update Data Transfer Tracking
            $dataTransferTracking = $this->dataTransferTrackingRepository->getByRef($refId, $serviceRefId);
            if (empty($dataTransferTracking)) {
                throw new DataNotFoundException("Contract data not found!");
            }
            $updatedData = $this->dataTransferTrackingRepository->updateProcessStatus($dataTransferTracking['id'], RecordStatus::ACTIVE);
            return $updatedData;
        } catch (Exception $e) {
            throw $e;
        }
    }

    protected function getServiceRefId($refType)
    {
        $constantName = "App\\Enums\\ServiceReference::{$refType}";
        if (!defined($constantName)) {
            throw new DataNotFoundException("Invalid reference type: {$refType}");
        }
        $serviceRefId = constant($constantName);
        return $serviceRefId;
    }

    protected function getReferenceType(int $serviceReferenceId): string
    {
        foreach (ServiceReference::cases() as $case) {
            if ($case->value === $serviceReferenceId) {
                return $case->name;
            }
        }
        throw new DataNotFoundException("Unknown ServiceReference ID: $serviceReferenceId");
    }
}
