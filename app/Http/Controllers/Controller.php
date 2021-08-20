<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Format and return error response
     *
     * @param string $message
     * @param array|null $errors
     * @param int $code
     * @return JsonResponse
     */
    public function sendErrorResponse($message = 'action failed', $errors = [], $code = 400)
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'errors' => $errors
        ], $code);
    }

    /**
     * Format and return success response
     *
     * @param  string  $message
     * @param  array|string  $data
     * @param  int  $code
     * @return JsonResponse
     */
    public function sendSuccessResponse($data = '', $message = 'action successful', $code = 200)
    {
        $response = ['status' => 'success'];

        if ($message != '') {
            $response['message'] = $message;
        }

        if ($data != '') {
            $response['data'] = $data;
        }

        return response()->json($response, $code);
    }
}
