<table class="table table-hover table-condensed table-striped animate__animated animate__fadeIn" id="table" width="100%">
    <thead class="table-dark">
        <tr class="text-center">
            <th>No</th>
            <th>{{ ucwords('name') }}</th>
            <th>{{ ucwords('description') }}</th>
            <th>{{ ucwords('action') }}</th>
        </tr>
    </thead>
    <tbody>
        @php
            $counter = 1;
        @endphp
        @foreach ($datas as $data)
            <tr class="text-center">
                <td>{{ $counter++ }}</td>
                <td class="no-wrap">{{ $data->name }}</td>
                <td>{{ $data->description }}</td>
                <td>
                    <div class="btn-group" role="group" >
                        <a href="{{ url("program-data/update/".$data->id)}}" class="text-primary" data-bs-toggle="modal" data-bs-target="#updateProgram{{ $data->id }}">
                            <button type="button" class="btn btn-success btn-xs" style="width:80px; margin-right:20px">
                                Update
                            </button>
                        </a>                                    
                        <form onsubmit="return confirm('Apakah anda ingin menghapus data suplemen?');"
                            action="{{ url('program-data/destroy/' . $data->id) }}" method="POST">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-danger btn-xs" style="width:80px">
                                <span class="glyphicon glyphicon-remove"></span> Delete
                            </button>
                        </form>   
                    </div>     
                </td>                                                          
            </tr>

               {{-- update modal --}}
            <div class="modal fade" id="updateProgram{{ $data->id }}" tabindex="-1" aria-labelledby="updateProgramLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable modal-xl modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="updateProgramLabel{{ $data->id }}">Update Program</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-4">
                            <form method="POST" action="{{ route('program-data.update', ['id' => $data->id]) }}">
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    <label for="name" class="form-label">{{ ucwords('name') }}</label>
                                    <input type="text" class="form-control" value="{{$data->name}}" id="name" name="name" >
                                </div>
                                <div class="mb-3">
                                    <label for="description" class="form-label">{{ ucwords('description') }}</label>
                                    <input type="text" class="form-control"  value="{{$data->description}}"  id="description" name="description" >
                                </div>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        {{-- end update modal --}}

        @endforeach
    </tbody>
</table>