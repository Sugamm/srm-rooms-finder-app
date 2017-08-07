@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading">Search Logs</div>

                <div class="panel-body" style="padding: 0;">
                  <table class="table table-fixed" style="">
                    <thead>
                      <tr>
                        <th class="col-xs-1">log id</th>
                        <th class="col-xs-2">User Name</th>
                        <th class="col-xs-2">Emp Id</th>
                        <th class="col-xs-1">Room</th>
                        <th class="col-xs-2">Block</th>
                        <th class="col-xs-2">Time & date</th>
                        <th class="col-xs-2">Result</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($logs as $log)
                        <tr>
                            <td class="col-xs-1">{{$log->log_id}}</td>
                            <td class="col-xs-2">{{$log->u_name}}</td>
                            <td class="col-xs-2">{{$log->u_reg_id}}</td>
                            <td class="col-xs-1">{{$log->search_room}}</td>
                            <td class="col-xs-2">{{$log->search_block}}</td>
                            <td class="col-xs-2">{{$log->search_time}} & {{$log->search_date}}</td>
                            <td class="col-xs-2">{{$log->result}}</td>
                          </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">Feedbacks</div>

                <div class="panel-body" style="padding: 0;">
                  <table class="table table-fixed">
                    <thead>
                      <tr>
                        <th class="col-xs-1">Name</th>
                        <th class="col-xs-1">Emp</th>
                        <th class="col-xs-2">Subject</th>
                        <th class="col-xs-2">Type</th>
                        <th class="col-xs-8">Message</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($feedbacks as $feedback)
                        <tr>
                            <td class="col-xs-1">{{$feedback->u_name}}</td>
                            <td class="col-xs-1">{{$feedback->u_reg_id}}</td>
                            <td class="col-xs-2">{{$feedback->f_subject}}</td>
                            <td class="col-xs-2">{{$feedback->f_type}}</td>
                            <td class="col-xs-6">{{$feedback->f_message}}</td>
                          </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
