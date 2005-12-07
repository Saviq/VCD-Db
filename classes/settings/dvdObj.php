<?php
/**
 * VCD-db - a web based VCD/DVD Catalog system
 * Copyright (C) 2003-2004 Konni - konni.com
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or (at
 * your option) any later version.
 * 
 * @author  Hákon Birgsson <konni@konni.com>
 * @package Settings
 * @version $Id$
 */
 
?>
<?
/**
 * No need to store this information in db since this is a part of the DVD standards,
 * which is definitely not going to change any time soon :) 
 *
 */
final class dvdObj {
	/**
	 * list of DVD region codes.
	 *
	 * @var array
	 */
	private $regions = array(
		'0'  => 'Region Free',
		'1'  => 'Bermuda, Canada, United States and U.S. territories.',
		'2'  => 'Japan, Europe, South Africa, and Middle East.',
		'3'  => 'Southeast Asia, Hong Kong, Macau, South Korea and Taiwan.',
		'4'  => 'Central America, Oceania, South America, Mexico',
		'5'  => 'Africa, Russia, India, Mongolia, North Korea.',
		'6'  => 'Mainland China.',
		'7'  => 'Reserved for future use.',
		'8'  => 'Special international routes (airplanes, cruise ships, etc.)'
	);
	
	
	/**
	 * Array of all known DVD Audio formats
	 *
	 * @var array
	 */
	private $audio = array(
		'DTS'  => 'Digital Theater Systems (DTS)',
		'DD2'  => 'Dolby Digital 2.0',
		'DD5'  => 'Dolby Digital 5.1',
		'DDEX' => 'Dolby Digital Surround EX',
		'MONO' => 'Dolby Mono 1.0',
		'SACD' => 'Super Audio CD',
		'COMM' => 'Director Commentary'
	);
	
	
	/**
	 * Array of known DVD Video formats.
	 *
	 * @var array
	 */
	private $video = array(
		'NTSC' => 'NTSC',
		'PAL'  => 'PAL'
	);
	
	/**
	 * Array of known DVD aspect ratios.
	 *
	 * @var array
	 */
	private $aspect = array(
		'FULL' => 'Full screen',
		'WIDE' => 'Wide screen'
	);
	
	/**
	 * Array of all available DVD languages.
	 *
	 * @var array
	 */
	private $languages = array(
		'AB' => 'Abkhazian',
		'AA' => 'Afar',
		'AF' => 'Afrikaans',
		'SQ' => 'Albanian',
		'AM' => 'Amharic, Ameharic',
		'AR' => 'Arabic',
		'HY' => 'Armenian',
		'AS' => 'Assamese',
		'AY' => 'Aymara',
		'AZ' => 'Azerbaijani',
		'BA' => 'Bashkir',
		'EU' => 'Basque',
		'BN' => 'Bengali, Bangla',
		'DZ' => 'Bhutani',
		'BH' => 'Bihari',
		'BI' => 'Bislama',
		'BR' => 'Breton',
		'BG' => 'Bulgarian',
		'MY' => 'Burmese',
		'BE' => 'Byelorussian',
		'KM' => 'Cambodian',
		'CA' => 'Catalan',
		'ZH' => 'Chinese',
		'CO' => 'Corsican',
		'HR' => 'Hrvatski (Croatian)',
		'CS' => 'Czech (Ceske)',
		'DA' => 'Dansk (Danish)',
		'NL' => 'Dutch (Nederlands)',
		'EN' => 'English',
		'EO' => 'Esperanto',
		'ET' => 'Estonian',
		'FO' => 'Faroese',
		'FJ' => 'Fiji',
		'FI' => 'Finnish',
		'FR' => 'French',
		'FY' => 'Frisian',
		'GL' => 'Galician',
		'KA' => 'Georgian',
		'DE' => 'Deutsch (German)',
		'EL' => 'Greek',
		'KL' => 'Greenlandic',
		'GN' => 'Guarani',
		'GU' => 'Gujarati',
		'HA' => 'Hausa',
		'IW' => 'Hebrew',
		'HI' => 'Hindi',
		'HU' => 'Hungarian',
		'IS' => 'Íslenska (Icelandic)',
		'IN' => 'Indonesian',
		'IA' => 'Interlingua',
		'IE' => 'Interlingue',
		'IK' => 'Inupiak',
		'GA' => 'Irish',
		'IT' => 'Italian',
		'JA' => 'Japanese',
		'JW' => 'Javanese',
		'KN' => 'Kannada',
		'KS' => 'Kashmiri',
		'KK' => 'Kazakh',
		'RW' => 'Kinyarwanda',
		'KY' => 'Kirghiz',
		'RN' => 'Kirundi',
		'KO' => 'Korean',
		'KU' => 'Kurdish',
		'LO' => 'Laothian',
		'LA' => 'Latin',
		'LV' => 'Latvian, Lettish',
		'LN' => 'Lingala',
		'LT' => 'Lithuanian',
		'MK' => 'Macedonian',
		'MG' => 'Malagasy',
		'MS' => 'Malay',
		'ML' => 'Malayalam',
		'MT' => 'Maltese',
		'MI' => 'Maori',
		'MR' => 'Marathi',
		'MO' => 'Moldavian',
		'MN' => 'Mongolian',
		'NA' => 'Nauru',
		'NE' => 'Nepali',
		'NO' => 'Norwegian (Norsk)',
		'OC' => 'Occitan',
		'OR' => 'Oriya',
		'OM' => 'Afan (Oromo)',
		'PA' => 'Panjabi',
		'PS' => 'Pashto, Pushto',
		'FA' => 'Persian',
		'PL' => 'Polish',
		'PT' => 'Portuguese',
		'QU' => 'Quechua',
		'RM' => 'Rhaeto-Romance',
		'RO' => 'Romanian',
		'RU' => 'Russian',
		'SM' => 'Samoan',
		'SG' => 'Sangho',
		'SA' => 'Sanskrit',
		'GD' => 'Scots Gaelic',
		'SH' => 'Serbo-Crotain',
		'ST' => 'Sesotho',
		'SR' => 'Serbian',
		'TN' => 'Setswana',
		'SN' => 'Shona',
		'SD' => 'Sindhi',
		'SI' => 'Singhalese',
		'SS' => 'Siswati',
		'SK' => 'Slovak',
		'SL' => 'Slovenian',
		'SO' => 'Somali',
		'ES' => 'Spanish (Espanol)',
		'SU' => 'Sundanese',
		'SW' => 'Swahili',
		'SE' => 'Svenska (Swedish)',
		'TL' => 'Tagalog',
		'TG' => 'Tajik',
		'TT' => 'Tatar',
		'TA' => 'Tamil',
		'TE' => 'Telugu',
		'TH' => 'Thai',
		'BO' => 'Tibetian',
		'TI' => 'Tigrinya',
		'TO' => 'Tonga',
		'TS' => 'Tsonga',
		'TR' => 'Turkish',
		'TK' => 'Turkmen',
		'TW' => 'Twi',
		'UK' => 'Ukranian',
		'UR' => 'Urdu',
		'UZ' => 'Uzbek',
		'VI' => 'Vietnamese',
		'VO' => 'Volapuk',
		'CY' => 'Welsh',
		'WO' => 'Wolof',
		'JI' => 'Yiddish',
		'YO' => 'Yoruba',
		'XH' => 'Xhosa',
		'ZU' => 'Zulu'
	);
	
	
	/**
	 * Default languages that are populated when selecting
	 * subtitles for a new DVD.  Modify at will for your locale.
	 *
	 * @var array
	 */
	private $defaultSubs = array(
		'EN', 'DA', 'NO', 'CZ', 'PL', 'IS', 'ES', 
		'PT', 'HU', 'NL', 'SV', 'DE', 'FR', 'IT', 'FI'
	);
	
	
	
