@extends('layouts.adminLayout')

@section('breadcrum-title', 'Data Pengguna / Admin')

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
                    <span class="fw-semibold fs-5 mb-1">Manajemen Data Admin</span>
                    <hr />
                    <div class="col-12 mt-2">
                        <table class="table table-bordered" id="table1">
                            <thead>
                                <tr class="table-secondary">
                                    <th scope="col">Nama</th>
                                    <th scope="col">E-mail</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="card py-3">
                    <span class="fw-semibold fs-5 mb-1">Tambah Data Admin</span>
                    <hr />
                    <form id="formAdmin" action="{{ route('admin.usrdat-jsonAddAdmin') }}" method="post">
                        <div class="row g-2 mt-2">
                            @csrf
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <label for="name" class="form-label">Nama</label>
                                    <div class="input-group mb-3">
                                        <input type="text" name="name" class="form-control" id="name"
                                            aria-label="name">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <label for="email" class="form-label">Email</label>
                                    <div class="input-group mb-3">
                                        <input type="text" name="email" class="form-control" id="email"
                                            aria-label="email">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <label class="form-label">Password</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" placeholder="default-sitapwk"
                                            aria-label="title" disabled>
                                    </div>
                                </div>
                            </div>

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
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {

            /** GET::USRDAT */
            var table = $('#table1').DataTable({
                "dom": 'ftp',
                responsive: true,
                lengthChange: false,
                processing: true,
                serverSide: true,
                scrollX: true,
                ajax: '{{ route('admin.usrdat-jsonAdmin') }}',
                columns: [{
                        data: 'name',
                        name: 'name'
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
                    [3, 'desc']
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
    <script>
        /** POST::ADD_ADMIN */
        $('#formAdmin').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    name: $('#name').val(),
                    email: $('#email').val(),

                },
                success: function(response) {
                    alert(response.message);
                    $('#formAdmin')[0].reset();
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
