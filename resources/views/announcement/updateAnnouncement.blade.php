<?php
session_start();
$courseID;
if(session()->has('courseID'))
{
    $courseID=session()->get('courseID');
}
?>

@extends('layouts.sidebar')
@section('content')
    <link href="{{asset('css/announcement/updateAnnouncement.css')}}" rel="stylesheet">
<div class="d2 container">
    <div class="row  text-center">
        <h1>{{$courseID}}</h1>
    </div>


    <div class="row text-center">

        <form action="{{route('saveupdate')}}" method="get">
        @csrf <!-- {{ csrf_field() }} -->

            <input type="hidden" name='courseID' value={{$courseID}}> <br>
            <input type="hidden" name='postid' value={{$postid}}> <br>
            <span  class="Announcement">Announcement</span>
            <br>
            <input class="s_name" type="text" name='body' value="{{$body}}" required> <br>
            <button type="submit" class="btn btn-defult btn-lg update"> update </button>
        </form>
    </div>

</div>

