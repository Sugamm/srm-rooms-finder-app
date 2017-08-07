<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Details;
use App\DayOrder;
use App\TimeTable;
use DB;
use Mail;
use \Illuminate\Mail\Mailer;
class SearchController extends Controller
{
    public function roomNumber(Request $request)
    {
    	try{
            // day Formate 20-Jan-16
            $currentDate = $request->input('current_day');

            //
            $roomNumber = $request->input('room');
            $block=$request->input('block');

            // time in 24 hr formate h:m:s
            $currentTime = $request->input('current_time');

            // Prefix's of all Blocks
            if ($block =="TECH PARK") {
                $roomNumber = 'TP '.$roomNumber;
            }elseif ($block =="ELEC.SCIENCE") {
                $roomNumber = 'ESB'.$roomNumber;
            }elseif ($block =="CRC BUILDING") {
                $roomNumber = 'CRC '.$roomNumber;
            }elseif ($block == "MECHANICAL A") {
                 $roomNumber = 'M'.$roomNumber;
            }elseif ($block == "MAIN") {
                 $roomNumber = 'MB'.$roomNumber;
            }elseif ($block == "BIO ENGINEERING") {
                 $roomNumber = 'B'.$roomNumber;
            }elseif ($block == "OLD LIBRARY") {
                 $roomNumber = 'L'.$roomNumber;
            }elseif ($block == "HI-TECH") {
                 $roomNumber = 'H'.$roomNumber;
            }

            // GET Day Order from Date and Time
            $ArrDayOrder = $this->getDayOrder($currentDate);
            $dayOrder=$ArrDayOrder->Day_Order;
            $AcademicYear = $ArrDayOrder->Academic_Year;
            $month = $ArrDayOrder->Month;

            // GET slot from Dayorder
            $getSlot=$this->getSlot($currentTime, $dayOrder);

            // Define Data Array
            $data = array();

            // Loop for 2 Value on the same day order!
            foreach ($getSlot as $value) {
                $s = $value->t_slot;
                if ($month >=7 && $month <=12) {
                    // odd
                    $data[]=$this->getFacultyDetails($roomNumber, $block, $s, $currentTime);
                }elseif($month >=1 && $month <=6){
                    // even
                    $data[]=$this->getFacultyDetails($roomNumber, $block, $s, $currentTime);
                }            
            }

            $user_id = $request->input('u_id');
            // if (!empty($data)) {
            //     foreach ($data as $v) {
                    // if ($data){
                    //      $this->EnterInLog($user_id, $roomNumber, $block, $currentTime, $currentDate, "Found", "1");
                    // }else{
                    //     $this->EnterInLog($user_id, $roomNumber, $block, $currentTime, $currentDate, "Not Found", "Null");
                    // }
            //     }
            // }

            if ($data[0] ===null && $data[1]===null) {
                $this->EnterInLog($user_id, $roomNumber, $block, $currentTime, $currentDate, "Not Found", NULL);
                return response(array("status"=>'Ensure that entered data is correct or entered room is vacant!',"Details"=>null)); 
            }else{
                $this->EnterInLog($user_id, $roomNumber, $block, $currentTime, $currentDate, "Found", "1");
                return response(array("Details"=>$data,'status'=>'true'));
            }
        }catch(\EXCEPTION $e){
            if ($e->getMessage() == "Undefined offset: 0") {
                return response(array("status"=>"At this time '".$roomNumber."' of '".$block."' is vacant!","Details"=>null));
            }elseif ($e->getMessage() == "Undefined offset: 1") {
                return response(array("status"=>"At this time '".$roomNumber."' of '".$block."' is vacant!","Details"=>null));
            }else{
                return response(array("status"=>"Okay ".$e->getMessage(),"Details"=>null));
            }
        }	
    }

    public function getDayOrder($day)
    {
    	$getDayOrder = DayOrder::where('o_Date',$day)->first();

    	return $getDayOrder;
    }

    public function getSlot($currentTime, $do)
    {
    	$getSlot = TimeTable::where('t_day_order','=',$do)
				->where(function ($query) use ($currentTime) {
					    $query->where('class_from', '<=', $currentTime);
					    $query->where('class_to', '>=', $currentTime);
					})->get();
		return $getSlot;
    }

    public function getFacultyDetails($roomNo, $block, $slot, $currentTime)
    {

    	if ($slot == "A" || $slot == "B" || $slot == "C" || $slot == "D" || $slot=="E" || $slot=="F" || $slot == "G") {
            // REPLACE(REPLACE(Block, CHAR(13), ''), CHAR(10), '')
            $getFacultyDetail = DB::table('table_detail')
                                ->join('table_time','table_time.t_slot','=','table_detail.Slot')
                                ->select('d_id','Program','Semester','Course_Code_Title','Course_Title','Offering_Department','Slot','Faculty_ID','Faculty_Name','Mobile_No','Official_Email','Offered_To','Lab_Slots','Class_Room','Block','table_time.t_id','table_time.class_from','table_time.class_to','table_time.t_day_order','table_time.t_slot','table_time.Batch')
                                ->where('Class_Room','=',$roomNo)
                                ->where('Block','=', $block)
                                ->where('Slot','=',$slot)
                                ->where(function ($query) use ($currentTime) {
                                    $query->where('table_time.class_from', '<=', $currentTime);
                                    $query->where('table_time.class_to', '>=', $currentTime);
                                })
                                ->first();
           return $getFacultyDetail;

        }else{
            // $labData = Details::where('Class_Room',$roomNo)
            //                             ->where('Block',$block)
            //                             ->whereRaw('FIND_IN_SET(?, Lab_Slots)', [$slot])
            //                             ->first();
            return null;
        }   	
    }

    public function EnterInLog($user, $room, $block, $time, $date, $result, $d){
        
        $result = DB::table('table_search_log')->insert(['user_id'=>$user, 'search_room'=>$room, 'search_block'=>$block, 'search_time'=>$time, 'search_date'=>$date, 'result'=>$result, 'detail_id'=>$d]);
        if ($result) {
            return true;
        }
        return false;
    }


    /* 
    * Contact Us / FeedBack Form 
    */
    public function contactUs(Request $request)
    {
        // try{
            $u_id = $request->input('u_id');
            $subject = $request->input('subject');
            $type = $request->input('type');
            $msg = $request->input('message');

            DB::table('table_feedback')->insert(['user_f_id'=>$u_id, 'f_subject'=>$subject, 'f_type'=>$type, 'f_message'=>$msg]);
            if ($u_id != -1) {
                $userData = DB::table('table_user')->select('u_name','u_email','u_reg_id')->where('u_id','=',$u_id)->first();
                $name = $userData->u_name;
                $u_email = $userData->u_email;
                $u_reg = $userData->u_reg_id;            
            }else{
                $name = "New User";
                $u_email = "New User";
                $u_reg = "New User";
            }
            $email = "malviya.sugam@gmail.com";
            Mail::to($email)->send(new \App\Mail\feedbackMail($name, $u_email, $u_reg, $subject, $type, $msg));
            return response(array("status"=>"true"));
        // }catch(\EXCEPTION $e){
        //     return response(array("status"=>$e->getMessage()));
        // }
    }
   
}
