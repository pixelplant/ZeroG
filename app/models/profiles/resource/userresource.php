<?php

namespace App\Models\Profiles\Resource
{
	
	class UserResource extends \Sys\Model\Resource
	{
		public function __construct()
		{
			// link to the xml file with the resource data
			parent::__construct('app/models/profiles/resource/user');
		}
	}
}
