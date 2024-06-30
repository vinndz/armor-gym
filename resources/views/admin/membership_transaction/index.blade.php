@extends('layouts.master')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/libs/gridjs/theme/mermaid.min.css') }}">
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    @include('instructor.gym_schedule.style')
@endsection

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
                <div style="width : 100%; height : 700px; overflow : auto; " >
                    <table class="table table-hover  table-responsive table-condensed animate__animated animate__fadeIn"
                        id="table" width="100%">
                        <thead class="table-dark ">
                            <th style="color: black;">{{ ucwords('no') }}</th>
                            <th style="color: black;">{{ ucwords('name') }}</th>
                            <th style="color: black;">{{ ucwords('membershi type') }}</th>
                            <th style="color: black;">{{ ucwords('start date') }}</th>
                            <th style="color: black;">{{ ucwords('end date') }}</th>
                            <th style="color: black;">{{ ucwords('total') }}</th>
                            <th style="color: black;">{{ ucwords('status') }}</th>
                            <th style="color: black;">{{ ucwords('action') }}</th>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        
        {{-- add modal --}}
        <div class="modal fade" id="activationMember" tabindex="-1" aria-labelledby="activationMemberLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="activationMemberLabel">Activation Member</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <form method="POST" id="transaction-form" action="{{ route('membership-transaction.store') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="username" class="form-label">{{ ucwords('username') }}</label>
                                <select id="username" name="username" class="form-select  @error('username') is-invalid @enderror" required>
                                    <option value="">Choose Username</option> <!-- Opsi default -->
                                    @foreach ($users as $user)
                                        @if ($user->role == 'GUEST')
                                            <option value="{{ $user->username }}">{{ $user->username }}</option>
                                        @endif
                                    @endforeach
                                </select>                             
                            </div>                            
                            
                            <div class="mb-3">
                                <label for="start_date" class="form-label">{{ ucwords('start date') }}</label>
                                <input type="date" class="form-control  @error('start_date') is-invalid @enderror" id="start_date" name="start_date" required>
                            </div>

                            <div class="mb-3">
                                <label for="month" class="form-label">{{ ucwords('Month') }}</label>
                                <select required name="month" id="month" class="form-control  @error('month') is-invalid @enderror">
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

                            <button type="button" class="btn btn-primary" id="submit-btn">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Confirmation Modal -->

        {{-- update modal --}}
        @foreach ($userMembership as $item)
        <div class="modal fade" id="updateActivationMember{{ $item->id }}" tabindex="-1"
            aria-labelledby="updateActivationMemberLabel{{ $item->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateActivationMemberLabel{{ $item->id }}">Update Activation Member</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <form method="POST" action="{{ route('membership-transaction.update', ['id' => $item->id]) }}">
                            @csrf
                            @method('PUT')
                            @if ($item->membership->type === 'membership')
                            <div class="mb-3">
                                <label for="type" class="form-label">{{ ucwords('type') }}</label>
                                <select class="form-select" id="type" name="type" required>
                                    @foreach ($types as $type)
                                    @if ($type->type == 'membership add instructor')
                                        <option value="{{ $type->id }}" {{ ($type->type == $item->membership->type) ? 'selected' : '' }}>
                                            {{ $type->type }}
                                        </option>
                                    @endif
                                @endforeach
                                </select>
                                
                            </div>
                            @endif
                            <button type="button" class="btn btn-primary update-submit-btn" data-id="{{ $item->id }}">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        {{-- end update modal --}}

         {{-- modal confirm --}}
        <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel"
            aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmationModalLabel">Confirmation</h5>
                    <button type="button" class="btn-close" onclick="openAddModal()"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to activation this member?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="openAddModal()">Cancel</button>
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
            ajax: "{{ route('membership-transaction.data') }}",
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
                    data: 'type',
                    name: 'type',
                    orderable:false
                },
                {
                    data: 'start_date',
                    name: 'start_date',
                    render: function(data, type, full, meta) {
                            var dateOfBirth = moment(data);
                            return dateOfBirth.format('DD-MM-YYYY');
                    }
                },
                {
                    data: 'end_date',
                    name: 'end_date',
                    render: function(data, type, full, meta) {
                            var dateOfBirth = moment(data);
                            return dateOfBirth.format('DD-MM-YYYY');
                    }
                },

                {
                    data: 'total',
                    name: 'total',
                    render: function(data, type, row) {
                        return data.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                    }
                },

                {
                    data: 'status',
                    name: 'status',
                    render: function(data) {
                            return data.charAt(0).toUpperCase() + data.slice(1);
                        }
                },

                {
                    data: 'action',
                    name: 'action',
                    orderable:false
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

        $('#table').on('click', '.card-btn', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            window.location.href = '/membership-transaction/card-member/' + id;
        });

        $('#table').on('click', '.receipt-btn', function(e) {
            e.preventDefault();
            var userId = $(this).data('id');
            console.log(userId);
            window.location.href = '/membership-transaction/receipt/' + userId;
        });

        // Menambahkan placeholder ke dalam kotak pencarian
        $('div.dataTables_wrapper input[type="search"]').attr('placeholder', 'Search...');

        $('#submit-btn').click(function() {
                var form = $('#transaction-form');
                $.ajax({
                    url: form.attr('action'),
                    method: form.attr('method'),
                    data: form.serialize(),
                    success: function(response) {
                        // Jika berhasil, reload DataTable dan tutup modal
                        table.ajax.reload();
                        $('#activationMember').modal('hide');
                        // Tampilkan pesan sukses jika perlu
                        Swal.fire("Success", "Successfully activation membership!", "success");
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
            
        $('.update-submit-btn').click(function() {
                var id = $(this).data('id');
                var form = $('#updateActivationMember' + id).find('form');
                $.ajax({
                    url: form.attr('action'),
                    method: form.attr('method'),
                    data: form.serialize(),
                    success: function(response) {
                        // Jika berhasil, reload DataTable dan tutup modal
                        table.ajax.reload();
                        $('#updateActivationMember' + id).modal('hide');
                        // Tampilkan pesan sukses jika perlu
                        Swal.fire("Success", "Successfully updated activation membership!", "success");
                    },
                    error: function(xhr) {
                        // Tampilkan pesan kesalahan di modal
                        var errors = xhr.responseJSON.errors;
                        $('#updateActivationMember' + id).find('.invalid-feedback').remove(); // Hapus pesan kesalahan sebelumnya
                        $('#updateActivationMember' + id).find('.is-invalid').removeClass('is-invalid'); // Hapus class is-invalid sebelumnya

                        $.each(errors, function(field, messages) {
                            var input = $('#updateActivationMember' + id).find('[name="' + field + '"]');
                            input.addClass('is-invalid');
                            $.each(messages, function(index, message) {
                                input.after('<div class="invalid-feedback">' + message + '</div>');
                            });
                        });
                    }
                });
        });
    });

    function handleCardMemberClick(event, status) {
        event.preventDefault(); // Mencegah tindakan default dari link
        console.log(123);
        if (status !== 'active') {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Member not active, cannot access!'
            });
        }
    }


    function submitForm() {
        document.getElementById('transaction-form').submit();
    }

    function openAddModal() {
        $('#confirmationModal').modal('hide'); // Hide the current modal
        $('#activationMember').modal('show'); // Show the addSuplement modal
    }
</script>
@endsection
