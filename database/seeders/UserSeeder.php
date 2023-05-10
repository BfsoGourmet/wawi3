<?php

namespace Database\Seeders;

use App\Models\Courier;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // Admin user
        $admin = (new User([
            'name' => 'Admin',
            'email' => 'admin@wawi.com',
            'password' => '$2y$10$z.CK1skXlmvhupHRUwpwaet9he8rLeZirT.unLxv1QD1gqb/7pKAS' // -> Bfo12345
        ]));

        $admin->save();

        $admin->assignRole('admin');

        // Sample courier
        $courier = Courier::create([
            'first_name' => 'Sample',
            'last_name' => 'Courier',
            'phone_number' => '+41 73 666 22 65',
        ]);

        // Create user for that courier
        $user = (new User([
            'name' => 'Sample Courier',
            'email' => 'sample@courier.ch',
            'password' => '$2y$10$z.CK1skXlmvhupHRUwpwaet9he8rLeZirT.unLxv1QD1gqb/7pKAS', // -> Bfo12345
            'courier_id' => $courier->id
        ]));

        $user->save();

        $user->assignRole('courier');
    }
}
