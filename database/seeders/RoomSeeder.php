<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Room;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Room::create([
            'name' => 'Room A',
            'total_seats' => 30,
            'layout_type' => '5x6'
        ]);
        Room::create([
            'name' => 'Room B',
            'total_seats' => 25,
            'layout_type' => '5x5'
        ]);
        Room::create([
            'name' => 'Room C',
            'total_seats' => 20,
            'layout_type' => '4x5'
        ]);

        Room::create([
            'name' => 'Room D',
            'total_seats' => 25,
            'layout_type' => '5x5'
        ]);
    }
}
