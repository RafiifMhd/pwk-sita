<?php
namespace App\Http\Controllers;

use App\Models\ProposalSubmission;
use Illuminate\View\View;

class admin_subTA extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // PAGE::REKAP_PROPOSAL
    public function taProposal(): View
    {
        return view('_admin.taProposal');
    }

    // PAGE::REKAP_UMUM
    public function taUmum(): View
    {
        return view('_admin.taUmum');
    }

}
