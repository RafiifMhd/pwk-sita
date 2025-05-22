@extends('layouts.userLayout')

@section('breadcrum-title', 'Sidang / Seminar Akhir')

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
                    <span class="fw-semibold fs-5 mb-1">Daftar seminar pembahasan</span>
                    <hr />
                    <div class="row">
                        <form id="formSemhas" action="{{ route('user.sid-postSemhas') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row g-2">
                                <input type="hidden" name="id_topik" id="id_topik" value="{{ $idTopik }}">
                                <input type="hidden" name="id_dosen" id="id_dosen" value="{{ $idDosen }}">
                                <input type="hidden" name="inp_judul" id="inp_judul" value="{{ $judul }}">

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
                                <div class="col-12">
                                    <label for="formFile2" class="form-label">Form Pendaftaran (Lampiran TA-02)</label>
                                    <div class="input-group mb-3">
                                        <input type="file" class="form-control" name="form1" id="form1"
                                            accept=".pdf,.doc,.docx" required>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label for="formFile2" class="form-label">Logbook Bimbingan (Lampiran TA-07)</label>
                                    <div class="input-group mb-3">
                                        <input type="file" class="form-control" name="form2" id="form2"
                                            accept=".pdf,.doc,.docx" required>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label for="formFile2" class="form-label">Draft TA (Disetujui Dosen Pembimbing)</label>
                                    <div class="input-group mb-3">
                                        <input type="file" class="form-control" name="form3" id="form3"
                                            accept=".pdf,.doc,.docx" required>
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
                    <div class="col-12 mt-3">
                        <span class="fw-normal  ">Data pengajuan sidang</span>
                        <table class="table table-bordered" id="table1">
                            <thead>
                                <tr class="table-secondary">
                                    <th scope="col">Topik Tugas Akhir</th>
                                    <th scope="col">Judul Tugas Akhir</th>
                                    <th scope="col">Jenis Sidang</th>
                                    <th scope="col">Form Pendaftaran</th>
                                    <th scope="col">Logbook Bimbingan</th>
                                    <th scope="col">Draft TA (Disetujui)</th>
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
        $(document).ready(function() {
            /** GET::DATA_SEMHAS */
            $('#table1').DataTable({
                dom: 'ftp',
                scrollX: true,
                lengthChange: false,
                processing: true,
                serverSide: true,
                ajax: '{{ route('user.sid-jsonSemhas') }}',
                columns: [{
                        data: 'topik',
                        name: 'topik'
                    },
                    {
                        data: 'judul',
                        name: 'judul'
                    },
                    {
                        data: 'tipe_sidang',
                        name: 'tipe_sidang'
                    },
                    {
                        data: 'form1',
                        name: 'form1',
                    },
                    {
                        data: 'form2',
                        name: 'form2',
                    },
                    {
                        data: 'form3',
                        name: 'form3',
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


            /** POST::DATA_SEMHAS */
            $('#formSemhas').on('submit', function(e) {
                e.preventDefault();
                let formData = new FormData(this);

                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        alert(response.message || 'Proposal berhasil diajukan!');
                        $('#formSemhas')[0].reset();
                        $('#table1').DataTable().ajax.reload();
                    },
                    error: function(xhr) {
                        let msg = "Terjadi kesalahan.";
                        if (xhr.responseJSON?.message) {
                            msg = xhr.responseJSON.message;
                        } else if (xhr.responseJSON?.errors) {
                            msg = Object.values(xhr.responseJSON.errors).map(e => e[0]).join(
                                '\n');
                        }
                        alert(msg);
                    }
                });
            });
        });
    </script>
@endpush
