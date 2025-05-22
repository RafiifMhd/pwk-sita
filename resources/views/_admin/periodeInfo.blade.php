@extends('layouts.adminLayout')

@section('breadcrum-title', 'Periode')

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
                    <span class="fw-semibold fs-5">Kelola Periode Tugas Akhir</span>
                    <hr />
                    <form id="formPeriode" action="{{ route('admin.periode-addPeriode') }}" method="post">
                        <div class="row g-2">
                            @csrf
                            <input type="hidden" name="type" id="selectedPeriodType">
                            <div class="col-12 col-md-2">
                                <label for="jenis_periode" class="form-label">Jenis Periode</label>
                                <div class="dropdown">
                                    <button id="dropdownMenuButton"
                                        class="btn btn-light border dropdown-toggle form-control text-start" type="button"
                                        data-coreui-toggle="dropdown" aria-expanded="false">
                                        Pilih Periode
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#"
                                                data-value="Pengajuan Proposal">Pengajuan Proposal</a></li>
                                        <li><a class="dropdown-item" href="#" data-value="Seminar Proposal">Seminar
                                                Proposal</a></li>
                                        <li><a class="dropdown-item" href="#" data-value="Seminar Pembahasan">Seminar
                                                Pembahasan</a></li>
                                        <li><a class="dropdown-item" href="#" data-value="Sidang Ujian">Sidang
                                                Ujian</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-12 col-md-10">
                                <label for="title" class="form-label">Title / Nama Periode</label>
                                <div class="input-group mb-3">
                                    <input type="text" name="title" class="form-control" id="title"
                                        aria-label="title">
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="tanggal_mulai" class="form-label">Tgl Buka (Hari ini)</label>
                                <input id="datepicker1" name="start_date" class="form-control" />
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="tanggal_selesai" class="form-label">Tgl Tutup (Manual)</label>
                                <input id="datepicker2" name="end_date" class="form-control" />
                            </div>
                            <p
                                class="p-2 text-warning-emphasis bg-warning-subtle border border-warning-subtle rounded-3 fst-italic">
                                <span class="fw-semibold fst-normal">Info! <br></span>1. Harap diperhatikan bahwa tanggal
                                tidak dapat dibuka sebelum hari-H pembukaan periode. <br>2.Tanggal dapat ditutup sebelum
                                ataupun sesudah hari-H penutupan periode, penutupan tidak otomatis mengikuti tanggal.
                            </p>
                            <div class="col-8 col-md-2">
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
                    <div class="col-12 mt-4">
                        <table class="table table-bordered" id="table1">
                            <thead>
                                <tr class="table-secondary">
                                    <th scope="col">Nama Periode</th>
                                    <th scope="col">Tipe Periode</th>
                                    <th scope="col">Tanggal Buka</th>
                                    <th scope="col">Tanggal Tutup</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Penutupan</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="card py-3">
                    <span class="fw-semibold fs-5">Manajemen Periode "Pengajuan Proposal"</span>
                    <hr />
                    <div class="col-12 mt-2">
                        <table class="table table-bordered" id="table2">
                            <thead>
                                <tr class="table-info">
                                    <th scope="col" colspan="4" id="periode-title">Nama Periode</th>
                                </tr>
                                <tr class="table-secondary">
                                    <th scope="col">Nama Dosen</th>
                                    <th scope="col">Tipe Dosen</th>
                                    <th scope="col">Kuota</th>
                                    <th scope="col">Status</th>
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
            /** GET::PERIODE */
            $('#table1').DataTable({
                dom: 'ftp',
                lengthChange: false,
                processing: true,
                serverSide: true,
                scrollX: true,
                ajax: '{{ route('admin.periode-jsonData') }}',
                columns: [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'type',
                        name: 'type'
                    },
                    {
                        data: 'start_date',
                        name: 'start_date',
                        render: function(data, type, row) {
                            if (type === 'display' && data) {
                                return data.split(' ')[0];
                            }
                            return data;
                        }
                    },
                    {
                        data: 'end_date',
                        name: 'end_date',
                        render: function(data, type, row) {
                            if (type === 'display' && data) {
                                return data.split(' ')[0];
                            }
                            return data;
                        }
                    },
                    {
                        data: 'is_open',
                        name: 'is_open'
                    },
                    {
                        data: 'aksi',
                        name: 'aksi',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        visible: false
                    }
                ],
                columnDefs: [{
                    targets: [5],
                    className: 'text-center'
                }],
                order: [
                    [6, 'desc']
                ]
            });

            $('#table2').DataTable({
                dom: 'ftp',
                lengthChange: false,
                processing: true,
                serverSide: true,
                scrollX: true,
                ajax: {
                    url: '{{ route('admin.periode-jsonProposal') }}',
                    type: 'GET',
                    dataSrc: function(json) {
                        if (json.data.length > 0) {
                            var title = json.data[0].title;
                            $('#periode-title').text(
                                title);
                        } else {
                            $('#periode-title').text('Periode Pengajuan Proposal Tidak Dibuka');
                        }

                        return json.data;
                    }
                },
                columns: [{
                        data: 'dosen',
                        name: 'dosen.name'
                    },
                    {
                        data: 'tipe_dosen',
                        name: 'dosen.tipe_dos'
                    },
                    {
                        data: 'kuota_ta',
                        name: 'kuota_ta'
                    },
                    {
                        data: 'aksi',
                        name: 'aksi',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'title',
                        name: 'period.title',
                        visible: false
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        visible: false
                    }
                ],
                order: [
                    [5, 'desc']
                ]
            });

            $('#table2').on('click', '.edit-kuota', function() {
                let row = $(this).closest('tr');
                let kuotaCell = row.find('td:eq(2)');
                let currentKuota = $(this).data('kuota');
                let id = $(this).data('id');

                if (kuotaCell.find('input').length) return;

                kuotaCell.html(`
                <div class="input-group input-group-sm border border-secondary rounded">
                    <input type="number" class="form-control" value="${currentKuota}" min="0">
                    <button class="btn btn-sm btn-secondary save-kuota" data-id="${id}">Save</button>
                </div>
                `);
            });

            $('#table2').on('click', '.save-kuota', function() {
                let id = $(this).data('id');
                let input = $(this).closest('.input-group').find('input');
                let newKuota = input.val();

                $.ajax({
                    url: `/admin/periode/kuota/put-json/${id}`,
                    type: 'PUT',
                    data: {
                        _token: '{{ csrf_token() }}',
                        kuota_ta: newKuota
                    },
                    success: function(response) {
                        $('#table2').DataTable().ajax.reload(null,
                            false);
                    },
                    error: function(xhr) {
                        let message = 'Gagal memperbarui kuota.';

                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }

                        alert(message);
                        console.error(xhr);
                    }

                });
            });


            // Inisialisasi datepicker
            const today = new Date().toISOString().split('T')[0];

            $("#datepicker1").val(today)
                .prop("readonly", true);;
            $("#datepicker2").datepicker({
                uiLibrary: "bootstrap5",
                format: "yyyy-mm-dd"
            });

            // Dropdown setting
            $('.dropdown-item').on('click', function(e) {
                e.preventDefault();

                var selectedValue = $(this).data('value');
                var selectedText = $(this).text();

                $('#selectedPeriodType').val(selectedValue);
                $('#dropdownMenuButton').text(selectedText);
            });
        });

        /** POST::CLOSE_PERIODE */
        function tutupPeriode(id) {
            //  if (confirm('Yakin ingin menutup periode ini?')) {
            $.ajax({
                url: '/admin/periode/' + id + '/post-json/close',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $('#table1').DataTable().ajax.reload();
                    $('#table2').DataTable().ajax.reload();
                    alert(response.message);
                },
                error: function() {
                    alert('Gagal menutup periode.');
                }
            });
            //  }
        }

        /** POST::ADD_PERIODE */
        $('#formPeriode').on('submit', function(e) {
            e.preventDefault();

            var periodType = $('#selectedPeriodType').val();

            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    title: $('#title').val(),
                    type: periodType,
                    start_date: $('#datepicker1').val(),
                    end_date: $('#datepicker2').val()
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

        function hapusPeriode(id) {
            fetch(`/admin/periode/del-json/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.message) alert(data.message);
                    location.reload();
                })
                .catch(error => {
                    console.error('Terjadi kesalahan:', error);
                    alert('Gagal menghapus data.');
                });
        }
    </script>
@endpush
