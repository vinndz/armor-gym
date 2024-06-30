@extends('layouts.master')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/libs/gridjs/theme/mermaid.min.css') }}">
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    @include('instructor.gym_schedule.style')
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
                <div style="width : 100%; height : 700px; overflow : auto; ">
                    <table class="table table-hover  table-responsive table-condensed animate__animated animate__fadeIn"
                        id="table" width="100%">
                        <thead class="table-dark ">
                            <th style="color: black;">{{ ucwords('no') }}</th>
                            <th style="color: black;">{{ ucwords('membership type') }}</th>
                            <th style="color: black;">{{ ucwords('price') }}</th>
                            <th style="color: black;">{{ ucwords('description') }}</th>
                            <th style="color: black;">{{ ucwords('action') }}</th>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- update modal --}}
    @foreach ($memberships as $item)
        <div class="modal fade" id="updateMembership{{ $item->id }}" tabindex="-1"
          aria-labelledby="updateMembershipLabel{{ $item->id }}" aria-hidden="true">
          <div class="modal-dialog modal-dialog-scrollable modal-xl modal-dialog-centered">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="updateMembershipLabel{{ $item->id }}">Update Suplement</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body p-4">
                      <form method="POST" action="{{ route('membership-data.update', ['id' => $item->id]) }}">
                          @csrf
                          @method('PUT')
                          <div class="mb-3">
                              <label for="type" class="form-label">{{ ucwords('type') }}</label>
                              <input type="text" class="form-control  @error('price') is-invalid @enderror"  value="{{ $item->type }}" id="type"
                                  name="type">
                                @error('type')
                                  <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                          </div>
                          <div class="mb-3">
                              <label for="price" class="form-label">{{ ucwords('price') }}</label>
                              <input type="text" class="form-control @error('price') is-invalid @enderror" value="{{ $item->price }}" id="price"
                                  name="price">
                                @error('price')
                                  <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                          </div>
                          <div class="mb-3">
                              <label for="description" class="form-label">{{ ucwords('description') }}</label>
                              <input type="text" class="form-control @error('description') is-invalid @enderror" value="{{ $item->description }}"
                                  id="description" name="description">
                                @error('description')
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

    {{-- add modal --}}
    <div class="modal fade" id="addMembership" tabindex="-1" aria-labelledby="addMembershipLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addMembershipLabel">Add Memberships</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form method="POST" id="add-form" action="{{ route('membership-data.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="type" class="form-label">{{ ucwords('type') }}</label>
                            <input type="text" class="form-control  @error('type') is-invalid @enderror" id="type" name="type">
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">{{ ucwords('price') }}</label>
                            <input type="number" class="form-control  @error('price') is-invalid @enderror" id="price" name="price">
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">{{ ucwords('description') }}</label>
                            <input type="text" class="form-control  @error('description') is-invalid @enderror" id="description" name="description">
                            @error('description')
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

    {{-- Modal confirm --}}
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
                    Are you sure you want to create supplement data?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>

                    <button type="submit" class="btn btn-primary" onclick="submitForm()">Confirm Submit</button>
                </div>
            </div>
        </div>
    </div>
    {{-- End modal confirm --}}

    <script type="text/javascript">
        $(document).ready(function() {
            // Pengaturan DataTables
            var table = $('#table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('membership-data.data') }}",
                columns: [
                    { data: 'id', orderable: false },
                    { data: 'type', name: 'type', render: function(data) { return data.charAt(0).toUpperCase() + data.slice(1); } },
                    { data: 'price', name: 'price' },
                    { data: 'description', name: 'description' },
                    { data: 'action', orderable: false },
                ],
                columnDefs: [{
                    className: "text-center",
                    targets: "_all"
                }],
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
    
            // Menampilkan modal update
            $('#table').on('click', '.btn-update', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                var modalId = $(this).data('target');
                $(modalId).modal('show');
            });
    
            // Mengatur placeholder untuk kotak pencarian DataTables
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
                        $('#addMembership').modal('hide');
                        // Tampilkan pesan sukses jika perlu
                        Swal.fire("Success", "Successfully created membership data!", "success");
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
                var form = $('#updateMembership' + id).find('form');
                $.ajax({
                    url: form.attr('action'),
                    method: form.attr('method'),
                    data: form.serialize(),
                    success: function(response) {
                        // Jika berhasil, reload DataTable dan tutup modal
                        table.ajax.reload();
                        $('#updateMembership' + id).modal('hide');
                        // Tampilkan pesan sukses jika perlu
                        Swal.fire("Success", "Successfully updated membership data!", "success");
                    },
                    error: function(xhr) {
                        // Tampilkan pesan kesalahan di modal
                        var errors = xhr.responseJSON.errors;
                        $('#updateMembership' + id).find('.invalid-feedback').remove(); // Hapus pesan kesalahan sebelumnya
                        $('#updateMembership' + id).find('.is-invalid').removeClass('is-invalid'); // Hapus class is-invalid sebelumnya

                        $.each(errors, function(field, messages) {
                            var input = $('#updateMembership' + id).find('[name="' + field + '"]');
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

        function openAddSuplementModal() {
            $('#confirmationModal').modal('hide'); 
            $('#addMembership').modal('show'); 
        }
    </script>
    


@endsection

