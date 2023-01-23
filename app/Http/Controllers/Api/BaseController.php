<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller as Controller;
use Illuminate\Http\Request;
use Exception;

class BaseController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($result, $message)
    {
    	$response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];


        return response()->json($response, 200);
    }


    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $errorMessages = [], $code = 200)
    {
    	$response = [
            'success' => false,
            'message' => $error,
        ];


        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }else{
            $response['data'] = null;
        }


        return response()->json($response, $code);
    }
    public function sendSms($mobile,$otp){
        // Account details
            $apiKey     = urlencode('Njk1MjY1MzQ2MzU2MzYzMDM0NjE3NDcxNDkzMDU0NTA=');
            // Message details
            $numbers    = array($mobile);
            $sender     = urlencode('IWLFLY');
            $message    = rawurlencode("Your OTP For I Will Fly Is :- ".$otp.".Please Don't Share It With Anyone.");
            $numbers    = implode(',', $numbers);
            // Prepare data for POST request
            $data       = array('apikey' => $apiKey, 'numbers' => $numbers, 'sender' => $sender, 'message' => $message);
            // Send the POST request with cURL
            $ch         = curl_init('https://api.textlocal.in/send/');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);
            // Process your response here
            //echo $response;
    }
}