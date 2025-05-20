<?php
namespace App\Http\Controllers;

use App\Models\BimbinganData;
use App\Models\Period;
use App\Models\SidangSubmission;
use Illuminate\Http\Request;
use Illuminate\View\View;

class user_subSidang extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // PAGE::S.PRO
    public function sidangSempro(): View
    // Fungsi ini dapat digunakan untuk mengambil data dari model (cth: BimbinganData) lalu menggunakannya di blade (cth: dalam form).
    // Perhatikan fungsi dibawah, lalu perhatikan sub halaman sidang di folder _user (views).
    {
        $data = BimbinganData::with(['topik', 'dosen'])
            ->where('user_id', auth()->id())
            ->where('status_bimbingan', 'Berjalan')
            ->first();

        return view('_user.sidangSempro', [
            'idTopik'    => $data?->topik?->id,
            'titleTopik' => $data?->topik?->title,
            'judul'      => $data?->judul,
            'idDosen'    => $data?->dosen?->id,
            'namaDosen'  => $data?->dosen?->name,

        ]);
    }

    // PAGE::S.HAS
    public function sidangSemhas(): View
    {
        $data = BimbinganData::with(['topik', 'dosen'])
            ->where('user_id', auth()->id())
            ->where('status_bimbingan', 'Berjalan')
            ->first();

        return view('_user.sidangSemhas', [
            'idTopik'    => $data?->topik?->id,
            'titleTopik' => $data?->topik?->title,
            'judul'      => $data?->judul,
            'idDosen'    => $data?->dosen?->id,
            'namaDosen'  => $data?->dosen?->name,

        ]);
    }

    // PAGE::S.UJIAN
    public function sidangUjian(): View
    {
        $data = BimbinganData::with(['topik', 'dosen'])
            ->where('user_id', auth()->id())
            ->where('status_bimbingan', 'Berjalan')
            ->first();

        return view('_user.sidangUjian', [
            'idTopik'    => $data?->topik?->id,
            'titleTopik' => $data?->topik?->title,
            'judul'      => $data?->judul,
            'idDosen'    => $data?->dosen?->id,
            'namaDosen'  => $data?->dosen?->name,

        ]);
    }

    // PAGE::S.NILAI
    public function sidangNilai(): View
    {
        return view('_user.sidangNilai');
    }

    // POST::SEMPRO
    public function submitSempro(Request $request)
    {
        // Cek apakah ada periode SEMPRO yang sedang dibuka
        $activePeriode = Period::where('type', 'Seminar Proposal')
            ->where('is_open', true) // atau ->where('is_active', true)
            ->first();

        if (! $activePeriode) {
            return response()->json([
                'message' => 'Periode pengajuan Seminar Proposal saat ini belum dibuka.',
            ], 403);
        }

        // Cek apakah user sudah memiliki pengajuan SEMPRO yang statusnya bukan 'Ditolak'
        $existingSubmission = SidangSubmission::where('user_id', auth()->id())
            ->where('tipe_sidang', 'Seminar Proposal')
            ->where('status_sidang', '!=', 'Ditolak')
            ->first();

        if ($existingSubmission) {
            return response()->json([
                'message' => 'Pengajuan sebelumnya masih dalam proses atau sudah diterima. Tidak dapat mengajukan ulang.',
            ], 422);
        }

        $request->validate([
            'id_topik' => 'required|integer|exists:topics,id',
            'form1'    => 'required|file|mimes:pdf,doc,docx|max:2048',
            'form2'    => 'required|file|mimes:pdf,doc,docx|max:2048',
            'form3'    => 'required|file|mimes:pdf,doc,docx|max:2048',
        ]);

        $filePath1 = $request->file('form1')->store('sempro/pendaftaran', 'public');
        $filePath2 = $request->file('form2')->store('sempro/logbook', 'public');
        $filePath3 = $request->file('form3')->store('sempro/draft', 'public');

        $proposal = SidangSubmission::create([
            'user_id'          => auth()->id(),
            'judul'            => $request->inp_judul,
            'topik_id'         => $request->id_topik,
            'dosen_id'         => $request->id_dosen,
            'fsp1_pendaftaran' => $filePath1,
            'fsp2_logbook'     => $filePath2,
            'fsp3_draft'       => $filePath3,
            'tipe_sidang'      => 'Seminar Proposal',
            'status_sidang'    => 'Pending',
        ]);

        return response()->json([
            'message' => 'Pengajuan seminar proposal berhasil disubmit.',
            'data'    => $proposal,
        ]);
    }

    // GET::DATA_SEMPRO
    public function getDataSempro(Request $request)
    {
        if ($request->ajax()) {
            $query = SidangSubmission::with(['user', 'topik', 'dosen'])
                ->where(function ($q) {
                    $q->where('user_id', auth()->id());
                })->where('tipe_sidang', 'Seminar Proposal');

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
                        'id'          => $item->id,
                        'topik'       => optional($item->topik)->title,
                        'judul'       => $item->judul,
                        'tipe_sidang' => $item->tipe_sidang,
                        'form1'       => $item->fsp1_pendaftaran
                        ? '<a href="' . asset('storage/' . $item->fsp1_pendaftaran) . '" target="_blank">Lihat File</a>'
                        : '-',
                        'form2'       => $item->fsp2_logbook
                        ? '<a href="' . asset('storage/' . $item->fsp2_logbook) . '" target="_blank">Lihat File</a>'
                        : '-',
                        'form3'       => $item->fsp3_draft
                        ? '<a href="' . asset('storage/' . $item->fsp3_draft) . '" target="_blank">Lihat File</a>'
                        : '-',
                        'status'      => $item->status_sidang,

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

    // POST::SEMHAS
    public function submitSemhas(Request $request)
    {
        // Cek apakah ada periode SEMHAS yang sedang dibuka
        $activePeriode = Period::where('type', 'Seminar Pembahasan')
            ->where('is_open', true)
            ->first();

        $skemaMandiri = true;

        // Jika skema mandiri false, maka cek periode aktif atau tidak 
        if (! $skemaMandiri){
            if (! $activePeriode) {
                return response()->json([
                    'message' => 'Periode pengajuan Seminar Pembahasan saat ini belum dibuka.',
                ], 403);
            }
        }

            

        // Cek apakah user sudah memiliki pengajuan SEMHAS yang statusnya bukan 'Ditolak'
        $existingSubmission = SidangSubmission::where('user_id', auth()->id())
            ->where('tipe_sidang', 'Seminar Pembahasan')
            ->where('status_sidang', '!=', 'Ditolak')
            ->first();

        if ($existingSubmission) {
            return response()->json([
                'message' => 'Pengajuan sebelumnya masih dalam proses atau sudah diterima. Tidak dapat mengajukan ulang.',
            ], 422);
        }

        $sempro = SidangSubmission::where('user_id', auth()->id())
            ->where('tipe_sidang', 'Seminar Proposal')
            ->where('status_sidang', 'Selesai')
            ->first();

        if (! $sempro) {
            return response()->json([
                'message' => 'Pengajuan Seminar Pembahasan ditolak. Anda belum menyelesaikan Seminar Proposal.',
            ], 403);
        }

        $request->validate([
            'id_topik' => 'required|integer|exists:topics,id',
            'form1'    => 'required|file|mimes:pdf,doc,docx|max:2048',
            'form2'    => 'required|file|mimes:pdf,doc,docx|max:2048',
            'form3'    => 'required|file|mimes:pdf,doc,docx|max:2048',
        ]);

        $filePath1 = $request->file('form1')->store('semhas/pendaftaran', 'public');
        $filePath2 = $request->file('form2')->store('semhas/logbook', 'public');
        $filePath3 = $request->file('form3')->store('semhas/draft', 'public');

        $proposal = SidangSubmission::create([
            'user_id'          => auth()->id(),
            'judul'            => $request->inp_judul,
            'topik_id'         => $request->id_topik,
            'dosen_id'         => $request->id_dosen,
            'penguji_id'       => $sempro->penguji_id,
            'penguji2_id'      => $sempro->penguji2_id,
            'fsh1_pendaftaran' => $filePath1,
            'fsh2_logbook'     => $filePath2,
            'fsh3_draft'       => $filePath3,
            'tipe_sidang'      => 'Seminar Pembahasan',
            'status_sidang'    => 'Pending',
        ]);

        return response()->json([
            'message' => 'Pengajuan seminar pembahasan berhasil disubmit.',
            'data'    => $proposal,
        ]);
    }

    // GET::DATA_SEMHAS
    public function getDataSemhas(Request $request)
    {
        if ($request->ajax()) {
            $query = SidangSubmission::with(['user', 'topik', 'dosen'])
                ->where(function ($q) {
                    $q->where('user_id', auth()->id());
                })->where('tipe_sidang', 'Seminar Pembahasan');

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
                        'id'          => $item->id,
                        'topik'       => optional($item->topik)->title,
                        'judul'       => $item->judul,
                        'tipe_sidang' => $item->tipe_sidang,
                        'form1'       => $item->fsh1_pendaftaran
                        ? '<a href="' . asset('storage/' . $item->fsh1_pendaftaran) . '" target="_blank">Lihat File</a>'
                        : '-',
                        'form2'       => $item->fsh2_logbook
                        ? '<a href="' . asset('storage/' . $item->fsh2_logbook) . '" target="_blank">Lihat File</a>'
                        : '-',
                        'form3'       => $item->fsh3_draft
                        ? '<a href="' . asset('storage/' . $item->fsh3_draft) . '" target="_blank">Lihat File</a>'
                        : '-',
                        'status'      => $item->status_sidang,

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

    // POST::SIDANGUJIAN
    public function submitSidu(Request $request)
    {

        // Cek apakah user sudah memiliki pengajuan SIDU yang statusnya bukan 'Ditolak'
        $existingSubmission = SidangSubmission::where('user_id', auth()->id())
            ->where('tipe_sidang', 'Sidang Ujian')
            ->where('status_sidang', '!=', 'Ditolak')
            ->first();

        if ($existingSubmission) {
            return response()->json([
                'message' => 'Pengajuan sebelumnya masih dalam proses atau sudah diterima. Tidak dapat mengajukan ulang.',
            ], 422);
        }

        $semhas = SidangSubmission::where('user_id', auth()->id())
            ->where('tipe_sidang', 'Seminar Pembahasan')
            ->where('status_sidang', 'Selesai')
            ->first();

        if (! $semhas) {
            return response()->json([
                'message' => 'Pengajuan Sidang Ujian ditolak. Anda belum menyelesaikan Seminar Pembahasan.',
            ], 403);
        }

        $request->validate([
            'id_topik' => 'required|integer|exists:topics,id',
            'form1'    => 'required|file|mimes:pdf,doc,docx|max:2048',
            'form2'    => 'required|file|mimes:pdf,doc,docx|max:2048',
            'form3'    => 'required|file|mimes:pdf,doc,docx|max:2048',
        ]);

        $filePath1 = $request->file('form1')->store('sidang/buku', 'public');
        $filePath2 = $request->file('form2')->store('sidang/logbook', 'public');
        $filePath3 = $request->file('form3')->store('sidang/ba', 'public');
        $filePath3 = $request->file('form3')->store('sidang/pengesahan', 'public');

        $proposal = SidangSubmission::create([
            'user_id'         => auth()->id(),
            'judul'           => $request->inp_judul,
            'topik_id'        => $request->id_topik,
            'dosen_id'        => $request->id_dosen,
            'penguji_id'      => $semhas->penguji_id,
            'penguji2_id'     => $semhas->penguji2_id,
            'fsu1_buku'       => $filePath1,
            'fsu2_logbook'    => $filePath2,
            'fsu3_ba'         => $filePath3,
            'fsu4_pengesahan' => $filePath3,
            'tipe_sidang'     => 'Sidang Ujian',
            'status_sidang'   => 'Pending',
        ]);

        return response()->json([
            'message' => 'Pengajuan sidang ujian berhasil disubmit.',
            'data'    => $proposal,
        ]);
    }

    // GET::SIDANGUJIAN
    public function getDataSidu(Request $request)
    {
        if ($request->ajax()) {
            $query = SidangSubmission::with(['user', 'topik', 'dosen'])
                ->where(function ($q) {
                    $q->where('user_id', auth()->id());
                })->where('tipe_sidang', 'Sidang Ujian');

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
                        'id'          => $item->id,
                        'topik'       => optional($item->topik)->title,
                        'judul'       => $item->judul,
                        'tipe_sidang' => $item->tipe_sidang,
                        'form1'       => $item->fsu1_buku
                        ? '<a href="' . asset('storage/' . $item->fsu1_buku) . '" target="_blank">Lihat File</a>'
                        : '-',
                        'form2'       => $item->fsu2_logbook
                        ? '<a href="' . asset('storage/' . $item->fsu2_logbook) . '" target="_blank">Lihat File</a>'
                        : '-',
                        'form3'       => $item->fsu3_ba
                        ? '<a href="' . asset('storage/' . $item->fsu3_ba) . '" target="_blank">Lihat File</a>'
                        : '-',
                        'form4'       => $item->fsu4_pengesahan
                        ? '<a href="' . asset('storage/' . $item->fsu4_pengesahan) . '" target="_blank">Lihat File</a>'
                        : '-',
                        'status'      => $item->status_sidang,

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

    // GET::NILAI_SIDANG
    public function getDataNilai(Request $request)
    {
        if ($request->ajax()) {
            $query = SidangSubmission::with(['user', 'topik', 'dosen'])
                ->where(function ($q) {
                    $q->where('user_id', auth()->id());
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
                        'tipe_sidang'   => $item->tipe_sidang,
                        'dosen'         => optional($item->dosen)->name,
                        'status_sidang' => $item->status_sidang,
                        'ket_hasil'     => $item->hasil,
                        'aksi'          => ($item->tipe_sidang === 'Sidang Ujian')
                        ? '<a href="' . route('user.sid-jsonUbah', ['id' => $item->id]) . '" class="btn btn-primary btn-sm">Perubahan</a>'
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
    public function ubahNilai(Request $request)
    {
        $data = SidangSubmission::with(['topik', 'user', 'dosen', 'penguji', 'penguji2'])
            ->where('id', $request->id)
            ->first();

        // kasih logika ini

        return view('_user.sidangUbahnilai', [
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

    // POST::UBAHNILAI
    public function reqUbah(Request $request)
    {
        // Cek apakah user sudah memiliki pengajuan SIDU yang statusnya bukan 'Ditolak'
        $existingSubmission = SidangSubmission::where('user_id', auth()->id())
            ->where('tipe_sidang', 'Ubah Nilai')
            ->where('status_sidang', '!=', 'Ditolak')
            ->first();

        if ($existingSubmission) {
            return response()->json([
                'message' => 'Pengajuan sebelumnya masih dalam proses atau sudah diterima. Tidak dapat mengajukan ulang.',
            ], 422);
        }

        $semhas = SidangSubmission::where('user_id', auth()->id())
            ->where('tipe_sidang', 'Sidang Ujian')
            ->where('status_sidang', 'Selesai')
            ->first();

        if (! $semhas) {
            return response()->json([
                'message' => 'Pengajuan Ubah Nilai ditolak. Anda belum menyelesaikan Sidang Ujian.',
            ], 403);
        }

        $request->validate([
            'id_topik' => 'required|integer|exists:topics,id',
            'form1'    => 'required|file|mimes:pdf,doc,docx|max:2048',
            'form2'    => 'required|file|mimes:pdf,doc,docx|max:2048',
            'form3'    => 'required|file|mimes:pdf,doc,docx|max:2048',
        ]);

        $filePath1 = $request->file('form1')->store('ubah/buku', 'public');
        $filePath2 = $request->file('form2')->store('ubah/logbook', 'public');
        $filePath3 = $request->file('form3')->store('ubah/ba', 'public');
        $filePath3 = $request->file('form3')->store('ubah/pengesahan', 'public');

        $proposal = SidangSubmission::create([
            'user_id'         => auth()->id(),
            'judul'           => $semhas->inp_judul,
            'topik_id'        => $semhas->id_topik,
            'dosen_id'        => $semhas->id_dosen,
            'penguji_id'      => $semhas->penguji_id,
            'penguji2_id'     => $semhas->penguji2_id,
            'un1_buku'       => $filePath1,
            'un2_logbook'    => $filePath2,
            'un3_ba'         => $filePath3,
            'un4_pengesahan' => $filePath3,
            'tipe_sidang'     => 'Ubah Nilai',
            'status_sidang'   => 'Pending',
        ]);

        return response()->json([
            'message' => 'Pengajuan ubah nilai berhasil disubmit.',
            'data'    => $proposal,
        ]);
    }

    // GET::UBAHNILAI
    public function getDataUbah(Request $request)
    {
        if ($request->ajax()) {
            $query = SidangSubmission::with(['user', 'topik', 'dosen'])
                ->where(function ($q) {
                    $q->where('user_id', auth()->id());
                })->where('tipe_sidang', 'Ubah Nilai');

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
                        'id'          => $item->id,
                        'form1'       => $item->un1_buku
                        ? '<a href="' . asset('storage/' . $item->un1_buku) . '" target="_blank">Lihat File</a>'
                        : '-',
                        'form2'       => $item->un2_logbook
                        ? '<a href="' . asset('storage/' . $item->un2_logbook) . '" target="_blank">Lihat File</a>'
                        : '-',
                        'form3'       => $item->un3_ba
                        ? '<a href="' . asset('storage/' . $item->un3_ba) . '" target="_blank">Lihat File</a>'
                        : '-',
                        'form4'       => $item->un4_pengesahan
                        ? '<a href="' . asset('storage/' . $item->un4_pengesahan) . '" target="_blank">Lihat File</a>'
                        : '-',
                        'status'      => $item->status_sidang,

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

}
