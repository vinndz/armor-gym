@extends('layouts.master')

@section('css')
<link rel="stylesheet" href="{{ URL::asset('assets/css/profile.css') }}">
@endsection


@section('content')
<div class="row">
    <div class="col-xl-4">
        <!-- Profile picture card-->
        <div class="card mb-4 mb-xl-0">
            <div class="card-header">Profile Picture</div>
            <div class="card-body text-center">
                <img class="img-account-profile mb-2 rounded-circle"
                    src="{{ $user->image != null ? asset('storage/' . $user->image) : asset('images/profile-default.png') }}"
                    width="150" alt="User-Profile-Image">
                <div class="small font-italic text-muted mb-4">JPG or PNG no larger than 2 MB</div>
                <button class="btn btn-primary" type="button" data-bs-toggle="modal"
                    data-bs-target="#updateUser{{$user->id}}">Upload new image</button>
            </div>
        </div>
    </div>
    <div class="col-xl-8">
        <!-- Account details card-->
        <div class="card mb-4">
            <div class="card-header">Account Details</div>
            <div class="card-body">
                <form method="POST" id="user-update" action="{{ route('guest.update-profile', ['id' => $user->id]) }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="small mb-1" for="name">name</label>
                        <input class="form-control @error('name') is-invalid @enderror" name="name" id="name"
                            type="text" placeholder="Enter your name" value="{{ ucwords($user->name) }}">
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="row gx-3 mb-3">
                        <div class="col-md-6">
                            <label class="small mb-1" for="gender">Gender</label>
                            <select name="gender" id="gender"
                                class="form-control @error('gender') is-invalid @enderror">
                                <option value="" disabled>Select Gender</option>
                                <option value="male" {{ $user->gender == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ $user->gender == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ $user->gender == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('gender')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="small mb-1" for="date_of_birth">Birthday</label>
                            <input class="form-control @error('date_of_birth') is-invalid @enderror" id="date_of_birth"
                                type="date" name="date_of_birth" placeholder="Enter your birthday"
                                value="{{ $user->date_of_birth }}">
                            @error('date_of_birth')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="small mb-1" for="username">Username</label>
                        <input class="form-control @error('username') is-invalid @enderror" id="username" type="text"
                            name="username" placeholder="Enter your username" value="{{ ucwords($user->username) }}">
                        @error('username')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="small mb-1" for="email">Email address</label>
                        <input class="form-control @error('email') is-invalid @enderror" id="email" type="email"
                            name="email" placeholder="Enter your email address" value="{{ ucwords($user->email) }}">
                        @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="small mb-1" for="password">Password*</label>
                        <input class="form-control" id="password" type="password" name="password"
                            placeholder="••••••••">
                        @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button class="btn btn-primary" id="submit-btn" type="button">Save changes</button>
                </form>
            </div>
        </div>
    </div>
</div>
</div>

<div class="modal fade" id="updateUser{{$user->id}}" tabindex="-1" aria-labelledby="updateUserLabel{{$user->id}}"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateUserLabel{{$user->id}}">Update User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form method="POST" id="user-form"
                    action="{{ route('guest.upload-profile-picture', ['id' => $user->id]) }}"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="image" class="form-label">{{ ucwords('image') }}</label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" id="image"
                            name="image" accept="image/*" onchange="previewImage(event)">
                        @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <img id="image-preview-update-{{ $user->id }}" class="image-preview"
                            src="{{ asset('storage/'.$user->image) }}" alt="Current Image"
                            style="max-width: 200px; margin-top: 10px; @if(!$user->image) display:none; @endif">
                    </div>
                    <button class="btn btn-primary" type="button" id="submit-image">Save changes</button>
                </form>

            </div>
        </div>
    </div>
</div>

@endsection
@section('scripts')
<script>
    $('#submit-image').click(function() {
            var id = $(this).attr('id');
            var form = $('#updateUser' + id).find('form');
            
            if (form.length > 0) {
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
                        $('#updateUser' + id).modal('hide');
                        // Tampilkan pesan sukses jika perlu
                        Swal.fire("Success", "Successfully updated profile image instructor!", "success");
                    },
                    error: function(xhr) {
                        // Tampilkan pesan kesalahan di modal
                        var errors = xhr.responseJSON.errors;
                        $('#updateUser' + id).find('.invalid-feedback').remove(); // Hapus pesan kesalahan sebelumnya
                        $('#updateUser' + id).find('.is-invalid').removeClass('is-invalid'); // Hapus class is-invalid sebelumnya

                        $.each(errors, function(field, messages) {
                            var input = $('#updateUser' + id).find('[name="' + field + '"]');
                            input.addClass('is-invalid');
                            input.after('<div class="invalid-feedback">' + messages.join('<br>') + '</div>');
                        });
                    }
                });
            }
        });

        $('#submit-btn').click(function(event) {
            event.preventDefault(); // Mencegah pengiriman form standar

            var form = $('#user-update'); // Ambil form
            var formData = new FormData(form[0]); // Buat FormData dari form

            // Hapus pesan error sebelumnya
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();

            $.ajax({
                url: form.attr('action'),
                method: form.attr('method'),
                data: formData,
                contentType: false, // Atur contentType menjadi false agar browser dapat menentukan ContentType secara otomatis
                processData: false, // Set processData menjadi false agar jQuery tidak memproses data
                success: function(response) {
                    console.log('Response:', response);
                    
                    // Pengecekan tambahan untuk respon sukses
                    if (response.success) {
                        // Tampilkan pesan sukses jika perlu
                        Swal.fire("Success", response.message, "success");
                    } else {
                        // Tampilkan pesan error dari respon jika ada
                        if (response.errors) {
                            $.each(response.errors, function(field, messages) {
                                $('#' + field).addClass('is-invalid').after('<div class="invalid-feedback">' + messages[0] + '</div>');
                            });
                        } else {
                            // Jika tidak ada response.success dan tidak ada response.errors
                            Swal.fire("Error", response.message, "error");
                        }
                    }
                },
                error: function(xhr) {
                    console.log('Error response', xhr);
                    var errors = xhr.responseJSON.errors;
                    $.each(errors, function(field, messages) {
                        $('#' + field).addClass('is-invalid').after('<div class="invalid-feedback">' + messages[0] + '</div>');
                    });
                }
            });
        });

    


        function previewImage(event) {
            var input = event.target;
            var reader = new FileReader();
            reader.onload = function() {
                var imgElement = document.getElementById('image-preview-update-{{$user->id}}'); // Perbarui pemanggilan elemen gambar
                imgElement.src = reader.result;
                imgElement.style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        }


        document.getElementById('submit-image').addEventListener('click', function() {
        // Ambil elemen formulir
            var form = document.getElementById('user-form');
            // Validasi formulir sebelum mengirimkan
            if (form.checkValidity()) {
                // Submit formulir jika valid
                form.submit();
            } else {
                // Jika formulir tidak valid, fokuskan pada elemen yang tidak valid
                form.reportValidity();
            }
    });
</script>
@endsection