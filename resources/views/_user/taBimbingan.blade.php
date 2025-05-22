@extends('layouts.userLayout')

@section('breadcrum-title', 'Tugas Akhir / Bimbingan')

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
                    <span class="fw-semibold fs-5 mb-1">Bimbingan tugas akhir</span>
                    <hr />
                    <div class="row">
                        <div class="col-12">
                            <p
                                class="p-2 text-warning-emphasis bg-warning-subtle border border-warning-subtle rounded-3 fst-italic">
                                <span class="fw-semibold fst-normal">Info! <br></span>Jika periode "Pengajuan Proposal" masih dibuka, masih ada kemungkinan bahwa dosen pembimbing ke-2 akan ditetapkan, tunggu informasi penutupan periode terlebih dahulu.</p>
                        </div>
                    </div>
                    <div class="col-12 mt-2">
                        <table class="table table-bordered" id="table1">
                            <thead>
                                <tr class="table-secondary">
                                    <th style="col">Topik</th>
                                    <th scope="col">Judul</th>
                                    <th scope="col">Dosen Pembimbing</th>
                                    <th scope="col">Status Bimbingan</th>
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
            var table = $('#table1').DataTable({
                dom: 'ftp',
                scrollX: true,
                lengthChange: false,
                processing: true,
                serverSide: true,
                ajax: '{{ route('user.ta-jsonDataDosen') }}',
                columns: [{
                        data: 'topik',
                        name: 'topik'
                    },
                    {
                        data: 'judul',
                        name: 'judul'
                    },
                    {
                        data: 'dosen_name',
                        name: 'dosen_name'
                    },
                    {
                        data: 'status_bimbingan',
                        name: 'status_bimbingan'
                    },
                ],
                order: [
                    [1, 'desc']
                ]
            });
        });
    </script>
@endpush
