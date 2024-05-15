<table class="table table-hover table-condensed table-striped animate__animated animate__fadeIn" id="table" width="100%">
    <thead class="table-dark">
        <tr class="text-center">
            <th>No</th>
            <th>{{ ucwords('name') }}</th>
            <th>{{ ucwords('Birth Date') }}</th>
            <th>{{ ucwords('gender') }}</th>
            <th>{{ ucwords('email') }}</th>
            <th>{{ ucwords('action') }}</th>
        </tr>
    </thead>
    <tbody>
        @php
            $counter = 1;
        @endphp
        @foreach ($instructor as $instructor)
            <tr class="text-center">

                <td>{{ $counter++ }}</td>
                <td>{{ ucwords($instructor->name) }}</td>
                <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d', $instructor->date_of_birth)->format('d-m-Y') }}
                </td>
                <td>{{ ucwords($instructor->gender) }}</td>
                <td>{{ $instructor->email }}</td>

                <td>
                    <form form onsubmit="return confirm('Apakah anda ingin menghapus data instruktur?');"
                        action="{{ route('instructor-data.destroy',  $instructor->id) }}"
                        method="POST">
                        @csrf
                        @method('delete')
                        <button type="submit" class="btn btn-danger btn-xs" style="width:100px">
                            <span class="glyphicon glyphicon-remove"></span> Non Active
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>