<table class="table table-hover table-condensed table-striped animate__animated animate__fadeIn" id="table" width="100%">
    <thead class="table-dark">
        <tr class="text-center">
            <th>{{ ucwords('no') }}</th>
            <th>{{ ucwords('name') }}</th>
            <th>{{ ucwords('suplement') }}</th>
            <th>{{ ucwords('Amount') }}</th>
            <th>{{ ucwords('price') }}</th>
            <th>{{ ucwords('Total') }}</th>
            <th>{{ ucwords('date') }}</th>
            <th>{{ ucwords('Action') }}</th>
        </tr>
    </thead>
    <tbody>
        @php
            $counter = 1;
        @endphp
        @foreach ($users as $user)
            @foreach ($user->suplements as $suplement)
                <tr class="text-center">
                    <td>{{ $counter++ }}</td>
                    <td>{{ ucwords($user->name) }}</td> 
                    <td>{{ ucwords($suplement->name) }}</td> 
                    <td>{{ $suplement->pivot->amount }}</td>
                    <td>{{ $suplement->price }}</td>
                    <td>Rp {{ number_format($suplement->pivot->total, 0, ',', '.') }}</td>
                    <td>{{ $suplement->pivot->date ? \Carbon\Carbon::parse($suplement->pivot->date)->format('d-m-Y') : '-' }}</td>
                    <td>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-success btn-xs" style="width:80px; margin-right:20px">
                                Update
                            </button>
                        </div>     
                    </td>  
                </tr>
            @endforeach
        @endforeach
    </tbody>
</table>
