<?php
session_start();
if(session()->has('id'))
{
    $instr_id=session()->get('id');
}
?>
{{--@extends('layouts.sidebar')--}}
{{--@section('content')--}}
    <link href="{{asset('css/course/createCourse.css')}}" rel="stylesheet">

<body id="CreateCourse">
<div class="d2 container">
    <div class="row ">
        <form action="{{route('addCourse')}}" method="post">
            {{@csrf_field()}}
            @if(isset($success))
                <div class="alert alert-success row text-center" role="alert" >
                    {{$success}}
                </div>
            @endif

            <p> Course <span> Name </span> </p>
            <input type="hidden" name="ID" value='{{$instr_id}}'>

            <input type="text" name="name"  id="CourseName">

            <br><br>
            <p> Course <span> Code </span> </p>
            <input type="text" name="courseID"  id="CourseCode">

            <br><br><br>

            <input  class="btn1" type="submit" value="Create">
        </form>


    </div>

</div>


