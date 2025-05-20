@extends('layouts.adminLayout')

@section('breadcrum-title', 'Data Pengguna / Mahasiswa')

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
        // semua halaman pakai id table seragam spt ini
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
                    <span class="fw-semibold fs-5 mb-1">Manajemen Data Mahasiswa</span>
                    <hr />
                    <div class="col-12 mt-2">
                        <table class="table table-bordered" id="table1">
                            <thead>
                                <tr class="table-secondary">
                                    <th scope="col">Nama</th>
                                    <th scope="col">NIM</th>
                                    <th scope="col">E-mail</th>
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
        /** GET::USRDAT */
        $(document).ready(function() {
            var table = $('#table1').DataTable({
                "dom": 'ftp',
                responsive: true,
                lengthChange: false,
                processing: true,
                serverSide: true,
                scrollX: true,
                ajax: '{{ route('admin.usrdat-jsonUser') }}',
                columns: [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'nim',
                        name: 'nim'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'id',
                        name: 'aksi',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `
                            <button class="btn btn-primary text-white btn-sm btn-reset" style="min-width: 100px;" data-id="${data}">Reset Password</button>
                            <button class="btn btn-danger text-white btn-sm btn-delete" style="min-width: 100px;" data-id="${data}">Hapus Data</button>
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
                    [4, 'desc']
                ]
            });

            /** POST::RESET_PSW */
            $(document).on('click', '.btn-reset', function() {
                var userId = $(this).data('id');
                if (confirm('Yakin ingin reset password akun ini?')) {
                    $.ajax({
                        url: '/admin/post-json/' + userId + '/reset-password',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            alert(response.message);
                            table.ajax.reload();
                        },
                        error: function(xhr) {
                            alert('Gagal reset password.'); // Jika gagal atau error database
                        }
                    });
                }
            });

            /** DELETE::USRDAT */
            $(document).on('click', '.btn-delete', function() {
                var userId = $(this).data('id');
                if (confirm('Yakin ingin menghapus akun ini?')) {
                    $.ajax({
                        url: '/admin/del-json/' + userId,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            _method: 'DELETE'
                        },
                        success: function(response) {
                            alert(response.message);
                            table.ajax.reload();
                        },
                        error: function(xhr) {
                            alert('Gagal menghapus data.'); // Jika gagal atau error database
                        }
                    });
                }
            });
        });
    </script>
@endpush
