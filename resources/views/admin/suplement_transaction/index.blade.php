@extends('layouts.master')

@section('content')
<div class="content">  
    <h2>Transaction Suplement</h2>
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-end">
                <div class="class">
                    <button type="button" class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#addSuplementr"><i
                            class="mdi mdi-plus me-1"></i>Transaction Suplement</button>
                </div>
            </div>
            <div class="my-2 w-50">
                <input type="text" id="search"  @if (session('keyword')) value="{{ session('keyword') }}" @endif class="form-control" placeholder="search...">
            </div>
            <div style="width : 100%; height : 700px; overflow : auto; " id="table">
                @include('admin.suplement_transaction.table')
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="addSuplementr" tabindex="-1" aria-labelledby="addSuplementrLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSuplementrLabel">Transaction Suplement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form method="POST" action="{{ route('suplement-transaction.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="user_id" class="form-label">{{ ucwords('User') }}</label>
                            <select id="user_id" name="user_id" class="form-control" required>
                                <option value="" disabled selected>Select User</option>
                                @foreach ($users as $user)
                                    @if ($user->role == 'GUEST' || ($user->role == 'MEMBER'))
                                        <option value="{{ $user->id }}">{{ $user->username }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    
                        <div class="mb-3">
                            <label for="suplement_id" class="form-label">{{ ucwords('Suplement') }}</label>
                            <select id="suplement_id" name="suplement_id" class="form-control" required>
                                <option value="" disabled selected>Select Suplement</option>
                                @foreach ($suplements as $suplement)
                                    <option value="{{ $suplement->id }}">{{ $suplement->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    
                        <div class="mb-3">
                            <label for="amount" class="form-label">{{ ucwords('Amount') }}</label>
                            <input type="number" class="form-control" id="amount" name="amount" required min="1">
                        </div>
                    
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ ucwords('Close') }}</button>
                        </div>
                    </form>
                    
                </div>
            </div>
        </div>
</div>

@endsection
@section('scripts')
    <script src="{{ asset('js/search.js') }}"></script>
    <script>
        search("search", "table", "{{ url('suplement-transaction/search?') }}")
    </script>
@endsection