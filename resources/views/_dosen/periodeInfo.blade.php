@extends('layouts.dosenLayout')

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

        #table1 td,
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
        <div class="container-lg px-3">
            <div class="row g-4 mb-4">
                <div class="card py-3">
                    <span class="fw-semibold fs-5 mb-1">Informasi Periode Tugas Akhir</span>
                    <hr />
                    <div class="col-12 my-2">
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
        /** GET::PERIODE */
        $(document).ready(function() {
            $('#table1').DataTable({
                dom: 'ftp',
                responsive: true,
                lengthChange: false,
                processing: true,
                serverSide: true,
                scrollX: true,
                ajax: '{{ route('dosen.periode-jsonData') }}',
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
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        visible: false
                    }
                ],
                columnDefs: [{
                    targets: 4,
                    className: 'text-center'
                }],
                order: [
                    [5, 'desc']
                ]
            });

            $("#form1").on("submit", function(event) {
                var input1 = $("#input1").val()
                    .trim();

                if (!input1) {
                    alert("Semua form harus diisi sebelum submit.");
                    event.preventDefault();
                }
            });
        });
    </script>
@endpush
