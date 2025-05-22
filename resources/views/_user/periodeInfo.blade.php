@extends('layouts.userLayout')

@section('breadcrum-title', 'Periode / Informasi Periode')

@push('style')
    <style>
        .dataTables_wrapper .dataTables_filter {
            float: left !important;
            text-align: left !important;
            margin-bottom: 10px;
        }

        .dataTables_wrapper .dataTables_paginate {
            float: right !important;
            text-align: right !important;
        }


        #table1 {
            table-layout: fixed;
            word-wrap: break-word;
            width: 100% !important;
        }

        #table1 td, // semua halaman pakai id table seragam spt ini
        #table1 th {
            word-break: break-word;
            white-space: normal;
        }

        .dataTables_wrapper {
            overflow-x: auto;
        }


        .badge-status {
            display: inline-block;
            min-width: 70px;
            text-align: center;
            padding: 0.35em 0.6em;
            font-size: 0.875em;
            font-weight: 600;
            border-radius: 0.25rem;
        }

        .badge-open {
            background-color: #b0f7d7;
            color: #0f5132;
        }

        .badge-closed {
            background-color: #f0a5ab;
            color: #842029;
        }
    </style>
@endpush

@section('mainContent')
    <div class="body flex-grow-1">
        <div class="container-lg px-4">
            <div class="row g-4 mb-4">
                <div class="card mb-4 py-3">
                    <span class="fw-semibold fs-5 mb-1">Periode Tugas Akhir</span>
                    <hr />
                    <div class="row">
                        <div class="col-12">
                        <p class="">Silahkan menuju halaman "Daftar Topik" jika ingin melihat topik yang tersedia di periode yang terbuka.</p>
                        </div>
                    </div>
                    <div class="col-12 mt-2">
                        <table class="table table-bordered" id="table1">
                            <thead>
                                <tr class="table-secondary">
                                    <th scope="col">Nama Periode</th>
                                    <th scope="col">Tipe Periode</th>
                                    <th scope="col">Tgl Buka (yyyy/mm/dd)</th>
                                    <th scope="col">Tgl Tutup (yyyy/mm/dd)</th>
                                    <th scope="col">Status</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <!-- /.col-->
                </div>
            </div>
            <!-- /.row-->
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            /** GET::PERIODE */
            $('#table1').DataTable({
                dom: 'ftp',
                scrollX: true,
                lengthChange: false,
                processing: true,
                serverSide: true,
                ajax: '{{ route('user.periode-jsonInfo') }}',
                columns: [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'type',
                        name: 'type'
                    },
                    {
                        data: 'start_date',
                        name: 'start_date',
                        render: function(data, type, row) {
                            if (type === 'display' && data) {
                                return data.split(' ')[0];
                            }
                            return data;
                        }
                    },
                    {
                        data: 'end_date',
                        name: 'end_date',
                        render: function(data, type, row) {
                            if (type === 'display' && data) {
                                return data.split(' ')[0];
                            }
                            return data;
                        }
                    },
                    {
                        data: 'is_open',
                        name: 'is_open',
                        searchable: false
                    }
                ],
                columnDefs: [{
                    targets: 4, // indeks kolom status
                    className: 'text-center'
                }],
                order: [
                    [4, 'desc']
                ] // atau ganti ke kolom sesuai kebutuhan
            });

        });
    </script>
@endpush
