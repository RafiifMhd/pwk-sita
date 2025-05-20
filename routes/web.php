<?php

use App\Http\Controllers\admin_subPanduan;
use App\Http\Controllers\admin_subPeriode;
use App\Http\Controllers\admin_subSidang;
use App\Http\Controllers\admin_subTA;
use App\Http\Controllers\admin_subUserdata;
use App\Http\Controllers\dosen_subMhs;
use App\Http\Controllers\dosen_subPeriode;
use App\Http\Controllers\dosen_subSchedule;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\user_subPeriode;
use App\Http\Controllers\user_subSchedule;
use App\Http\Controllers\user_subSidang;
use App\Http\Controllers\user_subTA;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();

        switch ($user->type) {
            case 'admin':
                return redirect()->route('admin.home');
            case 'dosen':
                return redirect()->route('dosen.home');
            case 'user':
                return redirect()->route('user.home');
            default:
                Auth::logout();
                return redirect()->route('login')->withErrors(['type' => 'User not found.']);
        }
    }
    return view('auth.login');
});

Auth::routes();

/** ROUTES::USER */
Route::middleware(['auth', 'user-access:user'])->group(function () {

    // USER::HOME
    Route::get('/user/home', [HomeController::class, 'userHome'])->name('user.home');

    // USER::PERIODE
    Route::get('/user/periode/info', [user_subPeriode::class, 'periodeInfo'])->name('user.periode-info');
    Route::get('/user/periode/topik', [user_subPeriode::class, 'periodeTopik'])->name('user.periode-topik');

    Route::get('/user/periode/info/get-json', [user_subPeriode::class, 'getPeriodeUser'])->name('user.periode-jsonInfo');
    Route::get('/user/periode/topik/get-json', [user_subPeriode::class, 'getTopikUser'])->name('user.periode-jsonTopik');

    // USER::TUGAS_AKHIR
    Route::get('/user/ta/proposal', [user_subTA::class, 'taProposal'])->name('user.ta-proposal');
    Route::get('/user/ta/bimbingan', [user_subTA::class, 'taBimbingan'])->name('user.ta-bimbingan');

    Route::get('/user/ta/proposal/get-json', [user_subTA::class, 'getDataProp'])->name('user.ta-jsonDataProp');
    Route::post('/user/ta/proposal/post-json', [user_subTA::class, 'submitProp'])->name('user.ta-addProposal');
    Route::get('/user/ta/bimbingan/get-json', [user_subTA::class, 'getDataDosen'])->name('user.ta-jsonDataDosen');

    // USER::SIDANG
    Route::get('/user/sidang/sempro', [user_subSidang::class, 'sidangSempro'])->name('user.sid-sempro');
    Route::get('/user/sidang/semhas', [user_subSidang::class, 'sidangSemhas'])->name('user.sid-semhas');
    Route::get('/user/sidang/ujian', [user_subSidang::class, 'sidangUjian'])->name('user.sid-ujian');
    Route::get('/user/sidang/nilai', [user_subSidang::class, 'sidangNilai'])->name('user.sid-nilai');

    Route::post('/user/sidang/sempro/post-json', [user_subSidang::class, 'submitSempro'])->name('user.sid-postSempro');
    Route::get('/user/sidang/sempro/get-json', [user_subSidang::class, 'getDataSempro'])->name('user.sid-jsonSempro');
    Route::post('/user/sidang/semhas/post-json', [user_subSidang::class, 'submitSemhas'])->name('user.sid-postSemhas');
    Route::get('/user/sidang/semhas/get-json', [user_subSidang::class, 'getDataSemhas'])->name('user.sid-jsonSemhas');
    Route::post('/user/sidang/sidu/post-json', [user_subSidang::class, 'submitSidu'])->name('user.sid-postSidu');
    Route::get('/user/sidang/sidu/get-json', [user_subSidang::class, 'getDataSidu'])->name('user.sid-jsonSidu');
    Route::get('/user/sidang/nilai/get-json', [user_subSidang::class, 'getDataNilai'])->name('user.sid-jsonNilai');
    Route::get('/user/sidang/nilai/get-json/{id}', [user_subSidang::class, 'ubahNilai'])->name('user.sid-jsonUbah');
    Route::post('/user/sidang/nilai/post-json', [user_subSidang::class, 'reqUbah'])->name('user.sid-reqUbah');

    // USER::SCHEDULE
    Route::get('/user/sched/jadwal', [user_subSchedule::class, 'schedJadwal'])->name('user.sched-jadwal');

    Route::get('/user/sched/jadwal/get-json', [user_subSchedule::class, 'getDataSidang'])->name('user.sid-jsonSidang');

});

