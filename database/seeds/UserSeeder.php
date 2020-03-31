<?php

use App\Domain\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $i = 1;
        User::truncate();

        factory(User::class)->create([
            'email' => 'admin@admin.com',
            'gender' => 1,
            'type' => 1
        ]);

        do {
            factory(User::class)->create([
                'gender' => ($i < 5) ? 1 : 2,
                'type' => ($i < 5) ? 1 : 2,
            ]);

            $i++;
        } while($i <= 15);
    }
}
