<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
		$this->app->singleton('\WMD\WebMessagesDispatcher', function () {

			$wmd = new \WMD\WebMessagesDispatcher();

			// Add WordsCloud dispatcher
			$wmd->registerModule('\WMD\Dispatchers\WordsCloud');

			// Add BotQ dispatcher
			$wmd->registerModule('\WMD\Dispatchers\BotQ');

    		return $wmd;
    	});
    }

}
