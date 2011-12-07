<?php

/**
 * ZeroG Profiler class
 *
 * @author radu.mogos
 */
namespace Sys
{
	class Profiler
	{

		/**
		 * Memory and CPU timers
		 * @var <array>
		 */
		protected $timers = array();

		/**
		 * Constructor
		 */
		public function  __construct()
		{
			/** set the xdebug profiler, if you use xdebug
			 * you can easily analize the profiler log with	webgrind
			 *  https://github.com/jokkedk/webgrind
			 */
			//\ini_set('xdebug.profiler_enable', 1);
			//\ini_set('xdebug.profiler_output_dir', 'd:/');
			//\ini_set('xdebug.profiler_output_name', 'cachegrind.out.%t.%p');
		}

		/**
		 * Start the timer
		 *
		 * @param <string> $timerName Name of the timer
		 */
		public function start($timerName)
		{
			// get start time
			$this->timers[$timerName][0] = microtime(TRUE);
			//$this->timers[$timerName][0] = xdebug_time_index();
			// and memory used so far
			$this->timers[$timerName][2] = memory_get_usage();
			//$this->timers[$timerName][2] = xdebug_memory_usage();
		}

		/**
		 * Stop the timer
		 *
		 * @param <string> $timerName Name of the timer
		 */
		public function stop($timerName)
		{
			// get stop time
			$this->timers[$timerName][1] = microtime(TRUE);
			//$this->timers[$timerName][1] = xdebug_time_index();
			// and the current memory we have now
			$this->timers[$timerName][3] = memory_get_usage();
			//$this->timers[$timerName][3] = xdebug_memory_usage();
		}

		/**
		 * Converts a byte value to a more apropriate memory unit
		 *
		 * @param <int> $size The size of memory to convert, in bytes
		 * @return <string> The formated size, with the unit appended
		 */
		private function convert($size)
		{
			$unit = array('BYTES', 'KB', 'MB', 'GB', 'TB', 'PB');
			return @round($size / pow(1024 ,($i = floor(log($size, 1024)))), 2).' '.$unit[$i].' ('.$size.' B)';
		}

		/**
		 * Returns the statistics from all the timers, regarding execution time and memory used
		 * Please note that the predefined timer "timer/global" shows the total execution and memory usage
		 * of the script.
		 *
		 * @return <type>
		 */
		public function getStatistics()
		{
			$usage = array();
			foreach ($this->timers as $timerName => $timerValue)
			{
				$usage[$timerName]['cpu'] = ($timerValue[1] - $timerValue[0]).' secunde';
				$usage[$timerName]['memory'] = $this->convert($timerValue[3] - $timerValue[2]);
			}
			return $usage;
		}

		/**
		 * Simple debug of the profiler data
		 * 
		 * @return <string>
		 */
		public function __toString()
		{
			$statistics = $this->getStatistics();
			$html = '';
			foreach ($statistics as $name => $data)
			{
				$html .=sprintf('<h3>%s</h3><ul><li>cpu: %s</li><li>memory: %s</li></ul>', $name, $data['cpu'], $data['memory']);
			}
			return $html;
		}

	}
}
