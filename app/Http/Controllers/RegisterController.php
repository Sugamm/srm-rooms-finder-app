<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Mail;   
use App\Register;
use App\Details;
use App\DayOrder;
use App\TimeTable;
use App\Table_User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use \Illuminate\Mail\Mailer;

class RegisterController extends Controller
{
    //
    public function index(Request $request)
    {
        $users = DB::table('user')->distinct()->get();

        return response(array(
                'error' => false,
                'users' =>$users,
               ),200); 
    }


    public function create(Request $request)
    {
        $name = $request->input('name');
        $reg_id = $request->input('reg_id');
        $email = $request->input('email');
        $acc_status = "Unverified";
        $default_password = "NA";
        $hash_token = md5(uniqid(rand()));;
        $fileUrl = "NA";

        if ($this->accessableEmail($email)) {
            $link = "http://directr.pratapraj056.com/verify/". $hash_token;
            if($this->checkUserEmail($email)){
                if($this->checkUserReg($reg_id)){
                    Mail::to($email)->send(new \App\Mail\Verification($name, $link));
                    if (Mail::failures()) {
                        $response = 'Invalid Email';
                    }else{
                        try{
                            $seachId= Details::where('Official_Email','=',$email)->where('Faculty_ID','=', $reg_id)->first();
                            if ($seachId == null) {
                                
                                DB::table('table_user')->insert(
                                ['u_name' => $name, 'u_image'=> $fileUrl,  'u_email'=> $email, 'u_reg_id' => $reg_id, 'u_password'=>$default_password, 'u_status'=>$acc_status, 'u_token'=> $hash_token]
                                );
                                $response = "true";
                            }else{
                                DB::table('table_user')->insert(
                                ['u_name' => $name, 'u_image'=> $fileUrl,  'u_email'=> $email, 'u_reg_id' => $reg_id, 'u_password'=>$default_password, 'u_status'=>$acc_status, 'u_token'=> $hash_token]
                                );
                                $response = "true";
                            }
                        }catch(\Exception $e){
                            $response = $e->getmessage();
                        }
                    }
                }else{
                    $response = "Register Id is already registered.";    
                }
            }else{
               $response = $this->getUserStatus($email);
            }
        }else{
            $response = "Sorry! this email address is not registered for using this application";  
        }
        return response(array('status'=>$response));
    }

    public function accessableEmail($value)
    {
        try{
            $email = DB::table('table_access')->select('a_email')->where('a_email','=',$value)->first();
            if ($email->a_email == $value) {
                return true;
            }
        }catch(\EXCEPTION $e){
            return false; 
        }
    }

    public function imageUpload(Request $request)
    {
        $file = $request->file('image');
        $email = $request->input('email');
        $reg_id = $request->input('reg_id');
        $ext = $file->getClientOriginalExtension();
        $fileOrg = $file->getClientOriginalName();
        $filename = "users/img-".substr($reg_id, -3)."-".$fileOrg;
        $fileimage = "img-".substr($reg_id, -3)."-".$fileOrg;
        //Move Uploaded File
        $fileUrl = "http://directr.pratapraj056.com/showimage/".$fileimage;
        Storage::disk('local')->put($filename, File::get($file));
        DB::table('table_user')->where('u_email', $email)->update(
            ['u_image'=> $fileUrl]
        );
        return response(array('status'=>'true'));
    }

    public function show($id)
    {
         $users = DB::table('user')->where('id', $id)->get();
         
        return response(array(
                'error' => false,
                'users' =>$users,
               ),200); 
    }

    public function update($id)
    {
        $name = $request->input('name');
        $r_id = $request->input('r_id');
        $email = $request->input('email');
        $branch = $request->input('branch');
        $year = $request->input('year');    
        $number = $request->input('phone');
        $designation = $year.". ". $branch;
        $type = $request->input('type');
        DB::table('user')->where('id', $id)->update(['number' => $number]);
        return response(array(
                'error' => false,
                'message' =>'Product updated successfully',
               ),200);
    }

    public function destroy($id)
    {
        Register::find($id)->delete();
        return response(array(
                'error' => false,
                'message' =>'Product deleted successfully',
               ),200);
    }


    public function checkUserEmail($emailtocheck)
    {
        $userEmail = DB::table('table_user')->select('u_email')->where('u_email', $emailtocheck)->first();
        if (isset($userEmail)) {
            if ($userEmail->u_email == $emailtocheck) {
              return false; 
            }            
        }else{
            return true;
        }
        return true;
    }

    public function getUserStatus($emailtocheck)
    {
        $userEmail = DB::table('table_user')->select('u_status')->where('u_email', $emailtocheck)->first();
        if ($userEmail->u_status == "Verified") {
               $response = "Email is already registered.";                        
        }else{
             $response = "Please Verify Your Email!";
        }
        return $response;
    }

   
    public function checkUserReg($reg_idtocheck)
    {
        $userReg = DB::table('table_user')->select('u_reg_id')->where('u_reg_id', $reg_idtocheck)->first();
        if (isset($userReg)) {
            if ($userReg->u_reg_id == $reg_idtocheck) {
               return false;
            }
        }else{
            return true;
        }
        return true;
    }
}
