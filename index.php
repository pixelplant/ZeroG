<?php
/**
* Main entrance point to the zerog framework
* @author Radu Mogos <radu.mogos@pixelplant.ro>
* @version 0.0.1
*/

namespace 
{
	define('ZEROG_VERSION', '1.0.1');

	spl_autoload_extensions('.php');
	spl_autoload_register();

	// initiate database connection, if a database is used
	Sys\Pdo::getInstance();

	// locale settings
	Sys\ZeroG::getModuleInstance(Sys\ZeroG::LOCALE, \App\Config\System::LOCALE);

	// initialize the framework, and process the controller
	Sys\ZeroG::init();
	Sys\ZeroG::bootstrap();
}
