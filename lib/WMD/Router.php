<?php

namespace WMD ;

use Log;

class Router {

	public function __construct(){
		
	}

	public function getRoutes()
	{
		return \App\Models\Route::all();
	}

	public function getRoutesCount()
	{
		return \App\Models\Route::all()->count();
	}

	public function is_module_dispatch($srvName, $modName, $to, $from)
	{
		$routes = \App\Models\Route::all()->where('srv_name', $srvName)->where('mod_name',$modName);

		// Parse results several times to organize priority
		// 1. FROM && TO
		// 2. TO && *
		// 3. FROM && *
		// 4. * && *
	
		// match FROM && TO
		foreach( $routes as $route )
		{
			if( $route->from == $from && $route->to == $to )
				return array( true, $route->mod_params );
		}
		// match TO && *
		foreach( $routes as $route )
		{
			if( $route->to == $to && $route->from == '*' )
				return array( true, $route->mod_params );
		}
		// match FROM && *
		foreach( $routes as $route )
		{
			if( $route->from == $from && $route->to == '*' )
				return array( true, $route->mod_params );
		}
		// match * && *
		foreach( $routes as $route )
		{
			if( $route->from == '*' && $route->to == '*' )
				return array( true, $route->mod_params );
		}
		return array( false, null) ;
	}

}