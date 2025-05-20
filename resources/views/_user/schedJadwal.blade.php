@extends('layouts.userLayout')

@section('breadcrum-title', 'Schedule / Jadwal Sidang')

@push('style')
    <style>
        .dataTables_wrapper .dataTables_filter {
            float: left !important;
            /* Paksa float ke kiri */
            text-align: left !important;
            /* Pastikan teks juga rata kiri */

            margin-bottom: 10px;
        }

        .dataTables_wrapper .dataTables_paginate {
            float: right !important;
            text-align: right !important;
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
                        <table class="table table-bordered" id="tableDataSidang">
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
        $('#tableDataSidang').DataTable({
            dom: 'ftp',
            responsive: true,
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
