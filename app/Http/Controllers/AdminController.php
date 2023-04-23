<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\UserNetwork;
use App\Models\Level;
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
    function getLevelPoints($level){
        $points             = 0;
        $level              = 'Level '.$level;
        $level_points       = Level::select('points')->where('title',$level)->first();
        if($level_points==""){
            return 0;
        }
        return $level_points->points;

    }
    /**
     * 
     */
    function getTotalReferralPoints($user_id,$level){
        $total_points   = 0;
        $level_name     = '';
        $user_network = UserNetwork::where('parent_user_id',$user_id)->get();
        if(count($user_network)==0){
            return 0;
        }
        foreach($user_network as $row){
            $next_user_id   = $row->user_id;
            $points         = $this->getLevelPoints($level);
            $total_points += $points;
            // If there are more levels to check, recursively call function
            if ($level > 1) {
                $total_points += $this->getTotalReferralPoints($next_user_id, $level - 1);
            }
        }
        return $total_points;
    }
    /**
     *
     */
    public function showUser(){
        $user               = User::where('user_type','1')->get();
        $total_points       = 0;
        $user_details       = [];
        foreach($user as $key=> $row){
            $user_id                                = $row->id;
            $user_details[$key]['user_name']        = $row->name;
            $total_points                           = $this->getTotalReferralPoints($user_id,10);
            $user_details[$key]['user_id']          = $user_id;
            $user_details[$key]['total_points']     = $total_points;
        }
        return view('admin.user',['user_details'=>$user_details]);
    }
    
}