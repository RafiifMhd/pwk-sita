@extends('layouts.dosenLayout')

@section('breadcrum-title', 'Mahasiswa / Mahasiswa')

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
                    <span class="fw-semibold fs-5 mb-1">Data mahasiswa</span>
                    <hr />
                    <div class="row">
                        <div class="col-12">
                            <p class="fw-normal">Data mahasiswa bimbingan saya :</p>
                        </div>
                    </div>
                    <div class="col-12 mt-2">
                        <table class="table table-bordered" id="tableDataMhs">
                            <thead>
                                <tr class="table-secondary">
                                    <th style="col">NIM</th>
                                    <th scope="col">Nama Mahasiswa</th>
                                    <th scope="col">Topik Pilihan</th>
                                    <th scope="col">Judul Tugas Akhir </th>
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
            /** GET::DATA_MHS */
            var table = $('#tableDataMhs').DataTable({
                dom: 'ftp',
                responsive: true,
                lengthChange: false,
                processing: true,
                serverSide: true,
                ajax: '{{ route('dosen.mhs-jsonBimbingan') }}',
                columns: [{
                        data: 'nim',
                        name: 'nim'
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'topik',
                        name: 'topik'
                    },
                    {
                        data: 'judul',
                        name: 'judul'
                    },
                    {
                        data: 'status_bimbingan',
                        name: 'status_bimbingan',
                    },
                ],
                order: [
                    [0, 'desc']
                ]
            });
        });
    </script>
@endpush
