<?php
/**
 * A Basic user models
 * User: radu.mogos
 * Date: 24.10.2010
 * Time: 00:54:35
 */

namespace App\Models\Profiles
{
	class User extends \Sys\Model
	{
		public function __construct($tableName)
		{
			parent::__construct('App\\Models\\Profiles\\Resource\\UserResource');
		}
	}
}