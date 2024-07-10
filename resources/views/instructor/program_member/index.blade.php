@extends('layouts.master')

@section('css')
<link rel="stylesheet" href="{{ URL::asset('assets/libs/gridjs/theme/mermaid.min.css') }}">
<script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
@include('instructor.gym_schedule.style')
@endsection

@section('content')
<div class="content ">
    <h2>Program Member</h2>
    <div class="card">
        <div class="card-body ">
            <div class="d-flex justify-content-end">
                <div class="class">
                    <button type="button" class="btn btn-primary mb-4" data-bs-toggle="modal"
                        data-bs-target="#addProgramMember"><i class="mdi mdi-plus me-1"></i>Program Member
                    </button>
                </div>
            </div>
            <div style="width : 100%; height : 700px; overflow : auto; ">
                <table class="table table-hover  table-responsive table-condensed animate__animated animate__fadeIn"
                    id="table" width="100%">
                    <thead class="table-dark ">
                        <th style="color: black;">{{ ucwords('no') }}</th>
                        <th style="color: black;">{{ ucwords('name member') }}</th>
                        <th style="color: black;">{{ ucwords('program') }}</th>
                        <th style="color: black;">{{ ucwords('start date') }}</th>
                        <th style="color: black;">{{ ucwords('status') }}</th>
                        <th style="color: black;">{{ ucwords('action') }}</th>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- add modal --}}
<div class="modal fade" id="addProgramMember" tabindex="-1" aria-labelledby="addProgramMemberLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addProgramMemberLabel">Add Program Member</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form action="{{ route('program-member.store') }}" method="POST" id="add-form">
                    @csrf
                    <input type="hidden" value="{{ auth()->user()->id}}" name="id">
                    <div class="mb-3">
                        <label for="membership_id" class="form-label">{{ ucwords('Member') }}</label>
                        <select id="membership_id" name="membership_id"
                            class="form-control  @error('membership_id') is-invalid @enderror">
                            <option value="" disabled selected>Select Member</option>
                            @foreach ($members as $userId => $name)
                            <option value="{{ $userId }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="program_data_id" class="form-label">{{ ucwords('Program') }}</label>
                        <select id="program_data_id" name="program_data_id"
                            class="form-control  @error('program_data_id') is-invalid @enderror">
                            <option value="" disabled selected>Select Program</option>
                            @foreach ($programs as $program)
                            <option value="{{ $program->id }}">{{ $program->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="date" class="form-label">{{ ucwords('start date') }}</label>
                        <input type="date" class="form-control  @error('date') is-invalid @enderror" id="date"
                            name="date">
                    </div>

                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>

            </div>
        </div>
    </div>
</div>
{{-- end add modal --}}

{{-- modal confirm --}}
<div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmationModalLabel">Confirmation</h5>
                <button type="button" class="btn-close" onclick="openAddModal()" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to create supplement data?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary" onclick="submitForm()">Confirm Submit</button>
            </div>
        </div>
    </div>
</div>
{{-- end modal confirm --}}

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
            var table = $('#table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('program-member.data') }}",
                columns: [{
                        data: 'id',
                        orderable: false
                    },
                    {
                        data: 'name',
                        name: 'name',
                        render: function(data) {
                            return data.charAt(0).toUpperCase() + data.slice(1);
                        },
                        orderable: false
                    },
                    {
                        data: 'program',
                        name: 'program',
                        orderable:false
                    },
                    {
                        data: 'date',
                        name: 'date',
                        render: function(data, type, full, meta) {
                                var dateOfBirth = moment(data);
                                return dateOfBirth.format('DD-MM-YYYY');
                        },
                        orderable:false
                    },
    
                    {
                        data: 'status',
                        name: 'status',
                        render: function(data) {
                            return data.charAt(0).toUpperCase() + data.slice(1);
                        },
                        orderable:false
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
                        $('#addProgramMember').modal('hide');
                        // Tampilkan pesan sukses jika perlu
                        Swal.fire("Success", "Successfully created program member!", "success");
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
            document.getElementById('transaction-form').submit();
        }
    
        function openAddModal() {
            $('#confirmationModal').modal('hide'); // Hide the current modal
            $('#addProgramMember').modal('show'); // Show the addSuplement modal
        }
</script>

@endsection