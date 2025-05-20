@extends('layouts.dosenLayout')

@section('breadcrum-title', 'Mahasiswa / Pengajuan Proposal')

@push('style')
    <style>
        .dataTables_wrapper .dataTables_filter {
            float: left !important;
            /* Paksa float ke kiri */
            text-align: left !important;
            /* Pastikan teks juga rata kiri */

            margin-bottom: 10px;
        }

        .dataTables_wrapper .dataTables_paginate {
            float: right !important;
            text-align: right !important;
        }
    </style>
@endpush

@section('mainContent')
    <div class="body flex-grow-1">
        <div class="container-lg px-4">
            <div class="row g-4 mb-4">
                <div class="card mb-4 py-3">
                    <span class="fw-semibold fs-5 mb-1">Pengajuan proposal mahasiswa</span>
                    <hr />
                    <div class="row">
                        <div class="col-12">
                            <p class="fw-normal">Hanya <i>Dosen Pembimbing 1</i> yang dapat menerima/menolak proposal,
                                silahkan hubungi yang bersangkutan jika ingin memverifikasi.</p>
                        </div>
                    </div>
                    <div class="col-12 mt-2">
                        <table class="table table-bordered" id="tablePengajuanMhs">
                            <thead>
                                <tr class="table-secondary">
                                    <th scope="col">NIM</th>
                                    <th scope="col">Nama Mahasiswa</th>
                                    <th scope="col">Topik</th>
                                    <th scope="col">Rancangan Judul</th>
                                    <th scope="col">Berkas Pengajuan</th>
                                    <th scope="col">Catatan Validasi</th>
                                    <th scope="col">Validasi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <!-- Modal Buat Catatan -->
                    <div class="modal fade" id="modalCatatan" tabindex="-1" aria-labelledby="catatanLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <form id="formCatatan" method="POST">
                                @csrf
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Buat Catatan Validasi</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Tutup"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p id="namaMhs"></p>
                                        <textarea name="catatan_validasi" class="form-control" rows="4" required></textarea>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                    </div>
                                </div>
                            </form>
                        </div>
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
            /** GET::PROPOSAL_MHS */
            var table = $('#tablePengajuanMhs').DataTable({
                dom: 'ftp',
                responsive: true,
                lengthChange: false,
                processing: true,
                serverSide: true,
                ajax: '{{ route('dosen.mhs-jsonProposal') }}',
                columns: [{
                        data: 'nim',
                        name: 'nim'
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'topik',
                        name: 'topik'
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
                        data: 'catatan_validasi',
                        name: 'catatan_validasi',
                        render: function(data, type, row) {
                            if (data) {
                                return `<span>${data}</span>`;
                            } else {
                                return `<button class="btn btn-sm btn-secondary btn-note" data-id="${row.id}" data-nama="${row.nama}">Buat Catatan</button>`;
                            }
                        }
                    },
                    {
                        data: 'id',
                        name: 'aksi',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            if (row.status_pengajuan === 'Disetujui') {
                                return `<button class="btn btn-success btn-sm" disabled>Disetujui</button>`;
                            } else if (row.status_pengajuan === 'Ditolak') {
                                return `<button class="btn btn-danger btn-sm" disabled>Ditolak</button>`;
                            } else {
                                return `
                                    <button class="btn btn-success btn-sm btn-acc text-light" data-id="${data}">Terima</button>
                                    <button class="btn btn-danger btn-sm btn-rej text-light" data-id="${data}">Tolak</button>
                                `;
                            }
                        }

                    }
                ],
                order: [
                    [0, 'desc']
                ]
            });

            // Modal "Buat Catatan"
            $(document).on('click', '.btn-note', function() {
                const id = $(this).data('id');
                const nama = $(this).data('nama');
                $('#formCatatan').attr('action', `/dosen/mhs/proposal/${id}/catatan`);
                $('#namaMhs').text(`Catatan untuk: ${nama}`);
                $('#modalCatatan').modal('show');
            });

            // Handle submit form catatan
            $('#formCatatan').submit(function(e) {
                e.preventDefault();
                const form = $(this);
                const action = form.attr('action');
                const data = form.serialize();

                $.post(action, data, function(res) {
                    $('#modalCatatan').modal('hide');
                    $('#formCatatan')[0].reset();
                    alert(res.message);
                    table.ajax.reload();
                }).fail(function(xhr) {
                    alert('Gagal menyimpan catatan.');
                });
            });


            $(document).on('click', '.btn-acc', function() {
                var id = $(this).data('id');
                $.post(`/dosen/mhs/proposal/${id}/accept`, {
                    _token: '{{ csrf_token() }}'
                }, function(res) {
                    alert(res.message);
                    table.ajax.reload();
                }).fail(function(xhr) {
                    alert('Gagal menerima proposal.');
                });
            });

            $(document).on('click', '.btn-rej', function() {
                var id = $(this).data('id');
                $.post(`/dosen/mhs/proposal/${id}/reject`, {
                    _token: '{{ csrf_token() }}'
                }, function(res) {
                    alert(res.message);
                    table.ajax.reload();
                }).fail(function(xhr) {
                    alert('Gagal menolak proposal.');
                });
            });
        });
    </script>
@endpush
