<?php
/**
* Main entrance point to the zerog framework
 * 
* @author Radu Mogos <radu.mogos@pixelplant.ro>
* @copyright Radu Mogos, www.pixelplant.ro
* @version 1.0.6
*/

namespace
{
	require_once('sys/zerog.php');
	
	/**
	 * Shortcut to \Sys\ZeroG. You can either use \Sys\ZeroG in your calls, or
	 * just the \Z shortcut
	 */
	final class Z extends \Sys\ZeroG {}

	/**
	* error reporting settings
	*/
	\error_reporting(E_ALL);
	\ini_set('display_errors', '1');

	/**
	* define version number
	*/
	const ZEROG_VERSION = '1.0.6';

	try
	{
		// initialize the framework
		\Z::init();

		// process the controller->action
		\Z::bootstrap();

		// test start - remove these lines
		/*$res = new Sys\Model\Resource("profiles/user");
		$res->getField('password')->setValue('fdsafsadfsfg4yb546buy45ubj54buj54ub5jtjgfdsafsadfsfg4yb546buy45ubj54buj54ub5jtjg
			fdsafsadfsfg4yb546buy45ubj54buj54ub5jtjgfdsafsadfsfg4yb546buy45ubj54buj54ub5jtjgfdsafsadfsfg4yb546buy45ubj54buj54ub5jtjg
			dsafsafsfsdfsdfdgdfgdgdgdgdgggggggggggggggggg');
		$res->getField('age')->setValue(13);
		var_dump($res->validateFields());
		var_dump($res);*/
		// test end

		echo \Z::getProfiler();
		
	}
	catch (\Sys\Exception $e)
	{
		echo $e;
	}
}
