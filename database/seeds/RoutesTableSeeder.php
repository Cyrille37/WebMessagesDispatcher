<?php

use \Illuminate\Database\Seeder;
use \Monolog\Handler\error_log;

class RoutesTableSeeder extends Seeder
{

	public function run()
	{
		DB::table('routes')->delete();

        $route = \App\Models\Route::create([
            'comment' => 'Tous les SMS sont envoyés à WordsCloud',
            'to' => '*',
            'from' => '*',
        	'srv_name' => 'sms',
        	'mod_name' => '\WMD\Dispatchers\WordsCloud',
			'mod_params' => ''        		
        ]);
        $route->save();

        if( empty($route->id) ) {
            error_log('ERROR: '.var_export($route->getErrors(), true));
        }
	}

}
