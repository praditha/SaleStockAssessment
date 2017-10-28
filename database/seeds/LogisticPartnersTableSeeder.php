<?php

use Illuminate\Database\Seeder;
use App\Models\LogisticPartner;

class LogisticPartnersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $partners = [
    		[
    			'name'		=> 'Si Ngebut',
    		],
    		[
    			'name'		=> 'Flash Delivery',
    		]
    	];

        foreach ($partners as $key => $partner) {
        	$partnerModel = LogisticPartner::firstOrNew($partner);
        	$partnerModel->save();
        }
    }
}
