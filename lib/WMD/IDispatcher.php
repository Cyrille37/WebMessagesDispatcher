<?php

namespace WMD ;

interface IDispatcher {
	public function setWebMessagesDispatcher(\WMD\WebMessagesDispatcher $wmd);
	public function message_put( $proxy_at, $from, $body, $srv_addr, $srv_at, $mod_params );
}
