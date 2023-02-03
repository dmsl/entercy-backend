<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use App\Userjourney;

class WebNotificationController extends Controller
{

    public function index()
    {
        return view('Push_Notification.home');
    }

    public function storeDeviceToken_api(Request $request) {
        $user = $request->user();
        $user->device_key = $request->input('device_key');
        $user->save();
        return response(['status' => 'success', 'data' => 'Successfully stored device key.']);
     }

     public function retrieveDeviceToken_api(Request $request)
     {
        $userID = auth('api')->user()->id;
        $user = User::find($userID);
        return response(['status' => 'success', 'device_key' => $user->device_key]);
     }
     public function sendWebNotification_api(Request $request)
     {
        $user = User::where('device_key', $request->input('device_key'))->pluck('device_key')->all();
        $url = 'https://fcm.googleapis.com/fcm/send';

        //$serverKey = 'AAAAgjYS8Zg:APA91bE4FPa_ysR-VyoryS6aDQlUzmN0dTn3a6hwhAHgWb-rQYbSuS1ytM6hN-4BWu8xi4GIoQWoh9x4eIeAWwNultuis1vh5xiKM1hkKDjUSCIKHydMlZdqaetuv9jlXNUsTEZTe_wE';

        $serverKey = 'AAAAxJVgAWA:APA91bFpC4ZeWW64gE4LcFmYw2dAjb5n-ESJ6xbyKU9bSo7M5iuGq7urNmN0PutO-Q3sPoR9bOA4DS_t794MOlg3gDsz1pqjinuLdIom0Sl3W-pIi9vXa4DqH_2KAjLqIr1yfRiD2HWW';

        $data = [
            "registration_ids" => $user,
            "notification" => [
                "title" => $request->input('title'),
                "body" => $request->input('body'),
            ],
            //o Kronis mpori na diavasi kai to data to field se periptosi pou t simperilavoume sto $data
            "data"=> [                
                "url"=> "https://platform.entercyprus.com/"
            ]
        ];
        $encodedData = json_encode($data);

        $headers = [
            'Authorization:key=' . $serverKey,
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);

        // Execute post
        $result = curl_exec($ch);

        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }

        // Close connection
        curl_close($ch);

        // FCM response
        dd($result);
        return response(['status' => 'success', 'user' => $user]);
     }

     public function send_recommendation_notification(Request $request)
     {
        $current_date = date('d-m-Y');
        $userjourneys = Userjourney::where('arriving_date','<=', $current_date)->where('departing_date','>=', $current_date)->where('status', 'active')->get();

        $user_tokens=[];
        foreach ($userjourneys  as $userjourney)
        {
            $user = User::where('id', $userjourney->user_id)->get();
            if (count($user)>0) 
            {
                if ($user[0]->device_key != null)
                {
                    $user_tokens[sizeof($user_tokens)] =  $user[0]->device_key;      
                }                
            }            
        }


         $url = 'https://fcm.googleapis.com/fcm/send';

        //$serverKey = 'AAAAgjYS8Zg:APA91bE4FPa_ysR-VyoryS6aDQlUzmN0dTn3a6hwhAHgWb-rQYbSuS1ytM6hN-4BWu8xi4GIoQWoh9x4eIeAWwNultuis1vh5xiKM1hkKDjUSCIKHydMlZdqaetuv9jlXNUsTEZTe_wE';

        $serverKey = 'AAAAxJVgAWA:APA91bFpC4ZeWW64gE4LcFmYw2dAjb5n-ESJ6xbyKU9bSo7M5iuGq7urNmN0PutO-Q3sPoR9bOA4DS_t794MOlg3gDsz1pqjinuLdIom0Sl3W-pIi9vXa4DqH_2KAjLqIr1yfRiD2HWW';

        /*$data = [
            "registration_ids" => $user_tokens,
            "notification" => [
                "title" => 'EnterCY',
                "body" => 'Your recommendations are available! Click to view!',
            ],
            //o Kronis mpori na diavasi kai to data to field se periptosi pou t simperilavoume sto $data
            "data"=> [                
                "url"=> "https://platform.entercyprus.com/"
            ]
        ];*/

        $data = [
            "registration_ids" => $user_tokens,
            //"notification" => [   ],
            //o Kronis mpori na diavasi kai to data to field se periptosi pou t simperilavoume sto $data
            "data"=> [                
                "url"=> "https://platform.entercyprus.com/",
                "title" => 'EnterCY',
                "body" => 'Your recommendations are available! Click to view!',
            ]
        ];

        $encodedData = json_encode($data);

        $headers = [
            'Authorization:key=' . $serverKey,
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);

        // Execute post
        $result = curl_exec($ch);

        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }

        // Close connection
        curl_close($ch);

        // FCM response
        dd($result);
        return response(['status' => 'success', 'user' => $user_tokens]);
     }

    public function storeDeviceToken(Request $request) {
        //$userID = auth('api')->user()->id;
        //$user = User::find($userID);
        //$user->device_key = $request->token;
        $user = $request->user();
        $user->device_key = $request->token;
        $user->save();
        $msg = "Token stored.".$user->device_key;
        return response()->json(array('msg'=> $msg), 200);
     }
    public function sendWebNotification(Request $request)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $FcmToken = User::whereNotNull('device_key')->pluck('device_key')->all();

        $serverKey = 'AAAAgjYS8Zg:APA91bE4FPa_ysR-VyoryS6aDQlUzmN0dTn3a6hwhAHgWb-rQYbSuS1ytM6hN-4BWu8xi4GIoQWoh9x4eIeAWwNultuis1vh5xiKM1hkKDjUSCIKHydMlZdqaetuv9jlXNUsTEZTe_wE';

        $data = [
            "registration_ids" => $FcmToken,
            "notification" => [
                "title" => $request->title,
                "body" => $request->body,
            ]
        ];
        $encodedData = json_encode($data);

        $headers = [
            'Authorization:key=' . $serverKey,
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);

        // Execute post
        $result = curl_exec($ch);

        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }

        // Close connection
        curl_close($ch);

        // FCM response
        dd($result);
    }
}
