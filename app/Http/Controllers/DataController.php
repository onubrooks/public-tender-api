<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use Illuminate\Http\Request;

use App\Services\UploadService;
use App\Services\ContractService;
class DataController extends Controller
{
    public function uploadForProcessing(Request $request) {
        $request->validate([
            'title' => ['nullable', 'string'],
            'upload_file' => ['required', 'mimes:xls,xlsx'],
        ]);

        $file = $request->file('upload_file');
        $upload = UploadService::queueUpload($file);

        return $this->sendSuccessResponse($upload, 'successfully queued for processing');
    }

    public function fetchUploads() {
        return $this->sendSuccessResponse(UploadService::getUploads(), 'successfully fetched uploads');
    }

    public function fetchUploadStatus($upload_id) {
        $upload_status = UploadService::getUploadStatus($upload_id);

        return $this->sendSuccessResponse($upload_status, 'successfully fetched upload status');
    }

    public function searchContracts(Request $request) {
        $search = $request->search ?? '';
        $dataCelebracaoContrato = $request->query('dataCelebracaoContrato');
        $precoContratual = $request->query('precoContratual');
        $adjudicatarios = $request->query('adjudicatarios');

        $contracts = ContractService::searchContracts($search, $dataCelebracaoContrato, $precoContratual, $adjudicatarios);

        return $this->sendSuccessResponse($contracts, 'successfully fetched contracts');
    }

    public function fetchContract($contract_id) {
        $contract = ContractService::fetchContract($contract_id);

        return $this->sendSuccessResponse($contract, 'successfully fetched contract');
    }

    public function fetchContractReadStatus($contract_id) {
        $contract = ContractService::fetchContractReadStatus($contract_id);

        return $this->sendSuccessResponse([
            'read' => ! is_null($contract->read_at)
        ], 'successfully fetched contract read status');
    }

}
