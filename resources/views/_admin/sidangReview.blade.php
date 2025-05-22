@extends('layouts.adminLayout')

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
                    <span class="fw-semibold fs-5 mb-1">Form pembuatan jadwal</span>
                    <hr />
                    <div class="row">
                        <form id="formReview" action="{{ route('admin.sidang-addData') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="submission_id" id="submission_id" value="{{ $idPengajuan }}">
                            <input type="hidden" name="waktu_sidang" id="waktu_sidang" value="{{ $data?->waktu_sidang }}">
                            <input type="hidden" name="dosen_pembimbing" id="dosen_pembimbing" value="{{ $idDosen }}">
                            <input type="hidden" name="dosen_penguji1" id="dosen_penguji1" value="{{ $data?->penguji_id }}"
                                required>
                            <input type="hidden" name="dosen_penguji2" id="dosen_penguji2"
                                value="{{ $data?->penguji2_id }}">

                            @php
                                $selectedPenguji1 = $data?->penguji?->name ?? 'Pilih Penguji 1';
                                $selectedPenguji2 = $data?->penguji2?->name ?? 'Pilih Penguji 2';
                                $selectedTanggal = $data?->jadwal_sidang ?? '';
                                $selectedWaktu = $data?->waktu_sidang ?? 'Pilih Waktu';
                            @endphp

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
                                <div class="col-12">
                                    <label for="input1" class="form-label">Pilihan Topik</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" value="{{ $titleTopik ?? '' }}" disabled>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label for="input2" class="form-label">Pembimbing 1</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" value="{{ $namaDosen ?? '' }}" disabled>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label for="input3" class="form-label">Judul Tugas Akhir</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" value="{{ $judul ?? '' }}" disabled>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label for="input3" class="form-label">Tipe Sidang</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" value="{{ $tipe_sidang ?? '' }}"
                                            disabled>
                                    </div>
                                </div>
                                <span class="">Cek Daftar Berkas</span>
                                <div class="col-12 col-md-3">
                                    <div class="input-group mb-3">
                                        @if ($form1)
                                            <a href="{{ asset('storage/' . $form1) }}" target="_blank"
                                                class="btn btn-sm btn-primary form-control">Form 1</a>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-12 col-md-3">
                                    <div class="input-group mb-3">
                                        @if ($form2)
                                            <a href="{{ asset('storage/' . $form2) }}" target="_blank"
                                                class="btn btn-sm btn-primary form-control">Form 2</a>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-12 col-md-3">
                                    <div class="input-group mb-3">
                                        @if ($form3)
                                            <a href="{{ asset('storage/' . $form3) }}" target="_blank"
                                                class="btn btn-sm btn-primary form-control">Form 3</a>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-12 col-md-3">
                                    <div class="input-group mb-3">
                                        @if ($form4)
                                            <a href="{{ asset('storage/' . $form4) }}" target="_blank"
                                                class="btn btn-sm btn-primary form-control">Form 4</a>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label">Dosen Penguji 1</label>
                                    <div class="dropdown mb-3">
                                        <button class="btn btn-light dropdown-toggle form-control text-start" type="button"
                                            data-coreui-toggle="dropdown" aria-expanded="false" id="dropdownMenuButton1">
                                            {{ $selectedPenguji1 }}
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item pnj1" href="#" data-value="">Tidak
                                                    Ada</a>
                                            </li>
                                            @foreach ($penguji as $dos1)
                                                <li><a class="dropdown-item pnj1" href="#"
                                                        data-value="{{ $dos1->id }}">{{ $dos1->name }}</a></li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label">Dosen Penguji 2 (Opsional)</label>
                                    <div class="dropdown mb-3">
                                        <button class="btn btn-light dropdown-toggle form-control text-start"
                                            type="button" data-coreui-toggle="dropdown" aria-expanded="false"
                                            id="dropdownMenuButton2">
                                            {{ $selectedPenguji2 }}
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item pnj2" href="#" data-value="">Tidak
                                                    Ada</a>
                                            </li>
                                            @foreach ($penguji2 as $dos2)
                                                <li><a class="dropdown-item pnj2" href="#"
                                                        data-value="{{ $dos2->id }}">{{ $dos2->name }}</a></li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label for="tanggal" class="form-label">Pilih Tanggal (yyyy/mm/dd)</label>
                                    <input id="datepicker1" name="date1" class="form-control"
                                        value="{{ $selectedTanggal }}" />
                                </div>

                                @php
                                    $startTime = strtotime('08:00:00');
                                    $endTime = strtotime('17:00:00'); // sampai 16:45
                                    $interval = 15 * 60; // 15 menit
                                @endphp

                                <div class="col-12 col-md-6">
                                    <label for="waktu" class="form-label">Pilih Waktu (Jam Sidang)</label>
                                    <div class="dropdown">
                                        <button class="btn btn-light dropdown-toggle form-control text-start"
                                            type="button" data-coreui-toggle="dropdown" aria-expanded="false"
                                            id="dropdownMenuButton3">
                                            {{ $selectedWaktu }}
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item waktu" href="#" data-value="">Pilih
                                                    Waktu</a>
                                            </li>
                                            @for ($time = $startTime; $time <= $endTime; $time += $interval)
                                                <li>
                                                    <a class="dropdown-item waktu" href="#"
                                                        data-value="{{ date('H:i:s', $time) }}">
                                                        {{ date('H:i:s', $time) }}
                                                    </a>
                                                </li>
                                            @endfor
                                        </ul>
                                    </div>
                                </div>



                            </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-2">
                            <div class="d-flex gap-2">
                                <button type="button" id="btnAcc" class="btn btn-sm btn-primary flex-fill">
                                    Terima
                                </button>
                                <button type="button" id="btnRej" class="btn btn-sm btn-danger text-white flex-fill">
                                    Tolak
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
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            // Datepicker
            $("#datepicker1").datepicker({
                uiLibrary: "bootstrap5",
                format: "yyyy-mm-dd"
            });
            // Dropdown setting
            $('.pnj1').on('click', function(e) {
                e.preventDefault();

                var selectedValue = $(this).data('value'); // Ambil nilai dari atribut data-value
                var selectedText = $(this).text(); // Ambil teks dari item yang dipilih

                $('#dosen_penguji1').val(selectedValue); // Set nilai input tersembunyi
                $('#dropdownMenuButton1').text(selectedText); // Ubah teks tombol dropdown (opsional)
            });
            // Dropdown setting
            $('.pnj2').on('click', function(e) {
                e.preventDefault();

                var selectedValue = $(this).data('value'); // Ambil nilai dari atribut data-value
                var selectedText = $(this).text(); // Ambil teks dari item yang dipilih

                $('#dosen_penguji2').val(selectedValue); // Set nilai input tersembunyi
                $('#dropdownMenuButton2').text(selectedText); // Ubah teks tombol dropdown (opsional)
            });
            // Dropdown setting
            $('.waktu').on('click', function(e) {
                e.preventDefault();

                var selectedValue = $(this).data('value'); // Ambil nilai dari atribut data-value
                var selectedText = $(this).text(); // Ambil teks dari item yang dipilih

                $('#waktu_sidang').val(selectedValue); // Set nilai input tersembunyi
                $('#dropdownMenuButton3').text(selectedText); // Ubah teks tombol dropdown (opsional)
            });

        });
    </script>
    <script>
        /** POST::ADD_DATA */
        function sendReview(status) {
            // Fungsi sendReview() untuk di script ini saja, tidak dibawa ke backend dan digunakan untuk mengelola button acc dan rej.
            var data = {
                _token: '{{ csrf_token() }}',
                submission_id: $('#submission_id').val(),
                status_sidang: status
            };

            // Hanya jika "Dijadwalkan", tambahkan dosen & tanggal
            if (status === 'Dijadwalkan') {
                data.dosen_penguji1 = $('#dosen_penguji1').val();
                data.dosen_penguji2 = $('#dosen_penguji2').val();
                data.dosen_pembimbing = $('#dosen_pembimbing').val();
                data.waktu_sidang = $('#waktu_sidang').val();
                data.date1 = $('#datepicker1').val();
            }

            const redirectUrl = "{{ route('admin.sidang-buat') }}";

            $.ajax({
                url: $('#formReview').attr('action'),
                method: 'POST',
                data: data,
                success: function(response) {
                    alert(response.message);
                    $('#formReview')[0].reset();
                    $('#dropdownMenuButton1').text('Pilih Penguji 1');
                    $('#dropdownMenuButton2').text('Pilih Penguji 2');
                    $('#dropdownMenuButton3').text('Pilih Waktu');
                    window.location.href = redirectUrl;
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
        }

        // Tombol aksi
        $('#btnAcc').on('click', function() {
            sendReview('Dijadwalkan');
        });

        $('#btnRej').on('click', function() {
            sendReview('Ditolak');
        });
    </script>
@endpush
