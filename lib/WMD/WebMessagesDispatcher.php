<?php

namespace WMD ;

use Log;

class WebMessagesDispatcher {

	/**
	 * @var \WMD\IDispatcher[]
	 */
	protected $dispatchers ;

	/**
	 * @var \WMD\Router
	 */
	protected $router ;

	public function __construct()
	{
		$this->router = new \WMD\Router();
		$this->dispatchers = array();
	}

	public function registerModule( $dispatcherClass )
	{
		$disp = new $dispatcherClass();
		$disp->setWebMessagesDispatcher($this);
		$this->dispatchers[$dispatcherClass] = $disp ;
	}

	public function message_put(array $data)
	{
		// 1. Store the message

		/**
		 * @var \WMD\Models\Message
		 */
		$msg = \WMD\Models\Message::create( $data );
		$ok = $msg->save();
		if( ! $ok){
			throw new \Exception('Failed to add message');
		}

		// 2. Dispatch the message to registred modules

		foreach( $this->dispatchers as $modName => $module )
		{
			Log::debug(__METHOD__.' Dispatch test srvName:"'.$msg->srv_name.'", modName:"'.$modName.'"');
			list($ok,$mod_params) = $this->router->is_module_dispatch($msg->srv_name, $modName, $msg->to, $msg->from );
			if( $ok === true )
			{
				Log::debug(__METHOD__.' ... Dispatching to:"'.$msg->to.'", from:"'.$msg->from.'", params:"'.$mod_params.'"');
				$module->message_put( $msg->proxy_at, $msg->from, $msg->body, $msg->srv_addr, $msg->srv_at, $mod_params );
			}
		}

	}

	public function getRouteServices() {
		return array(
			array( 'id'=>'sms', 'label'=>'SMS' )
		);
	}

	public function getRouteModules() {
		
		$routeModules = array();
		foreach( $this->dispatchers as $modName => $module )
		{
			$routeModules[] = array(
				'id' => $modName, 'label' => $modName
			);
		}
		return $routeModules ;
	}

	public function getRouter()
	{
		return $this->router;
	}

}