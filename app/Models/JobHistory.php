<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobHistory extends Model
{
  protected $fillable = [
    'job_name',
    'payload',
    'completed_at',
  ];
}
