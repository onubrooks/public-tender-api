<?php

namespace App\Http\Controllers;

use App\Imports\ContractsImport;
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
            $items = Excel::collection(new Contract(), $file);
            $path = "public/uploads/$name";

            Upload::create([
                'title' => $request->title ?? null,
                'file_path' => $path,
                'no_of_columns' => $items->count(),
            ]);
        }
    }

    public function processUpload(Request $request) {
        $upload = Upload::first();
        Excel::import(new ContractsImport($upload), storage_path($upload->file_pth));
        // if (Storage::disk('public')->exists($upload->file_path)) {
        //     $url = Storage::url($upload->file_path);
        //     $contents = Storage::get($upload->file_path);
            
        // }
    }

    public function fetchUploads(Request $request) {}

    public function fetchUploadStatus($upload_id) {}

    public function searchContracts(Request $request) {}

    public function fetchContract($contract_id) {}

    public function contractReadStatus($contract_id) {}
}
