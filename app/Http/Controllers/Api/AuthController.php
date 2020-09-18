<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Twilio\Rest\Client;
use Twilio\Exceptions\TwilioException;

class AuthController extends Controller
{

    public function login(Request $request) {

        if (is_numeric($request->phone)) {
            $validator = validator()->make($request->all(), [
                'phone' => 'required|min:11|max:15',
                'password' => 'required|min:8'
            ]);

            if ($validator->fails()){
                $response = [
                    'status' => 0,
                    'message' => 'Invalid Email or Password',
                    'data' => (object)[]
                ];
                return response()->json($response);
            }
            
            $checkVerify = User::where('phone', $request->phone)->where('isVerifyed', '=', 0)->count();
            
            if($checkVerify > 0){

                $code = rand(1111, 9999);
                
                $accountSid     = config('app.twilio')['TWILIO_ACCOUNT_SID'];
                $authToken      = config('app.twilio')['TWILIO_AUTH_TOKEN'];
                $twilioNumber   = config('app.twilio')['TWILIO_APP_SID'];
                
                $client = new Client($accountSid, $authToken);
                
                try {
                    // Use the client to do fun stuff like send text messages!
                    $client->messages->create(
                    // the number you'd like to send the message to
                        '+2'.$request->phone,
                    array(
                        // A Twilio phone number you purchased at twilio.com/console
                            'from' => $twilioNumber,
                        // the body of the text message you'd like to send
                            'body' => 'Your Verification Is: ' . $code
                        )
                    );
                } catch (Exception $e) {
                    echo "Error: " . $e->getMessage();
                }

                $updateVerify = User::where('phone', $request->phone)->update(['isVerifyed' => 0,'verification_code' => $code]);
                
                $response = [
                    'status' => 0,
                    'message' => 'Your Account is Not Verified, I Sent You Again So Check Your Message To Verify',
                    'data' => (object)[]
                ];
                return response()->json($response);
            }
            
            $user = User::where('phone', $request->phone)->first();
                    
        } else {
            $user = false;
        }

        if ($user) {
            if (Hash::check($request->password, $user->password)) {

                $input = $request->only('phone', 'password');
                $jwt_token = null;
         
                if (!$jwt_token = JWTAuth::attempt($input)) {
                    return response()->json([
                        'status' => 1,
                        'message' => 'Invalid Email or Password',
                        'data' => (object)[]
                    ], 401);
                }

                $response = [
                    'status' => 1,
                    'message' => 'Your Account is Correct',
                    'data' => [
                        'user' => $user,
                        'token' => $jwt_token
                    ]
                ];
                return response()->json($response);
            } else {
                $response = [
                    'status' => 0,
                    'message' => 'Invalid Email or Password, Try Again',
                    'data' => (object)[]
                ];
                return response()->json($response);
            }

        } else {
            $response = [
                'status' => 0,
                'message' => 'Invalid Email or Password',
                'data' => (object)[]
            ];
            return response()->json($response);
        }
    }

    public function register(Request $request) {
        $messages = [
            'phone.regex' => 'phone must contain only numbers',
            'email.regex' => 'enter your email like example@gmail.xyz',
        ];

        $validator = validator()->make($request->all(), [
            'username' => 'required|string|max:50|unique:users,username,null,null,isVerifyed,1|alpha_dash',
            'phone' => 'required|regex:/[0-9]/u|min:11|max:15|unique:users,phone,null,null,isVerifyed,1',
            'email' => 'required|unique:users,email,null,null,isVerifyed,1|email|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix',
            'password' => 'required|min:8',
        ], $messages);

        if ($validator->fails()) {

            $response = [
                'status' => 0,
                'message' => $validator->errors()->first(),
                'data' => (object)[]
            ];
            return response()->json($response);
        }

        $deleteUserNotVerifiedPhoneWhenRegisterAgain = User::where('phone', $request->phone)->where('isVerifyed', '=', 0)->delete();
        $deleteUserNotVerifiedEmailWhenRegisterAgain = User::where('email', $request->email)->where('isVerifyed', '=', 0)->delete();

        $checkEamil = User::where('email', $request->email)->where('isVerifyed', '=', 1)->count();
        $checkPhone = User::where('phone', $request->phone)->where('isVerifyed', '=', 1)->count();
        $checkEmailAndPhoneVerifyExist = User::where('email', '=', $request->email)->where('phone', '=', $request->phone)->where('isVerifyed', '=', 1)->count();

        if (filter_var(filter_var(strtolower($request->email), FILTER_SANITIZE_EMAIL), FILTER_VALIDATE_EMAIL) && $checkEmailAndPhoneVerifyExist == 0 && $checkEamil == 0 && $checkPhone == 0) {
            $code = rand(1111, 9999);

            $accountSid     = config('app.twilio')['TWILIO_ACCOUNT_SID'];
            $authToken      = config('app.twilio')['TWILIO_AUTH_TOKEN'];
            $twilioNumber   = config('app.twilio')['TWILIO_APP_SID'];
            
            $client = new Client($accountSid, $authToken);
            
            try {
                // Use the client to do fun stuff like send text messages!
                $client->messages->create(
                // the number you'd like to send the message to
                    '+2'.$request->phone,
                array(
                    // A Twilio phone number you purchased at twilio.com/console
                        'from' => $twilioNumber,
                    // the body of the text message you'd like to send
                        'body' => 'Your Verification Is: ' . $code
                    )
                );
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage();
            }

            $updateVerify = User::where('phone', $request->phone)->update(['isVerifyed' => 0,'verification_code' => $code]);

            $user = new User;
            $user->username = $request->username;
            $user->phone = convert($request->phone);
            $user->email = strtolower($request->email);
            $user->password = Hash::make($request->password);
            $user->isVerifyed = 0;
            $user->verification_code = $code;
            $user->save();

            $response = [
                'status' => 1,
                'message' => 'Check Verification Code On Your Message',
                'data' => (object)[]
            ];
            return response()->json($response);
        } else {
            if($checkEmailAndPhoneVerifyExist > 0 || $checkEamil > 0 || $checkPhone > 0) {
                $error = 'Email Or Phone is taken before';
            } else {
                $error = 'Invalid Email';
            }
            $response = [
                'status' => 0,
                'message' => $error,
                'data' => (object)[]
            ];
            return response()->json($response);
        }
    }

