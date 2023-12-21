<?php
if(session()->has('id'))
    {
        /** @var TYPE_NAME $instructorID */
        $instructorID=session()->get('id');
    }
?>
    <!DOCTYPE html>
<html>
<head>
    <title> Join Course</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link  href="https://fonts.googleapis.com/css?family=Playfair+Display:400,700,700i" rel="stylesheet">
    <link href="{{asset('css/course/joinCourse.css')}}" rel="stylesheet">
</head>

<body id="JoinCourse">
<div class="d1">
    <nav class="navbar navbar-expand-lg bg-secondary navbar-fixed-top" id="mainNav">
        <div class="container-fluid">
            <div class="navbar-header">

                <h2 class="logo mr-auto "><a href="#">Eduance</a></h2>

            </div>

            <div class="collapse navbar-collapse" id="myNavbar">
                <ul class="nav navbar-nav">
                    <li><a href="{{route('home')}}">Home</a></li>
                    <li><a href="#">Profile</a></li>
                    <li><a class="active" href="{{route('joinCourse')}}">Join Course</a></li>
                    <li><a href="{{route('create_course')}}">Create Course</a></li>
                    <li class="LogOut"><a href="{{route('logout')}}">Log out</a></li>
                </ul>

            </div>
        </div>
    </nav>
</div>

<div class="d2 container">
    <div class="row ">
        <form action="{{route('joinCourse')}}" method="post">
            {{@csrf_field()}}
            @if(isset($success))
                <div class="alert alert-success row text-center" role="alert" >
                    {{$success}}
                </div>
            @endif
            <p> Course<span> Code </span> </p>
            <input type="text" name="courseID" id="CourseCode">
            <br><br><br>
            <input type="hidden" name="ID" value="{{$instructorID}}">
            <input type="hidden" name="role" value="instructor">
            <input  class="btn1" type="submit" value="Join">
        </form>


    </div>

</div>

</body>
</html>
