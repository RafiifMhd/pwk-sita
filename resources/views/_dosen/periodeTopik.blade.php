@extends('layouts.dosenLayout')

@section('breadcrum-title', 'Periode / Buat Topik Baru')

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
                    <span class="fw-semibold fs-5 mb-1">Input topik tugas akhir untuk periode ini</span>
                    <hr />
                    <div class="row">
                        <form id="formTopik" action="{{ route('dosen.periode-addTopic') }}" method="post">
                            <div class="row">
                                <div class="col-12">
                                    <label for="input1" class="form-label">Topik Tugas Akhir</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" id="title" aria-label="title">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-4">
                                    <label for="input1" class="form-label">Kuota Mahasiswa</label>
                                    <div class="input-group mb-3">
                                        <input type="number" class="form-control" id="kuota_topik" name="kuota_topik" min="1" max="20" step="1">
                                        
                                    </div>
                                    <p class="p-2 text-warning-emphasis bg-warning-subtle border border-warning-subtle rounded-3 fst-italic"><span class="fw-semibold fst-normal">Info!</span> <br> Sisa kuota anda periode ini : <span class="fw-semibold">{{ $kuota_berjalan }} / {{ $kuota_bimbingan }} slot</span> <br> Silahkan dibagi ke beberapa topik.</p>
                                </div>
                            </div>
                            <div class="row">
                                <input type="hidden" name="focus" id="selectedBidang">
                                <div class="col-12 col-md-4 mb-2">
                                    <label for="dropdown" class="form-label">Kelompok Keahlian</label>
                                    <div class="dropdown">
                                        <button id="dropdownMenuButton"
                                            class="btn btn-light dropdown-toggle form-control text-start border" type="button"
                                            data-coreui-toggle="dropdown" aria-expanded="false">
                                            Pilih KK
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#" data-value="PPMK">KK-PPMK</a></li>
                                            <li><a class="dropdown-item" href="#" data-value="IP3W">KK-IP3W</a></li>
                                            <li><a class="dropdown-item" href="#" data-value="PPI">KK-PPI</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-2">
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary btn-sm flex-fill">
                                            Submit
                                        </button>
                                        <button type="reset" class="btn btn-danger btn-sm text-light flex-fill">
                                            Reset
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-12 mt-4">
                        <table class="table table-bordered" id="table1">
                            <thead>
                                <tr class="table-secondary">
                                    <th scope="col">Topik Tugas Akhir</th>
                                    <th scope="col">Kelompok Keahlian</th>
                                    <th scope="col">Periode</th>
                                    <th scope="col">Kuota Topik</th>
                                    <th scope="col">Pengajuan</th>
                                    <th scope="col">Diterima</th>
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
            /** GET::TOPIK_DOSEN */
            var table = $('#table1').DataTable({
                dom: 'ftp',
                responsive: true,
                lengthChange: false,
                processing: true,
                serverSide: true,
                scrollX: true,
                ajax: '{{ route('dosen.periode-jsonTopik') }}',
                columns: [{
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'focus',
                        name: 'focus'
                    },
                    {
                        data: 'period_name',
                        name: 'period.name',
                    },
                    {
                        data: 'kuota_topik',
                        name: 'kuota_topik',
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
                        data: 'id',
                        name: 'aksi',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `
                            <button class="btn btn-danger btn-sm btn-delete text-light" style="min-width: 100px;" data-id="${data}">Hapus Data</button>
                        `;
                        }
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        visible: false
                    }
                ],
                order: [
                    [7, 'desc']
                ]
            });

            // Dropdown setting
            $('.dropdown-item').on('click', function(e) {
                e.preventDefault();

                var selectedValue = $(this).data('value');
                var selectedText = $(this).text();

                $('#selectedBidang').val(selectedValue);
                $('#dropdownMenuButton').text(selectedText);
            });

            /** DELETE::TOPIK_DOSEN */
            $(document).on('click', '.btn-delete', function() {
                var topicId = $(this).data('id');
                if (confirm('Yakin ingin menghapus data ini?')) {
                    $.ajax({
                        url: '/dosen/periode/topik/del-json/' + topicId,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            _method: 'DELETE'
                        },
                        success: function(response) {
                            alert(response.message);
                            location.reload();
                        },
                        error: function(xhr) {
                            alert(
                                'Data gagal dihapus.');
                        }
                    });
                }
            });
        });
    </script>
    <script>
        /** POST::TOPIK_DOSEN */
        $('#formTopik').on('submit', function(e) {
            e.preventDefault();

            var topicFocus = $('#selectedBidang').val();
            var kuota = $('#kuota_topik').val();

            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    title: $('#title').val(),
                    focus: topicFocus,
                    kuota: kuota,

                },
                success: function(response) {
                    alert(response.message);
                    location.reload();
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
