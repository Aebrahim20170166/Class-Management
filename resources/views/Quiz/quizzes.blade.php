<?php
if(session()->has('courseID'))
{
$courseID=session()->get('courseID');
}?>
@extends('layouts.sidebar')
@section('content')
    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <link href="{{asset('css/quiz/quizzes.css')}}" rel="stylesheet">
    </head>


    <div class="container">

        <div class="container d2">
            <div>
                <div class=" title text-center">
                    <h1> Your <span> Quizzes </span></h1>
                </div>
                <div class="Quizs text-center">

            <table>
                @foreach($quizes as $quiz)
                <tr>
                    @if($quiz->status == '1')
                        <td>{{$quiz->topic}}</td>
                        <td>{{$quiz->date}}</td>
                        <td>already published</td>

                    @else
                        <td><a href={{route('showQuiz',['quizID' => $quiz->id])}}>{{$quiz->topic}}</a></td>
                        <td>{{$quiz->date}}</td>
                        <td id="{{$quiz->id}}">
                            <form action="{{route('publishQuiz')}}" method="post">
                                {{@csrf_field()}}
                                <input type="hidden" value="{{$quiz->id}}" name="quizID">
                                <input type="hidden" value="{{session('courseID')}}" name="courseID">
                                <input type="submit" value="publish" class="btn5">
                            </form>
                        </td>
                    @endif

                </tr>

                @endforeach
            </table>


                    <a href={{route('createQuiz',['courseID' => session('courseID')])}}><button type="button" class="btn5" > <span class="glyphicon glyphicon-check"></span> create quiz</button></a>


                </div>
            </div>
        </div>

    </div>
<script>
    $.ajaxSetup({
        headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var publishQuiz = function (quizID){
        // console.log(quiz)
        $.ajax({
            url: "{{ route('publishQuiz') }}",
            type: 'POST',
            data:{
                id: quizID
            },
            success:function(data){
                console.log(data);
            },
            error:function(xhr,status,error){
                $.each(xhr.responseJSON.errors,function (key,item)
                    {
                        alert(item)
                    }
                );
                // alert(data.code);
            }
        });
    }
</script>
