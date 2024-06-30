@extends('layouts.master')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="row mb-3">
                <div class="col text-end">
                    <a href="{{ route('suplement-transaction.index') }}" class="btn btn-outline-danger">Back</a>
                </div>
            </div>
            <h5 class="card-title">User Suplements Detail: {{ ucfirst($user->name) }}</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name Suplement</th>
                            <th>Amount</th>
                            <th>Total</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $counter = 1; @endphp
                        @foreach($transactions as $transaction)
                        <tr>
                            <td>{{$counter}}</td>
                            <td>{{ $transaction->suplement->name }}</td>
                            <td>{{ $transaction->amount ?? 'N/A' }}</td>
                            <td>{{ $transaction->total ?? 'N/A' }}</td>
                            <td>{{ $transaction->date ? \Carbon\Carbon::parse($transaction->date)->format('d-m-Y') : 'N/A' }}</td>
                            <td>
                                <a href="{{ route('suplement-transaction.receipt', ['userId' => $transaction->id]) }}" class="btn btn-outline-info">View Receipt</a> 
                                {{-- <a href="{{ route('suplement-transaction.receipt', ['userId' => $suplement->pivot->id]) }}" class="btn btn-outline-info">View Receipt</a> --}}

                                {{-- <a href="{{ route('suplement-transaction.receipt', ['userId' => $user->id, 'pivotId' => $suplement->pivot->id]) }}" class="btn btn-outline-info">View Receipt</a> --}}

                            </td>
                        </tr>
                        @php $counter++; @endphp
                        @endforeach
                    </tbody>
                </table>
                <div class="text-center d-flex justify-content-end">
                    {{ $transactions->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
