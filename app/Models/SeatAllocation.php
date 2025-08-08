<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeatAllocation extends Model
{
    protected $fillable = ['student_id', 'room_id', 'row_number', 'column_number'];
}
