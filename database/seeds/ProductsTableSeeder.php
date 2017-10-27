<?php

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = [
    		[
    			'name'		=> 'Baju Tidur',
				'price'		=> 70000,
				'quantity'	=> 100,
    		],
    		[
    			'name'		=> 'Kemeja Kantor',
				'price'		=> 95000,
				'quantity'	=> 100,
    		],
    		[
    			'name'		=> 'Sepatu Nyala',
				'price'		=> 100000,
				'quantity'	=> 100,
    		],
    		[
    			'name'		=> 'Dasi Kupu-Kupu',
				'price'		=> 30000,
				'quantity'	=> 100,
    		]
    	];

        foreach ($products as $key => $product) {
        	$productModel = Product::firstOrNew($product);
        	$productModel->save();
        }
    }
}
