<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SidangSubmission extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'judul',
        'topik_id',
        'dosen_id',
        'penguji_id', // upd by admin
        'penguji2_id', // upd by admin
        'jadwal_sidang',
        'tipe_sidang',
        'status_sidang', // upd by mhs
        
        /** Files */
        'fsp1_pendaftaran',
        'fsp2_logbook',
        'fsp3_draft',
        'fsp4_nilai',

        'fsh1_pendaftaran',
        'fsh2_logbook',
        'fsh3_draft',
        'fsh4_nilai',

        'fsu1_buku',
        'fsu2_logbook',
        'fsu3_ba',
        'fsu4_pengesahan',
        'fsu5_nilai',

    ];

    public function dosen()
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }

    public function penguji()
    {
        return $this->belongsTo(User::class, 'penguji_id');
    }

    public function penguji2()
    {
        return $this->belongsTo(User::class, 'penguji2_id');

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
