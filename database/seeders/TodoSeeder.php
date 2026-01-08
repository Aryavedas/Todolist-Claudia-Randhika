<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Todo;
use Illuminate\Support\Facades\Hash;

class TodoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat 10 user
        for ($u = 1; $u <= 10; $u++) {

            $user = User::create([
                'name' => "User $u",
                'email' => "user$u@example.com",
                'password' => Hash::make('password'),
            ]);

            // Setiap user punya 10 todo (total 100)
            // for ($i = 1; $i <= 10; $i++) {
            //     Todo::create([
            //         'user_id' => $user->id,
            //         'title' => "Todo $i milik User $u",
            //         'description' => "Deskripsi todo $i untuk user $u",
            //         'is_completed' => fake()->boolean(),
            //     ]);
            // }
        }
    }
}
