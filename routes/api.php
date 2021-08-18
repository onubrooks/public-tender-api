<?php

use App\Http\Controllers\DataController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*
|--------------------------------------------------------------------------
| 
|--------------------------------------------------------------------------
|
| An endpoint should allow users to upload a .xls with data for treatment. 
| The data always follows the same structure/format and that format can be seen on the linked file above. 
| Should handle files small and big.
| Should handle concurrent operations
|
*/
Route::post('upload-for-processing', [DataController::class, 'uploadForProcessing']);

/*
|--------------------------------------------------------------------------
| 
|--------------------------------------------------------------------------
|
| The uploaded data should be imported into a Postgres database containing all columns necessary for the storage
| of data as per the xls and respective operations detailed below. 
| The naming of the table, it’s columns and column types are up to you (as per what you feel makes more sense). 
| There should be no duplicated data;
|
*/
Route::get('/process-upload', [DataController::class, 'processUpload']);

/*
|--------------------------------------------------------------------------
| 
|--------------------------------------------------------------------------
|
| An endpoint to fetch all uploads including their status.
|
*/
Route::get('/fetch-uploads', [DataController::class, 'fetchUploads']);

/*
|--------------------------------------------------------------------------
| 
|--------------------------------------------------------------------------
|
| An endpoint should exist to query the status of processing the uploaded xls and adding it to the database.
|
*/
Route::get('/fetch-upload-status/{upload_id}', [DataController::class, 'fetchUploadStatus']);

/*
|--------------------------------------------------------------------------
| 
|--------------------------------------------------------------------------
|
| An endpoint should exist to query/search data from the database based on: 
| date (dataCelebracaoContrato in xls), amount (range) (precoContratual in xls), winning company (adjudicatarios in the xls). 
*/
Route::get('/search-contracts', [DataController::class, 'searchContracts']);

/*
|--------------------------------------------------------------------------
| 
|--------------------------------------------------------------------------
|
| An endpoint should exist to get all data for a given contract as long as provided with an ID. 
| Ids are incremental and created by you. This marks row as ‘read’.
|
*/
Route::get('/fetch-contract/{contract_id}', [DataController::class, 'fetchContract']);

/*
|--------------------------------------------------------------------------
| 
|--------------------------------------------------------------------------
|
| An endpoint should exist to check if a certain contract (row) was ever read or not.
|
*/
Route::get('/contract-read-status/{contract_id}', [DataController::class, 'contractReadStatus']);