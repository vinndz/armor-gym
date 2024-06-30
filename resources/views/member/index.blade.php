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
            </div>
            <div style="width: 100%; height: 700px; overflow: auto;">
                <table class="table table-hover  table-responsive table-condensed animate__animated animate__fadeIn" id="table" width="100%">
                    <thead class="table-dark ">
                        <th style="color: black;">{{ ucwords('no') }}</th>
                        <th style="color: black;">{{ ucwords('name instructor') }}</th>
                        <th style="color: black;">{{ ucwords('date') }}</th>
                        <th style="color: black;">{{ ucwords('day') }}</th>
                        <th style="color: black;">{{ ucwords('time') }}</th>
                        <th style="color: black;">{{ ucwords('status') }}</th>
                    </thead>                    
                </table>
            </div>            
        </div>
    </div>
</div>



  <!-- Script -->
  <script type="text/javascript">
    $(document).ready(function() {
        var table = $('#table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('gym-schedule.data-member') }}",
            columns: [
                { data: 'id', orderable: false },
                { 
                    data: 'name', 
                    name: 'name',
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
