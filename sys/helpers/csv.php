<?php

/**
 * The HTML Helper class
 */

namespace Sys\Helpers
{
	class Csv
	{
		/**
		 * Read a CSV file and store it as a PHP array
		 * @param <string> $filename Path to CSV file
		 * @param <string> $delimiter CSV fields delimiter character
		 * @param <string> $field Name of field to fetch, in case you want to fetch just a field from the CSV file
		 * @param <string> $prefix A prefix you could add before each CSV value
		 * @return <array> the csv data imported as a PHP array
		 */
		public static function csvToArray($filename, $delimiter=',', $field='', $prefix = '')
		{
			if(!file_exists($filename) || !is_readable($filename))
				return FALSE;

			$header = NULL;
			$data = array();
			$position = 0;
			if (($handle = fopen($filename, 'r')) !== FALSE)
			{
				while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE)
				{
					if(!$header)
					{
						$header = $row;
						if ($field != '')
						{
							foreach ($row as $key => $value)
							{
								if ($field === $value)
									$position = $key;
							}
						}
					}
					else
					{
						if ($field != '')
						{
							$data[] = $prefix.$row[$position];
						}
						else
						{
							$data[] = array_combine($header, $row);
						}
					}
				}
				fclose($handle);
			}
			return $data;
		}

		
	}
}