@extends('layouts.adminLayout')

@section('breadcrum-title', 'Jadwal Sidang / Permintaan Sidang')

@section('mainContent')
    <div class="body flex-grow-1">
        <div class="container-lg px-4">
            <div class="row g-4 mb-4">
                <div class="card mb-4 py-3">
                    <span class="fw-semibold fs-5 mb-1">Kelola jadwal sidang</span>
                    <hr />
                    <div class="row">
                        <div class="col-12">
                            <p class="">Silahkan klik "Buat Jadwal" untuk menuju halaman pembuatan jadwal sidang.</p>
                        </div>
                    </div>
                    <div class="col-12 mt-2">
                        <span class="fw-normal">Daftar Permintaan Sidang</span>
                        <table class="table table-bordered" id="tablePermintaan">
                            <thead>
                                <tr class="table-secondary">
                                    <th scope="col">NIM</th>
                                    <th scope="col">Nama Mahasiswa</th>
                                    <th scope="col">Tipe Sidang</th>
                                    <th scope="col">Penguji 1</th>
                                    <th scope="col">Penguji 2</th>
                                    <th scope="col">Tanggal (Y/M/D)</th>
                                    <th scope="col">Waktu (WIB)</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Aksi</th>
                                </tr>
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
            /** GET::DATA_PERMINTAAN */
            $('#tablePermintaan').DataTable({
                dom: 'ftp',
                responsive: true,
                lengthChange: false,
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.sidang-jsonData') }}',

                // data ini untuk table di landing page, buatkan halaman baru untuk review lengkap serta menambah dosen penguji dan jadwal
                columns: [{
                        data: 'nim',
                        name: 'nim'
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'tipe_sidang',
                        name: 'tipe_sidang'
                    },
                    {
                        data: 'penguji',
                        name: 'penguji'
                    },
                    {
                        data: 'penguji2',
                        name: 'penguji2'
                    },
                    {
                        data: 'tanggal',
                        name: 'tanggal'
                    },
                    {
                        data: 'waktu',
                        name: 'waktu'
                    },
                    {
                        data: 'status',
                        name: 'status',
                    },
                    {
                        data: 'aksi',
                        name: 'aksi',
                    },
                ],
                order: [
                    [0, 'desc']
                ]
            });
        });
    </script>
@endpush
