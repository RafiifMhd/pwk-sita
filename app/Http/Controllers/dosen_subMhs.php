<?php
namespace App\Http\Controllers;

use App\Models\BimbinganData;
use App\Models\ProposalSubmission;
use App\Models\SidangSubmission;
use Illuminate\Http\Request;
use Illuminate\View\View;

class dosen_subMhs extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // PAGE::PROPOSAL
    public function mhsProposal(): View
    {
        return view('_dosen.mhsProposal');
    }

    // PAGE::BIMBINGAN
    public function mhsBimbingan(): View
    {
        return view('_dosen.mhsBimbingan');
    }

    // PAGE::PENILAIAN
    public function mhsPenilaian(): View
    {
        return view('_dosen.mhsPenilaian');
    }

    // GET::PROPOSAL_MHS
    public function getTopikMhs(Request $request)
    {
        if ($request->ajax()) {
            $query = ProposalSubmission::with(['topik.dosen', 'user', 'topik.period'])
                ->where(function ($q) {
                    $q->where('dosen_id', auth()->id())
                        ->orWhere('dosen2_id', auth()->id());
                });

            $recordsTotal = ProposalSubmission::count();

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
                        'id'               => $item->id,
                        'nim'              => optional($item->user)->nim,
                        'nama'             => optional($item->user)->name,
                        'topik'            => optional($item->topik)->title,
                        'rancangan_judul'  => $item->rancangan_judul,
                        'draft_proposal'   => $item->draft_file_path
                        ? '<a href="' . asset('storage/' . $item->draft_file_path) . '" target="_blank">Lihat File</a>'
                        : '-',
                        'catatan_validasi' => $item->catatan_validasi,
                        'status_pengajuan' => $item->status_pengajuan,

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

    // POST::CATATAN
    public function simpanCatatan(Request $request, $id)
    {
        $request->validate([
            'catatan_validasi' => 'required|string|max:1000',
        ]);

        $proposal                   = ProposalSubmission::findOrFail($id);
        $proposal->catatan_validasi = $request->catatan_validasi;
        $proposal->save();

        return response()->json(['message' => 'Catatan berhasil disimpan.']);
    }

    // POST::ACCEPT_SUBMISSION
    public function acceptSubmission($id)
    // ENDPOINT::PROPOSAL_SUBMISSION_PROCCESS
    {
        $proposal = ProposalSubmission::where('id', $id)
            ->firstOrFail();

        $proposal->status_pengajuan = 'Disetujui';
        $proposal->save();

        BimbinganData::create([
            'user_id'          => $proposal->user_id,
            'dosen_id'         => $proposal->dosen_id,
            'topik_id'         => $proposal->topik_id,
            'judul'            => $proposal->rancangan_judul,
            'status_bimbingan' => 'Berjalan',

        ]);

        return response()->json(['message' => 'Proposal berhasil diterima.']);
    }

    // POST::REJECT_SUBMISSION
    public function rejectSubmission($id)
    {
        $proposal = ProposalSubmission::where('id', $id)
            ->firstOrFail();

        $proposal->status_pengajuan = 'Ditolak';
        $proposal->save();

        return response()->json(['message' => 'Proposal berhasil ditolak.']);
    }

    // GET::BIMBINGAN_MHS
    public function getDataMhs(Request $request)
    {
        if ($request->ajax()) {
            $query = BimbinganData::with(['user', 'topik'])
                ->where(function ($q) {
                    $q->where('dosen_id', auth()->id());
                });

            $recordsTotal = BimbinganData::count();

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
                        'id'               => $item->id,
                        'nim'              => optional($item->user)->nim,
                        'nama'             => optional($item->user)->name,
                        'topik'            => optional($item->topik)->title,
                        'judul'            => $item->judul,
                        'status_bimbingan' => $item->status_bimbingan,
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

    // GET::HASIL_SIDANG
    public function getDataHasil(Request $request)
    {
        if ($request->ajax()) {
            $query = SidangSubmission::with(['user', 'topik'])
                ->where(function ($q) {
                    $q->where('dosen_id', auth()->id());
                });

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
                        'id'            => $item->id,
                        'nim'           => optional($item->user)->nim,
                        'nama'          => optional($item->user)->name,
                        'judul'         => $item->judul,
                        'tipe_sidang'   => $item->tipe_sidang,
                        'status_sidang' => $item->status_sidang,
                        'aksi'          => $item->id
                        ? '<a href="' . route('dosen.mhs-jsonReview', ['id' => $item->id]) . '" class="btn btn-primary btn-sm">Upload Nilai</a>'
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
    public function reviewNilai(Request $request)
    {
        $data = SidangSubmission::with(['topik', 'user', 'dosen', 'penguji', 'penguji2'])
            ->where('id', $request->id)
            ->first();

        // kasih logika ini

        return view('_dosen.mhsNilaireview', [
            'data'        => $data,
            'idPengajuan' => $data?->id,
            'titleTopik'  => $data?->topik?->title,
            'nimUser'     => $data?->user?->nim,
            'namaUser'    => $data?->user?->name,
            'judul'       => $data?->judul,
            'namaDosen'   => $data?->dosen?->name,
            'idDosen'     => $data?->dosen?->id,
            'tipe_sidang' => $data?->tipe_sidang,
            'penguji'     => $data?->penguji?->name,
            'penguji2'    => $data?->penguji2?->name,
            'form1'       => $data?->form1,
            'form2'       => $data?->form2,
            'form3'       => $data?->form3,
            'form4'       => $data?->form4,
        ]);
    }

    // POST::REVIEW
    public function postNilai(Request $request)
    {
        $request->validate([
            'submission_id' => 'required|integer|exists:sidang_submissions,id',
            'status_sidang' => 'required|string',
            'ket_lulus'     => 'required|string',
            'form1'         => 'required|file|mimes:pdf,doc,docx|max:2048', // maksimal 2MB
        ]);

        $submission = SidangSubmission::find($request->submission_id);

        if ($request->hasFile('form1')) {

            switch ($submission->tipe_sidang) {
                case 'Seminar Proposal':
                    $filePath               = $request->file('form1')->store('sempro/penilaian', 'public');
                    $submission->fsp4_nilai = $filePath;
                    $submission->hasil      = $request->ket_lulus;
                    break;
                case 'Seminar Hasil':
                    $filePath               = $request->file('form1')->store('semhas/penilaian', 'public');
                    $submission->fsh4_nilai = $filePath;
                    $submission->hasil      = $request->ket_lulus;
                    break;
                case 'Sidang Ujian':
                    $filePath               = $request->file('form1')->store('sidang/penilaian', 'public');
                    $submission->fsu5_nilai = $filePath;
                    $submission->hasil      = $request->ket_lulus;
                    break;
            }

        }

        $submission->status_sidang = $request->status_sidang;
        $submission->save();

        return response()->json([
            'message' => 'Status sidang berhasil diperbarui dan file berhasil diunggah.',
        ]);
    }

}
