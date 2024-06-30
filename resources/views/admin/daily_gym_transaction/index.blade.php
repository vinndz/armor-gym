@extends('layouts.master')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/libs/gridjs/theme/mermaid.min.css') }}">
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    @include('instructor.gym_schedule.style')
@endsection

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
                <div style="width : 100%; height : 700px; overflow : auto; ">
                    <table class="table table-hover  table-responsive table-condensed animate__animated animate__fadeIn"
                        id="table" width="100%">
                        <thead class="table-dark ">
                            <th style="color: black;">{{ ucwords('no') }}</th>
                            <th style="color: black;">{{ ucwords('name') }}</th>
                            <th style="color: black;">{{ ucwords('username') }}</th>
                            <th style="color: black;">{{ ucwords('price') }}</th>
                            <th style="color: black;">{{ ucwords('transaction date') }}</th>
                            <th style="color: black;">{{ ucwords('action') }}</th>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- add modal --}}
    <div class="modal fade" id="addDailyGym" tabindex="-1" aria-labelledby="addDailyGymLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addDailyGymLabel">Add DailyGym</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form method="POST" id="add-form" action="{{ route('daily-gym-transaction.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="username" class="form-label">{{ ucwords('username') }}</label>
                            <select required name="username" id="username" class="form-control @error('username') is-invalid @enderror" >
                                <option value="" disabled selected>select username</option>
                                @foreach ($users as $item)
                                    <option value="{{ $item->username }}">{{ $item->username }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="price" class="form-label">{{ ucwords('price') }}</label>
                            <input type="text" class="form-control @error('price') is-invalid @enderror"  id="price" name="price">
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="button" class="btn btn-primary" id="submit-btn">Submit</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
    {{-- end add modal --}}


    {{-- update modal --}}
    @foreach ($dailys as $item)
        <div class="modal fade" id="updateDaily{{ $item->id }}" tabindex="-1"
            aria-labelledby="updateDailyLabel{{ $item->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateDailyLabel{{ $item->id }}">Update Suplement</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <form method="POST" action="{{ route('daily-gym-transaction.update', ['id' => $item->id]) }}">
                            @csrf
                            @method('PUT')
                            {{-- <div class="mb-3">
                                <label for="username" class="form-label">{{ ucwords('username') }}</label>
                                <select required name="username" id="username" class="form-control @error('username') is-invalid @enderror">
                                    <option value="" disabled selected>select username</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}" {{ $user->id == $item->user_id ? 'selected' : '' }}>
                                            {{ $user->username }}
                                        </option>
                                    @endforeach
                                </select>
                                
                            </div> --}}
                            <div class="mb-3">
                                <label for="username" class="form-label">{{ ucwords('username') }}</label>
                                <select required name="username" id="username" class="form-control @error('username') is-invalid @enderror">
                                    <option value="" disabled selected>select username</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}" {{ $user->id == $item->user_id ? 'selected' : '' }}>
                                            {{ $user->username }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @error('transaction')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
    
                            <div class="mb-3">
                                <label for="price" class="form-label">{{ ucwords('price') }}</label>
                                <input type="text" class="form-control @error('price') is-invalid @enderror" value="{{ $item->price }}" id="price" name="price">
                                @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            </div>
    
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
                    Are you sure you want to create daily gym transaction?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="openAddModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary" onclick="submitForm()">Confirm Submit</button>
                </div>
            </div>
        </div>
    </div>
 {{-- end modal confirm --}}


 <script type="text/javascript">
    $(document).ready(function() {
        var table = $('#table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('daily-gym-transaction.data') }}",
            columns: [{
                    data: 'id',
                    orderable: false
                },
                {
                    data: 'name',
                    name: 'name',orderable: false,
                    render: function(data, type, row) {
                        return data.charAt(0).toUpperCase() + data.slice(1);
                    }
                },
                {
                    data: 'username',
                    name: 'username',orderable: false,
                    render: function(data, type, row) {
                        return data.charAt(0).toUpperCase() + data.slice(1);
                    }
                },
                {
                    data: 'price',
                    name: 'price',orderable: false
                },
                {
                    data: 'date',
                    name: 'date',orderable: false
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

        $('#table').on('click', '.detail-btn', function(e) {
            e.preventDefault();
            var userId = $(this).data('id');
            console.log(userId);
            window.location.href = '/daily-gym-transaction/detail/' + userId;
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
                    $('#addDailyGym').modal('hide');
                    // Tampilkan pesan sukses jika perlu
                    Swal.fire("Success", "Successfully created transaction daily gym!", "success");
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
            var form = $('#updateDaily' + id).find('form');
            $.ajax({
                url: form.attr('action'),
                method: form.attr('method'),
                data: form.serialize(),
                success: function(response) {
                    // Jika berhasil, reload DataTable dan tutup modal
                    table.ajax.reload();
                    $('#updateDaily' + id).modal('hide');
                    // Tampilkan pesan sukses jika perlu
                    Swal.fire("Success", "Successfully updated daily gym!", "success");
                },
                error: function(xhr) {
                    // Tampilkan pesan kesalahan di modal
                    var errors = xhr.responseJSON.errors;
                    $('#updateDaily' + id).find('.invalid-feedback').remove(); // Hapus pesan kesalahan sebelumnya
                    $('#updateDaily' + id).find('.is-invalid').removeClass('is-invalid'); // Hapus class is-invalid sebelumnya

                    $.each(errors, function(field, messages) {
                        var input = $('#updateDaily' + id).find('[name="' + field + '"]');
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
        $('#addDailyGym').modal('show'); // Show the addSuplement modal
    }
</script>

@endsection