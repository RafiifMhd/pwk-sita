@extends('layouts.userLayout')

@section('breadcrum-title', 'Tugas Akhir / Pengajuan Proposal')

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
                    <span class="fw-semibold fs-5 mb-1">Buat pengajuan proposal</span>
                    <hr />
                    <div class="row">
                        <div class="col-12">
                            <p
                                class="p-2 text-warning-emphasis bg-warning-subtle border border-warning-subtle rounded-3 fst-italic">
                                <span class="fw-semibold fst-normal">Info! <br></span>Pastikan untuk memilih topik terlebih dahulu dari halaman "List Topik TA" dan melakukan pengajuan.</p>
                        </div>
                    </div>
                    <div class="row">
                        <form id="formProposal" action="{{ route('user.ta-addProposal') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id_topik" id="id_topik" value="{{ $idTopik }}">
                            <input type="hidden" name="id_dosen" id="id_dosen" value="{{ $idDosen }}">

                            <div class="row g-4">
                                <div class="col-12 col-md-6">
                                    <label class="form-label">Pilihan Topik</label>
                                    <input type="text" class="form-control" value="{{ $titleTopik ?? '' }}" disabled>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label">Dosen Pembimbing 1</label>
                                    <input type="text" class="form-control" value="{{ $namaDosen ?? '' }}" disabled>
                                </div>
                                <div class="col-12 mt-3">
                                    <label for="rancanganJudul" class="form-label">Rancangan Judul</label>
                                    <input type="text" class="form-control" name="rancangan_judul" id="rancangan_judul"
                                        required>
                                </div>
                                <div class="col-12 mt-3">
                                    <label for="draft" class="form-label">Draft Proposal (.pdf, .docx)</label>
                                    <input type="file" class="form-control" name="draft_proposal" id="draft_proposal"
                                        accept=".pdf,.doc,.docx" required>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-2">
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-sm btn-primary flex-fill">Submit</button>
                                        <button type="reset" class="btn btn-sm btn-danger text-light flex-fill">Reset</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="col-12 mt-3">
                        <table class="table table-bordered" id="table1">
                            <thead>
                                <tr class="table-secondary">
                                    <th>Topik Tugas Akhir</th>
                                    <th>Dosen Pembimbing</th>
                                    <th>Periode Topik</th>
                                    <th>Rancangan Judul</th>
                                    <th>File Draft Proposal</th>
                                    <th>Status</th>
                                    <th>Catatan Validasi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {

            /** GET::PROPOSAL_MHS */
            $('#table1').DataTable({
                dom: 'ftp',
                scrollX: true,
                lengthChange: false,
                processing: true,
                serverSide: true,
                ajax: '{{ route('user.ta-jsonDataProp') }}',
                columns: [{
                        data: 'topik',
                        name: 'topik'
                    },
                    {
                        data: 'dosen',
                        name: 'dosen'
                    },
                    {
                        data: 'periode_topik',
                        name: 'periode_topik'
                    },
                    {
                        data: 'rancangan_judul',
                        name: 'rancangan_judul'
                    },
                    {
                        data: 'draft_proposal',
                        name: 'draft_proposal',
                    },
                    {
                        data: 'status',
                        name: 'status',
                    },
                    {
                        data: 'catatan',
                        name: 'catatan',
                    },

                ],
                order: [
                    [4, 'desc']
                ]
            });
        });


        /** POST::PROPOSAL_MHS */
        $('#formProposal').on('submit', function(e) {
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
                    $('#formProposal')[0].reset();
                    $('#table1').DataTable().ajax.reload();
                },
                error: function(xhr) {
                    let msg = "Terjadi kesalahan.";
                    if (xhr.responseJSON?.message) {
                        msg = xhr.responseJSON.message;
                    } else if (xhr.responseJSON?.errors) {
                        msg = Object.values(xhr.responseJSON.errors).map(e => e[0]).join('\n');
                    }
                    alert(msg);
                }
            });
        });
    </script>
@endpush
