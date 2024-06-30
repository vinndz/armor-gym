@extends('layouts.master')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/css/profile.css') }}">
@endsection

@section('content')
<div class="card">
    <div class="card-body p-5">
        <div class="container">
            <div class="row justify-content-center align-items-center">
                <div class="col-xl-8 col-12 mx-auto">
                    <div class="card user-card-full">
                        <div class="row no-gutters">
                            <div class="col-sm-4 bg-c-lite-green user-profile d-flex align-items-center justify-content-center">
                                <div class="text-center text-white">
                                    
                                    {{-- <img src="{{ asset('images/instructors/'.$user->image) }}" width="100" height="120" class="img-fluid img-radius rounded-circle mb-2" alt="User-Profile-Image"> --}}
                                    <img src="{{ $user->image != null ? asset('images/instructors/' . $user->profil) : asset('images/profile-default.png') }}" width="100" height="120" class="img-fluid img-radius rounded-circle mb-2" alt="User-Profile-Image">
                                    <div>
                                        <span class="fw-medium" >{{ ucwords(Auth::user()->name) }}</span>
                                    </div>
                                    <div>
                                        <i class="mdi mdi-square-edit-outline feather icon-edit mt-3 f-16" data-bs-toggle="modal" data-bs-target="#updateUser{{$user->id}}"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <div class="card-body">
                                    <h6 class="mb-4 pb-2 border-bottom border-dark f-w-800">User Information</h6>
                                    <div class="row mb-3">
                                        <div class="col-sm-6">
                                            <p class="mb-1 text-uppercase text-muted">Email:</p>
                                            <p class="mb-0">{{ ucwords(Auth::user()->email) }}</p>
                                        </div>
                                        <div class="col-sm-6">
                                            <p class="mb-1 text-uppercase text-muted">Username:</p>
                                            <p class="mb-0">{{ ucwords(Auth::user()->username) }}</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <p class="mb-1 text-uppercase text-muted">Gender:</p>
                                            <p class="mb-0">{{ ucwords(Auth::user()->gender) }}</p>
                                        </div>
                                        <div class="col-sm-6">
                                            <p class="mb-1 text-uppercase text-muted">Birth Date:</p>
                                            <p class="mb-0">{{ date_format(date_create(Auth::user()->date_of_birth), 'd-m-Y') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

 {{-- add modal --}}
 <div class="modal fade" id="updateUser{{$user->id}}" tabindex="-1" aria-labelledby="updateUserLabel{{$user->id}}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateUserLabel{{$user->id}}">Update User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form method="POST" id ="user-form" action="{{ route('instructor.profile-update', ['id' => $user->id]) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="name" class="form-label">{{ ucwords('name') }}</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{$user->name}}" required>
                    </div>
                    <div>
                        <label for="date_of_birth" class="form-label">Birth Date</label>
                        <input type="date" name="date_of_birth" id="date_of_birth" value="{{$user->date_of_birth}}" class="form-control">
                        @error('date_of_birth')
                        <span class="text-red-600">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label for="gender" class="form-label">Gender</label>
                        <select name="gender" id="gender" class="form-control">
                            <option value="" disabled>Select Gender</option>
                            <option value="male" {{ $user->gender == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ $user->gender == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ $user->gender == 'other' ? 'selected' : '' }}>Other</option>
                        </select>                        
                        @error('gender')
                        <span class="text-red-600">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label for="email" class="form-label">Your email</label>
                        <input type="text" name="email" id="email" value="{{$user->email}}" class="form-control" placeholder="name@company.com">
                        @error('email')
                        <span class="text-red-600">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label for="username" class="form-label">Username</label>
                        <input type="text" name="username" id="username" value="{{$user->username}}" class="form-control" placeholder="username">
                        @error('username')
                        <span class="text-red-600">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" id="password" placeholder="••••••••" class="form-control">
                        @error('password')
                        <span class="text-red-600">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">{{ ucwords('image') }}</label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*" onchange="previewImage(event)">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <img id="image-preview-update-{{ $user->id }}" class="image-preview" src="{{ asset('images/instructors/'.$user->image) }}" alt="Current Image" style="max-width: 200px; margin-top: 10px; @if(!$user->image) display:none; @endif">
                    </div>
                    <button type="button" class="btn btn-primary mt-2" id="submit-btn">Submit</button>
                </form>

            </div>
        </div>
    </div>
</div>
{{-- end add modal --}}


    
@endsection

@section('scripts')
    <script>
        $('#submit-btn').click(function() {
            console.log(123);
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
                        Swal.fire("Success", "Successfully updated profile instructor!", "success");
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


        document.getElementById('submit-btn').addEventListener('click', function() {
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
