<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Twilio\Rest\Client;

class BaseController extends Controller
{
    public function apiResponse($data = null, $message = null, $status_code = 200)
    {
        return response()->json([
            'status_code' => is_array($status_code) ? $status_code[1] : $status_code,
            'message' => $message ?: trans('api.success'),
            'data' => $data,
        ], is_array($status_code) ? $status_code[0] : $status_code);
    }

    public function apiErrorResponse($message = null, $status_code = 400)
    {
        return response()->json([
            'status_code' => is_array($status_code) ? $status_code[1] : $status_code,
            'message' => $message ?: trans('api.Bad request'),
        ], is_array($status_code) ? $status_code[0] : $status_code);
    }

    public function internalServerError(\Exception $ex)
    {
        DB::rollBack();

        info('exception: ');
        info($ex->getMessage());
        info($ex);

        return response()->json([
            'status_code' => 500,
            'message' => trans('api.Something Went Wrong'),
            'info' => $ex->getMessage(),
        ], 500);
    }

    public function sendSMS($message, $phone_number)
    {
        $sid = config('services.twilio.sid');
        $token = config('services.twilio.token');
        $number = config('services.twilio.number');

        $client = new Client($sid, $token);

        $response = $client->messages->create($phone_number, ['from' => $number, 'body' => $message]);

        info(array_intersect_key($response->toArray(), array_flip(['body', 'to', 'status'])));

        return;
    }

}
