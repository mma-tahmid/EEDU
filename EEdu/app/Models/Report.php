<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $table='report';
    protected $primaryKey='id';
    public $timestamps=false;
    use HasFactory;
}
