<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReimbursementBukti extends Model
{
    protected $table = 'reimbursement_bukti';

    protected $fillable = [
        'reimbursement_id',
        'file'
    ];

    public function reimbursement()
    {
        return $this->belongsTo(Reimbursement::class);
    }
}
