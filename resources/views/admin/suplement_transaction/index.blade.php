@extends('layouts.master')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/libs/gridjs/theme/mermaid.min.css') }}">
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    @include('instructor.gym_schedule.style')
@endsection

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
                <div style="width : 100%; height : 700px; overflow : auto;">
                    <table class="table table-hover  table-responsive table-condensed animate__animated animate__fadeIn"
                            id="table" width="100%">
                            <thead class="table-dark ">
                                <th style="color: black;">{{ ucwords('no') }}</th>
                                <th style="color: black;">{{ ucwords('name') }}</th>
                                <th style="color: black;">{{ ucwords('suplement') }}</th>
                                <th style="color: black;">{{ ucwords('amount') }}</th>
                                <th style="color: black;">{{ ucwords('price') }}</th>
                                <th style="color: black;">{{ ucwords('total') }}</th>
                                <th style="color: black;">{{ ucwords('date') }}</th>
                                <th style="color: black;">{{ ucwords('action') }}</th>
                            </thead>
                        </table>
                </div>
            </div>
        </div>
    </div>

    {{-- add Modal --}}
    <div class="modal fade" id="addSuplementr" tabindex="-1" aria-labelledby="addSuplementrLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSuplementrLabel">Transaction Suplement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form method="POST" id="add-form" action="{{ route('suplement-transaction.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="user_id" class="form-label">{{ ucwords('User') }}</label>
                            <select id="user_id" name="user_id" class="form-control @error('user_id') is-invalid @enderror"" required>
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
                            <select id="suplement_id" name="suplement_id" class="form-control @error('suplement_id') is-invalid @enderror" required>
                                <option value="" disabled selected>Select Suplement</option>
                                @foreach ($suplements as $suplement)
                                    <option value="{{ $suplement->id }}">{{ $suplement->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    
                        <div class="mb-3">
                            <label for="amount" class="form-label">{{ ucwords('Amount') }}</label>
                            <input type="number" class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount" required min="1">
                            @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    
                        <button type="button" class="btn btn-primary" id="submit-btn">Submit</button>
                    </form>
                    
                </div>
            </div>
        </div>
    </div>
    {{-- end modal --}}

    {{-- update modal --}}
    <div class="modal fade" id="updateSuplementTransaction{{ $suplement->id }}" tabindex="-1" aria-labelledby="updateSuplementTransactionLabel{{ $suplement->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateSuplementTransactionLabel{{ $suplement->id }}">Update Daily Gym</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    
                    <form method="POST" action="{{ route('suplement-transaction.update', ['id' => $suplement->id]) }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="user_id" class="form-label">{{ ucwords('User') }}</label>
                            <select id="user_id" name="user_id" class="form-control" required>
                                <option value="" disabled>Select User</option>
                                @foreach ($users as $user)
                                    @if ($user->role == 'GUEST' || $user->role == 'MEMBER')
                                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->username }}</option>
                                    @endif
                                @endforeach
                            </select>
                            
                        </div>

                        <div class="mb-3">
                            <label for="suplement_id" class="form-label">{{ ucwords('Suplement') }}</label>
                            <select id="suplement_id" name="suplement_id" class="form-control" required>
                                <option value="" disabled selected>Select Suplement</option>
                                @foreach ($suplements as $sup)
                                    <option value="{{ $sup->id }}" {{ $suplement->id == $sup->id ? 'selected' : '' }}>{{ $sup->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="amount" class="form-label">{{ ucwords('Amount') }}</label>
                            <input type="text" class="form-control" value="{{ $suplement->amount }}" id="amount" name="amount">
                        </div>

                        <button type="submit" class="btn btn-success btn-xs" style="width:80px; margin-right:20px">
                            Update
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- end update modal --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            var table = $('#table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('suplement-transaction.data') }}",
                columns: [{
                        data: 'id', name: 'id',
                        orderable: false
                    },
                    {
                        data: 'name',
                        name: 'name', orderable: false
                    },
                    {
                        data: 'suplement',
                        name: 'suplement', orderable: false
                    },
                    {
                        data: 'amount',
                        name: 'amount', orderable: false
                    },
                    {
                        data: 'price',
                        name: 'price', orderable: false
                    },
                    {
                        data: 'total',
                        name: 'total', orderable: false
                    },
                    {
                        data: 'date',
                        name: 'date', orderable: false,
                        render: function(data, type, full, meta) {
                            var dateOfBirth = moment(data);
                            return dateOfBirth.format('DD-MM-YYYY');
                        }
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

            
    
            $('#table').on('click', '.btn-update', function(e) {
                e.preventDefault(); // Prevent default link behavior
                var url = $(this).attr('href');
                var modalId = $(this).data('target'); // Ambil ID modal dari data-target
    
                $(modalId).modal('show'); // Tampilkan modal yang sesuai dengan ID
            });


            $('#table').on('click', '.additional-btn', function(e) {
                console.log('clicked');
                e.preventDefault();
                var userId = $(this).data('id');
                console.log(userId);
                window.location.href = '/suplement-transaction/detail/' + userId;
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
                        $('#addSuplementr').modal('hide');
                        // Tampilkan pesan sukses jika perlu
                        Swal.fire("Success", "Successfully transaction suplement!", "success");
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
            document.getElementById('add-form').submit();
        }
    
        function openAddModal() {
            $('#confirmationModal').modal('hide'); // Hide the current modal
            $('#addSuplementtr').modal('show'); // Show the addSuplement modal
        }
    </script>
@endsection