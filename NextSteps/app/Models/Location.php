<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
protected $table = 'tbl_location';
protected $primaryKey = 'location_id';

protected $fillable = ['location_name'];
public $timestamps = false;
}