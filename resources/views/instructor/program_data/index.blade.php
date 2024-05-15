@extends('layouts.master')

@section('css')
<link rel="stylesheet" href="{{ URL::asset('assets/libs/gridjs/theme/mermaid.min.css') }}">
@endsection

@section('content')
    <div class="content">
        <h2>Program Data</h2>
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-end">
                    <div class="class">
                        <button type="button" class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#addProgram"><i
                                class="mdi mdi-plus me-1"></i> Program Data
                        </button>
                    </div>
                </div>
                <div class="my-2 w-50">
                    <input type="text" id="search"  @if (session('keyword')) value="{{ session('keyword') }}" @endif class="form-control" placeholder="search...">
                </div>
                <div style="width : 100%; height : 700px; overflow : auto; ">
                    @include('instructor.program_data.table')
                </div>
                </div>   
            </div>
        </div>
    
        {{-- add modal --}}
        <div class="modal fade" id="addProgram" tabindex="-1" aria-labelledby="addProgramLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addProgramLabel">Add Program Data</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <form method="POST" action="{{ route('program-data.store') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">{{ ucwords('name') }}</label>
                                <input type="text" class="form-control" id="name" name="name" >
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">{{ ucwords('description') }}</label>
                                <input type="text" class="form-control" id="description" name="description" >
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
        search("search", "table", "{{ url('program-data/search?') }}")
    </script>
@endsection