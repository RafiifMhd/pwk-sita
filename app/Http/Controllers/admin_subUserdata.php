<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class admin_subUserdata extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // PAGE::DATA_USER
    public function dataUser(): View
    {
        return view('_admin.dataUser');
    }

    // PAGE::DATA_ADMIN
    public function dataAdmin(): View
    {
        return view('_admin.dataAdmin');
    }

    // PAGE::DATA_DOSEN
    public function dataDosen(): View
    {
        return view('_admin.dataDosen');
    }

    // GET::USERDATA_ADMIN
    public function getAdminUsers(Request $request)
    {
        if ($request->ajax()) {
            $query = User::where('type', '1');

            $recordsTotal = $query->count();

            if ($request->has('search') && $request->search['value'] != '') {
                $search = $request->search['value'];
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            }

            $recordsFiltered = $query->count();

            //Fix Ordering
            if ($request->has('order')) {
                $orderColumnIndex = $request->order[0]['column'];
                $orderColumn      = $request->columns[$orderColumnIndex]['data'];
                $orderDir         = $request->order[0]['dir'];

                $validColumns = User::getTableColumns();
                if (in_array($orderColumn, $validColumns)) {
                    $query->orderBy($orderColumn, $orderDir);
                }
            }

            $data = $query->skip($request->start)
                ->take($request->length)
                ->get(['id', 'name', 'email', 'created_at']);

            return response()->json([
                'draw'            => intval($request->draw),
                'recordsTotal'    => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data'            => $data,
            ]);
        }

        abort(404);
    }

    // GET::USERDATA_DOSEN
    public function getDosenUsers(Request $request)
    {
        if ($request->ajax()) {
            $query = User::where('type', '2');

            $recordsTotal = $query->count();

            if ($request->has('search') && $request->search['value'] != '') {
                $search = $request->search['value'];
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('nip', 'like', "%{$search}%")
                        ->orWhere('tipe_dos', 'like', "%{$search}%");
                });
            }

            $recordsFiltered = $query->count();

            //Fix Ordering
            if ($request->has('order')) {
                $orderColumnIndex = $request->order[0]['column'];
                $orderColumn      = $request->columns[$orderColumnIndex]['data'];
                $orderDir         = $request->order[0]['dir'];

                $validColumns = User::getTableColumns();
                if (in_array($orderColumn, $validColumns)) {
                    $query->orderBy($orderColumn, $orderDir);
                }
            }

            $data = $query->skip($request->start)
                ->take($request->length)
                ->get(['id', 'name', 'nip', 'wa_dos', 'tipe_dos', 'email', 'created_at']);

            return response()->json([
                'draw'            => intval($request->draw),
                'recordsTotal'    => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data'            => $data,
            ]);
        }

        abort(404);
    }

    // GET::USERDATA_USER
    public function getUserUsers(Request $request)
    {
        if ($request->ajax()) {
            $query = User::where('type', '0');

            $recordsTotal = $query->count();

            if ($request->has('search') && $request->search['value'] != '') {
                $search = $request->search['value'];
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('nim', 'like', "%{$search}%");
                });
            }

            $recordsFiltered = $query->count();

            //Fix Ordering
            if ($request->has('order')) {
                $orderColumnIndex = $request->order[0]['column'];
                $orderColumn      = $request->columns[$orderColumnIndex]['data'];
                $orderDir         = $request->order[0]['dir'];

                $validColumns = User::getTableColumns();
                if (in_array($orderColumn, $validColumns)) {
                    $query->orderBy($orderColumn, $orderDir);
                }
            }

            $data = $query->skip($request->start)
                ->take($request->length)
                ->get(['id', 'name', 'nim', 'email', 'created_at']);

            return response()->json([
                'draw'            => intval($request->draw),
                'recordsTotal'    => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data'            => $data,
            ]);
        }

        abort(404);
    }

    // DELETE::USERDATA
    public function deleteUser($id)
    {
        if (auth()->user()->type !== 'admin') {
            return response()->json(['message' => 'Forbidden.'], 403);
        }
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['message' => 'Data berhasil dihapus.']);
    }

    // POST::RESET_PSW
    public function resetPassword($id)
    {
        if (auth()->user()->type !== 'admin') {
            return response()->json(['message' => 'Forbidden.'], 403);
        }
        $user           = User::findOrFail($id);
        $user->password = \Illuminate\Support\Facades\Hash::make('default-sitapwk');
        $user->save();

        return response()->json(['message' => 'Password telah direset.']);
    }

    // POST::ADD_ADMIN
    public function addAdmin(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
        ], [
            'name.required'  => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',

        ]);

        $email = $request->input('email');

        $emailSama = User::where('email', $email)
            ->exists();

        if ($emailSama) {
            return response()->json([
                'message' => 'Ada email yang sama, tidak bisa menambah data baru.',
            ], 422);
        }

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'type'     => 1,
            'password' => \Illuminate\Support\Facades\Hash::make('default-sitapwk'),

        ]);

        return response()->json(['message' => 'Data berhasil ditambah.']);
    }

    // POST::ADD_DOSEN
    public function addDosen(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255',
            'tipe_dos' => 'required|string|max:255',
            'nip'      => 'required|string|max:255',
            'no_telf'  => 'required|numeric',
        ], [
            'name.required'     => 'Nama wajib diisi.',
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak benar.',
            'tipe_dos.required' => 'Tipe dosen wajib diisi.',
            'nip.required'      => 'NIP wajib diisi.',
            'no_telf.required'  => 'No Telepon wajib diisi.',
            'no_telf.numeric'   => 'No Telepon hanya boleh angka dan diawali oleh 0 atau 62.',
        ]);

        $email = $request->input('email');

        $emailSama = User::where('email', $email)
            ->exists();

        if ($emailSama) {
            return response()->json([
                'message' => 'Ada email yang sama, tidak bisa menambah data baru.',
            ], 422);
        }

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'tipe_dos' => $request->tipe_dos,
            'nip'      => $request->nip,
            'wa_dos'   => $request->no_telf,
            'type'     => 2,
            'password' => \Illuminate\Support\Facades\Hash::make('default-sitapwk'),

        ]);

        return response()->json(['message' => 'Data berhasil ditambah.']);
    }

}