/** ROUTES::ADMIN */
Route::middleware(['auth', 'user-access:admin'])->group(function () {

    // ADMIN::HOME
    Route::get('/admin/home', [HomeController::class, 'adminHome'])->name('admin.home');

    // ADMIN::PERIODE
    Route::get('/admin/periode', [admin_subPeriode::class, 'periodeInfo'])->name('admin.periode');
    
    Route::put('/admin/periode/kuota/put-json/{id}', [admin_subPeriode::class, 'updateKuota']);
    Route::get('/admin/periode/data/get-json', [admin_subPeriode::class, 'getPeriode'])->name('admin.periode-jsonData');
    Route::get('/admin/proposal/data/get-json', [admin_subPeriode::class, 'getProposal'])->name('admin.periode-jsonProposal');
    Route::post('/admin/periode/post-json', [admin_subPeriode::class, 'store'])->name('admin.periode-addPeriode');
    Route::post('/admin/periode/{id}/post-json/close', [admin_subPeriode::class, 'closePeriode']);
    Route::delete('/admin/periode/del-json/{id}', [admin_subPeriode::class, 'deletePeriode']);

    // ADMIN::USERDATA
    Route::get('/admin/data-admin', [admin_subUserdata::class, 'dataAdmin'])->name('admin.data-admin');
    Route::get('/admin/data-dosen', [admin_subUserdata::class, 'dataDosen'])->name('admin.data-dosen');
    Route::get('/admin/data-user', [admin_subUserdata::class, 'dataUser'])->name('admin.data-user');

    Route::get('/admin/users/get-json', [admin_subUserdata::class, 'getAdminUsers'])->name('admin.usrdat-jsonAdmin');
    Route::get('/dosen/users/get-json', [admin_subUserdata::class, 'getDosenUsers'])->name('admin.usrdat-jsonDosen');
    Route::get('/user/users/get-json', [admin_subUserdata::class, 'getUserUsers'])->name('admin.usrdat-jsonUser');
    Route::post('/admin/users/post-json', [admin_subUserdata::class, 'addAdmin'])->name('admin.usrdat-jsonAddAdmin');
    Route::post('/dosen/users/post-json', [admin_subUserdata::class, 'addDosen'])->name('admin.usrdat-jsonAddDosen');
    Route::delete('/admin/del-json/{id}', [admin_subUserdata::class, 'deleteUser']);
    Route::post('/admin/post-json/{id}/reset-password', [admin_subUserdata::class, 'resetPassword']);

    // ADMIN::REKAP_TA
    Route::get('/admin/ta/proposal', [admin_subTA::class, 'taProposal'])->name('admin.ta-prop');
    Route::get('/admin/ta/umum', [admin_subTA::class, 'taUmum'])->name('admin.ta-umum');

    Route::get('/admin/ta/proposal/get-json/1', [admin_subTA::class, 'dataPropMhs'])->name('admin.ta-jsonProp1');
    Route::get('/admin/ta/proposal/get-json/2', [admin_subTA::class, 'dataPropDos'])->name('admin.ta-jsonProp2');
    
    Route::get('/admin/ta/umum/get-json', [admin_subTA::class, 'dataUmum'])->name('admin.ta-jsonUmum');

    // ADMIN::PANDUAN
    Route::get('/admin/pd/panduan', [admin_subPanduan::class, 'panduanInfo'])->name('admin.pd-info');

    // ADMIN::SIDANG
    Route::get('/admin/sidang/permintaan', [admin_subSidang::class, 'sidangPermintaan'])->name('admin.sidang-buat');

    Route::get('/admin/sidang/permintaan/get-json', [admin_subSidang::class, 'dataPermintaan'])->name('admin.sidang-jsonData');
    Route::get('/admin/sidang/review/get-json/{id}', [admin_subSidang::class, 'reviewPermintaan'])->name('admin.sidang-jsonReview');
    Route::post('/admin/sidang/review/post-json/', [admin_subSidang::class, 'postPermintaan'])->name('admin.sidang-addData');

});

