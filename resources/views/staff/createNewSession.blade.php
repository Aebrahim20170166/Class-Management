<?php
session_start();
$instr_id;
$courseID;
if(session()->has('id') and session()->has('courseID'))
{
    $instr_id=session()->get('id');
    $courseID=session()->get('courseID');
}
?>
@extends('layouts.sidebar')
@section('content')
<link href="{{asset('css/session/createNewSession.css')}}" rel="stylesheet">

<div class="d2 container">
    <div class="row ">
        <form action="{{route('create_session')}}" method="post">
            {{@csrf_field()}}
            <input type="hidden" name='courseID' value={{$courseID}}> <br>
            <input type="hidden" name='instructorID' value={{$instr_id}}> <br>
            @if(Session::has('error'))
                <div class="error" role="alert">
                    {{Session::get('error')}}
                </div>
            @endif
            <p> Session <span> Name </span> </p>
            <input type="text" name="SessionName" id="SessionName">
            <br><br><br>

            <input  class="btn1" type="submit" value="Create" >
        </form>


    </div>

</div>
