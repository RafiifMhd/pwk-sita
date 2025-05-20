<?php
namespace App\Http\Controllers;

use Illuminate\View\View;

class admin_subPanduan extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function panduanInfo(): View
    {
        return view('_admin.panduanInfo');
    }
}
