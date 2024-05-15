@extends('layouts.master')

@section('content')
    <div class="content">  
        <h2>Transaction Activation Member</h2>
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-end">
                    <div class="class">
                        <button type="button" class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#activationMember"><i
                                class="mdi mdi-plus me-1"></i> Activation Member</button>
                    </div>
                </div>
                <div class="my-2 w-50">
                    <input type="text" id="search"  @if (session('keyword')) value="{{ session('keyword') }}" @endif class="form-control" placeholder="search...">
                </div>
                <div style="width : 100%; height : 700px; overflow : auto; " id="table">
                    @include('admin.membership_transaction.table')
                </div>
            </div>
        </div>
        
        <div class="modal fade" id="activationMember" tabindex="-1" aria-labelledby="activationMemberLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="activationMemberLabel">Activation Member</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <form method="POST" action="{{ route('membership-transaction.store') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="username" class="form-label">{{ ucwords('username') }}</label>
                                <input list="usernames" id="username" name="username" class="form-control" required>
                                <datalist id="usernames">
                                    @foreach ($users as $user)
                                        @if ($user->role == 'GUEST' || ($user->role == 'MEMBER' && $membership->contains('user_id', $user->id)))
                                            <option value="{{ $user->username }}">{{ $user->username }}</option>
                                        @endif
                                    @endforeach
                                </datalist>                             
                            </div>
                            
                            <div class="mb-3">
                                <label for="start_date" class="form-label">{{ ucwords('start date') }}</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" required>
                            </div>

                            <div class="mb-3">
                                <label for="month" class="form-label">{{ ucwords('Month') }}</label>
                                <select required name="month" id="month" class="form-control">
                                    <option value="" disabled selected>Select Month</option>
                                    @for ($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="type" class="form-label">{{ ucwords('type') }}</label>
                                <select class="form-select" id="type" name="type" required>
                                    <option value="" selected disabled>Select Type</option>
                                    @foreach ($types as $type)
                                        <option value="{{ $type->type }}">{{ $type->type }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ ucwords('close') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
    </div>
        
        <!-- Confirmation Modal -->
        {{-- <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmationModalLabel">Confirmation</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to submit this form?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="confirmSubmitBtn">Confirm Submit</button>
                    </div>
                </div>
            </div>
        </div> --}}
        
@endsection

@section('scripts')
    <script src="{{ asset('js/search.js') }}"></script>
    <script>
    
    search("search", "table", "{{ url('membership-transaction/search?') }}")
       
    </script>
@endsection
