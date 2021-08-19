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

    public function fetchUploads(Request $request) {}

    public function fetchUploadStatus($upload_id) {}

    public function searchContracts(Request $request) {}

    public function fetchContract($contract_id) {}

    public function contractReadStatus($contract_id) {}

    // this function tests the processing of saved files, it is not exposed as a public api
    public function processUpload(Request $request)
    {
        $upload = Upload::all();return $upload;
        $upload->update(['status' => 'processing']);
        Excel::import(new ContractsImport($upload), ($upload->file_path));
        $upload->update(['status' => 'processed']);

        return response()->json([
            'status' => 'success',
            'message' => 'successfully queued for processing',
            'data' => Contract::limit(1)->get(),
            'result' => $upload
        ]);
    }
}
