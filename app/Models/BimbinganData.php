<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BimbinganData extends Model
{
    use HasFactory;

    protected $table = 'data_bimbingan_mhs_dosen';
    // Karena nama model tidak sama dengan nama tabel, baris ini ditambahkan

    protected $fillable = [
        'user_id',
        'dosen_id',
        'topik_id',
        'judul',
        'status_bimbingan',
    ];

    // Perbaikan penamaan fungsi dosen di ProposalSubmission model.
    public function dosen()
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function topik()
    {
        return $this->belongsTo(Topic::class, 'topik_id');
    }
}
