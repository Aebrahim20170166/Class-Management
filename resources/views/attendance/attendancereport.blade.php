@extends('layouts.sidebar')
@section('content')

<link href="{{asset('css/attendance/attendanceReport.css')}}" rel="stylesheet">
<div class="d2 container">
    <div class=" title ">
        <h2> students attend the lectures <span> frequently </span></h2>
    </div>
    <div class=" text-center">
        <table>
            <tr>
                <th>Student <span> Name</span> </th>
                <th>Student <span> ID</span> </th>
            </tr>
        @for($i=0;$i<sizeof($regularstudents['name']);$i++)

                <tr>
                    <td>{{$regularstudents['name'][$i]}} </td>
                    <td>{{$regularstudents['id'][$i]}}</td>

                </tr>

            @endfor


            </table>
    </div>


</div>
