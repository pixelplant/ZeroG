<?php
/**
* Main entrance point to the ZeroG framework
 * 
* @author Radu Mogos <radu.mogos@pixelplant.ro>
* @copyright Radu Mogos, www.pixelplant.ro
* @version 1.0.8
*/

namespace
{
	define('ROOT_DIRECTORY', realpath(dirname(__FILE__)));
	set_include_path(get_include_path() . PATH_SEPARATOR . ROOT_DIRECTORY);

	include "app/global.functions.php";

	//error_reporting(E_ALL);
	//ini_set('display_errors', '1');

	// framework version number
	const ZEROG_VERSION = '1.0.8';

	try
	{
		// possible types: website, website_view
		// $code is the website or website_view code
		$code = isset($_SERVER['ZEROG_RUN_CODE']) ? $_SERVER['ZEROG_RUN_CODE'] : '';
		$type = isset($_SERVER['ZEROG_RUN_TYPE']) ? $_SERVER['ZEROG_RUN_TYPE'] : 'website_view';

		\Z::run($code, $type);

		/*$mail = new \Sys\Mail();
		$mail->setFrom('me@me.com', 'Gringo Deluxe');
		$mail->addRecipient('test@test.com', 'Blabla');
		$mail->addRecipient('test2@test.com', 'Blabla2');
		$mail->addCcRecipient('hey@test.com', 'New man on the block');
		$mail->addRecipient('hey@test.com', 'Vasile Șeicaru');
		$mail->setTextBody('Acesta este doar text, nu-i asa?');
		$mail->setHtmlBody('<p>Un paragraf</p><p>Multe mașinuțe se plimbă peste tot... <b>paragraf</b></p>');
		$mail->setSubject('bunăseara țărăniștilor');
		var_dump($mail);*/

		// test start - remove these lines
		/*$res = new Sys\Model\Resource("profiles/user");
		$res->getField('password')->setValue('fdsafsadfsfg4yb546buy45ubj54buj54ub5jtjgfdsafsadfsfg4yb546buy45ubj54buj54ub5jtjg
			fdsafsadfsfg4yb546buy45ubj54buj54ub5jtjgfdsafsadfsfg4yb546buy45ubj54buj54ub5jtjgfdsafsadfsfg4yb546buy45ubj54buj54ub5jtjg
			dsafsafsfsdfsdfdgdfgdgdgdgdgggggggggggggggggg');
		$res->getField('age')->setValue(13);
		var_dump($res->validateFields());
		var_dump($res);*/
		// test end

		//echo \Z::getProfiler();
		
	}
	catch (\Sys\Exception $e)
	{
		//header('Location: error/index.html');
		echo $e;
	}
}
