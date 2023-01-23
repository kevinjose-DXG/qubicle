<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use App\Models\VendorPlan;
use App\Models\VendorDetail;
use App\Models\VendorFlyer;

class CheckPlanDate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:plan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Plan Date Daily';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $today_date     = date('Y-m-d');
        $next_date      = date('Y-m-d', strtotime($today_date . " +1 days"));
        $vendor_flyer   = VendorFlyer::where('end_date',$today_date)->count();
        if($vendor_flyer>0){
            for($i=0;$i<$vendor_flyer;$i++){

                $vendor_flyers          = VendorFlyer::where('end_date',$today_date)->where('admin_approved','1')->first();
                $vendor_flyers->status  = 'inactive';
                $vendor_flyers->save();
            }
        }
        //plan inactivate 
        $plan_inactive    = VendorPlan::where('end_date',$today_date)
                                        ->where('plan_status','active')
                                        ->where('payment_status','paid')
                                        ->where('payment_approval','active')
                                        ->count();
        $plan_active    = VendorPlan::where('start_date',$next_date)
                                    ->where('plan_status','inactive')
                                    ->where('payment_status','paid')
                                    ->where('payment_approval','active')
                                    ->count();                                
            if($plan_inactive>0){
                for($i=0;$i<$plan_inactive;$i++){
                    $plan_inactivate =  VendorPlan::select('id','vendor_id','start_date','plan_status','payment_approval','vendor_plan_status')
                                        ->where('end_date',$today_date)
                                        ->where('plan_status','active')
                                        ->where('payment_status','paid')
                                        ->where('payment_approval','active')
                                        ->first();
                    $plan_inactivate['plan_status']           = 'expired';
                    $plan_inactivate['vendor_plan_status']    = 'inactive';
                    $plan_inactivate['payment_status']        = 'notpaid';
                    $plan_inactivate['payment_approval']      = 'inactive';
                    $plan_inactivate->save();
                    $vendor_detail                                  = VendorDetail::where('vendor_id',$plan_inactivate->vendor_id)->first();
                    $vendor_detail->plan_id                         = '0';
                    $vendor_detail->plan_payment_status             = 'notpaid';
                    $vendor_detail->vendor_plan_activated           = '0';
                    $vendor_detail->admin_approved_vendor_payment   = '0';
                    $vendor_detail->save();     
                    $vendor_flyer_count                             = VendorFlyer::where('plan_id',$plan_inactivate->id)->count();
                    if($vendor_flyer_count>0){
                        $vendor_flyer                               = VendorFlyer::where('plan_id',$plan_inactivate->id)->update(['status' => 'inactive']);
                    }
                }
            }
            //plan active function
            if($plan_active>0){
                for($i=0;$i<$plan_active;$i++){
                    $plan_activate                                  =  VendorPlan::select('id','vendor_id','start_date','plan_status','payment_approval','vendor_plan_status')
                                                                                ->where('start_date',$next_date)
                                                                                ->where('plan_status','inactive')
                                                                                ->where('payment_status','paid')
                                                                                ->where('payment_approval','active')
                                                                                ->first();
                    $plan_activate['plan_status']                   = 'active';
                    $plan_activate['vendor_plan_status']            = 'active';
                    $plan_activate->save();
                    $vendor_detail                                  = VendorDetail::where('vendor_id',$plan_activate->vendor_id)->first();
                    $vendor_detail->plan_id                         = $plan_activate->id;
                    $vendor_detail->plan_payment_status             = 'paid';
                    $vendor_detail->vendor_plan_activated           = '1';
                    $vendor_detail->admin_approved_vendor_payment   = '1';
                    $vendor_detail->save();
                    $vendor_flyer_count                             = VendorFlyer::where('vendor_id',$vendor_detail->vendor_id)->count();
                    if($vendor_flyer_count>0){
                        $vendor_flyer                               = VendorFlyer::where('vendor_id',$vendor_detail->vendor_id)->update(['plan_id' => $vendor_detail->plan_id]);
                    }      
                }
            }
   }
}