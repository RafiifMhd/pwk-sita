@extends('layouts.userLayout')

@section('breadcrum-title', 'Periode / Pilihan Topik')

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
                    <span class="fw-semibold fs-5 mb-1">Daftar topik periode ini</span>
                    <hr />
                    <div class="row">
                        <div class="col-12">
                            <p class="">Daftar topik yang tersedia dapat diajukan di halaman "Pengajuan Proposal".</p>
                        </div>
                    </div>
                    <div class="col-12 mt-2">
                        <table class="table table-bordered" id="tableTopikUser">
                            <thead>
                                <tr class="table-info">
                                    <th scope="col" colspan="8" id="periode-title">Nama Periode</th>
                                </tr>
                                <tr class="table-secondary">
                                    <th scope="col">Kelompok Keahlian</th>
                                    <th scope="col">Topik Tugas Akhir</th>
                                    <th scope="col">Nama Dosen</th>
                                    <th scope="col">Kuota</th>
                                    <th scope="col">Diajukan</th>
                                    <th scope="col">Diterima</th>
                                    <th scope="col">Pengajuan</th>
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
            /** GET::TOPIK_DOSEN */
            var table = $('#tableTopikUser').DataTable({
                dom: 'ftp',
                responsive: true,
                lengthChange: false,
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('user.periode-jsonTopik') }}',
                    type: 'GET',
                    dataSrc: function(json) {
                        if (json.data.length > 0) {
                            var title = json.data[0].period_name;
                            $('#periode-title').text(
                                title);
                        } else {
                            $('#periode-title').text('Periode Pengajuan Proposal Tidak Dibuka');
                        }

                        return json.data;
                    }
                },
                columns: [{
                        data: 'focus',
                        name: 'focus'
                    },
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'dosen_name',
                        name: 'dosen_name'
                    },
                    {
                        data: 'kuota',
                        name: 'kuota'
                    },
                    {
                        data: 'submission_count',
                        name: 'submission_count',
                    },
                    {
                        data: 'validated_sc',
                        name: 'validated_sc',
                    },
                    {
                        data: 'submission',
                        name: 'submission',
                        orderable: false,
                        searchable: false
                    },
                ],
                order: [
                    [3, 'desc']
                ]
            });
        });
    </script>
@endpush
