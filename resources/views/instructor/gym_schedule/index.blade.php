@extends('layouts.master')

@section('css')
    <!-- Datatables CSS CDN -->
    <link rel="stylesheet" href="{{ URL::asset('assets/libs/gridjs/theme/mermaid.min.css') }}">
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    @include('instructor.gym_schedule.style')
@endsection

@section('content')

<div class="content">
    <h2>Schedule Gym</h2>
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-end">
                <div class="class">
                    <button type="button" class="btn btn-primary mb-4" data-bs-toggle="modal"
                        data-bs-target="#addScheduleGym"><i class="mdi mdi-plus me-1"></i> Schedule Gym
                    </button>
                </div>
            </div>
            <div style="width: 100%; height: 700px; overflow: auto;">
                <table class="table table-hover  table-responsive table-condensed animate__animated animate__fadeIn" id="table" width="100%">
                    <thead class="table-dark ">
                        <th style="color: black;">{{ ucwords('no') }}</th>
                        <th style="color: black;">{{ ucwords('name member') }}</th>
                        <th style="color: black;">{{ ucwords('date') }}</th>
                        <th style="color: black;">{{ ucwords('day') }}</th>
                        <th style="color: black;">{{ ucwords('time') }}</th>
                        <th style="color: black;">{{ ucwords('status') }}</th>
                        <th style="color: black;">{{ ucwords('action') }}</th>
                    </thead>                    
                </table>
            </div>            
        </div>
    </div>
</div>


 {{-- add modal --}}
 <div class="modal fade" id="addScheduleGym" tabindex="-1" aria-labelledby="addScheduleGymLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addScheduleGymLabel">Add Schedule Gym</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form method="POST" id ="add-form" action="{{ route('gym-schedule.store') }}">
                    @csrf
                    <input type="hidden" value="{{ auth()->user()->id}}" name="id">
                        <div class="mb-3">
                            <label for="membership_id" class="form-label">{{ ucwords('Member') }}</label>
                            <select id="membership_id" name="membership_id" class="form-control  @error('membership_id') is-invalid @enderror" required>
                                <option value="" disabled selected>Select Member</option>
                                @foreach ($members as $userId => $name)
                                    <option value="{{ $userId }}">{{ $name }}</option>
                                @endforeach
                            </select>                                                   
                        </div>
                    
                        <div class="mb-3">
                            <label for="date" class="form-label">{{ ucwords('date') }}</label>
                            <input type="datetime-local" class="form-control  @error('date') is-invalid @enderror" id="date" name="date">
                        </div>
                    <button type="button" class="btn btn-primary" id="submit-btn">Submit</button>
                </form>

            </div>
        </div>
    </div>
</div>
{{-- end add modal --}}

@foreach ($datas as $data)
<div class="modal fade" id="updateScheduleGym{{ $data->id }}" tabindex="-1"
    aria-labelledby="updateScheduleGymLabel{{ $data->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateScheduleGymLabel{{ $data->id }}">Update Program</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
               <form method="POST" action="{{ route('gym-schedule.update', ['id' => $data->id]) }}">
                   @csrf
                   @method('PUT')
                   <div class="mb-3">
                    <div class="mb-3">
                        <label for="membership_transaction_id" class="form-label">{{ ucwords('member') }}</label>
                        <select required name="membership_transaction_id" id="membership_transaction_id" class="form-control @error('membership_transaction_id') is-invalid @enderror">
                            @foreach ($members as $userId => $name)
                                <option value="{{ $userId }}" {{ old('membership_transaction_id') == $userId ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                   </div>
                   <div class="mb-3">
                        <label for="date" class="form-label">{{ ucwords('date') }}</label>
                        <input type="datetime-local" class="form-control @error('date') is-invalid @enderror" value="{{ $data->date }}" id="date" name="date">
                        @error('date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    </div>
                   <button type="button" class="btn btn-primary update-submit-btn" data-id="{{ $data->id }}">Submit</button>
               </form>
            </div>
        </div>
    </div>
</div>
@endforeach


  <!-- Script -->
  <script type="text/javascript">
    $(document).ready(function() {
        var table = $('#table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('gym-schedule.data') }}",
            columns: [
                { data: 'id', orderable: false },
                { 
                    data: 'membership_transaction_id', 
                    name: 'membership_transaction_id',
                    render: function(data) {
                        return data.charAt(0).toUpperCase() + data.slice(1);
                    }
                },
                { data: 'date', name: 'date' },
                { data: 'day', name: 'day' },
                { data: 'time', name: 'time' },
                { data: 'status', name: 'status',
                render: function(data) {
                        return data.charAt(0).toUpperCase() + data.slice(1);
                    }
                 },
                { data: 'action', orderable: false },
            ],
            columnDefs: [
                { className: "text-center", targets: "_all" },
            ],
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
                    $('#addScheduleGym').modal('hide');
                    // Tampilkan pesan sukses jika perlu
                    Swal.fire("Success", "Successfully created gym schedule member!", "success");
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
            var form = $('#updateScheduleGym' + id).find('form');
            $.ajax({
                url: form.attr('action'),
                method: form.attr('method'),
                data: form.serialize(),
                success: function(response) {
                    // Jika berhasil, reload DataTable dan tutup modal
                    table.ajax.reload();
                    $('#updateScheduleGym' + id).modal('hide');
                    // Tampilkan pesan sukses jika perlu
                    Swal.fire("Success", "Successfully updated daily gym!", "success");
                },
                error: function(xhr) {
                    // Tampilkan pesan kesalahan di modal
                    var errors = xhr.responseJSON.errors;
                    $('#updateScheduleGym' + id).find('.invalid-feedback').remove(); // Hapus pesan kesalahan sebelumnya
                    $('#updateScheduleGym' + id).find('.is-invalid').removeClass('is-invalid'); // Hapus class is-invalid sebelumnya

                    $.each(errors, function(field, messages) {
                        var input = $('#updateScheduleGym' + id).find('[name="' + field + '"]');
                        input.addClass('is-invalid');
                        $.each(messages, function(index, message) {
                            input.after('<div class="invalid-feedback">' + message + '</div>');
                        });
                    });
                }
            });
        });
    });
</script>
@endsection
