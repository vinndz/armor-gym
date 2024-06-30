@extends('layouts.master')

@section('css')
    <!-- Datatables CSS CDN -->
    <link rel="stylesheet" href="{{ URL::asset('assets/libs/gridjs/theme/mermaid.min.css') }}">
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    <script src="{{ URL::asset('assets/js/tableToExcel.js') }}"></script>
    @include('instructor.gym_schedule.style')
@endsection

@section('content')

<div class="content">
    <h2>Daily Gym Report</h2>
    <div class="card">
        <div class="card-body">
            <div class="dropdown show" id="export-dropdown" style="float: right;">
                <button class="btn btn-primary dropdown-toggle" type="button" id="exportDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Export Report<i class='bx bx-chevron-down'></i>
                </button>
                <div class="dropdown-menu" aria-labelledby="exportDropdown">
                    <a class="dropdown-item" href="#" id="export-excel">Excel</a>
                    <a class="dropdown-item" href="#" id="export-pdf">PDF</a>
                </div>
            </div>
                  
            <div class="form-group">
                <label for="type">Search by Type Transaction:</label>
                <select class="form-control" id="type" name="type" style="max-width: 200px; display: inline; margin-bottom:40px">
                    <option value="All" selected>All</option>
                    <option value="Suplement">Suplement</option>
                    <option value="Daily Gym Transaction">Daily Gym Transaction</option>
                    <option value="Membership Transaction">Membership Transaction</option>
                </select>
            </div>
             
            <div style="width: 100%; height: 700px;">
                <table class="table table-hover  table-responsive table-condensed animate__animated animate__fadeIn" id="table" width="100%">
                    <thead class="table-dark ">
                        <th style="color: black;">{{ ucwords('no') }}</th>
                        <th style="color: black;">{{ ucwords('name') }}</th>
                        <th style="color: black;">{{ ucwords('transaction type') }}</th>
                        <th style="color: black;">{{ ucwords('date') }}</th>
                        <th style="color: black;">{{ ucwords('amount') }}</th>
                        <th style="color: black;">{{ ucwords('total') }}</th>
                    </thead>                    
                </table>
            </div>            
        </div>
    </div>
</div>



  <!-- Script -->
  <script type="text/javascript">
    $(document).ready(function() {
        var table = $('#table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('report.daily-report') }}",
                data: function(d) {
                    d.type_transaction = $('#type').val(); // Add transaction type to request data
                }
            },
            columns: [
                { data: 'id', orderable: false },
                { data: 'name', name: 'name', 
                    render: function(data) {
                        return data.charAt(0).toUpperCase() + data.slice(1);
                    }
                },
                { data: 'type_transaction', name: 'type_transaction' },
                { data: 'date_transaction', name: 'date_transaction' },
                { data: 'amount', name: 'amount' },
                { data: 'total', name: 'total' }
            ],
            columnDefs: [
                { className: "text-center", targets: "_all" },
            ],
            language: {
                search: "", // Placeholder for search box
                paginate: {
                    first: '<i class="fa fa-angle-double-left"></i>',
                    last: '<i class="fa fa-angle-double-right"></i>',
                    next: '<i class="fa fa-angle-right"></i>',
                    previous: '<i class="fa fa-angle-left"></i>'
                }
            }
        });

        // Add placeholder to the search box
        $('div.dataTables_wrapper input[type="search"]').attr('placeholder', 'Search...');

        // Update table on dropdown change
        $('#type').on('change', function() {
            table.ajax.reload(); // Reload table with new transaction type
        });

        // Export function
        
    });    
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
      var exportDropdown = document.getElementById('exportDropdown');
      exportDropdown.addEventListener('click', function() {
        this.nextElementSibling.classList.toggle('show');
      });
    
      document.getElementById('export-excel').addEventListener('click', () => {
        const tables = document.getElementById('table');
        if (tables) {
          TableToExcel.convert(tables, {
            name: 'report-daiy-transaction.xlsx',
            sheet: {
              name: 'Report Daily Transaction'
            }
          });
        } else {
          console.error('Table element not found');
        }
      });
    
      document.getElementById('export-pdf').addEventListener('click', () => {           

            var exportDropdown = document.getElementById('export-dropdown');

            exportDropdown.style.display = 'none';

            window.print();
            window.location.reload();
        });
    });
    </script>
    


@endsection
