<?php
/**
 * Debug block
 *
 * @author radu.mogos
 */
namespace App\Code\Local\Pixelplant\Debug\Block
{

	class Logger extends \Sys\Layout\Block {

		public function getLoggerData()
		{
			return \Z::getSingleton('debug/logger')->getLogData();
		}
	}
}
