<?php

namespace WMD\Dispatchers ;
use Log;

class BotQ implements \WMD\IDispatcher {

	var $wmd ;

	/**
	 * @param \WMD\WebMessagesDispatcher $wmd
	 */
	public function setWebMessagesDispatcher(\WMD\WebMessagesDispatcher $wmd)
	{
		$this->wmd = $wmd ;
	}

	public function message_put( $proxy_at, $from, $body, $srv_addr, $srv_at, $mod_params )
	{
		$channelId = 1 ;
		$priority= 100 ;
		$text = $body ;
		$url = 'http://botq.localhost/api/textMessage/'.$channelId.'/'.$priority.'/'.urlencode($text) ;
		$hc = new \HttpClient();
		$hc->request($url);
	}

}
