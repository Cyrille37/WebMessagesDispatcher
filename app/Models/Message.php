<?php

namespace App\Models;

use \Illuminate\Database\Eloquent\Model;
use \Esensi\Model\Contracts\ValidatingModelInterface;
use \Esensi\Model\Traits\ValidatingModelTrait;
use \Esensi\Model\Traits\SoftDeletingModelTrait;
use \Illuminate\Support\Facades\Validator;
use Prophecy\Argument\Token\IdenticalValueToken;

use Carbon\Carbon;
use Log ;

class Message extends Model implements ValidatingModelInterface
{
	/**
	 * @property id
	 * @property from
	 * @property to
	 * @property body
	 * @property proxy_at
	 * @property srv_name
	 * @property srv_addr
	 * @property srv_at
	 */

	/**
	 *
	 */
	use DatePresenter ;

	/**
	 * https://github.com/esensi/model#validating-model-trait
	 */
	use ValidatingModelTrait ;

	/**
	 * @var array
	 */
	protected $rules = [
			'from' => ['required','min:1','max:255'],
			'to' => ['required','min:1','max:255'],
			'proxy_at' => ['required','min:1','max:255'],
			'srv_name' => ['required','min:1','max:255'],
			'srv_addr' => ['required','min:1','max:255'],
			'srv_at' => ['required','min:1','max:255']
	];
	
	/**
	 * Permit mass assignement with those fields.
	 * Avoid Illuminate\Database\Eloquent\MassAssignmentException.
	 *
	 * @var array
	 */
	protected $fillable = [
			'from',
			'to',
			'body',
			'proxy_at',
			'srv_name',
			'srv_addr',
			'srv_at'
	];

	/**
	 * Trap some Model events
	 * @see http://laravel.com/docs/5.1/eloquent#events
	 */
	protected static function boot()
	{
		parent::boot();

		// Model event : creating
		Message::creating(function ($msg) {

			// "SMS" datetime are big timestamp,
			// divide by 1000 then format as DataTime

			if( is_numeric($msg->proxy_at) )
			{
				if( $msg->proxy_at > 9999999999 )
					$msg->proxy_at /= 1000 ;
				$msg->proxy_at = Carbon::createFromTimeStamp( $msg->proxy_at ) ;
			}
			if( is_numeric($msg->srv_at) )
			{
				if( $msg->srv_at > 9999999999 )
					$msg->srv_at /= 1000 ;
					$msg->srv_at = Carbon::createFromTimeStamp( $msg->srv_at ) ;
			}

		});
	}

}
