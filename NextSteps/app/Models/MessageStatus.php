<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MessageStatus extends Model
{
    protected $table = 'tbl_message_status';
    protected $primaryKey = 'message_status_id';

    public $timestamps = false;

    protected $fillable = [
        'visitor_id', 
        'follow_up_stage_id',
        'status'
    ];

    public function visitor()
    {
        return $this->belongsTo(Visitor::class, 'visitor_id', 'visitor_id');
    }
}