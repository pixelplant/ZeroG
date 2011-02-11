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

		protected $timers = array();

		public function  __construct() {}

		/**
		 * Start the timer
		 * @param <string> $timerName Name of the timer
		 */
		public function startTimer($timerName)
		{
			// get start time
			$this->timers[$timerName][0] = microtime(TRUE);
			// and memory used so far
			$this->timers[$timerName][2] = memory_get_usage();
		}

		/**
		 * Stop the timer
		 * @param <string> $timerName Name of the timer
		 */
		public function stopTimer($timerName)
		{
			// get stop time
			$this->timers[$timerName][1] = microtime(TRUE);
			// and the current memory we have now
			$this->timers[$timerName][3] = memory_get_usage();
		}

		/**
		 * Returns the statistics from all the timers, regarding execution time and memory used
		 * Please note that the predefined timer "timer/global" shows the total execution and memory usage
		 * of the script.
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
		 * Converts a byte value to a more apropriate memory unit
		 * @param <int> $size The size of memory to convert, in bytes
		 * @return <string> The formated size, with the unit appended
		 */
		private function convert($size)
		{
			$unit = array('BYTES', 'KB', 'MB', 'GB', 'TB', 'PB');
			return @round($size / pow(1024 ,($i = floor(log($size, 1024)))), 2).' '.$unit[$i].' ('.$size.' B)';
		}

	}
}
