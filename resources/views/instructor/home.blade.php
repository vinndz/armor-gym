@extends('layouts.master')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/homePage.css') }}">
@endsection

@section('content')


<!-- Page Content -->
<div class="container bootstrap snippets bootdey  animate__animated animate__fadeIn">
    <div class="row mt-5">
        <div class="col-md-4 col-sm-6 mt-4">
            <a href="{{route('program-data.index')}}">
                <div class="panel panel-dark panel-colorful rounded">
                    <div class="panel-body text-center">
                        <p class="text-uppercase mar-btm text-sm">Data Program</p>
                        <i class="fa fa-solid fa-database fa-5x"></i>
                        <hr>
                        <p class="h2 text-thin" style="color:white" id="dataProgramCount">{{$program}} </p>
                        <small><span class="text-semibold"><i class="fa fa-solid fa-database fa-fw"></i></span> Total All Program in Armor Gym</small>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4 col-sm-6 mt-4">
            <a href="{{route('program-member.index', ['instructor_id' => $instructorId]) }}">
                <div class="panel panel-danger panel-colorful rounded">
                    <div class="panel-body text-center">
                        <p class="text-uppercase mar-btm text-sm">Program Member</p>
                        <i class="fa fa-users fa-5x"></i>
                        <hr>
                        <p class="h2 text-thin" style="color:white" id="programMemberCount">{{$programMember}}</p>
                        <small><span class="text-semibold"><i class="fa fa-users fa-fw"></i></span> Total Program Member</small>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4 col-sm-6 mt-4">
            <a href="{{route('gym-schedule.index',['instructor_id' => $instructorId]) }}">
                <div class="panel panel-primary panel-colorful rounded">
                    <div class="panel-body text-center">
                        <p class="text-uppercase mar-btm text-sm">Schedule Gym</p>
                        <i class="fa-solid fa-calendar-days"></i>
                        <i class="bx bxs-calendar fa-5x"></i>
                        <hr>
                        <p class="h2 text-thin" style="color:white" id="scheduleGymCount">{{$scheduleGym}}</p>
                        <small><span class="text-semibold"><i class="bx bxs-calendar fa-fw"></i></i></span>Total All Schedule Gym</small>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function() {
        // Function to animate the count
        function animateCount(targetElement, startCount, endCount, duration) {
            let currentCount = startCount;
            let increment = Math.ceil((endCount - startCount) / duration);
            let interval = setInterval(function() {
                currentCount += increment;
                if (currentCount >= endCount) {
                    clearInterval(interval);
                    currentCount = endCount;
                }
                $(targetElement).text(currentCount);
            }, 100); // Adjust the interval as needed
        }

        // Start animation
        animateCount("#dataProgramCount", 0, {{ $program }}, 5);
        animateCount("#programMemberCount", 0, {{ $programMember }}, 5);
        animateCount("#scheduleGymCount", 0, {{ $scheduleGym }}, 5); 
    });
</script>

@endsection