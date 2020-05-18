<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new \App\User();
        $user->firstname = 'Hugo' ;
        $user->lastname = 'Boss' ;
        $user->is_helper = false;
        $user->street = 'Marienstraße' ;
        $user->streetnumber = '9' ;
        $user->plz = '4020' ;
        $user->city = 'Linz' ;
        $user->email = 'test@gmail.com' ;
        $user->password = bcrypt( 'secret' );
        $user->save();


        $user2 = new \App\User();
        $user2->firstname = 'Helper' ;
        $user2->lastname = 'Boss' ;
        $user2->is_helper = true;
        $user2->street = 'Marienstraße' ;
        $user2->streetnumber = '9' ;
        $user2->plz = '4020' ;
        $user2->city = 'Linz' ;
        $user2->email = 'another@gmail.com' ;
        $user2->password = bcrypt( 'secret' );
        $user2->save();
    }
}
