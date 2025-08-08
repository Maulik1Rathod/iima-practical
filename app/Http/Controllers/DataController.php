<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\DataService;

class DataController extends Controller
{
    protected $dataService;

    public function __construct(DataService $dataService)
    {
        $this->dataService = $dataService;
    }

    public function getStudents()
    {
        return response()->json($this->dataService->getAllStudents());
    }

    public function getRooms()
    {
        return response()->json($this->dataService->getAllRooms());
    }
}
