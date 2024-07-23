<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class PhSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create(
            [
                'name'=>'wissam',
                'phone'=> '0952723498',
                'email'=>'wissam@gmail.com',
                'password'=> bcrypt('password'),
                'role'=> '1',
                'notiToken'=>'mmmmmmm'
            ]);

        User::create(
            [
                'name'=>'abood',
                'phone'=> '0962723498',
                'email'=>'abood@gmail.com',
                'password'=> bcrypt('password'),
                'role'=> '1',
                'notiToken'=>'mmmmmmm'
            ]);


        User::create(
            [
                'name'=>'saria',
                'phone'=> '0972723498',
                'email'=>'fofo@gmail.com',
                'password'=> bcrypt('password'),
                'role'=> '1',
                'notiToken'=>'mmmmmmm'
            ]);

            User::create(
            [
                'name'=>'abd',
                'phone'=> '092222',
                'email'=>'abd@gmail.com',
                'password'=> bcrypt('password'),
                'notiToken'=>'mmmmmmm'
            ]);


    }
}
