@extends('layouts.master')

@section('css')
<style>
    @media print {
        .card.user-card-full {
            width: auto;
            height: 200px;
            margin: auto;
        }
        .card.user-card-full .row {
            margin-left: 0;
            margin-right: 0;
        }
        .img-fluid.img-radius.rounded-circle.mb-2 {
            display: block;
        }
    }
</style>
@endsection

@section('content')

<div class="card">
    <div class="card-body p-5">
        <div class="container">
            <div class="row justify-content-center align-items-center">
                <div class="row mb-3">
                    <div class="col text-end">
                        <a href="{{ route('membership-transaction.index') }}" class="btn btn-outline-danger ml-4" style="margin-left:-20px;">Back</a>
                        <button class="btn btn-outline-info ml-2" onclick="printCard()">Print</button>
                    </div>
                                      
                </div>
                <div class="col-xl-8 col-12 mx-auto" id="print-content">
                    <div class="card user-card-full" style="background: url('/images/card.jpg'); width:520px;" >
                        <div class="row no-gutters">
                            <div class="col-sm-4 bg-c-lite-green user-profile d-flex align-items-center justify-content-center">
                                <div class="text-center text-white">
                                    <img src="{{ URL::asset('assets/images/logo_armor.png') }}" width="100" height="120" class="img-fluid img-radius rounded-circle mb-2 printable-image" alt="logo-armor-gym">
                                    <div>
                                        <span class="fw-bold" style="color:white; padding-left: 10px;">ARMOR GYM</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-sm-8">
                                <div class="card-body">
                                    <h6 class="mb-4 pb-2 border-bottom border-light f-w-800 text-center" style="font-weight: bold;color:white;">Membership Armor Gym</h6>
                                    <div class="row mb-3">
                                        <div class="col-sm-6">
                                            <p class="mb-1 text-uppercase" style="color:white;">Name:</p>
                                            <p class="mb-0" style="font-weight: bold; color:white;">{{ ucwords($membership->user->name) }}</p>
                                        </div>
                                        <div class="col-sm-6">
                                            <p class="mb-1 text-uppercase" style="color:white;">Type:</p>
                                            <p class="mb-0" style="font-weight: bold; color:white;">{{ ucwords($membership->membership->type) }}</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <p class="mb-1 text-uppercase" style="color:white;">Start Date:</p>
                                            <p class="mb-0" style="font-weight: bold; color:white;">{{ date('d F Y', strtotime($membership->start_date)) }}</p>
                                        </div>
                                        <div class="col-sm-6">
                                            <p class="mb-1 text-uppercase text-bold" style="color:white;">End Date:</p>
                                            <p class="mb-0" style="font-weight: bold; color:white">{{ date('d F Y', strtotime($membership->end_date)) }}</p>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                            
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>

<script>
     function printCard() {
        var printContents = document.getElementById("print-content").innerHTML;
        console.log(printContents);
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }
</script>

@endsection