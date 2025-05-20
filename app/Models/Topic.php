<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    protected $fillable = ['title', 'focus', 'period_id', 'dosen_id', 'user_id', 'kuota_topik'];

    public function proposalSubmission()
    {
        return $this->hasMany(ProposalSubmission::class, 'topik_id');
    }

    public function dosen()
    {
        return $this->belongsTo(User::class, 'dosen_id');

    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function period()
    {
        return $this->belongsTo(Period::class);
    }

    public function rekapProposal()
{
    return $this->hasMany(RekapProposal::class, 'topik_id');
}
}
