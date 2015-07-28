<?php

namespace WMD\Models;

use \Illuminate\Database\Eloquent\Model;
use \Esensi\Model\Contracts\ValidatingModelInterface;
use \Esensi\Model\Traits\ValidatingModelTrait;
use \Esensi\Model\Traits\SoftDeletingModelTrait;
use \Illuminate\Support\Facades\Validator;
use Prophecy\Argument\Token\IdenticalValueToken;

class Route extends Model implements ValidatingModelInterface
{	
	//use DatePresenter ;

	/**
	 * https://github.com/esensi/model#validating-model-trait
	 */
	use ValidatingModelTrait ;

	/**
	 * @var array
	 */
	protected $rules = [
		'to' => ['required','min:1','max:255'],
		'from' => ['required','min:1','max:255'],
		'srv_name' => ['required','min:1','max:255'],
		'mod_name' => ['required','min:1','max:255']
	];

	/**
	 * Permit mass assignement with those fields.
	 * Avoid Illuminate\Database\Eloquent\MassAssignmentException.
	 *
	 * @var array
	 */
	protected $fillable = [
		'comment',
		'to',
		'from',
		'srv_name',
		'mod_name',
		'mod_params'        		
	];

}
