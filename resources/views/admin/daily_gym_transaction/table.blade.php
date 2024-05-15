<table class="table table-hover table-condensed table-striped animate__animated animate__fadeIn" id="table" width="100%">
    <thead class="table-dark">
        <tr class="text-center">
            <th>{{ ucwords('no') }}</th>
            <th>{{ ucwords('name') }}</th>
            <th>{{ ucwords('username') }}</th>
            <th>{{ ucwords('price') }}</th>
            <th>{{ ucwords('transaction date') }}</th>
            <th>{{ ucwords('action') }}</th>
        </tr>
    </thead>
    <tbody>
        @php
            $counter = 1;
        @endphp
        @foreach ($dailys as $daily)
            <tr class="text-center">
                <td>{{ $counter++ }}</td>
                <td>{{ ucwords($daily->user->name) }}</td>
                <td>{{ ucwords($daily->user->username) }}</td>
                <td>{{ ucwords($daily->price) }}</td>
                <td>{{ $daily->date ? \Carbon\Carbon::createFromFormat('Y-m-d', $daily->date)->format('d-m-Y') : '-' }}</td>
                <td>
                    <a href="{{ url("daily-gym-transaction/".$daily->id)}}" class="text-primary" data-bs-toggle="modal" data-bs-target="#updateDailyGymTransaction{{ $daily->id }}">
                        <button type="button" class="btn btn-success btn-xs" style="width:80px; margin-right:20px">
                            Update
                        </button>
                    </a> 
                </td>
            </tr>

            <div class="modal fade" id="updateDailyGymTransaction{{ $daily->id }}" tabindex="-1" aria-labelledby="updateDailyGymTransactionLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable modal-xl modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="updateDailyGymTransactionLabel{{ $daily->id }}">Update Daily Gym</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-4">
                            <form method="POST" action="{{ route('daily-gym-transaction.update', ['id' => $daily->id]) }}">
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    <label for="username" class="form-label">{{ ucwords('username') }}</label>
                                    <input type="text" class="form-control" value="{{ $daily->user->username }}" id="username" name="username">
                                </div>

                                <div class="mb-3">
                                    <label for="price" class="form-label">{{ ucwords('price') }}</label>
                                    <input type="text" class="form-control" value="{{ $daily->price }}" id="price" name="price">
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
