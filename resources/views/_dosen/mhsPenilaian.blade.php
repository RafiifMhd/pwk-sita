@extends('layouts.dosenLayout')

@section('breadcrum-title', 'Mahasiswa / Input Nilai')

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
                    <span class="fw-semibold fs-5 mb-1">Input penilaian mahasiswa</span>
                    <hr />
                    <div class="row">
                        <div class="col-12">
                            <p class="fw-normal">Info : Cari nama mahasiswa, nilai mengikuti jenis sidang.</p>
                        </div>
                    </div>
                    <div class="col-12 mt-2">
                        <table class="table table-bordered" id="tableDataSidmhs">
                            <thead>
                                <tr class="table-secondary">
                                    <th scope="col">Jenis Sidang</th>
                                    <th scope="col">NIM</th>
                                    <th scope="col">Nama Mahasiswa</th>
                                    <th scope="col">Judul Tugas Akhir</th>
                                    <th scope="col">Status Sidang</th>
                                    <th scope="col">Aksi</th>
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
            /** GET::DATA_SIDANG */
            var table = $('#tableDataSidmhs').DataTable({
                dom: 'ftp',
                responsive: true,
                lengthChange: false,
                processing: true,
                serverSide: true,
                ajax: '{{ route('dosen.mhs-jsonPenilaian') }}',
                columns: [{
                        data: 'tipe_sidang',
                        name: 'tipe_sidang',
                    },
                    {
                        data: 'nim',
                        name: 'nim'
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'judul',
                        name: 'judul'
                    },
                    
                    {
                        data: 'status_sidang',
                        name: 'status_sidang',
                    },
                    {
                        data: 'aksi',
                        name: 'aksi',
                    },
                ],
                order: [
                    [0, 'desc']
                ]
            });

        });
    </script>
@endpush
