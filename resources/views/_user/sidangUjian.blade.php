@extends('layouts.userLayout')

@section('breadcrum-title', 'Sidang / Sidang Ujian')

@section('mainContent')
    <div class="body flex-grow-1">
        <div class="container-lg px-4">
            <div class="row g-4 mb-4">
                <div class="card mb-4 py-3">
                    <span class="fw-semibold fs-5 mb-1">Daftar sidang ujian</span>
                    <hr />
                    <div class="row">
                        <form id="formSidu" action="{{ route('user.sid-postSidu') }}" method="POST"  enctype="multipart/form-data">
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
                                    <label for="formFile2" class="form-label">Laporan Tugas Akhir (Yang Sudah Fix Cetak)</label>
                                    <div class="input-group mb-3">
                                        <input type="file" class="form-control" name="form1" id="form1" accept=".pdf,.doc,.docx" required>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label for="formFile2" class="form-label">Logbook Bimbingan Tugas Akhir (Keseluruhan)</label>
                                    <div class="input-group mb-3">
                                        <input type="file" class="form-control" name="form2" id="form2" accept=".pdf,.doc,.docx" required>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label for="formFile2" class="form-label">Berita Acara Sidang Akhir (Lampiran TA-06)</label>
                                    <div class="input-group mb-3">
                                        <input type="file" class="form-control" name="form3" id="form3" accept=".pdf,.doc,.docx" required>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label for="formFile2" class="form-label">Lembar Pengesahan Tugas Akhir</label>
                                    <div class="input-group mb-3">
                                        <input type="file" class="form-control" name="form4" id="form4" accept=".pdf,.doc,.docx" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-2">
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary flex-fill">
                                            Submit
                                        </button>
                                        <button type="reset" class="btn btn-danger text-light flex-fill">
                                            Reset
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-12 mt-3">
                        <span class="fw-normal  ">Data pengajuan sidang</span>
                        <table class="table table-bordered" id="tableDataSidu">
                            <thead>
                                <tr class="table-secondary">
                                    <th scope="col">Topik Tugas Akhir</th>
                                    <th scope="col">Judul Tugas Akhir</th>
                                    <th scope="col">Jenis Sidang</th>
                                    <th scope="col">Laporan TA Final</th>
                                    <th scope="col">Logbook Bimbingan</th>
                                    <th scope="col">Berita Acara Sidang</th>
                                    <th scope="col">Lembar Pengesahan</th>
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
            /** GET::DATA_SIDU */
            $('#tableDataSidu').DataTable({
                dom: 'ftp',
                responsive: true,
                lengthChange: false,
                processing: true,
                serverSide: true,
                ajax: '{{ route('user.sid-jsonSidu') }}',
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
                        data: 'form4',
                        name: 'form4',
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


            /** POST::DATA_SIDU */
            $('#formSidu').on('submit', function(e) {
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
                        $('#formSidu')[0].reset();
                        $('#tableDataSidu').DataTable().ajax.reload();
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
