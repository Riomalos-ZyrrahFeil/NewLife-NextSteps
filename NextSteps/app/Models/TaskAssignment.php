<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskAssignment extends Model
{
  protected $table = 'tbl_task_assignment';
  protected $primaryKey = 'task_assignment_id';
  public $timestamps = false; 

  protected $fillable = [
    'visitor_id',
    'user_id',
    'assigned_at'
  ];
}