<?php

namespace Search_Filter_Pro\Vendor\Wamania\Snowball;

use Search_Filter_Pro\Vendor\voku\helper\UTF8;
use Search_Filter_Pro\Vendor\Wamania\Snowball\Stemmer\Catalan;
use Search_Filter_Pro\Vendor\Wamania\Snowball\Stemmer\Danish;
use Search_Filter_Pro\Vendor\Wamania\Snowball\Stemmer\Dutch;
use Search_Filter_Pro\Vendor\Wamania\Snowball\Stemmer\English;
use Search_Filter_Pro\Vendor\Wamania\Snowball\Stemmer\Finnish;
use Search_Filter_Pro\Vendor\Wamania\Snowball\Stemmer\French;
use Search_Filter_Pro\Vendor\Wamania\Snowball\Stemmer\German;
use Search_Filter_Pro\Vendor\Wamania\Snowball\Stemmer\Italian;
use Search_Filter_Pro\Vendor\Wamania\Snowball\Stemmer\Norwegian;
use Search_Filter_Pro\Vendor\Wamania\Snowball\Stemmer\Portuguese;
use Search_Filter_Pro\Vendor\Wamania\Snowball\Stemmer\Romanian;
use Search_Filter_Pro\Vendor\Wamania\Snowball\Stemmer\Russian;
use Search_Filter_Pro\Vendor\Wamania\Snowball\Stemmer\Spanish;
use Search_Filter_Pro\Vendor\Wamania\Snowball\Stemmer\Stemmer;
use Search_Filter_Pro\Vendor\Wamania\Snowball\Stemmer\Swedish;
class StemmerFactory {

	const LANGS = array(
		Catalan::class    => array( 'ca', 'cat', 'catalan' ),
		Danish::class     => array( 'da', 'dan', 'danish' ),
		Dutch::class      => array( 'nl', 'dut', 'nld', 'dutch' ),
		English::class    => array( 'en', 'eng', 'english' ),
		Finnish::class    => array( 'fi', 'fin', 'finnish' ),
		French::class     => array( 'fr', 'fre', 'fra', 'french' ),
		German::class     => array( 'de', 'deu', 'ger', 'german' ),
		Italian::class    => array( 'it', 'ita', 'italian' ),
		Norwegian::class  => array( 'no', 'nor', 'norwegian' ),
		Portuguese::class => array( 'pt', 'por', 'portuguese' ),
		Romanian::class   => array( 'ro', 'rum', 'ron', 'romanian' ),
		Russian::class    => array( 'ru', 'rus', 'russian' ),
		Spanish::class    => array( 'es', 'spa', 'spanish' ),
		Swedish::class    => array( 'sv', 'swe', 'swedish' ),
	);
	/**
	 * @throws NotFoundException
	 */
	public static function create( string $code ): Stemmer {
		$code = UTF8::strtolower( $code );
		foreach ( self::LANGS as $classname => $isoCodes ) {
			if ( in_array( $code, $isoCodes ) ) {
				return new $classname();
			}
		}
		throw new NotFoundException( sprintf( 'Stemmer not found for %s', $code ) );
	}
}