	/**
	 * Get the list of all DVD Region codes
	 *
	 * @return array
	 */
	public function getRegionList() {
		return $this->regions;
	}
	
	/**
	 * Get description of specified region code.
	 * Return false if the requested region code is not found.
	 *
	 * @param int $code
	 * @return string
	 */
	public function getRegion($code) {
		if (isset($this->regions[$code])) {
			return $this->regions[$code];
		}
		return false;
	}
	
	
	/**
	 * Get the entire DVD Languague list.
	 *
	 * @param bool $include_default
	 * @return array
	 */
	public function getLanguageList($include_default = true) {
		if ($include_default) {
			return $this->languages;
		} else {
			return array_diff($this->getLanguageList(true), $this->getDefaultSubtitles());
		}
		
	}
	
	/**
	 * Get langage name based on the language code.
	 * If language code is not found, function returns false.
	 *
	 * @param string $code
	 * @return string
	 */
	public function getLanguage($code) {
		if (isset($this->languages[$code])) {
			return $this->languages[$code];
		}
		return false;
	}
	
	/**
	 * Get the path to the image representing the selected language
	 *
	 * @param string $code | The language code
	 * @return string
	 */
	public function getCountryFlag($code) {
		$code = strtolower($code);
		if (strcmp($code, "cat") == 0) {
			$code = "es_cat";
		}
		
		// Check that the files exists ..
		$unknown = "unknown.png";
		$filename = $code.".png";
		$flagFolder = "images/flags/";
		$fullpath = $flagFolder.$filename;
		
		if (file_exists($fullpath)) {
			return $fullpath;
		} else {
			return $flagFolder.$unknown;
		}
		
	}
	
	
	/**
	 * Get all known DVD audio formats.
	 *
	 * @return array
	 */
	public function getAudioList() {
		return $this->audio;
	}
	
	/**
	 * Get Audio by audio code.
	 *
	 * @param string $code
	 * @return string
	 */
	public function getAudio($code) {
		if (isset($this->audio[$code])) {
			return $this->audio[$code];
		} 
		return false;
	}
	
	/**
	 * Get list of known video formats.  
	 * NTSC or PAL
	 *
	 * @return array
	 */
	public function getVideoFormats() {
		return $this->video;
	}
	
	/**
	 * Return list of all known DVD aspect ratios.
	 *
	 * @return array
	 */
	public function getAspectRatios() {
		return $this->aspect;		
	}
	
	/**
	 * Get the Aspect Ratio by aspect code
	 *
	 * @param string $code
	 * @return string
	 */
	public function getAspectRatio($code) {
		if (isset($this->aspect[$code])) {
			return $this->aspect[$code];
		} else {
			return false;
		}
	}
	
	
	
	/**
	 * Get list of default subtitles to display when entering DVD
	 * subtitles to a movie.  Returns sorted array of specified languages.
	 *
	 * @return array
	 */
	public function getDefaultSubtitles() {
		$arrSubtitles = array();
		foreach ($this->defaultSubs as $sub) {
			$lang = $this->getLanguage($sub);
			if ($lang) {
				$arrSubtitles[$sub] = $lang;
			}
		}
		
		ksort($arrSubtitles, SORT_LOCALE_STRING);
		return $arrSubtitles;
	}
	
	
	
	public function getArrayDiff(&$arrAll, &$arrItems, $dataType) {
		if (!is_array($arrItems)) {
			return $arrAll;
		}
		
		switch ($dataType) {
			case 'audio':
				$arrDiff = array();
						
			
				break;
		
			default:
				return $arrAll;
				break;
		}
		
		
	}
	
	
}


?>
