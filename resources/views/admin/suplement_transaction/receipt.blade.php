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
                        <div class="col text-end">
                            @if ($user)
                                <a href="{{ route('suplement-transaction.detail', ['userId' => $user->id]) }}" class="btn btn-outline-danger">Back</a>
                            @else
                                <span>User not found</span>
                            @endif
                        </div>
                        

                    </div>
                    <h2>Receipt</h2>
                </div>

                {{-- <div class="card-body receipt-body">
                    <div class="receipt">
                        <span class="text-center" style="font-size: 20px"><strong>Receipt</strong></span>
                        <hr>
                        <span><strong>Name:</strong> {{ $user ? $user->name : 'User not found' }}</span>
                        
                        @if ($suplement)
                            <span><strong>Date:</strong> {{ $suplement->pivot ? \Carbon\Carbon::parse($suplement->pivot->date)->format('d-m-Y') : 'N/A' }}</span>
                            <span><strong>Transaction ID:</strong> {{ $suplement->s->id }}</span>
                            <span><strong>Item:</strong> {{ $suplement->name }}</span>
                            <span><strong>Price:</strong> Rp{{ number_format($suplement->pivot->total, 0, ',', '.') }}</span>
                        @else
                            <span>Data not available</span>
                        @endif
                    </div>
                </div> --}}
                <div class="card-body receipt-body">
                    <div class="receipt">
                        <span class="text-center" style="font-size: 20px"><strong>Receipt</strong></span>
                        <hr>
                            <span><strong>Name:</strong> {{ $transaction->user->name }}</span>
                            <span><strong>Date:</strong> {{ \Carbon\Carbon::createFromFormat('Y-m-d', $transaction->date)->format('d/m/Y') }}</span>
                            <span><strong>Transaction ID:</strong> {{ $transaction->id }}</span>
                            <hr>
                            <span><strong>Item:</strong> {{ $transaction->suplement->name }} </span>
                            <span><strong>Amount:</strong> {{ $transaction->amount ?? 0 }} </span>
                            <span><strong>Total:</strong> Rp{{ number_format($transaction->total, 0, ',', '.') }}</span>
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
