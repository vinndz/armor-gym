@extends('layouts.master')

@section('css')
<style>
    .receipt {
        font-family: Arial, sans-serif;
        max-width: 300px;
        margin: 0 auto;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 5px;
        background-color: #fff;
    }

    .receipt-header {
        text-align: center;
        margin-bottom: 20px;
    }

    .receipt-body {
        margin-bottom: 20px;
    }

    .receipt-body span {
        display: block;
        margin-bottom: 5px;
    }

    .receipt-footer {
        text-align: center;
    }

    .print-btn {
        display: block;
        width: 100%;
        text-align: center;
        padding: 10px;
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        text-decoration: none;
    }

    .print-btn:hover {
        background-color: #0056b3;
    }

    @media print {
        body * {
            visibility: hidden;
        }
        .receipt, .receipt * {
            visibility: visible;
        }
        .receipt {
            position: absolute;
            left: 0;
            top: 0;
        }
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header receipt-header">
                    <div class="col text-end">
                        <a href="{{ route('membership-transaction.index', ['userId' => $user->id]) }}" class="btn btn-outline-danger">Back</a>

                    </div>
                    <h2>Receipt</h2>
                </div>

                <div class="card-body receipt-body">
                    <div class="receipt">
                        <span class="text-center" style="font-size: 20px"><strong>Receipt</strong></span>
                        <hr>
                            <span><strong>Name:</strong> {{ $membership->user->name }}</span>
                            <span><strong>Date:</strong> {{ \Carbon\Carbon::parse($membership->date)->format('d/m/Y') }}</span>
                            <span><strong>Start Date:</strong> {{ \Carbon\Carbon::parse($membership->start_date)->format('d/m/Y') }}</span>
                            <span><strong>End Date:</strong> {{ \Carbon\Carbon::parse($membership->end_date)->format('d/m/Y') }}</span>
                            <span><strong>Transaction ID:</strong> {{ $membership->id }}</span>
                            <hr>
                            <span><strong>Item:</strong>{{$membership->membership->type}}</span>
                            <span><strong>Total:</strong> Rp{{ number_format($membership->total, 0, ',', '.') }}</span>
                    </div>
                </div>
                
                <div class="card-footer receipt-footer">
                    <a href="javascript:void(0)" class="print-btn" onclick="window.print()">Print Receipt</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
