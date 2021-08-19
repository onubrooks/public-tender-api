<?php

namespace App\Http\Controllers;

use App\Imports\ContractsImport;
use App\Jobs\ProcessXslFile;
use App\Models\Contract;
use App\Models\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use Maatwebsite\Excel\Facades\Excel;

class DataController extends Controller
{
    public function uploadForProcessing(Request $request) {
        $request->validate([
            'title' => ['nullable', 'string'],
            'upload_file' => ['required', 'mimes:xls,xlsx'],
        ]);
        // php artisan storage:link
        $file = $request->file('upload_file');
        if ($file->isValid()) {
            // $path = $file->store('uploads', 'local');
            $name = $file->getClientOriginalName();
            $myfile = Storage::disk('public')->put('uploads/' . $name, fopen($file, 'r+'));
            $items = Excel::toCollection(new Contract(), $file);
            $path = "public/uploads/$name";

            $upload = Upload::create([
                'title' => $request->title ?? null,
                'file_path' => $path,
                'number_of_rows' => $items[0]->count() - 1,
                'status' => 'queued'
            ]);
            ProcessXslFile::dispatch($upload)->delay(now()->addMinutes(2));

            return response()->json([
                'status' => 'success',
                'message' => 'successfully queued for processing',
                'data' => $upload
            ]);
        }
    }

    public function fetchUploads(Request $request) {
        return response()->json([
            'status' => 'success',
            'message' => 'successfully fetched uploads',
            'data' => Upload::select('title', 'status', 'file_path')->get()
        ]);
    }

    public function fetchUploadStatus($upload_id) {
        $upload = Upload::select('status')->find($upload_id);

        return response()->json([
            'status' => 'success',
            'message' => 'successfully fetched upload status',
            'data' => $upload
        ]);
    }

    public function searchContracts(Request $request) {
        $search = $request->search;// dataCelebracaoContrato, precoContratual, adjudicatarios
        $contracts = Contract::whereDate('dataCelebracaoContrato', $search)
            -> orWhere('precoContratual', $search)
            -> orWhere('adjudicatarios', $search)
            ->get();
        return response()->json([
            'status' => 'success',
            'message' => 'successfully fetched contracts',
            'data' => $contracts
        ]);
    }

    public function fetchContract($contract_id) {
        $contract = Contract::find($contract_id);

        $contract->read_at = now();
        $contract->save();

        return response()->json([
            'status' => 'success',
            'message' => 'successfully fetched contract',
            'data' => $contract
        ]);
    }

    public function contractReadStatus($contract_id) {
        $contract = Contract::select('read_at')->find($contract_id);

        return response()->json([
            'status' => 'success',
            'message' => 'successfully fetched contract read status',
            'data' => [
                'read' => ! is_null($contract->read_at)
            ]
        ]);
    }

}
