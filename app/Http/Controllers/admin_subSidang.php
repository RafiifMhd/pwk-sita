<?php
namespace App\Http\Controllers;

use App\Models\SidangSubmission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class admin_subSidang extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // PAGE::PERMINTAAN
    public function sidangPermintaan(): View
    {
        return view('_admin.sidangPermintaan');
    }

    // GET::PERMINTAAN_SIDANG
    public function dataPermintaan(Request $request)
    {
        if ($request->ajax()) {
            $query = SidangSubmission::with(['user', 'topik', 'dosen', 'penguji', 'penguji2']);

            $recordsTotal = SidangSubmission::count();

            if ($request->has('search') && $request->search['value'] != '') {
                $search = $request->search['value'];
                $query->where(function ($q) use ($search) {
                    $q->whereHas('topik', function ($subQ) use ($search) {
                        $subQ->where('title', 'like', "%{$search}%");
                    })->orWhereHas('user', function ($subQ) use ($search) {
                        $subQ->where('name', 'like', "%{$search}%")
                            ->orWhere('nim', 'like', "%{$search}%");
                    });
                });
            }

            $filteredQuery   = clone $query;
            $recordsFiltered = $filteredQuery->count();

            if ($request->has('order')) {
                $orderColumnIndex = $request->order[0]['column'];
                $orderColumn      = $request->columns[$orderColumnIndex]['data'];
                $orderDir         = $request->order[0]['dir'];
                $query->orderBy($orderColumn, $orderDir);
            }

            $data = $query->skip($request->start)
                ->take($request->length)
                ->get()
                ->map(function ($item) {

                    return [
                        'nim'         => optional($item->user)->nim,
                        'nama'        => optional($item->user)->name,
                        'tipe_sidang' => $item->tipe_sidang,
                        'penguji'     => optional($item->penguji)->name,
                        'penguji2'    => optional($item->penguji2)->name,
                        'tanggal'     => $item->jadwal_sidang,
                        'waktu'       => $item->waktu_sidang,
                        'status'      => $item->status_sidang,
                        'aksi'        => $item->id
                        ? '<a href="' . route('admin.sidang-jsonReview', ['id' => $item->id]) . '" class="btn btn-primary btn-sm">Manage</a>'
                        : '',

                    ];
                });

            return response()->json([
                'draw'            => intval($request->draw),
                'recordsTotal'    => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data'            => $data,
            ]);
        }
    }

    // PAGE::REVIEW
    public function reviewPermintaan(Request $request)
    {
        $data = SidangSubmission::with(['topik', 'user', 'dosen', 'penguji', 'penguji2'])
            ->where('id', $request->id)
            ->first();

        $penguji  = User::where('type', '2')->get();
        $penguji2 = User::where('type', '2')->get();
        $form1    = null;
        $form2    = null;
        $form3    = null;
        $form4    = null;

        if ($data?->tipe_sidang === 'Seminar Proposal') {
            $form1 = $data?->fsp1_pendaftaran;
            $form2 = $data?->fsp2_logbook;
            $form3 = $data?->fsp3_draft;
            $form4 = $data?->fsp4_nilai;
        } elseif ($data?->tipe_sidang === 'Seminar Pembahasan') {
            $form1 = $data?->fsh1_pendaftaran;
            $form2 = $data?->fsh2_logbook;
            $form3 = $data?->fsh3_draft;
            $form4 = $data?->fsh4_nilai;
        } elseif ($data?->tipe_sidang === 'Sidang Ujian') {
            $form1 = $data?->fsu1_buku;
            $form2 = $data?->fsu2_logbook;
            $form3 = $data?->fsu3_ba;
            $form4 = $data?->fsu4_pengesahan;
        }

        return view('_admin.sidangReview', [
            'data'        => $data,
            'idPengajuan' => $data?->id,
            'titleTopik'  => $data?->topik?->title,
            'nimUser'     => $data?->user?->nim,
            'namaUser'    => $data?->user?->name,
            'judul'       => $data?->judul,
            'namaDosen'   => $data?->dosen?->name,
            'idDosen'     => $data?->dosen?->id,
            'tipe_sidang' => $data?->tipe_sidang,
            'penguji'     => $penguji,
            'penguji2'    => $penguji2,
            'form1'       => $form1,
            'form2'       => $form2,
            'form3'       => $form3,
            'form4'       => $form4,
        ]);
    }

    // POST::REVIEW
    public function postPermintaan(Request $request)
    {
        $request->validate([
            'submission_id' => 'required|integer|exists:sidang_submissions,id',
            'status_sidang' => 'required|string|in:Dijadwalkan,Ditolak',
        ]);

        // Validasi tambahan jika diterima (status 'Dijadwalkan')
        if ($request->status_sidang === 'Dijadwalkan') {
            $request->validate([
                'dosen_penguji1' => 'required|exists:users,id',
                'dosen_penguji2' => 'nullable|exists:users,id',
                'date1'          => 'required|date',
                'waktu_sidang'   => 'required|date_format:H:i:s',
            ], [
                'dosen_penguji1.required' => 'Penguji 1 wajib dipilih.',
                'date1.required'          => 'Jadwal sidang harus diisi.',
                'waktu_sidang.required'   => 'Waktu sidang harus diisi.',
            ]);
        }

        // Validasi logika: penguji tidak boleh sama
        if ($request->dosen_penguji1 && $request->dosen_penguji1 == $request->dosen_penguji2) {
            return response()->json([
                'message' => 'Penguji 1 dan Penguji 2 tidak boleh sama.',
            ], 422);
        }

        // Validasi logika: pembimbing tidak boleh sama dengan penguji
        if (
            $request->dosen_pembimbing && $request->dosen_pembimbing == $request->dosen_penguji1 ||
            $request->dosen_pembimbing && $request->dosen_pembimbing == $request->dosen_penguji2
        ) {
            return response()->json([
                'message' => 'Dosen pembimbing tidak boleh sama dengan dosen penguji.',
            ], 422);
        }

        // Ambil data submission
        $submission = SidangSubmission::find($request->submission_id);

        // Update data submission
        $submission->penguji_id    = $request->dosen_penguji1;
        $submission->penguji2_id   = $request->dosen_penguji2 ?? null;
        $submission->jadwal_sidang = $request->date1;
        $submission->waktu_sidang  = $request->waktu_sidang;
        $submission->status_sidang = $request->status_sidang;
        $submission->save();

        return response()->json([
            'message' => 'Status sidang berhasil diperbarui.',
        ]);
    }
}
