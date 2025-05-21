<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class ProposalKuotaDosen extends Model
{
    protected $table = 'kuota_bimbingan_periode_dosen';

    protected $fillable = [
        'period_id',
        'dosen_id',
        'kuota_bimbingan',
        'kuota_berjalan',
    ];

    public static function getTableColumns()
    {
        return Schema::getColumnListing((new self)->getTable());
    }

    public function dosen()
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }

    public function period()
    {
        return $this->belongsTo(Period::class, 'period_id');
    }
}
