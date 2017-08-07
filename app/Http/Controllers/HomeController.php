<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $log = DB::table('table_search_log')->join('table_user','table_user.u_id','=','table_search_log.user_id')->orderBy('searched_time', 'desc')->get();

        $feedbacks = DB::table('table_feedback')->join('table_user','table_user.u_id','=','table_feedback.user_f_id')->orderBy('feedback_time', 'desc')->get();

        return view('home', ["logs"=>$log, "feedbacks"=>$feedbacks]);
    }
}
