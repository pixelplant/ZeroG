<?php
/**
* Main entrance point to the zerog framework
* @author Radu Mogos <radu.mogos@pixelplant.ro>
* @version 0.0.1
*/

namespace 
{
	define('ZEROG_VERSION', '1.0.2');

	spl_autoload_extensions('.php');
	spl_autoload_register();

	try
	{
		// initiate database connection, if a database is used
		Sys\Database\Pdo::getInstance();

		// locale settings
		Sys\ZeroG::getModuleInstance(Sys\ZeroG::LOCALE, \App\Config\System::LOCALE);

		// initialize the framework, and process the controller
		Sys\ZeroG::init();
		Sys\ZeroG::bootstrap();

		// test start - remove these lines
		$res = new Sys\Model\Resource("profiles/user");
		$res->getField('password')->setValue('fdsafsadfsfg4yb546buy45ubj54buj54ub5jtjgfdsafsadfsfg4yb546buy45ubj54buj54ub5jtjg
			fdsafsadfsfg4yb546buy45ubj54buj54ub5jtjgfdsafsadfsfg4yb546buy45ubj54buj54ub5jtjgfdsafsadfsfg4yb546buy45ubj54buj54ub5jtjg
			dsafsafsfsdfsdfdgdfgdgdgdgdgggggggggggggggggg');
		$res->getField('age')->setValue(13);
		var_dump($res->validateFields());
		var_dump($res);
		// test end
	}
	catch (Exception $e)
	{
		echo "Am dat de o exceptie ZeroG: ".$e->getMessage();
	}
}
