<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class ProposalSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'dosen_id',
        'topik_id',
        'rancangan_judul',
        'draft_file_path',
        'status_pengajuan',
    ];

    public static function getTableColumns()
    {
        return Schema::getColumnListing((new self)->getTable());
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function topik()
    {
        return $this->belongsTo(Topic::class);
    }

    public function rekapProposal()
    {
        return $this->hasOne(RekapProposal::class, 'propsub_id');
    }
}
