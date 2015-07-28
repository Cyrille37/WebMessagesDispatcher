<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Log;

class ApiController extends BaseController
{
	public function ping()
	{
		return response()->json(array('status'=>'OK'));
	}

	public function stats(/*\Laravel\Lumen\Application $app,*/ \WMD\WebMessagesDispatcher $wmd)
	{
        $stats = array(
            'messagesCount' => 0,
            'routesCount' => $wmd->getRouter()->getRoutesCount()
        );
        return response()->json($stats);
	}

	public function message_put(Request $request, \WMD\WebMessagesDispatcher $wmd)
	{
		$input = $request->all();
		//Log::debug( var_export($input,true) );

		$wmd->message_put($input);
		return response()->json(array('status'=>'OK'));
	}

	public function routeTest(Request $request, \WMD\WebMessagesDispatcher $wmd)
	{
		$input = $request->all();
		list($ok,$mod_params) = $wmd->getRouter()->is_module_dispatch($input['srv_name'],$input['mod_name'], $input['to'], $input['from']);
		return response()->json(
			$ok
		);
	}

	public function routeModulesGet(\WMD\WebMessagesDispatcher $wmd)
	{
		return response()->json(
			$wmd->getRouteModules()
		);
	}

	public function routeServicesGet(\WMD\WebMessagesDispatcher $wmd)
	{
		return response()->json(
			$wmd->getRouteServices()
		);
	}

	/**
	 * Routes GET : return all routes
	 * @param \WMD\WebMessagesDispatcher $wmd
	 */
	public function routesGet(\WMD\WebMessagesDispatcher $wmd)
	{
		return response()->json(
			$wmd->getRouter()->getRoutes()
		);
	}

	/**
	 * Route update
	 * @param Request $request
	 * @param \WMD\WebMessagesDispatcher $wmd
	 */
	public function routesPut(Request $request, \WMD\WebMessagesDispatcher $wmd)
	{
		return response()->json(
			$wmd->getRouter()->putRoute( $request->all() )
		);
	}

	/**
	 * @param Request $request
	 * @param \WMD\WebMessagesDispatcher $wmd
	 */
	public function routesPost(Request $request, \WMD\WebMessagesDispatcher $wmd)
	{
		return response()->json(
			$wmd->getRouter()->postRoute( $request->all() )
		);
	}

	public function routesDelete(Request $request, \WMD\WebMessagesDispatcher $wmd)
	{
		return response()->json(
			$wmd->getRouter()->deleteRoute( $request->all() )
		);
	}

}
