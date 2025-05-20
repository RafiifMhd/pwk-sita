<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekapProposal extends Model
{
    use HasFactory;
    protected $table = 'rekap_proposal';

    protected $fillable = [
        'period_id',
        'dosen_id',
        'topik_id',
        'propsub_id',
        'kuota_saat_rekap',
        'jumlah_bimbingan_saat_rekap',
    ];

    // Relasi ke periode
    public function period()
    {
        return $this->belongsTo(Period::class);
    }

    // Relasi ke dosen (user)
    public function dosen()
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }

    // Relasi ke topik
    public function topik()
    {
        return $this->belongsTo(Topic::class, 'topik_id');
    }

    // Relasi ke pengajuan proposal
    public function proposalSubmission()
    {
        return $this->belongsTo(ProposalSubmission::class, 'propsub_id');
    }

}
