<table class="table table-hover table-condensed table-striped animate__animated animate__fadeIn" id="table" width="100%">
    <thead class="table-dark">
        <tr class="text-center">
            <th>{{ ucwords('no') }}</th>
            <th>{{ ucwords('Membership Type') }}</th>
            <th>{{ ucwords('Price') }}</th>
            <th>{{ ucwords('Description') }}</th>
            <th>{{ ucwords('Action') }}</th>

        </tr>
    </thead>
    <tbody>
        @php
            $counter = 1;
        @endphp
        @foreach ($memberships as $membership)
            <tr class="text-center">
                <td>{{ $counter++ }}</td>
                <td>{{ ucwords($membership->type) }}</td>
                <td>{{ ucwords($membership->price) }}</td>
                <td>{{ $membership->description }}</td>
                <td>
                    <div class="btn-group" role="group" >
                        <a href="{{ route('membership-data.update', ['id' => $membership->id]) }}" class="text-primary" data-bs-toggle="modal" data-bs-target="#updateMembership{{ $membership->id }}">
                            <button type="button" class="btn btn-success btn-xs" style="width:80px; margin-right:20px">
                                Update
                            </button>
                        </a>                                    
                        <form onsubmit="return confirm('Apakah anda ingin menghapus data suplemen?');"
                            action="{{ url('membership-data/destroy/' . $membership->id) }}" method="POST">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-danger btn-xs" style="width:80px">
                                <span class="glyphicon glyphicon-remove"></span> Delete
                            </button>
                        </form>   
                    </div>     
                </td>
            </tr>
            <div class="modal fade" id="updateMembership{{ $membership->id }}" tabindex="-1" aria-labelledby="updateMembershipLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable modal-xl modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="updateMembershipLabel{{ $membership->id }}">Update Membership</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-4">
                            <form method="POST" action="{{ route('membership-data.update', ['id' => $membership->id]) }}">
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    <label for="type" class="form-label">{{ ucwords('type') }}</label>
                                    <input type="text" class="form-control" value="{{ $membership->type }}" id="type"
                                        name="type">
                                </div>
                                <div class="mb-3">
                                    <label for="price" class="form-label">{{ ucwords('price') }}</label>
                                    <input type="text" class="form-control" value="{{ $membership->price }}" id="price"
                                        name="price">
                                </div>
                                <div class="mb-3">
                                    <label for="description" class="form-label">{{ ucwords('description') }}</label>
                                    <input type="text" class="form-control" value="{{ $membership->description }}"
                                        id="description" name="description">
                                </div>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                        </div>
            
                    </div>
                </div>
            </div>
            @endforeach    

    </tbody>
</table>
