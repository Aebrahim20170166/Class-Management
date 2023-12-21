<?php
session_start();
if(session()->has('id'))
    {
        $instructorID=session()->get('id');
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link  href="https://fonts.googleapis.com/css?family=Playfair+Display:400,700,700i" rel="stylesheet">
    <link href="{{asset('css/course/courses.css')}}" rel="stylesheet">
</head>
<body id="HomePage">
<!-- Navbar-->
<div class="d1 navbar-fixed-top">
    <nav class="navbar navbar-expand-lg bg-secondary navbar-fixed-top" id="mainNav">
        <div class="container-fluid">
            <div class="navbar-header">

                <h2 class="logo mr-auto "><a href="#">Eduance</a></h2>

            </div>

            <div class="collapse navbar-collapse" id="myNavbar">
                <ul class="nav navbar-nav">
                    <li><a class="active" href="#section1">Home</a></li>
                    <li><a href="{{route('join_course')}}">Join Course</a></li>
                    <li><a href="{{route('create_course')}}">Create Course</a></li>
                    <li><a href="{{route('logout')}}">Log Out</a></li>
                </ul>

            </div>
        </div>
    </nav>
</div>

@if(isset($success))
    <div class="alert alert-success row text-center" role="alert" >
        {{$success}}
    </div>
@endif

<div class="container">



    <div class="d2  text-center">
        <div class="title">
            <h1>Your <span> Courses </span> </h1>
        </div>

        @if(!is_null($courses))
            @foreach($courses as $course)
                <div class="row">
                    <div >
                        <a href="/courseView/{{$course->course_id}}">
                            <button type="button" class="btn btn-defult btn-lg" > {{$course->name}}  </button>
                        </a>
                    </div>

        {{-- <a href="#"><span class="gl glyphicon glyphicon-minus-sign"></span> </a>--}}
                    <div>
                        <form action="{{route('delete_course')}}" method="post">
                            {{@csrf_field()}}
                            <input type="hidden" name="courseID" value="{{$course->course_id}}">
                            <input type="hidden" name="instructorID" value="{{$instructorID}}">
                            <button type="submit"><span class="gl glyphicon glyphicon-minus-sign"></span></button>
                        </form>
                    </div>
                </div>

            @endforeach
        @endif
    </div>
{{--
        @if(!is_null($courses))
            @foreach($courses as $course)
                <div class="row">
                    <div class="col-sm-6 text-center">
                        <a class="btn btn-defult btn-lg" href="/courseView/{{$course->course_id}}">
                            {{$course->name}}  </a>
                    </div>
                    <div col sm-6>
                        <form action="{{route('delete_instructor_course')}}" method="post">
                            {{@csrf_field()}}
                            <input type="hidden" name="courseID" value="{{$course->course_id}}">
                            <input type="hidden" name="instructorID" value="{{$instructorID}}">
                            <button type="submit"><span class="gl glyphicon glyphicon-minus-sign"></span></button>
                        </form>
                    </div>

                </div>
            @endforeach
            <div class="row">
                <a href="{{route('create_course')}}"> <span class="gl1 glyphicon glyphicon-plus-sign"></span> </a>

            </div>
        @endif
        --}}

</div>
</body>
</html>

