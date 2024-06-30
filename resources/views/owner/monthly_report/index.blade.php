@extends('layouts.master')

@section('css')
    <!-- Datatables CSS CDN -->
    <link rel="stylesheet" href="{{ URL::asset('assets/libs/gridjs/theme/mermaid.min.css') }}">
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    <script src="{{ URL::asset('assets/js/tableToExcel.js') }}"></script>
    @include('instructor.gym_schedule.style')
    <style>
        .text-right {
            text-align: right;
        }
    </style>
@endsection

@section('content')

<div class="content">
    <h2>Monthly Gym Report</h2>
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
                <label for="type">Search by Type Transaction: *</label>
                <select class="form-control" id="type" name="type" style="max-width: 200px; display: inline; margin-bottom:40px">
                    <option value="All" selected>All</option>
                    <option value="Suplement">Suplement</option>
                    <option value="Daily Gym Transaction">Daily Gym Transaction</option>
                    <option value="Membership Transaction">Membership Transaction</option>
                </select>
            </div>

            <div class="form-group">
                <label for="month">Month: *</label>
                <select class="form-control" id="month" name="month" style="max-width: 200px; display: inline; margin-bottom:40px">
                    <option value="All" selected>All</option>
                    <option value="1">January</option>
                    <option value="2">February</option>
                    <option value="3">March</option>
                    <option value="4">April</option>
                    <option value="5">May</option>
                    <option value="6">June</option>
                    <option value="7">July</option>
                    <option value="8">August</option>
                    <option value="9">September</option>
                    <option value="10">October</option>
                    <option value="11">November</option>
                    <option value="12">December</option>
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
                    
                    {{-- <tfoot>
                        <tr>
                            <td colspan="5" class="text-right"><strong>Total Income:</strong></td>
                            <td id="totalIncome" class="text-right"></td>
                        </tr>
                    </tfoot> --}}
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
            url: "{{ route('report.monthly-report') }}",
            data: function(d) {
                d.type_transaction = $('#type').val();
                d.month = $('#month').val();
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
            { data: 'total', name: 'total', className: 'text-right',
                render: function(data, type, row) {
                    if (type === 'display' || type === 'filter') {
                        return formatCurrency(data);
                    }
                    return data;
                }
            }
        ],
        columnDefs: [
            { className: "text-center", targets: "_all" }
        ],
        scrollY: false,
        scrollX: false,
        language: {
            search: "", // Placeholder untuk kotak pencarian
            paginate: {
                first: '<i class="fa fa-angle-double-left"></i>',
                last: '<i class="fa fa-angle-double-right"></i>',
                next: '<i class="fa fa-angle-right"></i>',
                previous: '<i class="fa fa-angle-left"></i>'
            }
        },
        "footerCallback": function (row, data, start, end, display) {
            var api = this.api();
            
            // Calculate total income
            var totalIncome = api.column(5, { page: 'current' }).data().reduce(function(a, b) {
                var x = parseFloat(a.toString().replace(/\./g, '').replace(',', '.')) || 0;
                var y = parseFloat(b.toString().replace(/\./g, '').replace(',', '.')) || 0;
                return x + y;
            }, 0);
            
            // Update the footer for the 'total' column
            $(api.column(5).footer()).html(formatCurrency(totalIncome));
        },

    });

    // Menambahkan placeholder ke dalam kotak pencarian
    $('div.dataTables_wrapper input[type="search"]').attr('placeholder', 'Search...');

    // Menggunakan event change pada dropdown untuk memperbarui tabel
    $('#type, #month').on('change', function() {
        table.ajax.reload(); // Perbarui tabel dengan nilai tipe transaksi yang baru dipilih
    });

    // Function to format number as currency
    function formatCurrency(number) {
        return 'Rp. ' + number.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
    }
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
            name: 'monthly-transaction.xlsx',
            sheet: {
              name: 'Monthly Daily Transaction'
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
