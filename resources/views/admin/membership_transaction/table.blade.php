<table class="table table-hover table-condensed table-striped animate__animated animate__fadeIn" id="table" width="100%">
    <thead class="table-dark">
        <tr class="text-center">
            <th>{{ ucwords('no') }}</th>
            <th>{{ ucwords('name') }}</th>
            <th>{{ ucwords('membership type') }}</th>
            <th>{{ ucwords('Start Date') }}</th>
            <th>{{ ucwords('End Date') }}</th>
            <th>{{ ucwords('total') }}</th>
            <th>{{ ucwords('Status') }}</th>
            
            <th>{{ ucwords('Action') }}</th>
            
        </tr>
    </thead>
    <tbody>
        @php
            $counter = 1;
        @endphp
        @foreach ($memberships as $membership)
            @if ($membership->user !== null && $membership->user->role == 'MEMBER' && $membership->status == 'active')
                <tr class="text-center">
                    <td>    {{$counter++}}</td>
                    <td>    {{ucwords($membership->user->name)}}</td> 
                    <td>    {{ucwords($membership->membership->type)}}</td> 
                    <td>    {{ $membership->start_date ? \Carbon\Carbon::createFromFormat('Y-m-d', $membership->start_date)->format('d-m-Y') : '-' }}</td>
                    <td>    {{ $membership->end_date ? \Carbon\Carbon::createFromFormat('Y-m-d', $membership->end_date)->format('d-m-Y') : '-' }}</td>
                    <td>Rp  {{ number_format($membership->total, 0, ',', '.') }}</td>
                    <td>    {{ucwords($membership->status)}}</td>
                    <td>
                        <div class="btn-group" role="group" >
                            {{-- <a href="{{ url("admin/update-membership/".$membership->id)}}" class="text-primary" data-bs-toggle="modal" data-bs-target="#updateSuplement"> --}}
                                <button type="button" class="btn btn-success btn-xs" style="width:80px; margin-right:20px" >
                                    Update
                                </button>
                            {{-- </a>      --}}
                        </div>     
                    </td>  
                </tr>
            @endif
        @endforeach
    </tbody>
    
    
</table>