@extends('layouts.master')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/homePage.css') }}">
@endsection

@section('content')


<!-- Page Content -->
<div class="container bootstrap snippets bootdey  animate__animated animate__fadeIn">
    <div class="row mt-5 justify-content-center">
        <div class="col-md-4 col-sm-6 mt-4">
            <a href="{{ route('gym-schedule.index-member',['member_id' => $memberId]) }}">
                <div class="panel panel-dark panel-colorful rounded text-center">
                    <div class="panel-body">
                        <p class="text-uppercase mar-btm text-sm">Schedule Gym</p>
                        <i class="bx bxs-calendar fa-5x"></i>
                        <hr>
                        <p class="h2 text-thin text-white" id="schedulePresent">{{ $schedulePresent }}</p>
                        <small><span class="text-semibold"><i class="bx bxs-calendar fa-fw"></i></span> Total All Schedule Gym Present</small>
                        <p class="h2 text-thin text-white" id="scheduleNotPresent">{{ $scheduleNotPresent }}</p>
                        <small><span class="text-semibold"><i class="bx bxs-calendar fa-fw"></i></span> Total All Schedule Gym Not Present</small>
                    </div>
                </div>
            </a>
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
        animateCount("#scheduleNotPresent", 0, {{ $scheduleNotPresent }}, 5);

        animateCount("#schedulePresent", 0, {{ $schedulePresent }}, 5);
    });
</script>
@endsection