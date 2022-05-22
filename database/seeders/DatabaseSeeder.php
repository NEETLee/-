<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Member;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::create(['name'     => '管理员',
                      'account'  => 'neet',
                      'password' => Hash::make('123123')
        ]);
        Member::create(['name'       => '李瀚淇',
                        'age'        => 24,
                        'profession' => 'IT民工',
                        'gender'     => 1,
                        'telephone'  => '17323968447',
                        'deposit'    => 100,
                        'balance'    => 200,
                        'password'   => '$2y$10$7Ihi6Ip4CitVkUSQQwGcZOPGbsFm3XaxU2Xy8A4kZiUnhQv5fVL0e'
        ]);
        Member::factory(10)->create();

        Book::factory(500)->create();
    }
}