    public function pinCode(Request $request) {
        $validator = validator()->make($request->all(),[
            'phone' => 'required|min:11|max:15',
            'pin_code' => 'required|max:4'
        ]);

        if ($validator->fails()){

            $response = [
                'status' => 0,
                'message' => $validator->errors()->first(),
                'data' => (object)[]
            ];
            return response()->json($response);

        }

        $user = User::where('verification_code', $request->pin_code)
            ->where('phone', $request->phone)
            ->where('isVerifyed', 0)
            ->count();

        if ($user > 0){
            $updateVerify = User::where('verification_code', $request->pin_code)
                ->where('phone', $request->phone)
                ->update(['isVerifyed' => 1, 'verification_code' => null]);

            $response = [
                'status' => 1,
                'message' => 'Successful',
                'data' => $user
            ];
            return response()->json($response);
        } else {
            $response = [
                'status' => 0,
                'message' => 'Invalid Verification Code',
                'data' => (object)[]
            ];
            return response()->json($response);
        }
    }

    public function profile(Request $request) {
        $validator= validator()->make($request->all(),[
            'id' => 'required',
        ]);

        if ($validator->fails()){
            $response = [
                'status' => 0,
                'message' => $validator->errors()->first(),
                'data' => (object)[]
            ];
            return response()->json($response);
        }

        $user = User::find($request->id);

        if($user){
            $response = [
                'status' => 1,
                'message' => 'Successfully',
                'data' => $user
            ];
        } else {
            $response = [
                'status' => 0,
                'message' => 'Failed',
                'data' => (object)[]
            ];
        }
        return response()->json($response);
    }

    public function updateProfile(Request $request) {

        $id = $request->id;
        
        $messages = [
            'phone.regex' => trans('admin.phone must be contain only numbers'),
            'email.regex' => trans('admin.enter your email like example@gmail.xyz'),
        ];
        
        $validator= validator()->make($request->all(),[
            'username' => 'required|string|max:50|unique:users,username,'.$id.',id|alpha_dash',
            'phone' => 'required|regex:/[0-9]/u|min:11|max:15|unique:users,phone,'.$id.',id',
            'email' => 'required|email|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix|unique:users,email,'.$id.',id',
            'new_password' => 'nullable|min:8|confirmed',
            'old_password' => 'nullable|min:8',
        ], $messages);

        if ($validator->fails()){

            $response = [
                'status' => 0,
                'message' => $validator->errors()->first(),
                'data' => (object)[]
            ];
            return response()->json($response);

        }

        $user = User::find($id);

        if ($user){

            if($request->old_password) {
                if(Hash::check($request->old_password, $user->password)){
                    $user->update([
                        'username' => $request->username,
                        'phone' => $request->phone,
                        'email' => $request->email
                    ]);

                    if($request->new_password) {
                        $user->update(['password' => Hash::make($request->new_password)]);
                    }
                } else {
                    $response = [
                        'status' => 0,
                        'message' => 'Your Password Is Not Correct, Try Again',
                        'data' => (object)[]
                    ];
                    return response()->json($response);
                }
                
            }
            
            $response = [
                'status' => 1,
                'message' => 'Updated Successfully',
                'data' => $user
            ];
            return response()->json($response);

        }else{

            $response = [
                'status' => 0,
                'message' => 'Failed',
                'data' => (object)[]
            ];
            return response()->json($response);

        }
    }

    public function logout(Request $request) {
        $validator = validator()->make($request->all(), [
            'token' => 'required'
        ]);

        if ($validator->fails()){
            $response = [
                'status' => 0,
                'message' => 'Invalid Email or Password',
                'data' => (object)[]
            ];
            return response()->json($response);
        }
 
        try {
            JWTAuth::invalidate($request->token);
 
            return response()->json([
                'status' => 1,
                'message' => 'User Logged Out Successfully',
                'data' => (object)[]
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'status' => 0,
                'message' => 'Sorry, This User Cannot Be Logged Out',
                'data' => (object)[]
            ], 500);
        }
    }
}
