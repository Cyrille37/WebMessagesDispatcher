<?php

namespace WMD\Dispatchers ;
use Log;
use DB;

class WordsCloud implements \WMD\IDispatcher {

	const SQL_TABLE_WORDS = 'swswall_words' ;

	var $wmd ;

	/**
	 * @param \WMD\WebMessagesDispatcher $wmd
	 */
	public function setWebMessagesDispatcher(\WMD\WebMessagesDispatcher $wmd)
	{
		$this->wmd = $wmd ;
	}

	/**
	 * TODO : comment installer les tables de données des dispatchers ? Migration ou autre système ?
	 * @throws \Exception
	 */
	public function install()
	{

		throw new \Exception('ERROR: '.__METHOD__.' NOT YET IMPLEMENTED !');

		$sql = 'DROP TABLE IF EXISTS `'.self::SQL_TABLE_WORDS .'`  ;
		CREATE TABLE `'.self::SQL_TABLE_WORDS.'` (
				`wrd_word` varchar(32) NOT NULL,
				`wrd_count` int(11) NOT NULL DEFAULT "1",
				`wrd_updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
				`wrd_id` int(11) NOT NULL AUTO_INCREMENT,
				PRIMARY KEY (`wrd_id`),
				KEY `idx_words_timestamp` (`wrd_updated_at`)
				) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;
				';
		$ok = $this->wmd->db()->exec ( $sql );
		if( $ok !== 0 )
			throw new \Exception('DB install "'.self::SQL_TABLE_WORDS.'" failed');
	}

	public function message_put( $rcvTime, $from, $body, $srvAddr, $srvTime, $mod_params )
	{
		$this->addWords($body);
	}

	public static $smileys = array (
			':-)-~' => 'cigarette',
			'0:-)' => 'innocent' ,
			':’(' => 'une larme',
			':-(' => 'déçu',
			':-)' => 'sourire',
			':-*' => 'bisou',
			':-D' => 'mort de rire',
			':-I' => 'indifférence',
			':-o' => 'oh!',
			':-P' => 'tirer la langue',
			':-x' => 'aucun commentaire',
			':-X' => 'bisou',
			';-)' => 'clin d’oeil',
			';->' => 'coquin',
			':*' => 'bisou',
	);

	//public static $dummychars = array('’','\'','(',')','[',']');
	//public static $delimiters = array(',','.',';',':');

	public static $stopWords = array('avec','une','les','des','ses','cette','dans','sur',
			'que','pas','qui','est','par','leur','leurs','même','alors','parce');

	/**
	 * Extract words from message then put them into da db table.
	 */
	public function addWords($text) {

		// search for smileys, replace them by the corresponding word.
		$text = str_replace ( array_keys ( self::$smileys ), array_values ( self::$smileys ), $text );

		// search for dummies chars, remove them.
		$text = preg_replace( '/[^a-zéèçàù]/i', ' ', $text );

		// search for smileys, replace them by the corresponding word.
		$text = str_replace ( array_keys ( self::$smileys ), array_values ( self::$smileys ), $text );

		// slipt words
		$textparts = explode ( ' ', $text );

		// insert each words into db
		foreach($textparts as $word) {
			// only words >= 3
			if( mb_strlen($word,'UTF-8') <3)
				continue;
			// do not store stop words
			if( in_array( strtolower($word), self::$stopWords) )
				continue;

			//$sth = DB::prepare ('INSERT INTO '. self::SQL_TABLE_WORDS . ' (wrd_word) values (?)');
			//$ok = $sth->execute ( array ( $word ) );
			$ok = DB::insert('INSERT INTO '. self::SQL_TABLE_WORDS . ' (wrd_word) values (?)', array ( $word ));
			if( ! $ok)
				throw new \Exception( __CLASS__.' Failed to add word');
		}
	}

}
