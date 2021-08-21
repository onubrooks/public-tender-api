<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Services\UploadService;
use App\Services\ContractService;
class DataController extends Controller
{
    public function uploadForProcessing(Request $request) {
        ini_set('max_execution_time', 360);
        // ini_set('upload_max_filesize', '100M');
        // ini_set('post_max_size', '100M');
        $request->validate([
            'title' => ['nullable', 'string'],
            'upload_file' => ['required', 'mimes:xls,xlsx'],
        ]);

        $file = $request->file('upload_file');
        $upload = UploadService::queueUpload($file);

        ini_set('max_execution_time', 60);

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

        if( is_null($contract) )
        {
            return $this->sendErrorResponse('contract not found');
        }

        return $this->sendSuccessResponse([
            'read' => ! is_null($contract->read_at)
        ], 'successfully fetched contract read status');
    }

}
