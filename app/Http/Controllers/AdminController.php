<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\UserNetwork;
use DB;

class AdminController extends Controller
{
     /**
     *
     */
    public function showLogin(Request $request){

        return view('auth.login');
    }
     /**
     *
     */
    public function showDashboard(Request $request){
        $total_user             = User::where('user_type','1')->count();
        $data                   = [
            'total_user'        => $total_user,
            ];
        return view('dashboard',$data);
    }
    /***
     *
     */
    public function adminLogout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/admin');
    }
    /**
     *
     */
    public function showUser(){
        $user       = User::where('user_type','1')->withCount('network')->get();
        $data       = [
            'user'  => $user
        ];
        return view('admin.user',$data);
    }
}