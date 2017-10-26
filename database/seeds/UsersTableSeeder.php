<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$users = [
    		[
    			'name'		=> 'Admin Sales Stock',
				'email'		=> 'admin@salestock.com',
				'role'		=> 'admin',
    		],
    		[
    			'name'		=> 'Customer Sales Stock',
				'email'		=> 'customer@mail.com',
				'role'		=> 'customer',
    		]
    	];

        foreach ($users as $key => $user) {
        	$userModel = User::firstOrNew($user);
        	$userModel->password = bcrypt('secret');
        	$userModel->save();
        }
    }
}
