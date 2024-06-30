@extends('layouts.master')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/homePage.css') }}">
@endsection

@section('content')
    {{-- <div id="container">
        <div class="row animate__animated animate__fadeIn justify-content-center p-4">
            <div class="col-md-4 col-sm-6 mt-4">
                <a href="{{ route('instructor-data.index') }}" class="text-decoration-none text-dark">
                    <div class="card bg-c-blue order-card">
                        <div class="card-block">
                            <h6 class="m-b-20 text-center" style="color:white">Total Instructor</h6>
                            <h2 class="text-right text-center"><i class='bx bx-user' style="color:white"></i><span id="instructorCount" style="color:white">
                                    {{ $instructor }}</span></h2>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-4 col-sm-6 mt-4">
                <a href="{{ route('membership-transaction.index') }}" class="text-decoration-none text-dark">
                    <div class="card bg-c-yellow order-card">
                        <div class="card-block text-center">
                            <h6 class="m-b-20" style="color:white">Total Membership Member</h6>
                            <h2 class="text-right"> <i class='bx bx-group' style="color:white"></i><span style="color:white"
                                    id="membershipCount"> {{ $membershipTr }} </span></h2>
                            <h6 class="m-b-20" style="color:white">Active Membership</h6>
                            <h2 class="text-right"> <i class='bx bx-user-check' style="color:white"></i><span style="color:white"
                                    id="activeCount"> {{ $active }} </span></h2>
                            <h6 class="m-b-20" style="color:white">Expired Membership</h6>
                            <h2 class="text-right"> <i class='bx bx-user-x' style="color:white"></i><span style="color: white"
                                    id="notActiveCount"> {{ $notActive }} </span></h2>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-4 col-sm-6 mt-4">
                <a href="{{ route('membership-data.index') }}" class="text-decoration-none text-dark">
                    <div class="card bg-c-green order-card">
                        <div class="card-block text-center">
                            <h6 class="m-b-20" style="color:white">Type Membership</h6>
                            <h2 class="text-right"> <i class='bx bxs-data' style="color:white"></i><span id="membershipDataCount" style="color:white"> {{ $membership }} </span></h2>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-4 col-sm-6 mt-4">
                <a href="{{ route('daily-gym-transaction.index') }}" class="text-decoration-none text-dark">
                    <div class="card bg-c-blue order-card">
                        <div class="card-block text-center">
                            <h6 class="m-b-20" style="color:white">Total Transaction Daily Gym</h6>
                            <h2 class="text-right"> <i class="bx bx-receipt icon nav-icon" style="color:white"></i><span id="totalDailyCount" style="color:white"> {{ $totalDaily }} </span></h2>
                            <h6 class="m-b-20" style="color:white">Transaction Daily Gym</h6>
                            <h2 class="text-right"> <i class="bx bx-receipt icon nav-icon" style="color:white"></i><span id="dailyCount" style="color:white"> {{ $daily }} </span></h2>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-4 col-sm-6">
                <a href="{{ route('suplement-transaction.index') }}" class="text-decoration-none text-dark">
                    <div class="card bg-c-yellow order-card">
                        <div class="card-block text-center">
                            <h6 class="m-b-20" style="color:white">Total Suplement Transaction</h6>
                            <h2 class="text-right"> <i class='bx bxs-data' style="color:white"></i><span id="suplementTransactionCount" style="color:white"> {{ $suplementTransaction }} </span></h2>
                            <h6 class="m-b-20" style="color:white">Daily Suplement Transaction</h6>
                            <h2 class="text-right"> <i class='bx bxs-data' style="color:white"></i><span id="suplementTransactionDailyCount" style="color:white"> {{ $suplementTransactionDaily }} </span></h2>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-4 col-sm-6">
                <a href="{{ route('suplement-data.index') }}" class="text-decoration-none text-dark">
                    <div class="card bg-c-green order-card">
                        <div class="card-block text-center">
                            <h6 class="m-b-20" style="color:white">Total Suplement</h6>
                            <h2 class="text-right"> <i class='bx bxs-data' style="color:white"></i><span id="suplementDataCount" style="color:white"> {{ $suplement }} </span></h2>
                        </div>
                    </div>
                </a>
            </div>

         

        </div>
    </div> --}}


    <div class="container bootstrap snippets bootdey  animate__animated animate__fadeIn">
        <div class="row">
            <div class="col-md-4 col-sm-6 mt-4">
                <a href="{{ route('instructor-data.index') }}">
                    <div class="panel panel-dark panel-colorful rounded">
                        <div class="panel-body text-center">
                            <p class="text-uppercase mar-btm text-sm">Data Instructor</p>
                            <i class="fa fa-users fa-5x"></i>
                            <hr>
                            <p class="h2 text-thin" style="color:white" id="instructorCount"> {{ $instructor }}</p>
                            <small><span class="text-semibold"><i class="fa fa-users fa-fw"></i></span> Total All Instructor in Armor Gym</small>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 col-sm-6 mt-4">
                <a href="{{ route('membership-data.index') }}" class="text-decoration-none text-dark">
                    <div class="panel panel-danger panel-colorful rounded">
                        <div class="panel-body text-center">
                            <p class="text-uppercase mar-btm text-sm">Data Membership</p>
                            <i class="fa fa-solid fa-dumbbell fa-5x"></i>
                            <hr>
                            <p class="h2 text-thin" style="color:white" id="membershipDataCount">{{ $membership }}</p>
                            <small><span class="text-semibold"><i class="fa fa fa-solid fa-dumbbell fa-fw"></i></span> Total All Type Membership in Armor Gym</small>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 col-sm-6 mt-4">
                <a href="{{ route('membership-transaction.index') }}">
                    <div class="panel panel-primary panel-colorful rounded">
                        <div class="panel-body text-center">
                            <p class="text-uppercase mar-btm text-sm">Activation Membership</p>
                            <i class="fa fa-solid fa-user-check fa-5x"></i>
                            <hr>
                            <p class="h2 text-thin" style="color:white" id="activeCount">{{ $active }}</p>
                            <small><span class="text-semibold"><i class="fa fa-shopping-cart fa-fw"></i></span>Total All Active Membership in Armor Gym</small>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 col-sm-6 mt-4">
                <a href="{{ route('daily-gym-transaction.index') }}">
                    <div class="panel panel-info panel-colorful rounded">
                        <div class="panel-body text-center">
                            <p class="text-uppercase mar-btm text-sm">Transaction Daily</p>
                            <i class="fa fa-shopping-cart fa-5x"></i>
                            <hr>
                            <p class="h2 text-thin" style="color:white" id="totalDailyCount">{{ $totalDaily }}</p>
                            <small><span class="text-semibold"><i class="fa fa-shopping-cart fa-fw"></i></span>Total All Transaction Daily in Armory Gym</small>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 col-sm-6 mt-4">
                <a href="{{ route('suplement-data.index') }}">
                    <div class="panel panel-silver panel-colorful rounded">
                        <div class="panel-body text-center">
                            <p class="text-uppercase mar-btm text-sm">Data Suplement</p>
                            <i class="fa fa-solid fa-box fa-5x"></i>
                            <hr>
                            <p class="h2 text-thin" id="suplementDataCount" style="color: white">{{ $suplement }}</p>
                            <small><span class="text-semibold"><i class="fa fa-solid fa-box fa-fw"></i></span> Total All Suplement in Armor Gym</small>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 col-sm-6 mt-4">
                <a href="{{ route('suplement-transaction.index') }}">
                    <div class="panel panel-sage panel-colorful rounded">
                        <div class="panel-body text-center">
                            <p class="text-uppercase mar-btm text-sm">Transaction Suplement</p>
                            <i class="fa fa-shopping-cart fa-5x"></i>
                            <hr>
                            <p class="h2 text-thin" style="color:white" id="suplementTransactionCount">{{ $suplementTransaction }}</p>
                            <small><span class="text-semibold"><i class="fa fa-shopping-cart fa-fw"></i></span> Total All Transaction Suplement in Armor Gym</small>
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
            animateCount("#instructorCount", 0, {{ $instructor }}, 5);

            animateCount("#membershipDataCount", 0, {{ $membership }}, 5);

            animateCount("#membershipCount", 0, {{ $membershipTr }}, 5); 
            animateCount("#activeCount", 0, {{ $active }}, 5); 
            animateCount("#notActiveCount", 0, {{ $notActive }}, 5); 

            animateCount("#totalDailyCount", 0, {{ $totalDaily }}, 5); 
            animateCount("#dailyCount", 0, {{ $daily }}, 5); 

            animateCount("#suplementDataCount", 0, {{ $suplement }}, 5);

            animateCount("#suplementTransactionCount", 0, {{ $suplementTransaction }}, 5); 
            animateCount("#suplementTransactionDailyCount", 0, {{ $suplementTransactionDaily }}, 5); 
        });
    </script>
@endsection
