<?php

/**
* A small list of global functions for ZeroG
*
* @author Radu Mogos <radu.mogos@pixelplant.ro>
* @copyright Radu Mogos, www.pixelplant.ro
*/

/**
 * Define our own error_handler
 *
 * @param <int> $errno Error number
 * @param <string> $errstr Error message
 * @param <string> $errfile Error file
 * @param <int> $errline Error line number
 */
function error_handler($errno, $errstr, $errfile, $errline)
{
	$error = sprintf('<p>%s - %s : %s @ %s</p>', $errno, $errstr, $errfile, $errline);
	throw
		new \Sys\Exception($error);
}

/**
 * Define our custom shutdown procedure
 */
function shutdown_function()
{
	$lastError = error_get_last();

	if ($lastError['type'] == E_ERROR || $lastError['type'] == E_USER_ERROR)
	{
		$error = 'Fatal error in file: '.$lastError['file'];
		die($error);
	}
}

/**
 * There is a bug with the default namespaces separator on *nix systems
 * so we need to define our own autoloader
 *
 * @param <string> $class
 */
function linux_spl_autoload($class)
{
	$class = str_replace('\\', DIRECTORY_SEPARATOR, $class).'.php';
	include ($class);
}

set_error_handler('error_handler');
register_shutdown_function('shutdown_function');

/*
* Check to see wether we're in a Windows or *nix system
*/
if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
{
	\spl_autoload_extensions('.php');
	\spl_autoload_register();
}
else
	\spl_autoload_register(linux_spl_autoload);
