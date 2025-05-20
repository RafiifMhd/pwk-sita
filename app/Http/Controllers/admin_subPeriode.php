<?php
namespace App\Http\Controllers;

use App\Models\Period;
use App\Models\ProposalKuotaDosen;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class admin_subPeriode extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // PAGE::INFO
    public function periodeInfo(): View
    {
        return view('_admin.periodeInfo');
    }

    // GET::PERIODE
    public function getPeriode(Request $request)
    {
        if ($request->ajax()) {
            $query = Period::query();

            $recordsTotal = $query->count();

            if ($request->has('search') && $request->search['value'] != '') {
                $search = $request->search['value'];
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('type', 'like', "%{$search}%");
                });
            }

            $recordsFiltered = $query->count();

            if ($request->has('order')) {
                $orderColumnIndex = $request->order[0]['column'];
                $orderColumn      = $request->columns[$orderColumnIndex]['data'];
                $orderDir         = $request->order[0]['dir'];
                $query->orderBy($orderColumn, $orderDir);
            }

            $data = $query->skip($request->start)
                ->take($request->length)
                ->get(['id', 'name', 'type', 'start_date', 'end_date', 'is_open', 'created_at'])
                ->map(function ($item) {
                    return [
                        'id'         => $item->id,
                        'name'       => $item->name,
                        'type'       => $item->type,
                        'start_date' => $item->start_date,
                        'end_date'   => $item->end_date,
                        'is_open'    => $item->is_open
                        ? '<div class="d-flex"><span class="badge-status badge-open flex-fill">Dibuka</span></div>'
                        : '<div class="d-flex"><span class="badge-status badge-closed flex-fill">Ditutup</span></div>',
                        'aksi'       => $item->is_open
                        ? '<button class="btn btn-primary btn-sm" style="min-width: 100px;" onclick="if(confirm(\'Yakin ingin tutup periode ini?\')) tutupPeriode(' . $item->id . ')">Tutup</button>'
                        : '<button class="btn btn-secondary btn-sm" style="min-width: 100px;" onclick="if(confirm(\'Yakin ingin hapus data ini?\')) hapusPeriode(' . $item->id . ')">Hapus</button>',
                        'created_at' => $item->created_at->toDateTimeString(),
                    ];
                });

            return response()->json([
                'draw'            => intval($request->draw),
                'recordsTotal'    => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data'            => $data,
            ]);
        }

        abort(404);
    }

    // GET::KUOTA_PROP
    public function getProposal(Request $request)
    {
        if ($request->ajax()) {
            $query = ProposalKuotaDosen::with(['period', 'dosen'])->whereHas('period', function ($q) {
                $q->where('is_open', true);
            });

            $recordsTotal = $query->count();

            if ($request->has('search') && $request->search['value'] != '') {
                $search = $request->search['value'];
                $query->whereHas('dosen', function ($q) use ($search) {
                    $q->where('tipe_dos', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%");
                });
            }

            $recordsFiltered = $query->count();

            if ($request->has('order')) {
                $orderColumnIndex = $request->order[0]['column'];
                $orderColumn      = $request->columns[$orderColumnIndex]['data'];
                $orderDir         = $request->order[0]['dir'];

                $allowedSorts = ['kuota_bimbingan', 'created_at'];

                if (in_array($orderColumn, $allowedSorts)) {
                    $query->orderBy($orderColumn, $orderDir);
                }
            }

            $data = $query->skip($request->start)
                ->take($request->length)
                ->get()
                ->map(function ($item) {
                    return [
                        'id'         => $item->id,
                        'dosen'      => optional($item->dosen)->name,
                        'tipe_dosen' => optional($item->dosen)->tipe_dos,
                        'kuota_ta'   => $item->kuota_bimbingan,
                        'aksi'       => '<button class="btn btn-primary btn-sm edit-kuota" data-id="' . $item->id . '" data-kuota="' . $item->kuota_bimbingan . '">Add/Edit Kuota</button>',
                        'title'      => optional($item->period)->name,
                        'created_at' => $item->created_at->toDateTimeString(),
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

    // UPD::UPDATE_KUOTA
    public function updateKuota(Request $request, $id)
    {
        $validated = $request->validate([
            'kuota_ta' => 'required|integer|min:0',
        ]);

        $kuota = ProposalKuotaDosen::findOrFail($id);

        $jumlahTerpakai = $kuota->kuota_berjalan;

        if ($validated['kuota_ta'] < $jumlahTerpakai) {
            return response()->json([
                'message' => "Gagal: Kuota tidak boleh lebih kecil dari jumlah yang sudah digunakan ($jumlahTerpakai mahasiswa).",
            ], 422);
        }

        // Hitung sisa kuota saat ini
        $sisaSaatIni = $kuota->kuota_bimbingan - $kuota->kuota_berjalan;

        // Update nilai kuota
        $kuota->kuota_bimbingan = $validated['kuota_ta'];
        $kuota->kuota_berjalan  = $validated['kuota_ta'] - $sisaSaatIni;

        $kuota->save();

        return response()->json(['message' => 'Kuota berhasil diperbarui']);
    }

    // POST::PERIODE
    public function store(Request $request)
    {
        $request->validate([
            'title'      => 'required|string|max:255',
            'type'       => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
        ], [
            'title.required'          => 'Title wajib diisi.',
            'type.required'           => 'Jenis periode wajib dipilih.',
            'start_date.required'     => 'Tanggal mulai wajib diisi.',
            'end_date.required'       => 'Tanggal selesai wajib diisi.',
            'end_date.after_or_equal' => 'Tanggal selesai harus setelah tanggal mulai.',
        ]);

        $type = $request->input('type');

        $adaYangMasihBuka = Period::where('type', $type)
            ->where('is_open', true)
            ->exists();

        if ($adaYangMasihBuka) {
            return response()->json([
                'message' => 'Ada jenis periode yang sama masih dibuka. Tutup dulu sebelum membuat yang baru.',
            ], 422);
        }

        $periode = Period::create([
            'name'       => $request->title,
            'type'       => $request->type,
            'start_date' => Carbon::parse($request->start_date)->startOfDay(),
            'end_date'   => Carbon::parse($request->end_date)->endOfDay(),
            'is_open'    => true,
        ]);

        if ($request->type === 'Pengajuan Proposal') {
            $this->generateKPeriode($periode->id);
        }

        return response()->json(['message' => 'Periode berhasil dibuat.']);
    }

    public function generateKPeriode($period_id)
    {
        $dosenList = User::where('type', '2')->get();

        foreach ($dosenList as $dosen) {
            ProposalKuotaDosen::updateOrCreate(
                [
                    'period_id' => $period_id,
                    'dosen_id'  => $dosen->id,
                ],
                [
                    'kuota_bimbingan' => 5,
                    'kuota_berjalan'  => 5,
                ]
            );
        }
    }

    // POST::CLOSE_PERIODE
    public function closePeriode($id)
    {
        $periode = Period::findOrFail($id);
        if (! $periode->is_open) {
            return response()->json(['message' => 'Periode sudah ditutup.'], 400);
        }

        $periode->is_open = false;
        $periode->save();

        return response()->json(['message' => 'Periode berhasil ditutup.']);
    }

    // DELETE::PERIODE
    public function deletePeriode($id)
    {
        $period = Period::findOrFail($id);
        $period->delete();

        return response()->json([
            'message' => 'Periode dan semua topik terkait berhasil dihapus.',
        ]);
    }

}
