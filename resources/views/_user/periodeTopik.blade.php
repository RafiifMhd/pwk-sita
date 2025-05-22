@extends('layouts.userLayout')

@section('breadcrum-title', 'Periode / Pilihan Topik')

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
                    <span class="fw-semibold fs-5 mb-1">Daftar topik periode ini</span>
                    <hr />
                    <div class="row">
                        <div class="col-12">
                            <p class="">Daftar topik yang tersedia dapat diajukan di halaman "Pengajuan Proposal".</p>
                        </div>
                    </div>
                    <div class="col-12 mt-2">
                        <table class="table table-bordered" id="table1">
                            <thead>
                                <tr class="table-info">
                                    <th scope="col" colspan="7" id="table1">Nama Periode</th>
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
            var table = $('#table1').DataTable({
                dom: 'ftp',
                scrollX: true,
                lengthChange: false,
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('user.periode-jsonTopik') }}',
                    type: 'GET',
                    dataSrc: function(json) {
                        if (json.data.length > 0) {
                            var title = json.data[0].period_name;
                            $('#table1').text(
                                title);
                        } else {
                            $('#table1').text('Periode Pengajuan Proposal Tidak Dibuka');
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
