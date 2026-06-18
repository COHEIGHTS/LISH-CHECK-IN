<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $password = Hash::make('collins@001A');

        // Create 20 users with real names and Gmail addresses
        $users = [
            [
                'name' => 'John Kamau',
                'email' => 'john.kamau@gmail.com',
                'role' => 'staff',
                'status' => 'approved',
            ],
            [
                'name' => 'Mary Wanjiku',
                'email' => 'mary.wanjiku@gmail.com',
                'role' => 'staff',
                'status' => 'approved',
            ],
            [
                'name' => 'Peter Ochieng',
                'email' => 'peter.ochieng@gmail.com',
                'role' => 'attachee',
                'status' => 'approved',
            ],
            [
                'name' => 'Grace Njeri',
                'email' => 'grace.njeri@gmail.com',
                'role' => 'attachee',
                'status' => 'approved',
            ],
            [
                'name' => 'David Mutua',
                'email' => 'david.mutua@gmail.com',
                'role' => 'staff',
                'status' => 'approved',
            ],
            [
                'name' => 'Sarah Akinyi',
                'email' => 'sarah.akinyi@gmail.com',
                'role' => 'staff',
                'status' => 'approved',
            ],
            [
                'name' => 'James Mwangi',
                'email' => 'james.mwangi@gmail.com',
                'role' => 'attachee',
                'status' => 'approved',
            ],
            [
                'name' => 'Elizabeth Wairimu',
                'email' => 'elizabeth.wairimu@gmail.com',
                'role' => 'staff',
                'status' => 'approved',
            ],
            [
                'name' => 'Michael Omondi',
                'email' => 'michael.omondi@gmail.com',
                'role' => 'attachee',
                'status' => 'approved',
            ],
            [
                'name' => 'Hannah Njoki',
                'email' => 'hannah.njoki@gmail.com',
                'role' => 'staff',
                'status' => 'approved',
            ],
            [
                'name' => 'Robert Kipkorir',
                'email' => 'robert.kipkorir@gmail.com',
                'role' => 'staff',
                'status' => 'approved',
            ],
            [
                'name' => 'Lucy Chebet',
                'email' => 'lucy.chebet@gmail.com',
                'role' => 'attachee',
                'status' => 'approved',
            ],
            [
                'name' => 'Daniel Kimani',
                'email' => 'daniel.kimani@gmail.com',
                'role' => 'staff',
                'status' => 'approved',
            ],
            [
                'name' => 'Jane Muthoni',
                'email' => 'jane.muthoni@gmail.com',
                'role' => 'attachee',
                'status' => 'approved',
            ],
            [
                'name' => 'Samuel Otieno',
                'email' => 'samuel.otieno@gmail.com',
                'role' => 'staff',
                'status' => 'approved',
            ],
            [
                'name' => 'Ann Wanjiru',
                'email' => 'ann.wanjiru@gmail.com',
                'role' => 'staff',
                'status' => 'approved',
            ],
            [
                'name' => 'Benjamin Kiplagat',
                'email' => 'benjamin.kiplagat@gmail.com',
                'role' => 'attachee',
                'status' => 'approved',
            ],
            [
                'name' => 'Catherine Nyambura',
                'email' => 'catherine.nyambura@gmail.com',
                'role' => 'staff',
                'status' => 'approved',
            ],
            [
                'name' => 'Francis Maina',
                'email' => 'francis.maina@gmail.com',
                'role' => 'attachee',
                'status' => 'approved',
            ],
            [
                'name' => 'Margaret Atieno',
                'email' => 'margaret.atieno@gmail.com',
                'role' => 'staff',
                'status' => 'approved',
            ],
        ];

        $createdUsers = [];
        foreach ($users as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                array_merge($userData, [
                    'password' => $password,
                    'profile_completed' => true,
                ])
            );
            $createdUsers[] = $user;
        }

        // Seed attendance data for each user for the past 6 months
        foreach ($createdUsers as $user) {
            for ($month = 5; $month >= 0; $month--) {
                $currentMonth = Carbon::now()->subMonths($month);
                $daysInMonth = $currentMonth->daysInMonth;
                
                for ($day = 1; $day <= $daysInMonth; $day++) {
                    $date = Carbon::create($currentMonth->year, $currentMonth->month, $day);
                    
                    // Skip weekends (Saturday = 6, Sunday = 0)
                    if ($date->dayOfWeek === 0 || $date->dayOfWeek === 6) {
                        continue;
                    }

                    // Skip future dates
                    if ($date->isFuture()) {
                        continue;
                    }

                    // Randomly decide if user attended (75% attendance rate)
                    if (rand(1, 10) <= 7) {
                        // Random check-in time between 7:30 AM and 9:30 AM
                        $checkInHour = 7 + rand(0, 2);
                        $checkInMinute = rand(0, 59);
                        $checkInTime = Carbon::createFromTime($checkInHour, $checkInMinute, 0);

                        // Determine status based on check-in time (late if after 8:00 AM)
                        $status = 'present';
                        if ($checkInHour >= 8 && $checkInMinute > 0) {
                            $status = 'late';
                        }

                        Attendance::firstOrCreate(
                            [
                                'user_id' => $user->id,
                                'attendance_date' => $date->format('Y-m-d'),
                            ],
                            [
                                'check_in_time' => $checkInTime->format('H:i:s'),
                                'check_out_time' => Carbon::createFromTime(17, rand(0, 30), 0)->format('H:i:s'),
                                'status' => $status,
                                'qr_token' => null,
                            ]
                        );
                    }
                }
            }
        }

        $this->command->info('Successfully seeded 20 users with attendance data for the past 6 months.');
        $this->command->info('All users have password: collins@001A');
    }
}
