<?php
/**
* Main entrance point to the zerog framework
* @author Radu Mogos <radu.mogos@pixelplant.ro>
* @version 1.0.3
*/

namespace 
{
	// disable error reporting
	//error_reporting(0);
	
	spl_autoload_extensions('.php');
	spl_autoload_register();

	// define version number
	const ZEROG_VERSION = '1.0.4';

	// specify our current running app
	//const ZEROG_APP = '\\App\\Config\\System';

	// start global profiler timer
	Sys\ZeroG::getModuleInstance(Sys\ZeroG::PROFILER)->startTimer('timer/global');

	try
	{
		// initiate database connection, if a database is used
		Sys\Database\Pdo::getInstance();

		// initialize the framework
		Sys\ZeroG::init();
		// process the controller
		Sys\ZeroG::bootstrap();

		// test start - remove these lines
		/*$res = new Sys\Model\Resource("profiles/user");
		$res->getField('password')->setValue('fdsafsadfsfg4yb546buy45ubj54buj54ub5jtjgfdsafsadfsfg4yb546buy45ubj54buj54ub5jtjg
			fdsafsadfsfg4yb546buy45ubj54buj54ub5jtjgfdsafsadfsfg4yb546buy45ubj54buj54ub5jtjgfdsafsadfsfg4yb546buy45ubj54buj54ub5jtjg
			dsafsafsfsdfsdfdgdfgdgdgdgdgggggggggggggggggg');
		$res->getField('age')->setValue(13);
		var_dump($res->validateFields());
		var_dump($res);*/
		// test end
	}
	catch (Exception $e)
	{
		echo "Am dat de o exceptie ZeroG: ".$e->getMessage();
	}

	// end global profiler timer
	Sys\ZeroG::getModuleInstance(Sys\ZeroG::PROFILER)->stopTimer('timer/global');
	print_r(Sys\ZeroG::getModuleInstance(Sys\ZeroG::PROFILER)->getStatistics());
}
