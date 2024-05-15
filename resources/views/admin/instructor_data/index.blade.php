@extends('layouts.master')


@section('content')
    <div class="content">
        <h2>Data Instructor</h2>
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-end">
                    <div class="class">
                        <button type="button" class="btn btn-primary mb-4" data-bs-toggle="modal"
                            data-bs-target="#addInstructor"><i class="mdi mdi-plus me-1"></i> Data Instructor
                        </button>
                    </div>
                </div>
                <div class="my-2 w-50">
                    <input type="text" id="search"  @if (session('keyword')) value="{{ session('keyword') }}" @endif class="form-control" placeholder="search...">
                </div>
                <div style="width : 100%; height : 700px; overflow : auto; ">
                    @include('admin.instructor_data.table')
                </div>
            </div>
        </div>

        {{-- add Instructor Modal --}}
        <div class="modal fade" id="addInstructor" tabindex="-1" aria-labelledby="addInstructorLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addInstructorLabel">Add Instructor</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <form method="POST" action="{{ route('instructor-data.store') }}">
                            @csrf
                            <div class="mb-3">
                                <select required name="username" id="username" class="form-control">
                                    <option value="" disabled selected>select username</option>
                                    @foreach ($users as $item)
                                        <option value="{{ $item->username }}">{{ $item->username }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
        {{-- end add Instructor Modal  --}}
        <script></script>
    @endsection
@section('scripts')
    <script src="{{ asset('js/search.js') }}"></script>
    <script>
        search("search", "table", "{{ url('instructor-data/search?') }}")
    </script>
@endsection