@extends('layouts.adminLayout')

@section('breadcrum-title', 'Tugas Akhir / Edit Panduan')

@section('mainContent')
    <div class="body flex-grow-1">
        <div class="container-lg px-4">
            <div class="row g-4 mb-4">
                <div class="card mb-4 py-3">
                    <span class="fw-semibold fs-5 mb-1">Edit panduan tugas akhir</span>
                    <hr />
                    <div class="row">
                        <form id="form1" action="#" method="post">
                            <div class="row">
                                <div class="col-12">
                                    <label for="formFile1" class="form-label">Upload Panduan A</label>
                                    <div class="input-group mb-3">
                                        <input type="file" class="form-control" id="inputFile1" aria-label="inputFile1" multiple>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label for="formFile2" class="form-label">Upload Panduan B</label>
                                    <div class="input-group mb-3">
                                        <input type="file" class="form-control" id="inputFile2" aria-label="inputFile2" multiple>
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
        // Validasi sebelum submit
        $("#form1").on("submit", function(event) {
            var input1 = $("#input1").val().trim(); // Trim mencegah spasi berlebih di sebelum dan akhir kalimat

            // Pasang kondisi untuk beberapa form yg tidak boleh kosong / tidak ada nilai default
            // (!input2 || !input3 || !input4) 
            if (!input1) {
                alert("Semua form harus diisi sebelum submit.");
                event.preventDefault(); // Cegah submit
            }
        });
    });
    </script>
@endpush
