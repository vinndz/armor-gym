<style>
    /* th color */
    .dataTables_wrapper thead th {
        color: #ffffff !important;
        background-color: #070707 !important;
    }

    table.dataTable thead .sorting:before,
    table.dataTable thead .sorting_asc:before,
    table.dataTable thead .sorting_desc:before {
        color: #ffffff !important; /* Warna putih */
    }

    /* end th color */



    /* filter search */
    .dataTables_wrapper .dataTables_filter input {
        border: 2px solid #ccc;
        /* Mengatur warna border */
        border-radius: 4px;
        /* Mengatur sudut border */
        padding: 8px 12px;
        /* Mengatur padding di dalam input */
        width: 300px;
        /* Mengatur lebar input */
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        /* Menambahkan bayangan untuk efek ketinggian */
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        /* Efek transisi saat hover */
    }

    .dataTables_wrapper .dataTables_filter input:focus {
        border-color: #6c757d;
        /* Mengubah warna border saat input aktif */
        outline: 0;
        /* Menghapus outline saat input aktif */
        box-shadow: 0 0 0 0.2rem rgba(108, 117, 125, 0.25);
        /* Efek ketinggian saat input aktif */
    }
    /* end filter search */


    /* tabel border */
    #table {
        font-family: Arial, sans-serif;
        font-size: 14px;
    }

    /* Mengatur warna latar belakang header dan teksnya */
    #table thead th {
        background-color: #f2f2f2;
        /* Latar belakang abu-abu muda */
        color: black;
        /* Teks berwarna hitam */
    }

    /* Mengatur warna latar belakang baris bergantian */
    #table tbody tr:nth-child(even) {
        background-color: #f9f9f9;
        /* Latar belakang putih keabu-abuan */
    }

    /* Mengatur tata letak teks untuk seluruh sel dalam tabel */
    #table tbody td {
        text-align: center;
        /* Teks berada di tengah-tengah setiap sel */
    }

    /* Menyembunyikan garis vertikal di antara kolom */
    #table td,
    #table th {
        border: none;
    }

    /* Menambahkan garis bawah pada baris */
    #table tbody tr {
        border-bottom: 1px solid #ddd;
        /* Garis abu-abu muda */
    }
    /* end tabel */


    /* show entries */

    /* Menyesuaikan tampilan "Show entries" */
    .dataTables_length {
        margin-bottom: 10px;
        /* Menambahkan ruang di bawah elemen "Show entries" */
        font-size: 14px;
        /* Mengubah ukuran teks */
    }

    /* Mengubah tampilan label "Show" */
    .dataTables_length label {
        font-weight: normal;

        /* Mengurangi ketebalan teks label */
        margin-right: 10px;
        /* Memberikan ruang di sebelah kanan label */
    }

    /* Menyesuaikan tampilan dropdown */
    .dataTables_length select {
        border-radius: 4px;
        /* Mengatur sudut border */
        padding: 6px 12px;
        /* Mengatur padding di dalam dropdown */
        font-size: 14px;
        /* Mengubah ukuran teks dropdown */
        color: #333;
        /* Mengubah warna teks dropdown */
        background-color: #fff;
        /* Mengubah warna latar belakang dropdown */
        border: 1px solid #ccc;
        /* Mengatur border dropdown */
    }

    /* Mengubah tampilan panah dropdown */
    .dataTables_length select::-ms-expand {
        display: none;
        /* Menyembunyikan panah dropdown untuk IE */
    }

    /* end show entries */
</style>
