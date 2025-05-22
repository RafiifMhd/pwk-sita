@extends('layouts.adminLayout')

@section('breadcrum-title', 'Tugas Akhir / Rekapitulasi Proposal')

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

        #table2 {
            table-layout: fixed;
            word-wrap: break-word;
            width: 100% !important;
        }

        #table2 td,
        #table2 th {
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
                    <span class="fw-semibold fs-5 mb-1">Rekapitulasi Periode Pengajuan Proposal Terbuka</span>
                    <hr />
                    <div class="row">
                        <div class="col-12">
                            <p class="">Fitur belum aktif.
                            </p>
                        </div>
                    </div>
                    <div class="col-12 mt-2">
                        <table class="table table-bordered" id="table1">
                            <thead>
                                <tr class="table-secondary">
                                    <th scope="col">NIM</th>
                                    <th scope="col">Nama Mahasiswa</th>
                                    <th scope="col">Draft Rancangan</th>
                                    <th scope="col">Topik TA Pilihan</th>
                                    <th scope="col">Pembimbing Utama</th>
                                    <th scope="col">Pembimbing Kedua</th>
                                    <th scope="col">Status Pengajuan</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="col-12 mt-2">
                        <table class="table table-bordered" id="table2">
                            <thead>
                                <tr class="table-secondary">
                                    <th scope="col">Nama Dosen</th>
                                    <th scope="col">Status Pembimbing</th>
                                    <th scope="col">Topik TA</th>
                                    <th scope="col">Kelompok Keahlian</th>
                                    <th scope="col">Kuota Topik</th>
                                    <th scope="col">Jumlah Diajukan</th>
                                    <th scope="col">Jumlah Tervalidasi</th>
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

            $('#table1').DataTable({
                dom: 'ftp',
                responsive: true,
                lengthChange: false,
                processing: true,
                serverSide: true,
                scrollX: true,
                ajax: '{{ route('admin.ta-jsonProp1') }}',
                columns: [{
                        data: 'nim',
                        name: 'nim'
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'draft_path',
                        name: 'draft_path'
                    },
                    {
                        data: 'topik_pilihan',
                        name: 'topik_pilihan'
                    },
                    {
                        data: 'dos_utama',
                        name: 'dos_utama'
                    },
                    {
                        data: 'dos_kedua',
                        name: 'dos_kedua'
                    },
                    {
                        data: 'status_pengajuan',
                        name: 'status_pengajuan'
                    },
                ],
                columnDefs: [{
                    targets: [5],
                    className: 'text-center'
                }],
                order: [
                    [6, 'desc']
                ]


            });

            $('#table2').DataTable({
                dom: 'ftp',
                responsive: true,
                lengthChange: false,
                processing: true,
                serverSide: true,
                scrollX: true,
                ajax: '{{ route('admin.ta-jsonProp2') }}',
                columns: [{
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'status_dosen',
                        name: 'status_dosen'
                    },
                    {
                        data: 'topik_ta',
                        name: 'topik_ta'
                    },
                    {
                        data: 'kelompok_keahlian',
                        name: 'kelompok_keahlian'
                    },
                    {
                        data: 'kuota_topik',
                        name: 'kuota_topik'
                    },
                    {
                        data: 'jml_diajukan',
                        name: 'jml_diajukan'
                    },
                    {
                        data: 'jml_validasi',
                        name: 'jml_validasi'
                    },
                ],
                columnDefs: [{
                    targets: [5],
                    className: 'text-center'
                }],
                order: [
                    [6, 'desc']
                ]

            });


        });
    </script>
@endpush
