<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Services\UploadService;
use App\Services\ContractService;

class DataController extends Controller
{
    /**
     * @group Uploads
     * 
     * Upload Data for Processing
     *
     * This endpoint allows users to upload .xls and .xlsx files for processing. 
     * The data must follow the following format and number of columns as validations are in place:
     * idcontrato, nAnuncio, tipoContrato, tipoprocedimento, objectoContrato, adjudicantes, adjudicatarios, dataPublicacao, dataCelebracaoContrato, precoContratual, cpv, prazoExecucao, localExecucao, fundamentacao
     * 
     * @bodyParam upload_file file The file to be uploaded. Example C:\Users\onu\Downloads\contracts-medium-xls
     * @bodyParam title string An optional file title for the group of records. Example: 2016 Public Contracts
     *
     *
     * @response 400 scenario="Service is down or an unexpected error occurred".
     * @responseField status The status of this API (`success` or `error`).
     * @responseField message The service message based on the status of the call.
     * @responseField data 'Upload' record just created.
     */
    public function uploadForProcessing(Request $request) {
        $request->validate([
            'title' => ['nullable', 'string'],
            'upload_file' => ['required', 'mimes:xls,xlsx'],
        ]);

        $file = $request->file('upload_file');
        $upload = UploadService::queueUpload($file);

        return $this->sendSuccessResponse($upload, 'successfully queued for processing');
    }

    /**
     * @group Uploads
     * 
     * Fetch Uploads
     *
     * Fetch a list of all uploads to the system. If everything is okay, you'll get a 200 OK response.
     *
     * Otherwise, the request will fail with a 400 error, and an error field.
     * 
     *
     * @response 400 scenario="Service is down or an unexpected error occurred".
     * @responseField status The status of this API (`success` or `error`).
     * @responseField message The service message based on the status of the call.
     * @responseField data List of all uploads  (both processed and unprocessed) sent to the API.
     */
    public function fetchUploads() {
        return $this->sendSuccessResponse(UploadService::getUploads(), 'successfully fetched uploads');
    }

    /**
     * @group Uploads
     * 
     * Fetch Upload Status 
     *
     * This endpoint fetches the status of a file previously uploaded. It accepts the ID of the upload and checks the status. 
     * The status can be `queued`, `processing` or `processed`
     *
     * A not found error is returned if the upload ID doesn't match any item on the system.
     * 
     * @urlParam upload_id integer required The ID of the upload. Example: 1
     *
     * @response 400 scenario="Service is down or an unexpected error occurred".
     * @responseField status The status of this API (`success` or `error`).
     * @responseField message The service message based on the status of the call.
     * @responseField data The status of the upload.
     */
    public function fetchUploadStatus($upload_id) {
        $upload_status = UploadService::getUploadStatus($upload_id);

        if (is_null($upload_status)) {
            return $this->sendErrorResponse('upload not found');
        }

        return $this->sendSuccessResponse($upload_status, 'successfully fetched upload status');
    }

    /**
     * @group Contracts
     * 
     * Search Contracts
     *
     * This endpoint allows you to search for contracts based on the following columns:
     * dataCelebracaoContrato, precoContratual, adjudicatarios
     * Any of the columns that matches the data in each column will be returned.
     *
     * 
     * @queryParam dataCelebracaoContrato date optional The item to search for in the dataCelebracaoContrato column. Example: 2016-08-09
     * @queryParam precoContratual_lower float optional The lower range to search for in the precoContratual column. Example: 2605654.08
     * @queryParam precoContratual_upper float optional The upper range to search for in the precoContratual column. Example: 2605654.08
     * @queryParam adjudicatarios string optional The item to search for in the adjudicatarios column. Example: 502496878 - CONSTRUÇÕES PRAGOSA, S.A.
     *
     * @response 400 scenario="Service is down or an unexpected error occurred".
     * @responseField status The status of this API (`success` or `error`).
     * @responseField message The service message based on the status of the call.
     * @responseField data List of contracts that match the search.
     */
    public function searchContracts(Request $request) {
        $request->validate([
            'dataCelebracaoContrato' => ['nullable', 'date'],
            'precoContratual_lower' => ['nullable', 'numeric'],
            'precoContratual_upper' => ['nullable', 'numeric'],
            'adjudicatarios' => ['nullable', 'string'],
        ]);
        $dataCelebracaoContrato = $request->query('dataCelebracaoContrato');
        $precoContratual_lower = $request->query('precoContratual_lower');
        $precoContratual_upper = $request->query('precoContratual_upper');
        $adjudicatarios = $request->query('adjudicatarios');

        $contracts = ContractService::searchContracts($dataCelebracaoContrato, $precoContratual_lower, $precoContratual_upper, $adjudicatarios);

        return $this->sendSuccessResponse($contracts, 'successfully fetched contracts');
    }

    /**
     * @group Contracts
     * 
     * Fetch Contract
     *
     * This endpoint fetches the contract, identified by the id in the url parameter. 
     * If it fetches successfully, the read status of the contract will be updated with a timestamp.
     *
     * Otherwise, the request will fail with a 400 not found error, and an error field.
     * 
     * @urlParam contract_id integer required The ID of the contract. Example: 1
     *
     * @response 400 scenario="Service is down or an unexpected error occurred".
     * @responseField status The status of this API (`success` or `error`).
     * @responseField message The service message based on the status of the call.
     * @responseField data The contract with the given ID.
     */
    public function fetchContract($contract_id) {
        $contract = ContractService::fetchContract($contract_id);

        if (is_null($contract)) {
            return $this->sendErrorResponse('contract not found');
        }

        return $this->sendSuccessResponse($contract, 'successfully fetched contract');
    }

    /**
     * @group Contracts
     * 
     * Fetch Contract Read Status  
     *
     * This endpoint fetches the read status of a contract, identified by its ID.
     * If the contract exists, its status is fetched and returned.
     *
     * Otherwise, the request will fail with a 400 not found error, and an error message.
     * 
     * @urlParam contract_id integer required The ID of the contract. Example: 1
     *
     * @response 400 scenario="Service is down or an unexpected error occurred".
     * @responseField status The status of this API (`success` or `error`).
     * @responseField message The service message based on the status of the call.
     * @responseField data The contract read status.
     */
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
