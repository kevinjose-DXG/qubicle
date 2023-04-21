<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\UserNetwork;
use Exception;

class UserController extends Controller
{
    public function showRegistration(){
        return view('registration');
    }
    /**
     *
     */
    public function saveUserRegistration(Request $request){
        try{
            $rules = [
                'username'                      => 'required',
                'email'                         => 'required|unique:users,email,',
            ];
            $messages = [
                'username.required'             => 'Username is required',
                'email.required'                => 'Email is required',
            ];
            $validator = Validator::make(request()->all(), $rules, $messages);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
            }
            //generate referral code
            $referral_code                       = Str::random(10);
            if($request->refferal_code!=""){
                //check the refferal code exits or not
                $check_referral                 = User::where('referral_code',$request->refferal_code)->get();
                if(count($check_referral)>0){
                    $user                       = new User();
                    $user->name                 = $request->username;
                    $user->email                = $request->email;
                    $user->referral_code        = $referral_code;
                    $user->save();
                    //insertion to user network table
                    $user_network                   = new UserNetwork();
                    $user_network->user_id          = $user->id;
                    $user_network->parent_user_id   = $check_referral[0]['id'];
                    $user_network->referral_code    = $request->refferal_code;
                    $user_network->save();
                }else{
                    return response()->json(['status'=>false,'message'=>'Referral Code Not Found']);
                }
            }else{
                $user                       = new User();
                $user->name                 = $request->username;
                $user->email                = $request->email;
                $user->referral_code        = $referral_code;
                $user->save();
            }
            if($user){
                return response()->json(['status'=>true,'message'=>'Your Registration Done and Your Refferal Code is '.$user->referral_code]);
            }else{
                return response()->json(['status'=>false,'message'=>'Error']);
            }
        } catch (Exception $e){
            return response()->json(['status'=>false,'message'=>$e->getMessage()]);
        }
    }
}
