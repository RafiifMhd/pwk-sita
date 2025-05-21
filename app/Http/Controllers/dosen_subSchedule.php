<?php
namespace App\Http\Controllers;

use App\Models\SidangSubmission;
use Illuminate\Http\Request;
use Illuminate\View\View;

class dosen_subSchedule extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // PAGE::JADWAL
    public function schedJadwal(): View
    {
        return view('_dosen.schedJadwal');
    }

    public function getDataJadwal(Request $request)
    {
        if ($request->ajax()) {
            $query = SidangSubmission::with(['user', 'topik', 'dosen', 'penguji', 'penguji2'])
                ->where(function ($q) {
                    $q->where('dosen_id', auth()->id())
                        ->orWhere('penguji_id', auth()->id())
                        ->orWhere('penguji2_id', auth()->id());
                })
                ->where('status_sidang', 'Dijadwalkan');

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
                    $loggedInDosenId = auth()->id();

                    if ($item->dosen_id === $loggedInDosenId) {
                        $role = 'Pembimbing';
                    } elseif ($item->penguji_id === $loggedInDosenId) {
                        $role = 'Penguji 1';
                    } elseif ($item->penguji2_id === $loggedInDosenId) {
                        $role = 'Penguji 2';
                    } else {
                        $role = '-';
                    }

                    return [
                        'id'          => $item->id,
                        'tipe_sidang' => $item->tipe_sidang,
                        'role'        => $role,
                        'nim'         => optional($item->user)->nim,
                        'nama'        => optional($item->user)->name,
                        'topik'       => optional($item->topik)->title,
                        'tanggal'     => $item->jadwal_sidang,
                        'waktu'       => $item->waktu_sidang,
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
