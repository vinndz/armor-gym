@extends('layouts.master')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/libs/gridjs/theme/mermaid.min.css') }}">
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    @include('instructor.gym_schedule.style')
@endsection

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
                <div style="width : 100%; height : 700px; overflow : auto; ">
                    <table class="table table-hover  table-responsive table-condensed animate__animated animate__fadeIn"
                        id="table" width="100%">
                        <thead class="table-dark ">
                            <th style="color: black;">{{ ucwords('no') }}</th>
                            <th style="color: black;">{{ ucwords('name') }}</th>
                            <th style="color: black;">{{ ucwords('birth date') }}</th>
                            <th style="color: black;">{{ ucwords('gender') }}</th>
                            <th style="color: black;">{{ ucwords('email') }}</th>
                            <th style="color: black;">{{ ucwords('action') }}</th>
                        </thead>
                    </table>
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
                        <form method="POST" id="add-form" action="{{ route('instructor-data.store') }}">
                            @csrf
                            <div class="mb-3">
                                <select required name="username" id="username" class="form-control  @error('username') is-invalid @enderror" >
                                    <option value="" disabled selected>select username</option>
                                    @foreach ($users as $item)
                                        <option value="{{ $item->username }}">{{ $item->username }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="button" class="btn btn-primary" id="submit-btn">Submit</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
        {{-- end add Instructor Modal  --}}


        {{-- modal confirm --}}
        <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmationModalLabel">Confirmation</h5>
                        <button type="button" class="btn-close" onclick="openAddInstructorModal()"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to create supplement data?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" onclick="openAddInstructorModal()">Cancel</button>
                        <button type="submit" class="btn btn-primary" onclick="submitForm()">Confirm Submit</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- end modal confirm --}}

    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            var table = $('#table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('instructor-data.data') }}",
                columns: [{
                        data: 'id',
                        orderable: false
                    },
                    {
                        data: 'name',
                        name: 'name',
                        render: function(data) {
                            return data.charAt(0).toUpperCase() + data.slice(1);
                        }
                    },
                    {
                        data: 'date_of_birth',
                        name: 'date_of_birth',
                        render: function(data, type, full, meta) {
                            var dateOfBirth = moment(data);
                            return dateOfBirth.format('DD-MM-YYYY');
                        }
                    },
                    {
                        data: 'gender',
                        name: 'gender'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'action',
                        orderable: false
                    },
                ],
                columnDefs: [{
                    className: "text-center",
                    targets: "_all"
                }, ],
                language: {
                    search: "", // Placeholder untuk kotak pencarian
                    paginate: {
                        first: '<i class="fa fa-angle-double-left"></i>',
                        last: '<i class="fa fa-angle-double-right"></i>',
                        next: '<i class="fa fa-angle-right"></i>',
                        previous: '<i class="fa fa-angle-left"></i>'
                    }
                }
            });

            // Menambahkan placeholder ke dalam kotak pencarian
            $('div.dataTables_wrapper input[type="search"]').attr('placeholder', 'Search...');

            $('#submit-btn').click(function() {
                var form = $('#add-form');
                $.ajax({
                    url: form.attr('action'),
                    method: form.attr('method'),
                    data: form.serialize(),
                    success: function(response) {
                        // Jika berhasil, reload DataTable dan tutup modal
                        table.ajax.reload();
                        $('#addInstructor').modal('hide');
                        // Tampilkan pesan sukses jika perlu
                        Swal.fire("Success", "Successfully created instructor data!", "success");
                    },
                    error: function(xhr) {
                        // Tampilkan pesan kesalahan di modal
                        var errors = xhr.responseJSON.errors;
                        $('.invalid-feedback').remove(); // Hapus pesan kesalahan sebelumnya
                        $('.is-invalid').removeClass('is-invalid'); // Hapus class is-invalid sebelumnya

                        $.each(errors, function(field, messages) {
                            var input = $('[name="' + field + '"]');
                            input.addClass('is-invalid');
                            $.each(messages, function(index, message) {
                                input.after('<div class="invalid-feedback">' + message + '</div>');
                            });
                        });
                    }
                });
            });
        });

        function submitForm() {
            document.getElementById('instructor-form').submit();
        }

        function openAddInstructorModal() {
            $('#confirmationModal').modal('hide'); // Hide the current modal
            $('#addInstructor').modal('show'); // Show the addSuplement modal
        }
    </script>
    @endsection
