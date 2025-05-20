@extends('layouts.userLayout')

@section('breadcrum-title', 'Sidang / Hasil Sidang')

@section('mainContent')
    <div class="body flex-grow-1">
        <div class="container-lg px-4">
            <div class="row g-4 mb-4">
                <div class="card mb-4 py-3">
                    <span class="fw-semibold fs-5 mb-1">Hasil keputusan dan nilai sidangmu</span>
                    <hr />
                    <div class="row">
                        <div class="col-12">
                            <p class="">Silahkan menghubungi Dosen Pembimbing atau Koordinator Tugas Akhir jika ada
                                kekeliruan.</p>
                        </div>
                    </div>
                    <div class="col-12 mt-2">
                        <span class="fw-normal">Data keputusan dan nilai sidang</span>
                        <table class="table table-bordered" id="tableDataHasil">
                            <thead>
                                <tr class="table-secondary">
                                    <th scope="col">Jenis Sidang</th>
                                    <th scope="col">Dosen Pembimbing</th>
                                    <th scope="col">Status Sidang</th>
                                    <th scope="col">Hasil Keputusan</th>
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
            /** GET::DATA_HASIL */
            var table = $('#tableDataHasil').DataTable({
                dom: 'ftp',
                responsive: true,
                lengthChange: false,
                processing: true,
                serverSide: true,
                ajax: '{{ route('user.sid-jsonNilai') }}',
                columns: [{
                        data: 'tipe_sidang',
                        name: 'tipe_sidang',
                    },
                    {
                        data: 'dosen',
                        name: 'dosen'
                    },
                    {
                        data: 'status_sidang',
                        name: 'status_sidang',
                    },
                    {
                        data: 'ket_hasil',
                        name: 'ket_hasil',
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
