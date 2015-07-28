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
		Log::debug( var_export($input,true) );

		$wmd->message_put($input);
		return response()->json(array('status'=>'OK'));
	}

}
