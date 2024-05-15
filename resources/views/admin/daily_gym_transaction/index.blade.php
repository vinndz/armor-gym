@extends('layouts.master')


@section('content')
    <div class="content">
        <h2>Daily Gym Transaction</h2>
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-end">
                    <div class="class">
                        <button type="button" class="btn btn-primary mb-4" data-bs-toggle="modal"
                            data-bs-target="#addDailyGym"><i class="mdi mdi-plus me-1"></i> Daily Gym
                        </button>
                    </div>
                </div>
                <div class="my-2 w-50">
                    <input type="text" id="search"
                        @if (session('keyword')) value="{{ session('keyword') }}" @endif class="form-control"
                        placeholder="search...">
                </div>
                <div style="width : 100%; height : 700px; overflow : auto; ">
                    @include('admin.daily_gym_transaction.table')
                </div>
            </div>
        </div>
    </div>

    {{-- add modal --}}
    <div class="modal fade" id="addDailyGym" tabindex="-1" aria-labelledby="addDailyGymLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addDailyGymLabel">Add DailyGyms</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form method="POST" action="{{ route('daily-gym-transaction.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="username" class="form-label">{{ ucwords('username') }}</label>
                            <input required list="username" name="username" id="usernameInput" class="form-control">
                            <datalist id="username">
                                @foreach ($users as $user)
                                    <option value="{{ $user->username }}" data-userid="{{ $user->id }}">
                                @endforeach
                            </datalist>
                            {{-- <select required name="username" id="username" class="form-control">
                                <option value="" disabled selected>select username</option>
                                @foreach ($users as $item)
                                    <option value="{{ $item->id }}">{{ $item->username }}</option>
                                @endforeach
                            </select> --}}
                        </div>

                        <div class="mb-3">
                            <label for="price" class="form-label">{{ ucwords('price') }}</label>
                            <input type="text" class="form-control" id="price" name="price">
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
    {{-- end add modal --}}


@endsection


@section('scripts')
    <script src="{{ asset('js/search.js') }}"></script>
    <script>

        search("search", "table", "{{ url('daily-gym-transaction/search?') }}")
    </script>
@endsection