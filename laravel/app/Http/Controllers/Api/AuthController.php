<?php

namespace App\Http\Controllers\Api;

use App\User;
use Exception;
use App\Cy_district;
use App\Poimediatype;
use App\Sitecategory;
use DB;

//use Validator;
use App\Accessibility;
use GuzzleHttp\Client;
use ReallySimpleJWT\Jwt;
use ReallySimpleJWT\Parse;
use ReallySimpleJWT\Token;
use ReallySimpleJWT\Decode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Client as OClient;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Traveltype_user;
use Illuminate\Support\Str;


class AuthController extends Controller
{

    public $successStatus = 200;

    public function login()
    {
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $oClient = OClient::where('password_client', 1)->first();
            return $this->getTokenAndRefreshToken($oClient, request('email'), request('password'));
        } else {
            return response()->json(['status' => 'fail', 'error' => 'Unauthenticated'], 401);
        }
    }

    /*public function test2() //http://entercy.test/api/test2
    {
        return response(['status' => 'success', 'data' => Poimediatype::get()]);
    }*/

    public function reset_password(Request $request)
    {

        /*$to      = 'michalisnav@gmail.com';
        $subject = 'the subject';
        $message = 'hello';
        $headers = 'From: no-reply@entercyprus.com' . "\r\n" .
            'Reply-To: no-reply@entercyprus.com' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

        $var=mail($to, $subject, $message, $headers);
        print_r($var);*/

        //$credentials = request()->validate([$request->input('email') => 'required|email']);
        
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'fail', 'message' => 'Reset password link was not sent with success.Maybe the email is wrong']);
        }

        $status =Password::sendResetLink($request->only('email'));
        $status === Password::RESET_LINK_SENT
        ? back()->with(['status' => __($status)])
        : back()->withErrors(['email' => __($status)]);

        return response()->json(['status' => 'success', 'message' => $status]);

    }

    //when user already logged in
    public function password_change(Request $request) 
    {
        $userID = auth('api')->user()->id;
        $user = User::find($userID);
        $user->password = Hash::make($request->input('password'));
        $user->save();
        return response()->json(['status' => 'success', 'message' => 'password changed with success']);
    }

    //custom-email reset password link
    public function reset_password_link_custom(Request $request) 
    {
        $this->validate($request, [
        'email' => 'required'
        ]);

        $email=$request->input('email');
        $user = User::where('email', $email)->get();
        if (count($user) > 0) //USER EXIST
        {
           $user[0]->remember_token = substr(sha1(mt_rand()),17,15); //gives a random 15 string with letters+numbers
           $user[0]->save();

           $data = array('email'=>$user[0]->email,'remember_token'=>$user[0]->remember_token); 
           Mail::send([], $data, function ($message) use ($data){
              $message
              ->to($data['email'])
              ->subject('EnterCY - Reset Password Notification')
              ->setBody("Hello! \n\n You are receiving this email because we received a password reset request for your account. \n\n Please follow the link below in order to change your password: \n https://platform.entercyprus.com/reset-password/" . $data['remember_token'] . "?email=" . $data['email'] . "\n\n If you did not request a password reset, no further action is required. \n\n Respectfully, \n EnterCY Team"); // assuming text/plain
            });
             
           return response()->json(['status' => 'success', 'message' => 'Password reset link successfully sent on email!']);
        } 
        else  //USER DOES NOT EXIST
        {
            return response()->json(['status' => 'success', 'message' => 'User/Email does not exist!']);
        }
        
    }

    //custom-actuall reset password
    public function reset_password_custom(Request $request) 
    {
        $this->validate($request, [
        'email' => 'required',
        'token' => 'required',
        'password' => 'required'
        ]);

        $email=$request->input('email');
        $user = User::where('email', $email)->get();
        if (count($user) > 0) //USER EXIST
        {

           if ($user[0]->remember_token == $request->input('token'))  //correct token
           {
                $user[0]->remember_token = NULL;
                $user[0]->password = Hash::make($request->input('password'));
                $user[0]->save();

                return response()->json(['status' => 'success', 'message' => 'Password changed successfully!']);
           }
           else // token not matched- wrong token provided
           {
                return response()->json(['status' => 'success', 'message' => 'Invalid or expired token!']);
           }
           
        } 
        else  //USER DOES NOT EXIST
        {
            return response()->json(['status' => 'success', 'message' => 'User/Email does not exist!']);
        }
    }

     //custom-email register link
    public function register_email_link_custom(Request $request) 
    {
        $this->validate($request, [
        'email' => 'required'
        ]);

        $email=$request->input('email');
        $user = User::where('email', $email)->get();
        if (count($user) > 0) //USER EXIST
        {
           $user[0]->remember_token = substr(sha1(mt_rand()),17,15); //gives a random 15 string with letters+numbers
           $user[0]->save();

           $data = array('email'=>$user[0]->email,'remember_token'=>$user[0]->remember_token); 
           Mail::send([], $data, function ($message) use ($data){
              $message
              ->to($data['email'])
              ->subject('EnterCY - Email Verification')
              ->setBody("Hello and welcome to EnterCY! \n\n Please follow the link below in order to verify you account: \n https://platform.entercyprus.com/verify-account/" . $data['remember_token'] . "?email=" . $data['email'] . "\n\n If you did not create an account, no further action is required. \n\n Respectfully, \n EnterCY Team"); // assuming text/plain
            });
             
           return response()->json(['status' => 'success', 'message' => 'Email verification link successfully sent on email!']);
        } 
        else  //USER DOES NOT EXIST
        {
            return response()->json(['status' => 'success', 'message' => 'User/Email does not exist!']);
        }
        
    }

    //custom-actuall register account-verify
    public function register_verify_custom(Request $request) 
    {
        $this->validate($request, [
        'email' => 'required',
        'token' => 'required',
        //'password' => 'required'
        ]);

        $email=$request->input('email');
        $user = User::where('email', $email)->get();
        if (count($user) > 0) //USER EXIST
        {

           if ($user[0]->remember_token == $request->input('token'))  //correct token
           {
                $user[0]->remember_token = NULL;
                //$user[0]->password = Hash::make($request->input('password'));
                $user[0]->email_verified_at = date("Y-m-d H:i:s");
                $user[0]->save();

                return response()->json(['status' => 'success', 'message' => 'Account verified successfully!']);
           }
           else // token not matched- wrong token provided
           {
                return response()->json(['status' => 'success', 'message' => 'Invalid or expired token!']);
           }
           
        } 
        else  //USER DOES NOT EXIST
        {
            return response()->json(['status' => 'success', 'message' => 'User/Email does not exist!']);
        }
    }

    public function login_socialmedia(Request $request)
    {
        if ($request->input('type') == 'google') //GOOGLE
        {

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://oauth2.googleapis.com/tokeninfo?id_token=' . $request->input('token'),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
            ));

            $response = curl_exec($curl);
            curl_close($curl);
            $responseDecoded = json_decode($response);

            if (isset($responseDecoded->error)) //FAILED TO GOOGLE AUTHENTICATED
            {
                if ($responseDecoded->error == 'invalid_token') {
                    return response()->json(['status' => 'fail', 'message' => 'invalid_token']);
                }
            } else //ELSE IF SUCCESSFULLY GOOGLE AUTHENTICATED
            {
                if (!isset($responseDecoded->email)) //NO EMAIL PROVIDED
                {
                    return response()->json(['status' => 'fail', 'message' => 'No email provided']);
                }

                //check if user exist
                $user = User::where('email', $responseDecoded->email)->get();
                if (count($user) > 0) //USER EXIST
                {
                     //checks whether the user pre-register in platform before login with social
                    $hashedPass=DB::select("select password from users where email =?", [$user[0]->email] );
                    
                    if (Hash::check('??????????', $hashedPass[0]->password))
                    {
                        $oClient = OClient::where('password_client', 1)->first();
                        return $this->getTokenAndRefreshToken($oClient, $user[0]->email, '??????????');
                    }
                    else
                    {
                        return response()->json(['status' => 'fail', 'message' => 'User/email already registered in platform']);
                    }

                } else  //NEW USER
                {
                    $user = new User;
                    if (!isset($responseDecoded->given_name)) //NO given_name PROVIDED
                    {
                        $user->name = 'Name';
                        $user->username = 'Username';
                    } else {
                        $user->name = $responseDecoded->given_name;
                        $user->username = $responseDecoded->given_name;
                    }

                    if (!isset($responseDecoded->family_name)) //NO family_name PROVIDED
                    {
                        $user->surname = 'Surname';
                    } else {
                        $user->surname = $responseDecoded->family_name;
                    }

                    $user->email = $responseDecoded->email;
                    $user->password = Hash::make('??????????');
                    $user->role = 'normal';
                    $user->email_verified_at = '2022-06-06 12:34:56';
                    $user->save();
                    $oClient = OClient::where('password_client', 1)->first();
                    return $this->getTokenAndRefreshToken($oClient, $user->email, '??????????');
                    //return $response;
                }
            }
        } else if ($request->input('type') == 'facebook') //FACEBOOK
        {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://graph.facebook.com/me?fields=email,first_name,last_name&access_token=' . $request->input('token'),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            //echo $response;
            $responseDecoded = json_decode($response);

            if (isset($responseDecoded->error)) //FAILED TO FACEBOOK AUTHENTICATED
            {
                //if ($responseDecoded->error=='invalid_token')
                //{
                return response()->json(['status' => 'fail', 'message' => $responseDecoded->error->message]);
                //}
            } else //ELSE IF SUCCESSFULLY FACEBOOK AUTHENTICATED
            {
                if (!isset($responseDecoded->email)) //NO EMAIL PROVIDED
                {
                    return response()->json(['status' => 'fail', 'message' => 'No email provided']);
                }

                //check if user exist
                $user = User::where('email', $responseDecoded->email)->get();
                if (count($user) > 0) //USER EXIST
                {   
                    //checks whether the user pre-register in platform before login with social
                    $hashedPass=DB::select("select password from users where email =?", [$user[0]->email] );
                    
                    if (Hash::check('??????????', $hashedPass[0]->password))
                    {
                        $oClient = OClient::where('password_client', 1)->first();
                        return $this->getTokenAndRefreshToken($oClient, $user[0]->email, '??????????');
                    }
                    else
                    {
                        return response()->json(['status' => 'fail', 'message' => 'User/email already registered in platform']);
                    }
                    
                    
                } else  //NEW USER
                {
                    $user = new User;
                    if (!isset($responseDecoded->first_name)) //NO first_name PROVIDED
                    {
                        $user->name = 'Name';
                        $user->username = 'Username';
                    } else {
                        $user->name = $responseDecoded->first_name;
                        $user->username = $responseDecoded->first_name;
                    }

                    if (!isset($responseDecoded->last_name)) //NO last_name PROVIDED
                    {
                        $user->surname = 'Surname';
                    } else {
                        $user->surname = $responseDecoded->last_name;
                    }

                    $user->email = $responseDecoded->email;
                    $user->password = Hash::make('??????????');
                    $user->role = 'normal';
                    $user->email_verified_at = '2022-06-06 12:34:56';
                    $user->save();
                    $oClient = OClient::where('password_client', 1)->first();
                    return $this->getTokenAndRefreshToken($oClient, $user->email, '??????????');
                    //return $response;
                }
            }
        } else if ($request->input('type') == 'apple')  //APPLE
        {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://appleid.apple.com/auth/keys',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            //echo $response;
            $responseDecoded = json_decode($response);
            if (isset($responseDecoded->error)) //FAILED TO apple AUTHENTICATED
            {
                //if ($responseDecoded->error=='invalid_token')
                //{
                return response()->json(['status' => 'fail', 'message' => $responseDecoded->error->message]);
                //}
            } else //ELSE IF SUCCESSFULLY apple AUTHENTICATED
            {
                $token = $request->input('token');
                $filename = 'storage/media/jwt_apple_login.py';
                $output = shell_exec('python3.8 ' . $filename . ' ' . $token);
                #$output='{"kid": "eXaunmL", "alg": "RS256", "iss": "https://appleid.apple.com", "aud": "com.mdl.entercy", "exp": 1627382127, "iat": 1627295727, "sub": "001510.a6443729a7954fd2a166727cc864d52f.0828", "c_hash": "V_rPmCoUZoIfz9IAoVpAMg", "email": "frederick.mdl@gmail.com", "email_verified": "true", "auth_time": 1627295727, "nonce_supported": true}';


                $decode_output = json_decode($output);
                if (isset($decode_output->expire)) //NO KID PROVIDED
                {
                    if ($decode_output->expire == 'true') {
                        return response()->json(['status' => 'fail', 'message' => 'Signature has Expired']);
                    }
                }
                $keys = $responseDecoded->keys;
                $valid = 0;
                if (!isset($decode_output->kid)) //NO KID PROVIDED
                {
                    return response()->json(['status' => 'fail', 'message' => 'No key id provided']);
                }
                foreach ($keys as $key) {
                    if (strval($key->kid) == $decode_output->kid) {
                        $valid = 1;
                    }
                }
                if ($valid == 0) {
                    return response()->json(['status' => 'fail', 'message' => 'key id is not valid']);
                }
                if (!isset($decode_output->email)) //NO EMAIL PROVIDED
                {
                    return response()->json(['status' => 'fail', 'message' => 'No email provided']);
                }

                $user = User::where('email', $decode_output->email)->get();
                if (count($user) > 0) //USER EXIST
                {
                    //checks whether the user pre-register in platform before login with social
                    $hashedPass=DB::select("select password from users where email =?", [$user[0]->email] );
                    
                    if (Hash::check('??????????', $hashedPass[0]->password))
                    {
                        $oClient = OClient::where('password_client', 1)->first();
                        return $this->getTokenAndRefreshToken($oClient, $user[0]->email, '??????????');
                    }
                    else
                    {
                        return response()->json(['status' => 'fail', 'message' => 'User/email already registered in platform']);
                    }


                } else //NEW USER 
                {
                    $user = new User;
                    $name = $request->input('name');
                    if (!isset($name)) //NO first_name PROVIDED
                    {
                        $user->name = 'Name';
                        $user->username = 'Username';
                    } else {
                        $user->name = $name;
                        $user->username = $name;
                    }
                    $surname = $request->input('surname');
                    if (!isset($surname)) //NO surname PROVIDED
                    {
                        $user->surname = 'Surname';
                    } else {
                        $user->surname = $surname;
                    }

                    $user->email = $decode_output->email;
                    $user->password = Hash::make('??????????');
                    $user->role = 'normal';
                    $user->email_verified_at = '2022-06-06 12:34:56';
                    $user->save();
                    $oClient = OClient::where('password_client', 1)->first();
                    return $this->getTokenAndRefreshToken($oClient, $user->email, '??????????');
                }
            }
        }
    }


    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => 'sometimes|required_with:password',
            'surname' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255']
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'fail', 'error' => $validator->errors()], 401);
        }

        $password = $request->password;
        //$input = $request->all();
        //$input['password'] = bcrypt($input['password']);
        //$user = User::create($input);
        $user = new User;
        $user->name = $request->input('name');
        $user->surname = $request->input('surname');
        $user->username = $request->input('username');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->role = 'normal';
        $user->gender = $request->input('gender');
        $user->dateOfBirth = $request->input('dateOfBirth');
        $user->country = $request->input('country');
        $user->save();
        $oClient = OClient::where('password_client', 1)->first();
        //$user->sendEmailVerificationNotification(); //old way
        app(AuthController::class)->register_email_link_custom($request); //new way
        return $this->getTokenAndRefreshToken($oClient, $user->email, $password);
    }

    public function getTokenAndRefreshToken(OClient $oClient, $email, $password)
    {
        $oClient = OClient::where('password_client', 1)->first();
        $http = new Client(['http_errors' => false]); //HANDLES EXCEPTIONS

        //$response = $http->request('POST', 'http://entercy.test/oauth/token', [
        $response = $http->request('POST', URL::to('/') . '/oauth/token', [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => $oClient->id,
                'client_secret' => $oClient->secret,
                'username' => $email,
                'password' => $password,
                'scope' => '*',
            ],
        ]);

        $result = json_decode((string) $response->getBody(), true);

        //return response()->json($result, $this->successStatus);
        return response()->json(['status' => 'success', 'data' => $result]);
    }

    public function refreshToken(Request $request)
    {
        $refresh_token = $request->header('Refreshtoken');
        $oClient = OClient::where('password_client', 1)->first();
        $http = new Client(['http_errors' => false]); //HANDLES EXCEPTIONS

        try {
            //$response = $http->request('POST', 'http://entercy.test/oauth/token', [
            $response = $http->request('POST', URL::to('/') . '/oauth/token', [
                'form_params' => [
                    'grant_type' => 'refresh_token',
                    'refresh_token' => $refresh_token,
                    'client_id' => $oClient->id,
                    'client_secret' => $oClient->secret,
                    'scope' => '*',
                ],
            ]);
            $result = json_decode((string) $response->getBody(), true);
            return response()->json(['status' => 'success', 'data' => $result]);
        } catch (Exception $e) {
            return response()->json(['status' => 'fail', 'error' => "Unauthenticated"]);
        }
    }

    public function test(Request $request)
    {
        //return URL::to('/') . '/oauth/token';
        //EXAMPLE: below are some tests from random generation
        /*$input = array('111','222','3333','444','555','666');
        $rand_keys = array_rand($input, 2);
        echo $input[$rand_keys[0]];*/

        //Traveltype_user::where('user_id', $userID)...
        //$current_user_travelTypes= Traveltype_user::where('user_id', 1)..
        // na kataliksi kapos etsi ... $current_user_travelTypes= 1,2,3
        /*return public_path().$request->input('name');
        $user_id = auth('api')->user()->id;
        $seperated='';
        $current_user_travelTypes= Traveltype_user::where('user_id', $user_id)->get();
        foreach($current_user_travelTypes as $current_user_travelType){
            $seperated=$seperated.$current_user_travelType->traveltype_id.',';
        }
        $seperated=rtrim($seperated, ',');
        return $seperated;*/

        //REMEMBER THIS WORKS ONLY IN PRODUCTION SERVER
        $data = array('email'=>'sotiriscon92@gmail.com');
      Mail::send([], $data, function ($message) use ($data){
        $message
          ->to($data['email'])
          ->subject('Confirmation of Registration-EnterCY')
          ->setBody('Congratulations for your Registration, welcome to EnterCY platform!'); // assuming text/plain
       });

    }



    /* public function register (Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => 'sometimes|required_with:password',
            'surname' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255']
        ]);

        if ($validator->fails())
        {
            return response(['status' => 'fail','errors'=>$validator->errors()->all()], 422);
        }

        //$request['password']=Hash::make($request['password']);
        //$user = User::create($request->toArray());
        $user = new User;
        $user->name=$request->input('name');
        $user->surname=$request->input('surname');
        $user->username=$request->input('username');
        $user->email=$request->input('email');
        $user->password=Hash::make($request->input('password'));
        $user->role='normal';
        $user->save();

        $token = $user->createToken('Laravel Password Grant Client')->accessToken;
        //$response = ['token' => $token];

        return response(['status' => 'success', 'token' => $token]);

    }

    public function login(Request $request)
    {

        $loginData = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);

        if (!auth()->attempt($loginData))
        {
            return response(['status' => 'fail','message'=>'Invalid credentials']);
        }

        $accessToken = auth()->user()->createToken('authToken')->accessToken;

        return response(['status' => 'success','user' => auth()->user(), 'access_token' => $accessToken]);

    }*/

    public function logout(Request $request)
    {
        $token = $request->user()->token();
        $token->revoke();

        $response = 'You have been succesfully logged out!';
        //return response($response, 200);
        return response(['status' => 'success', 'message' => $response]);
    }


    /*--------------------------OTHER GENERIC APIs-----------------------------*/

    public function api_getpoimediatypes()
    {
        return response(['status' => 'success', 'data' => Poimediatype::get()]);
    }
    public function api_getpoimediatype(Request $request)
    {
        $data = Poimediatype::find($request->input('id'));
        return response(['status' => 'success', 'data' => $data]);
    }

    public function api_getcy_districts()
    {
        return response(['status' => 'success', 'data' => Cy_district::get()]);
    }
    public function api_getcy_district(Request $request)
    {
        $data = Cy_district::find($request->input('id'));
        return response(['status' => 'success', 'data' => $data]);
    }

    public function api_getsitecategories()
    {
        return response(['status' => 'success', 'data' => Sitecategory::get()]);
    }
    public function api_getsitecategory(Request $request)
    {
        $data = Sitecategory::find($request->input('id'));
        return response(['status' => 'success', 'data' => $data]);
    }

    public function api_getaccessibilities()
    {
        return response(['status' => 'success', 'data' => Accessibility::get()]);
    }
    public function api_getaccessibility(Request $request)
    {
        $data = Accessibility::find($request->input('id'));
        return response(['status' => 'success', 'data' => $data]);
    }
}
