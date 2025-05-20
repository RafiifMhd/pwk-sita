<?php
namespace App\Http\Controllers;

use App\Models\Period;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\View\View;

class user_subPeriode extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // PAGE::INFO
    public function periodeInfo(): View
    {
        return view('_user.periodeInfo');
    }

    // PAGE::TOPIK
    public function periodeTopik(): View
    {
        return view('_user.periodeTopik');
    }

    // GET::PERIODE
    public function getPeriodeUser(Request $request)
    {
        if ($request->ajax()) {
            $query = Period::query();

            $recordsTotal = $query->count();

            if ($request->has('search') && $request->search['value'] != '') {
                $search = $request->search['value'];
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
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
                ->get(['id', 'name', 'type', 'start_date', 'end_date', 'is_open'])
                ->map(function ($item) {
                    return [
                        'name'       => $item->name,
                        'type'       => $item->type,
                        'start_date' => $item->start_date,
                        'end_date'   => $item->end_date,
                        'is_open'    => $item->is_open
                        ? '<span class="badge-status badge-open">Dibuka</span>'
                        : '<span class="badge-status badge-closed">Ditutup</span>',
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

    // GET::TOPIK
    public function getTopikUser(Request $request)
    {
        if ($request->ajax()) {
            $query = Topic::with(['dosen', 'period'])
                ->whereHas('period', function ($q) {
                    $q->where('is_open', true);
                });

            $recordsTotal = $query->count();

            if ($request->has('search') && $request->search['value'] != '') {
                $search = $request->search['value'];
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('focus', 'like', "%{$search}%")
                        ->orWhereHas('period', function ($subQ) use ($search) {
                            $subQ->where('name', 'like', "%{$search}%");
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

            $data = $query->withCount([
                'proposalSubmission',
                'proposalSubmission as validated_submission_count' => function ($query) {
                    $query->where('status_pengajuan', 'Disetujui');
                },
            ])
                ->skip($request->start)
                ->take($request->length)
                ->get()
                ->map(function ($item) {
                    $idTopik    = urlencode($item->id ?? '');
                    $titleTopik = urlencode($item->title ?? '');
                    $idDosen    = urlencode(optional($item->dosen)->id ?? '');
                    $namaDosen  = urlencode(optional($item->dosen)->name ?? '');

                    $link = route('user.ta-proposal', ['idTopik' => $idTopik, 'idDosen' => $idDosen, 'namaDosen' => $namaDosen, 'titleTopik' => $titleTopik]);
             
                    return [
                        'id'               => $item->id,
                        'period_name'      => optional($item->period)->name,
                        'title'            => $item->title,
                        'focus'            => $item->focus,
                        'dosen_name'       => optional($item->dosen)->name,
                        'kuota'            => $item->kuota_topik,
                        'submission_count' => $item->proposal_submission_count,
                        'validated_sc'     => $item->validated_submission_count,
                        'submission'       => $item->proposal_submission_count >= 15 || $item->kuota_topik == $item->validated_submission_count
                        ? '<button class="btn btn-sm btn-secondary" disabled>Penuh</button>'
                        : '<a href="' . $link . '" class="btn btn-sm btn-primary">Ajukan</a>',
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
