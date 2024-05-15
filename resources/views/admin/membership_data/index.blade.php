@extends('layouts.master')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/libs/gridjs/theme/mermaid.min.css') }}">
@endsection

@section('content')
    <div class="content ">
        <h2>Data Membership</h2>
        <div class="card">
            <div class="card-body ">
                <div class="d-flex justify-content-end">
                    <div class="class">
                        <button type="button" class="btn btn-primary mb-4" data-bs-toggle="modal"
                            data-bs-target="#addMembership"><i class="mdi mdi-plus me-1"></i> Data Membership
                        </button>
                    </div>
                </div>
                <div class="my-2 w-50">
                    <input type="text" id="search"
                        @if (session('keyword')) value="{{ session('keyword') }}" @endif class="form-control"
                        placeholder="search...">
                </div>
                <div style="width : 100%; height : 700px; overflow : auto; ">
                    @include('admin.membership_data.table')
                </div>
            </div>
        </div>
    </div>

    {{-- add modal --}}
    <div class="modal fade" id="addMembership" tabindex="-1" aria-labelledby="addMembershipLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addMembershipLabel">Add Memberships</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form method="POST" action="{{ route('membership-data.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="type" class="form-label">{{ ucwords('type') }}</label>
                            <input type="text" class="form-control" id="type" name="type">
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">{{ ucwords('price') }}</label>
                            <input type="number" class="form-control" id="price" name="price">
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">{{ ucwords('description') }}</label>
                            <input type="text" class="form-control" id="description" name="description">
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
