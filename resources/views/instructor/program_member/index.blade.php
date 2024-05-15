@extends('layouts.master')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/libs/gridjs/theme/mermaid.min.css') }}">
@endsection

@section('content')
    <div class="content ">
        <h2>Program Member</h2>
        <div class="card">
            <div class="card-body ">
                <div class="d-flex justify-content-end">
                    <div class="class">
                        <button type="button" class="btn btn-primary mb-4" data-bs-toggle="modal"
                            data-bs-target="#addMembership"><i class="mdi mdi-plus me-1"></i>Program Member
                        </button>
                    </div>
                </div>
                <div class="my-2 w-50">
                    <input type="text" id="search"
                        @if (session('keyword')) value="{{ session('keyword') }}" @endif class="form-control"
                        placeholder="search...">
                </div>
                <div style="width : 100%; height : 700px; overflow : auto; ">
                    @include('instructor.program_member.table')
                </div>
            </div>
        </div>
    </div>

    {{-- add modal --}}
    <div class="modal fade" id="addMembership" tabindex="-1" aria-labelledby="addMembershipLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addMembershipLabel">Add Program Member</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form method="POST" action="{{ route('program-member.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="membership_id" class="form-label">{{ ucwords('Member') }}</label>
                            <select id="membership_id" name="membership_id" class="form-control" required>
                                <option value="" disabled selected>Select Member</option>
                                @foreach ($members as $userId => $name)
                                    <option value="{{ $userId }}">{{ $name }}</option>
                                @endforeach
                            </select>                                                   
                        </div>

                        <div class="mb-3">
                            <label for="program_data_id" class="form-label">{{ ucwords('Program') }}</label>
                            <select id="program_data_id" name="program_data_id" class="form-control" required>
                                <option value="" disabled selected>Select Program</option>
                                @foreach ($programs as $program)
                                    <option value="{{ $program->id }}">{{ $program->name }}</option>
                                @endforeach
                            </select>                                                   
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
        search("search", "table", "{{ url('membership-data/search?') }}")
    </script>
@endsection
