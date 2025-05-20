@extends('layouts.dosenLayout')

@section('breadcrum-title', 'Schedule / Sidang Menguji')

@section('mainContent')
    <div class="body flex-grow-1">
        <div class="container-lg px-4">
            <div class="row g-4 mb-4">
                <div class="card mb-4 py-3">
                    <span class="fw-semibold fs-5 mb-1">Daftar jadwal menguji/membimbing sidang mahasiswa</span>
                    <hr />
                    <div class="col-12 mt-2">
                        <table class="table table-bordered" id="tableDataJadwal">
                            <thead>
                                <tr class="table-secondary">
                                    <th scope="col">Jenis Sidang</th>
                                    <th scope="col">Sebagai Dosen-</th>
                                    <th scope="col">NIM</th>
                                    <th scope="col">Nama Mahasiswa</th>
                                    <th scope="col">Topik</th>
                                    <th scope="col">Tanggal</th>
                                    <th scope="col">Waktu</th>
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
            /** GET::DATA_JADWAL */
            $('#tableDataJadwal').DataTable({
                dom: 'ftp',
                responsive: true,
                lengthChange: false,
                processing: true,
                serverSide: true,
                ajax: '{{ route('dosen.sched-jsonJadwal') }}',
                columns: [{
                        data: 'tipe_sidang',
                        name: 'tipe_sidang'
                    },
                    {
                        data: 'role',
                        name: 'role'
                    },
                    {
                        data: 'nim',
                        name: 'nim'
                    },
                    {
                        data: 'nama',
                        name: 'nama',
                    },
                    {
                        data: 'topik',
                        name: 'topik',
                    },
                    {
                        data: 'tanggal',
                        name: 'tanggal',
                    },
                    {
                        data: 'waktu',
                        name: 'waktu',
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
        });
    </script>
@endpush
