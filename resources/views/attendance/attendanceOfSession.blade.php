@extends('layouts.sidebar')
@section('content')

    <link href="{{asset('css/attendance/attendacneOfSession.css')}}" rel="stylesheet">

<div class="container d2 text-center">
    <div class=" title ">
        <h1>{{$sessionName}}</h1>
    </div>
    <div class="row text-center">
        <table >
            <tr >
                <th>Student <span> ID</span> </th>
                <th>Student <span> Name</span> </th>

            </tr>
            @foreach($attendances as $attendance)
                <tr>
                    <td>{{$attendance->student_id}}</td>
                    <td>{{$attendance->Fname." ".$attendance->Lname}}</td>
                </tr>
            @endforeach
        </table>

    </div>
</div>

