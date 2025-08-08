<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\DataService;

class SeatAllocationController extends Controller
{
    protected $dataService;

    public function __construct(DataService $dataService)
    {
        $this->dataService = $dataService;
    }

    public function seatAllocation(){

        $getAllStudents = $this->dataService->getAllStudents();
        $getAllRooms = $this->dataService->getAllRooms();

        return view('welcome', compact('getAllStudents', 'getAllRooms'));
    }

    public function storeData(Request $request)
    {
        // Get all students and rooms
        $students = $this->dataService->getAllStudents();
        $rooms = $this->dataService->getAllRooms();

        // Separate special needs and regular students
        $specialNeeds = $students->where('special_needs', true)->values();
        $regularStudents = $students->where('special_needs', false)->values();

        // Shuffle regular students for randomness
        $regularStudents = $regularStudents->shuffle();

        $result = [];

        foreach ($rooms as $room) {
            $roomId = $room->id;
            $roomName = $room->name;
            $layoutType = $room->layout_type; // Example: "5x4"
            $layoutParts = explode('x', strtolower($layoutType));
            $rows = isset($layoutParts[0]) ? (int)$layoutParts[0] : 1;
            $cols = isset($layoutParts[1]) ? (int)$layoutParts[1] : 1;

            // seat map created and first w e creates empty
            $seatMap = [];
            for ($r = 1; $r <= $rows; $r++) {
                for ($c = 1; $c <= $cols; $c++) {
                    $seatMap[$r][$c] = null;
                }
            }

            // Place special needs students at edges/corners, leave 1 empty seat adjacent
            $placedSpecial = [];
            $edges = [];
            for ($r = 1; $r <= $rows; $r++) {
                for ($c = 1; $c <= $cols; $c++) {
                    if ($r == 1 || $r == $rows || $c == 1 || $c == $cols) {
                        $edges[] = [$r, $c];
                    }
                }
            }

            // foreach ($specialNeeds as $student) {
            //     if (count($edges) == 0) break;
            //     $seat = array_shift($edges);
            //     $r = $seat[0];
            //     $c = $seat[1];
            //     $seatMap[$r][$c] = $student; // Again we are maping the seat 
            //     $placedSpecial[] = [
            //         'student' => $student,
            //         'row' => $r,
            //         'col' => $c
            //     ];

            //     // Mark adjacent seats as reserved (leave empty)
            //     foreach ([[1,0] ,[0,1]] as $specialStud) {
            //         $rr = $r + $specialStud[0];
            //         $cc = $c + $specialStud[1];
            //         if ($rr >= 1 && $rr <= $rows && $cc >= 1 && $cc <= $cols && $seatMap[$rr][$cc] === null) {
            //             $seatMap[$rr][$cc] = 'reserved';
            //         }
            //     }
            // }

            // Place regular students
            $placed = [];
            foreach ($regularStudents as $student) {
                $found = false;
                for ($r = 1; $r <= $rows; $r++) {
                    for ($c = 1; $c <= $cols; $c++) {
                        if ($seatMap[$r][$c] === null) {
                            $seatMap[$r][$c] = $student;
                            $placed[] = [
                                'student' => $student,
                                'row' => $r,
                                'col' => $c
                            ];
                            $found = true;
                            break 2;
                        }
                    }
                }
                // If not placed, skip (room full)
            }

            // Store allocations in seat_allocations table
            foreach (array_merge($placedSpecial, $placed) as $alloc) {
                if (!isset($alloc['student']->id)) continue;
                \App\Models\SeatAllocation::create([
                    'student_id' => $alloc['student']->id,
                    'room_id' => $roomId,
                    'row_number' => $alloc['row'],
                    'column_number' => $alloc['col'],
                ]);
            }

            // Build the layout array for this room
            $layout = [];
            $subjectSet = [];
            $maleCount = 0;
            $femaleCount = 0;
            $totalAssigned = 0;
            for ($r = 1; $r <= $rows; $r++) {
                $rowArr = [];
                for ($c = 1; $c <= $cols; $c++) {
                    $seat = $seatMap[$r][$c];
                    if (is_object($seat)) {
                        $rowArr[] = $seat->roll_number;
                        $subjectSet[$seat->subject_code] = true;
                        // Count gender
                        if ($seat->gender === 'M') {
                            $maleCount++;
                        } elseif ($seat->gender === 'F') {
                            $femaleCount++;
                        }

                        $totalAssigned++;
                    } else {
                        $rowArr[] = null;
                    }
                }
                $layout[] = $rowArr;
            }

            // Subject mix
            $subjectMix = array_keys($subjectSet);

            // Calculate gender ratios
            $maleRatio = $totalAssigned > 0 ? round(($maleCount / $totalAssigned) * 100) : 0;
            $femaleRatio = $totalAssigned > 0 ? round(($femaleCount / $totalAssigned) * 100) : 0;

            $result[$roomName] = [
                'layout' => $layout,
                'subject_mix' => $subjectMix,
                'layout_type' => $room->layout_type,
                'male_gender_ratio' => $maleRatio . '%',
                'female_gender_ratio' => $femaleRatio . '%'
            ];
        }

        return response()->json($result);
    }

}
