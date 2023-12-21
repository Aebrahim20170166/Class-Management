<?php
$courseID;
if(session()->has('courseID'))
{
    $courseID=session()->get('courseID');
}
?>
@extends('layouts.sidebar')
@section('content')
    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    </head>
    <link href="{{asset('css/announcement/makeAnnouncement.css')}}" rel="stylesheet">

    <div class="d2 container ">
        <div class="row text-center">
            <h1 class="h1">{{$courseID}}</h1>
        </div>



        <div class="row text-center">
            @if(isset($error))
                <div  style="margin-left:30" class="alert alert-danger" role="alert">
                    {{$error}}
                </div>
            @endif
            <form ACTION="{{route('makepost')}}" method="post">
                @csrf

                <input type="hidden" name='courseID' value={{$courseID}}> <br>

                <span class="Announcement">Your Announcement</span>
                <br>
                <input class="s_name" type="text" name='announcement'  required> <br>
                <button type="submit" class="btn btn-defult btn-lg post" >post </button>
            </form>
        </div>
        <div class="butt" id="announcements">
            @if(isset($Announcements))
                @foreach($Announcements as $announce)
                    <div class="row">

                        <a href={{route('updatepost',['courseID' => $courseID,'postid'=>$announce->id,'body'=>$announce->body])}} >

                            <button type="button" class="btn button btn1" >  <span class=" glyphicon glyphicon-edit"></span></button></a>

                        <a href={{route('deletepost',['courseID' => $courseID,'postid'=>$announce->id,'body'=>$announce->body])}} >
                            <button type="button" class="btn button btn2" >   <span class=" glyphicon glyphicon-trash"></span></button>
                        </a>

                    </div>
                    <div class="ann"> {{$announce->body}}</div>

                @endforeach
            @endif

        </div>
    </div>

    <script>

        $.ajaxSetup({
            headers:{
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var makePost = function (post){
            // return  alert(post['announcement'].value)
            // return console.log(post['courseID'])
            $.ajax({
                url: "{{ route('makepost') }}",
                type: 'POST',
                data:{
                    courseID: post['courseID'].value,
                    announcement: post['announcement'].value
                },
                success:function(data){
                    alert(data);
                    console.log(data);

                },
            });
        }
    </script>
