<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'room'], function() {
    //Search
    Route::post('/search', 'SearchController@roomNumber');
    Route::post('/register', 'RegisterController@create');
    Route::post('/uploadfile', 'RegisterController@imageUpload');
    Route::post('/contactus', 'SearchController@contactUs');
    Route::post('login', function(Request $request) {
        $email = $request->input('email');
        $pass = $request->input('password');
        try{
            $users = DB::table('table_user')->select('u_id','u_name','u_reg_id','u_email','u_password', 'u_image')->where('u_email', $email)->first();
            $useremail = $users->u_email;
            $userpassword = $users->u_password;
            if ($email === $useremail && $pass === $userpassword) {
                return response(array("status"=>"success", "UserData"=>$users));

            }else{
                return response(array("status"=>"Please enter correct password!"));
            }
        }catch(\Exception $e){
            return response(array('status'=>"Please enter correct Email Address!", "Message"=>$e->getmessage()));
        }
    });

    Route::post('/users', function(Request $key){
        $keyword = $key->input('key');
        $table =DB::table('table_user')->where('u_name','like','%'.$keyword.'%')->orWhere('u_email','like','%'.$keyword.'%')->orWhere('u_reg_id','like','%'.$keyword.'%')->get();

        return response(array("user"=>$table));

    });
});


Route::get('showimage/{filename}', function ($filename)
{
    $path = storage_path('app/users/' . $filename);

    if (!File::exists($path)) {
        abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
});


Route::get('verify/{token}', function($token) {
    try{
        $users = DB::table('table_user')->select('u_name','u_email','u_reg_id','u_token')->where('u_token', $token)->first();
         DB::table('table_user')->where('u_token', $token)->update(['u_status' => "Verified"]);
        return view('verify', ['token'=>$token, 'name'=>$users->u_name]);
    }catch(\Exception $e){
        return "Invalid Token 1 : ". $e->getmessage();
    }
});



Route::post('changepassword', function(Request $request){
    try{        
        $token = $request->input('token');
        $pass = $request->input('password');
        $pass1 = $request->input('password1');
        $users = DB::table('table_user')->select('u_name','u_token')->where('u_token', $token)->first();
        $u_name =$users->u_name;
        if ($token == $users->u_token) {
            if ($pass === $pass1) {
                DB::table('table_user')->where('u_token', $token)->update(['u_password' => $pass]);
                return view('success', ['error'=> "matched",'name'=>$u_name]);
            }else{
                return view('success', ['error'=>"notmatched",'name'=>$u_name]);
            }
        }else{
            return view('success', ['error'=>"token",'name'=>$u_name]);
        }      
    }catch(\Exception $e){
        return view('success', ['error'=>"exception", 'name'=>"Sorry, Some mistake exists!"]);
    }
})->name('changepassword');



Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


