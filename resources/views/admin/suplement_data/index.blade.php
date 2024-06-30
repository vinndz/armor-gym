@extends('layouts.master')

@section('css')
<link rel="stylesheet" href="{{ URL::asset('assets/libs/gridjs/theme/mermaid.min.css') }}">
<script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
@include('instructor.gym_schedule.style')
@endsection

@section('content')
<div class="content">
    <h2>Data Suplement</h2>
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-end">
                <div class="class">
                    <button type="button" class="btn btn-primary mb-4" data-bs-toggle="modal"
                        data-bs-target="#addSuplement"><i class="mdi mdi-plus me-1"></i> Data Suplement
                    </button>
                </div>
            </div>
            <div style="width : 100%; height : 700px; overflow : auto; ">
                <table class="table table-hover  table-responsive table-condensed animate__animated animate__fadeIn"
                    id="table" width="100%">
                    <thead class="table-dark ">
                        <th style="color: black;">{{ ucwords('no') }}</th>
                        <th style="color: black;">{{ ucwords('name') }}</th>
                        <th style="color: black;">{{ ucwords('stock') }}</th>
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
@foreach ($suplements as $item)
<div class="modal fade" id="updateSuplement{{ $item->id }}" tabindex="-1"
    aria-labelledby="updateSuplementLabel{{ $item->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateSuplementLabel{{ $item->id }}">Update Suplement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form method="POST" action="{{ route('suplement-data.update', ['id' => $item->id]) }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="name" class="form-label">{{ ucwords('name') }}</label>
                        <input type="text" class="form-control  @error('stock') is-invalid @enderror"
                            value="{{ $item->name }}" id="name" name="name">
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="stock" class="form-label">{{ ucwords('stock') }}</label>
                        <input type="text" class="form-control  @error('stock') is-invalid @enderror"
                            value="{{ $item->stock }}" id="stock" name="stock">
                        @error('stock')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">{{ ucwords('price') }}</label>
                        <input type="text" class="form-control @error('price') is-invalid @enderror"
                            value="{{ $item->price }}" id="price" name="price">
                        @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">{{ ucwords('description') }}</label>
                        <input type="text" class="form-control @error('description') is-invalid @enderror"
                            value="{{ $item->description }}" id="description" name="description">
                        @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <input type="file" class="form-control @error('image') is-invalid @enderror" id="image"
                            name="image" accept="image/*" onchange="previewImageUpdate(event, '{{ $item->id }}')">
                        @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <img id="image-preview-update-{{ $item->id }}" class="image-preview"
                            src="{{ asset('storage/'.$item->image) }}" alt="Current Image"
                            style="max-width: 200px; margin-top: 10px; @if(!$item->image) display:none; @endif">
                    </div>

                    <button type="button" class="btn btn-primary update-submit-btn"
                        data-id="{{ $item->id }}">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach
{{-- end update modal --}}

{{-- add modal --}}
<div class="modal fade" id="addSuplement" tabindex="-1" aria-labelledby="addSuplementLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSuplementLabel">Add Suplements</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form method="POST" id="suplement-form" action="{{ route('suplement-data.store') }}"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">{{ ucwords('name') }}</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                            name="name" required>
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="stock" class="form-label">{{ ucwords('stock') }}</label>
                        <input type="text" class="form-control @error('stock') is-invalid @enderror" id="stock"
                            name="stock" required>
                        @error('stock')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">{{ ucwords('price') }}</label>
                        <input type="text" class="form-control @error('price') is-invalid @enderror" id="price"
                            name="price" required>
                        @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">{{ ucwords('description') }}</label>
                        <input type="text" class="form-control" id="description" name="description" required>
                        @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label">{{ ucwords('image') }}</label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" id="image"
                            name="image" accept="image/*" onchange="previewImageAdd(event)">
                        @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <img id="image-preview-add" class="image-preview" src="#" alt="Image Preview"
                            style="display: none; margin-top: 10px; max-width: 200px;">
                    </div>

                    {{-- <div class="mb-3">
                        <label for="image" class="form-label">{{ ucwords('image') }}</label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" id="image"
                            name="image" accept="image/*" onchange="previewImage(event)">
                        @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <img id="image-preview-update-{{ $item->id }}" class="image-preview"
                            src="{{ asset('storage/'.$item->image) }}" alt="Current Image"
                            style="max-width: 200px; margin-top: 10px; @if(!$item->image) display:none; @endif">
                    </div> --}}
                    <button type="button" class="btn btn-primary submit-btn" id="submit-btn">Submit</button>
                </form>

            </div>
        </div>
    </div>