/** ROUTES::DOSEN */
Route::middleware(['auth', 'user-access:dosen'])->group(function () {

    // DOSEN::HOME
    Route::get('/dosen/home', [HomeController::class, 'dosenHome'])->name('dosen.home');

    // DOSEN::PERIODE
    Route::get('/dosen/periode/info', [dosen_subPeriode::class, 'periodeInfo'])->name('dosen.periode-info');
    Route::get('/dosen/periode/topik', [dosen_subPeriode::class, 'periodeTopik'])->name('dosen.periode-topik');

    Route::get('/dosen/periode/info/get-json', [dosen_subPeriode::class, 'getPeriodeDosen'])->name('dosen.periode-jsonData');
    Route::get('/dosen/periode/topik/get-json', [dosen_subPeriode::class, 'getTopikDosen'])->name('dosen.periode-jsonTopik');
    Route::post('/dosen/periode/topik/post-json', [dosen_subPeriode::class, 'addTopik'])->name('dosen.periode-addTopic');
    Route::delete('/dosen/periode/topik/del-json/{id}', [dosen_subPeriode::class, 'deleteTopik']);

    // DOSEN::MAHASISWA
    Route::get('/dosen/mhs/proposal', [dosen_subMhs::class, 'mhsProposal'])->name('dosen.mhs-proposal');
    Route::get('/dosen/mhs/bimbingan', [dosen_subMhs::class, 'mhsBimbingan'])->name('dosen.mhs-bimbingan');
    Route::get('/dosen/mhs/penilaian', [dosen_subMhs::class, 'mhsPenilaian'])->name('dosen.mhs-penilaian');

    Route::get('/dosen/mhs/proposal/get-json', [dosen_subMhs::class, 'getTopikMhs'])->name('dosen.mhs-jsonProposal');
    Route::get('/dosen/mhs/bimbingan/get-json', [dosen_subMhs::class, 'getDataMhs'])->name('dosen.mhs-jsonBimbingan');
    Route::get('/dosen/mhs/penilaian/get-json', [dosen_subMhs::class, 'getDataHasil'])->name('dosen.mhs-jsonPenilaian');
    Route::get('/dosen/mhs/penilaian/manage/get-json/{id}', [dosen_subMhs::class, 'reviewNilai'])->name('dosen.mhs-jsonReview');
    Route::post('/dosen/mhs/penilaian/manage/post-json/', [dosen_subMhs::class, 'postNilai'])->name('dosen.mhs-addData');
    Route::post('/dosen/mhs/proposal/{id}/accept', [dosen_subMhs::class, 'acceptSubmission']);
    Route::post('/dosen/mhs/proposal/{id}/reject', [dosen_subMhs::class, 'rejectSubmission']);
    Route::post('/dosen/mhs/proposal/{id}/catatan', [dosen_subMhs::class, 'simpanCatatan']);


    // DOSEN::SCHEDULE
    Route::get('/dosen/sched/jadwal', [dosen_subSchedule::class, 'schedJadwal'])->name('dosen.sched-jadwal');

    Route::get('/dosen/sched/jadwal/get-json', [dosen_subSchedule::class, 'getDataJadwal'])->name('dosen.sched-jsonJadwal');

});
