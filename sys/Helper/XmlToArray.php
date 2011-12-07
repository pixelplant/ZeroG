<?php
/**
 * XMLToArray Helper class
 * Based on the work of MA Razzaque Rupom <rupom_315@yahoo.com>, http://www.rupom.info
*/

namespace Sys\Helper
{
	class XmlToArray
	{
		/**
		 * Name of the config xml file to load
		 *
		 * @var <string>
		 */
		private $xml;

		/**
		 * Array holding the flat data for this config file
		 * 
		 * @var <array>
		 */
		private $flat;

		/**
		 * Constructor
		 *
		 * @param <string> $xmlFile
		 */
		public function __construct($xmlFile)
		{
			$this->xml = file_get_contents($xmlFile);
		}

		/**
		 * Our recursive building function.
		 * Update: We're now storing the array + the flat data.
		 * Flat data can be retrieved by calling getFlatData()
		 *
		 * @param <array> $values
		 * @param <int> $i
		 * @return <array>
		 */
		private function struct_to_array($values, &$i, $parent = 'config')
		{
			$child = array();
			if (isset($values[$i]['value']))
				array_push($child, $values[$i]['value']);

			while ($i++ < count($values))
				{
					switch ($values[$i]['type'])
					{
						case 'cdata':
							array_push($child, $values[$i]['value']);
							$this->flat[$parent] = $values[$i]['value'];
						break;

						case 'complete':
							$path = $parent.'/'.$values[$i]['tag'];
							$name = $values[$i]['tag'];
							$flatName = $path;
							if(!empty($name))
							{
								$this->flat[$flatName]= isset($values[$i]['value'])?($values[$i]['value']):'';
								$child[$name]= isset($values[$i]['value'])?($values[$i]['value']):'';
								if(isset($values[$i]['attributes']))
								{
									$child[$name] = $values[$i]['attributes'];
								}
							}
						break;

					case 'open':
						$path = $parent.'/'.$values[$i]['tag'];
						$name = $values[$i]['tag'];
						$flatName = $path;
						//$size = isset($child[$name]) ? sizeof($child[$name]) : 0;
						$child[$name] = $this->struct_to_array($values, $i, $flatName);
					break;

					case 'close':
						$path = '';
						return $child;
					break;
					}
				}
			return $child;
		}

		/**
		 * Returns the XML data as an array
		 * @return <array>
		 */
		public function createArray()
		{
			$xml = $this->xml;
			$values = array();
			$index = array();
			$array = array();
			$parser = xml_parser_create();
			xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
			xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
			xml_parse_into_struct($parser, $xml, $values, $index);
			xml_parser_free($parser);
			$i = 0;
			$name = $values[$i]['tag'];
			$array[$name] = isset($values[$i]['attributes']) ? $values[$i]['attributes'] : '';
			$array[$name] = $this->struct_to_array($values, $i);
			return $array;
		}

		/**
		 * Returns the config array as a flat one level array
		 * 
		 * @return <array>
		 */
		public function getFlatData()
		{
			return $this->flat;
		}
	}

}
