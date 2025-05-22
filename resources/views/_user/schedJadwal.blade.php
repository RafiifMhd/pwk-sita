@extends('layouts.userLayout')

@section('breadcrum-title', 'Schedule / Jadwal Sidang')

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
                    <span class="fw-semibold fs-5 mb-1">Informasi jadwal sidang</span>
                    <hr />
                    <div class="row">
                        <form id="" action="" method="GET"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row g-2">
                                <div class="col-12 col-md-4">
                                    <label for="input2" class="form-label">Pembimbing 1</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" id="dosen" aria-label="input2"
                                            value="{{ $namaDosen ?? '' }}" disabled>
                                    </div>
                                </div>
                                <div class="col-12 col-md-8">
                                    <label for="input1" class="form-label">Pilihan Topik</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" id="topik" aria-label="input1"
                                            value="{{ $titleTopik ?? '' }}" disabled>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label for="input3" class="form-label">Judul Tugas Akhir</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" id="judul" aria-label="input3"
                                            value="{{ $judul ?? '' }}" disabled>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-12 mt-2">
                        <table class="table table-bordered" id="table1">
                            <thead>
                                <tr class="table-secondary">
                                    <th scope="col">Jenis Sidang</th>
                                    <th scope="col">Dosen Pembimbing</th>
                                    <th scope="col">Dosen Penguji 1</th>
                                    <th scope="col">Dosen Penguji 2</th>
                                    <th scope="col">Tanggal</th>
                                    <th scope="col">Waktu</th>
                                    <th scope="col">Status Sidang</th>
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
        /** GET::DATA_JADWAL */
        $('#table1').DataTable({
            dom: 'ftp',
            scrollX: true,
            lengthChange: false,
            processing: true,
            serverSide: true,
            ajax: '{{ route('user.sid-jsonSidang') }}',
            columns: [{
                    data: 'tipe_sidang',
                    name: 'tipe_sidang'
                },
                {
                    data: 'dosen',
                    name: 'dosen'
                },
                {
                    data: 'penguji',
                    name: 'penguji'
                },
                {
                    data: 'penguji2',
                    name: 'penguji2',
                },
                {
                    data: 'tanggal',
                    name: 'tanggal',
                },
                {
                    data: 'waktu',
                    name: 'waktu',
                },
                {
                    data: 'status',
                    name: 'status',
                },

            ],
            order: [
                [0, 'desc']
            ]
        });
    });
    </script>
@endpush
