<?php
namespace App\Http\Controllers;

use App\Models\SidangSubmission;
use Illuminate\Http\Request;
use Illuminate\View\View;

class user_subSchedule extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // PAGE::JADWAL
    public function schedJadwal(): View
    {
        $data = SidangSubmission::with(['user', 'topik', 'dosen'])
            ->where('user_id', auth()->id())
            ->first();

        return view('_user.schedJadwal', [
            'titleTopik' => $data?->topik?->title,
            'judul'      => $data?->judul,
            'namaDosen'  => $data?->dosen?->name,

        ]);
    }

    // GET::DATA_SIDANG
    public function getDataSidang(Request $request)
    {
        if ($request->ajax()) {
            $query = SidangSubmission::with(['user', 'topik', 'dosen', 'penguji', 'penguji2'])
                ->where(function ($q) {
                    $q->where('user_id', auth()->id());
                })
                ->where('status_sidang', 'Dijadwalkan')
                ->orWhere('status_sidang', 'Selesai');

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

            //Fix Ordering
            if ($request->has('order')) {
                $orderColumnIndex = $request->order[0]['column'];
                $orderColumn      = $request->columns[$orderColumnIndex]['data'];
                $orderDir         = $request->order[0]['dir'];

                $validColumns = SidangSubmission::getTableColumns();
                if (in_array($orderColumn, $validColumns)) {
                    $query->orderBy($orderColumn, $orderDir);
                }
            }

            $data = $query->skip($request->start)
                ->take($request->length)
                ->get()
                ->map(function ($item) {
                    return [
                        'id'          => $item->id,
                        'tipe_sidang' => $item->tipe_sidang,
                        'dosen'       => optional($item->dosen)->name,
                        'penguji'     => optional($item->penguji)->name,
                        'penguji2'    => optional($item->penguji2)->name,
                        'tanggal'     => $item->jadwal_sidang,
                        'waktu'       => $item->jadwal_sidang,
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
