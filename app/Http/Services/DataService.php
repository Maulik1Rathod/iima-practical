<?php 

namespace App\Http\Services;
use App\Models\Student;
use App\Models\Room;
use Illuminate\Http\JsonResponse;
class DataService
{
    public function getAllStudents()
    {
        return Student::all();
    }

    public function getAllRooms()
    {
        return Room::all();
    }
}
