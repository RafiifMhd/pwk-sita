@extends('layouts.adminLayout')

@section('breadcrum-title', 'Tugas Akhir / Edit Panduan')

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
                    <span class="fw-semibold fs-5 mb-1">Edit panduan tugas akhir</span>
                    <hr />
                    <div class="row">
                        <form id="form1" action="#" method="post">
                            <div class="row">
                                <div class="col-12">
                                    <label for="formFile1" class="form-label">Upload Panduan A</label>
                                    <div class="input-group mb-3">
                                        <input type="file" class="form-control" id="inputFile1" aria-label="inputFile1"
                                            multiple>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label for="formFile2" class="form-label">Upload Panduan B</label>
                                    <div class="input-group mb-3">
                                        <input type="file" class="form-control" id="inputFile2" aria-label="inputFile2"
                                            multiple>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-2">
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-sm btn-primary flex-fill">
                                            Submit
                                        </button>
                                        <button type="reset" class="btn btn-sm btn-danger text-light flex-fill">
                                            Reset
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
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

        });
    </script>
@endpush
