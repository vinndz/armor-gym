@extends('layouts.master')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="row mb-3">
                <div class="col text-end">
                    <a href="{{ route('daily-gym-transaction.index') }}" class="btn btn-outline-danger">Back</a>
                </div>
            </div>
            <h5 class="card-title">User Daily Gym Detail: {{ ucfirst($user->name) }}</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Price</th>
                            <th>Transaction Date</th>
                            <th>action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $counter = 1; @endphp
                        @foreach($dailys as $daily)
                        <tr>
                            <td>{{$counter}}</td>
                            <td>{{ ucfirst($daily->user->name) }}</td>
                            <td>{{ ucfirst($daily->user->username) }}</td>
                            <td>{{ $daily->price }}</td>
                            <td> {{ \Carbon\Carbon::createFromFormat('Y-m-d', $daily->date)->format('d-m-Y') }}</td>
                            <td>
                                <a href="{{ route('daily-gym-transaction.receipt', $daily->id) }}" class="btn btn-outline-info">View Receipt</a> 
                            </td>
                        </tr>
                        @php $counter++; @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
