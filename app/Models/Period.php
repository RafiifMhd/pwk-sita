<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Period extends Model
{
    protected $fillable = ['name', 'type', 'start_date', 'end_date', 'is_open'];

    public function topics()
    {
        return $this->hasMany(Topic::class);
    }

    public function kuotaBimbingan()
    {
        return $this->hasMany(ProposalKuotaDosen::class, 'period_id');
    }

    public function rekapProposal()
    {
        return $this->hasMany(RekapProposal::class, 'period_id');
    }

}
