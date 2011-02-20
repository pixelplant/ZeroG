<?php

namespace Sys\L10n
{
	
	class Locale
	{
		/**
		 * The current locale used in the project, specified in the App\Config\System::LOCALE constant
		 * @var <string> locale, in format language_COUNTRY, eg: ro_RO for romania, fr_FR for france, fr_CH for switzerland, etc...
		 */
		protected $locale = '';

		protected $translations = array();

		protected $currencies = array();

		protected $days = array();

		protected $shortDays = array();

		protected $months = array();

		protected $shortMonths =  array();

		//abstract public function setLocale();

		public function __construct($locale)
		{
			$this->locale = $locale;
			$this->setLocale();
			$this->cacheTranslations();
		}

		public function setLocale()
		{
			$xml = new \SimpleXMLElement('sys/locale/'.$this->locale.'.xml', NULL, TRUE);

			// make sure the xml locale and the app locale settings are the same
			if ($xml->locale->zone != $this->locale)
				throw new \Sys\Exception("Current xml locale file does not match the application's locale settings...");

			// set datetime zome
			date_default_timezone_set($xml->locale->date_default_timezone_set);

			// set currencies...
			$decimals = $xml->numbers->decimals;
			foreach ($xml->currencies->currency as $currency)
			{
				$this->currencies[(string)$currency->name] = new \Sys\L10n\Currency(
						(string)$currency->name,
						(string)$currency->symbol,
						(string)$currency->singleFormat,
						(string)$currency->multiFormat,
						(int)$decimals);
			}

			// set datetime
			// first get days
			foreach ($xml->datetime->days->dayFormat as $days)
			{
				foreach ($days as $day)
				{
					$this->days[(string)$day["type"]] = (string)$day;
					$this->shortDays[(string)$day["type"]] = (string)$day;
				}
			}

			// then get months
			foreach ($xml->datetime->months->monthFormat as $months)
			{
				foreach ($months as $month)
				{
					$this->months[(string)$month["type"]] = (string)$month;
					$this->shortMonths[(string)$month["type"]] = (string)$month;
				}
			}

			// delete $xml var
			unset($xml);
		}

		/**
		 *
		 * @param <string> $symbol the symbol name to return
		 * @return <Sys\I18n\Currency> returns a reference to the Currency class with the name $symbol
		 */
		public function getCurrency($symbol)
		{
			return $this->currencies[$symbol];
		}

		/**
		 *
		 * @return <array> returns an array of Currency classes
		 */
		public function getCurrencies()
		{
			return $this->currencies;
		}

		/**
		 * Gets a date specified in $timestamp, or the current date
		 * and formats it using php's date format specifiers but using the localized
		 * strings...
		 *
		 * @param <string> $format date format, the same as those from the php DATE function
		 * @param <int> $timestamp integer timestamp
		 * @return <string> returns the formated localized date
		 */
		public function getDate($format = 'j.F.Y', $timestamp = NULL)
		{
			if ($timestamp === NULL)
				$timestamp = time();
			$localizedDate = '';
			for ($i = 0; $i < strlen($format); $i++)
			{
				$character = $format[$i];
				$localizedDate .= $this->dateValue($character, $timestamp);
			}
			return $localizedDate;
		}

		public function dateValue($character, $timestamp)
		{
			switch ($character)
			{
				case 'F': return $this->months[date('F', $timestamp)]; break;
				case 'M': return $this->shortMonths[date('M', $timestamp)]; break;
				case 'l': return $this->days[date('l', $timestamp)]; break;
				case 'D': return $this->shortDays[date('D', $timestamp)]; break;
				default:
					if (($character >= 'a' && $character <= 'z') ||
							($character >= 'A' && $character <= 'Z'))
						return date($character, $timestamp);
					break;
			}
			return $character;
		}

		private function cacheTranslations($forceRegenerateCache = FALSE)
		{
			$cacheFile = 'var/cache/locale/'.$this->locale.'.lng';
			$localeDir = 'app/locale/'.$this->locale.'/';
			if (\Z::getConfig('developer/mode') === TRUE)
				$forceRegenerateCache = TRUE;
			// if the cache does not exist, regenerate it...
			if (!file_exists($cacheFile) || $forceRegenerateCache === TRUE)
			{
				if (is_dir($localeDir))
				{
					if ($dh = opendir($localeDir))
					{
						while (($file = readdir($dh)) !== false)
						{
							if (strpos($file, '.csv') > 0)
							{
								$module = substr($file, 0, -4);
								$labels = \Sys\Helper\Csv::csvToArray($localeDir.$file);
								foreach ($labels as $label)
								{
									$this->translations[$module][$label['from']] = $label['to'];
								}
							}
						}
					}
				}
				$f = fopen($cacheFile, "w");
				fwrite($f, serialize($this->translations));
				fclose($f);
			}
			else
			{
				$f = fopen($cacheFile, "r");
				$this->translations = unserialize(fread($f, filesize($cacheFile)));
				//print_r($this->translations);
				fclose($f);
			}
		}

		/**
		 * Returns a translation of the current locale, for a specified module
		 *
		 * @param <string> $label the label to translate
		 * @param <string> $module the module that holds the translation (csv file). If not specified, defaults to the global.csv file
		 * @return <string> the translated label
		 */
		public function __($label, $module = 'global')
		{
			if (isset($this->translations[$module][$label]))
				return $this->translations[$module][$label];
			return $label;
		}
	}
}