</div>
{{-- end add modal --}}


<script type="text/javascript">
    $(document).ready(function() {
            var table = $('#table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('suplement-data.data') }}",
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
                        data: 'stock',
                        name: 'stock'
                    },
                    {
                        data: 'price',
                        name: 'price'
                    },
                    {
                        data: 'description',
                        name: 'description'
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

            // Menambahkan placeholder ke dalam kotak pencarian
            $('div.dataTables_wrapper input[type="search"]').attr('placeholder', 'Search...');

            $('#submit-btn').click(function() {
                var form = $('#suplement-form');
                var formData = new FormData(form[0]); // Buat objek FormData dan tambahkan formulir ke dalamnya
                $.ajax({
                    url: form.attr('action'),
                    method: form.attr('method'),
                    data: formData, // Mengirim formData alih-alih data serialisasi
                    processData: false, // Diperlukan ketika mengirim FormData
                    contentType: false, // Diperlukan ketika mengirim FormData
                    success: function(response) {
                        // Jika berhasil, reload DataTable dan tutup modal
                        table.ajax.reload();
                        $('#addSuplement').modal('hide');
                        // Tampilkan pesan sukses jika perlu
                        Swal.fire("Success", "Successfully created suplement data!", "success");
                        window.location.reload();
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
                var form = $('#updateSuplement' + id).find('form');
                var formData = new FormData(form[0]); // Buat objek FormData dari formulir

                $.ajax({
                    url: form.attr('action'),
                    method: form.attr('method'),
                    data: formData, // Gunakan objek FormData untuk mengirim data
                    contentType: false, // Atur contentType menjadi false agar browser dapat menentukan ContentType secara otomatis
                    processData: false, // Set processData menjadi false agar jQuery tidak memproses data
                    success: function(response) {
                        // Jika berhasil, reload DataTable dan tutup modal
                        table.ajax.reload();
                        $('#updateSuplement' + id).modal('hide');
                        // Tampilkan pesan sukses jika perlu
                        Swal.fire("Success", "Successfully updated suplement data!", "success");
                    },
                    error: function(xhr) {
                        // Tampilkan pesan kesalahan di modal
                        var errors = xhr.responseJSON.errors;
                        $('#updateSuplement' + id).find('.invalid-feedback').remove(); // Hapus pesan kesalahan sebelumnya
                        $('#updateSuplement' + id).find('.is-invalid').removeClass('is-invalid'); // Hapus class is-invalid sebelumnya

                        $.each(errors, function(field, messages) {
                            var input = $('#updateSuplement' + id).find('[name="' + field + '"]');
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

<script>
    function previewImageAdd(event) {
        var input = event.target;
        var reader = new FileReader();
        reader.onload = function() {
            var imgElement = document.getElementById('image-preview-add');
            imgElement.src = reader.result;
            imgElement.style.display = 'block';
        }
        reader.readAsDataURL(input.files[0]);
    }

    function previewImageUpdate(event, itemId) {
        var input = event.target;
        var reader = new FileReader();
        reader.onload = function() {
            var imgElement = document.getElementById('image-preview-update-' + itemId);
            imgElement.src = reader.result;
            imgElement.style.display = 'block';
        }
        reader.readAsDataURL(input.files[0]);
    }



   
</script>

@endsection