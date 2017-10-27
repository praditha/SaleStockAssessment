<?php

use Illuminate\Database\Seeder;
use App\Models\Coupon;

class CouponsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $coupons = [
        	[
        		'name'			=> 'Diskon Gajian',
        		'description'	=> 'Potongan harga sebesar 10% dari total pembelian',
        		'amount'		=> 10,
        		'type'			=> 'percentage',
        		'valid_from'	=> date('Y-m-d'),
        		'valid_to'		=> date('Y-m-d', strtotime(date('Y-m-d') . " +5 day")),
        		'quantity'		=> 10
        	],
        	[
        		'name'			=> 'Diskon Spesial',
        		'description'	=> 'Potongan harga sebesar Rp. 50.000 dari total pembelian',
        		'amount'		=> 50000,
        		'type'			=> 'nominal',
        		'valid_from'	=> date('Y-m-d'),
        		'valid_to'		=> date('Y-m-d', strtotime(date('Y-m-d') . " +7 day")),
        		'quantity'		=> 30
        	]
        ];

        foreach ($coupons as $key => $coupon) {
        	$couponModel = Coupon::firstOrNew($coupon);
        	$couponModel->save();
        }
    }
}
