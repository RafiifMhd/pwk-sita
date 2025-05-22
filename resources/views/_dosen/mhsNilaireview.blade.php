@extends('layouts.dosenLayout')

@section('breadcrum-title', 'Jadwal Sidang / Permintaan Sidang / Review Sidang')

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
                    <span class="fw-semibold fs-5 mb-1">Form penilaian mhs</span>
                    <hr />
                    <div class="row g-2">
                        <form id="formReview" action="{{ route('dosen.mhs-addData') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="submission_id" id="submission_id" value="{{ $idPengajuan }}">
                            <input type="hidden" name="status_sidang" value="Selesai">

                            <div class="row g-2">
                                <div class="col-12 col-md-2">
                                    <label for="input2" class="form-label">NIM</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" value="{{ $nimUser ?? '' }}" disabled>
                                    </div>
                                </div>
                                <div class="col-12 col-md-10">
                                    <label for="input2" class="form-label">Nama Mahasiswa</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" value="{{ $namaUser ?? '' }}" disabled>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label for="input3" class="form-label">Judul Tugas Akhir</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" value="{{ $judul ?? '' }}" disabled>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label for="input2" class="form-label">Pembimbing 1</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" value="{{ $namaDosen ?? '' }}" disabled>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label for="input2" class="form-label">Penguji 1</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" value="{{ $penguji }}" disabled>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label for="input2" class="form-label">Penguji 2</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" value="{{ $penguji2 ?? '' }}" disabled>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label for="input3" class="form-label">Tipe Sidang</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" value="{{ $tipe_sidang ?? '' }}"
                                            disabled>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label for="formFile1" class="form-label">Upload File Penilaian</label>
                                    <div class="input-group mb-3">
                                        <input type="file" class="form-control" name="form1" id="form1"
                                            accept=".pdf,.doc,.docx" required>
                                    </div>
                                </div>
                                <div class="col-12 col-md-2 mb-2">
                                    <input type="hidden" name="ket_lulus" id="ket_lulus">
                                    <label for="dropdown" class="form-label">Keterangan Lulus</label>
                                    <div class="dropdown">
                                        <button id="dropdownMenuButton"
                                            class="btn btn-light dropdown-toggle form-control text-start" type="button"
                                            data-coreui-toggle="dropdown" aria-expanded="false">
                                            Keterangan nilai
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#" data-value="Lulus tanpa
                                                    perbaikan">Lulus tanpa
                                                    perbaikan</a></li>
                                            <li><a class="dropdown-item" href="#" data-value="Lulus dengan
                                                    perbaikan">Lulus dengan
                                                    perbaikan</a></li>
                                            <li><a class="dropdown-item" href="#" data-value="Lulus bersyarat">Lulus bersyarat</a>
                                            </li>
                                            <li><a class="dropdown-item" href="#" data-value="Tidak lulus">Tidak lulus</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-2">
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-sm btn-primary flex-fill" id="btnSubmit">
                                            Submit
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger text-white flex-fill">
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
            $('#formReview').on('submit', function(e) {
                e.preventDefault();

                let formData = new FormData(this);

                $.ajax({
                    url: "{{ route('dosen.mhs-addData') }}",
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        $('#btnSubmit').prop('disabled', true).text('Submitting...');
                    },
                    success: function(response) {
                        alert(response.message);
                        location.reload();
                    },
                    error: function(xhr) {
                        let err = xhr.responseJSON?.message || 'Terjadi kesalahan.';
                        alert(err);
                    },
                    complete: function() {
                        $('#btnSubmit').prop('disabled', false).text('Submit');
                    }
                });
            });
            // Dropdown setting
            $('.dropdown-item').on('click', function(e) {
                e.preventDefault();

                var selectedValue = $(this).data('value'); // Ambil nilai dari atribut data-value
                var selectedText = $(this).text(); // Ambil teks dari item yang dipilih

                $('#ket_lulus').val(selectedValue); // Set nilai input tersembunyi
                $('#dropdownMenuButton').text(selectedText); // Ubah teks tombol dropdown (opsional)
            });
        });
    </script>
@endpush
