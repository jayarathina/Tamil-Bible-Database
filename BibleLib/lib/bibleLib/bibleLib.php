<?php
class bibleLib {

	protected $database;

	function __construct($db_ = null) {
		if (is_null ( $db_ )) {
			try { // Meedo http://medoo.in
				$this->database = new medoo ( array (
						'database_type' => 'mysql',
						'database_name' => DB_NAME,
						'server' => DB_HOST,
						'username' => DB_USER,
						'password' => DB_PASSWORD,
						'charset' => 'utf8' 
				) );
			} catch ( Exception $e ) {
				if (DEBUG_APP) {
					echo 'Caught exception: ', $e->getMessage (), '<br/>';
				}
				die ( BLIB_ERR_MSG );
			}
		} else {
			$this->database = $db_;
		}
	}

	/**
	 * Returns formated HTML code for the given chapetr in the given book.
	 *
	 * @param int $bk
	 *        	- Book Number
	 * @param string|int $ch
	 *        	- Chapter number
	 * @return string HTML text of the chapter
	 */
	function getChapterHTML($bk, $ch = 'i') {
		$vd = $this->convertBkCh2Code ( $bk, $ch );
		
		$datas = '';
		if ($ch === 'i') { // Introduction
			$intro = $this->database->select ( BLIB_INDEX, array (
					"intro" 
			), array (
					"bn" => $bk 
			) );
			return (isset ( $intro [0] ['intro'] )) ? $intro [0] ['intro'] : '';
		} else {
			
			$chap = $this->getFormatedVerses ( $vd );
			
			// Mat 4:4 - If double quotes left hanging alone outside indended then place it inside;
			// Dont change this directly in DB, you will get problem in red lettering
			$this->SwapConsecutiveCharacters ( '“', BLIB_INDENT_START, $chap );
			$this->SwapConsecutiveCharacters ( BLIB_INDENT_END, '”', $chap );
			$this->SwapConsecutiveCharacters ( '“', BLIB_OUTDENT_START, $chap );
			$this->SwapConsecutiveCharacters ( BLIB_OUTDENT_END, '”', $chap );
			
			// Needed for 51001078
			$this->SwapConsecutiveCharacters ( BLIB_POEM1_END, '”', $chap );
			$this->SwapConsecutiveCharacters ( BLIB_POEM2_END, '”', $chap );
			
			/* Trying to merge multiple red letter tags */
			$this->SwapConsecutiveCharacters ( BLIB_POEM1_START, BLIB_RED_LTR_START, $chap );
			$this->SwapConsecutiveCharacters ( BLIB_RED_LTR_END, BLIB_POEM1_END, $chap );
			$this->SwapConsecutiveCharacters ( BLIB_POEM2_START, BLIB_RED_LTR_START, $chap );
			$this->SwapConsecutiveCharacters ( BLIB_RED_LTR_END, BLIB_POEM2_END, $chap );
			
			$this->moveBeforeVerseNumber ( $chap, array (
					BLIB_INDENT_START,
					BLIB_OUTDENT_START,
					BLIB_POEM1_START,
					BLIB_POEM2_START,
					BLIB_RED_LTR_START . BLIB_POEM1_START, // 49015009 - If untill this point if red letter is not merged, then it will never be, so undo some changes;
					BLIB_RED_LTR_START . BLIB_POEM2_START,
					BLIB_RED_LTR_START . BLIB_INDENT_START 
			) );
			
			// Needed for fixing multiple verse block quote 49019005- Not needed anymore. But probably will be needed aftwerwards
			// $chap = str_replace ( BLIB_INDENT_END . BLIB_RED_LTR_END . BLIB_VRS_END . BLIB_VRS_START . BLIB_RED_LTR_START . BLIB_INDENT_START, '', $chap );
			// $chap = str_replace ( BLIB_INDENT_END . BLIB_VRS_END . BLIB_VRS_START . BLIB_INDENT_START, '', $chap );
			
			// Tag Replacements
			$pattern = '(' . BLIB_H2_START . '[^\(]+)(\([^\)]+\))([^\)]*' . BLIB_H2_END . ')';
			$chap = preg_replace ( "/$pattern/um", '$1<small>$2</small>$3', $chap ); // Add small tag to brackets
			
			$replaceVals = array (
					BLIB_BREAK_PT => '<br/>',
					BLIB_H1_START => '<h2>',
					BLIB_H1_END => '</h2>',
					BLIB_H2_START => '<h3>',
					BLIB_H2_END => '</h3>',
					
					BLIB_VERSE_NUMBER_START => "<span class='verseNum'>",
					BLIB_VERSE_NUMBER_END => "</span>",
					
					BLIB_VRS_START => " ", // " <div class='verse'>",
					BLIB_VRS_END => "", // "</div>",
					
					BLIB_RED_LTR_START => "<div class='redLetter'>",
					BLIB_RED_LTR_END => "</div>",
					
					BLIB_P_START => "<div class='para'>",
					BLIB_P_END => "</div>",
					
					BLIB_INDENT_START => "<div class='indent'>",
					BLIB_INDENT_END => "</div>",
					
					BLIB_OUTDENT_START => "<span class='outdent'>",
					BLIB_OUTDENT_END => "</span>",
					
					BLIB_POEM1_START => "<div class='poem poem1'><p>",
					BLIB_POEM1_END => "</p></div>",
					BLIB_POEM2_START => "<div class='poem poem2'><p>",
					BLIB_POEM2_END => "</p></div>",
					
					BLIB_POEM_BREAK => "</p><p>",
					
					BLIB_FOOTNOTE_START => "<span class='footnote'>",
					BLIB_FOOTNOTE_END => "</span> ",
					
					BLIB_CROSSREF_START => "<span class='crossref'>",
					BLIB_CROSSREF_END => "</span> " 
			);
			$chap = str_replace ( array_keys ( $replaceVals ), $replaceVals, $chap );
			return $chap;
		}
	}

	/**
	 * Returns certain internal tags before verse number so that they can be merged, if possible.
	 *
	 * @param string $chap        	
	 * @param string[] $replaceArray        	
	 */
	private function moveBeforeVerseNumber(&$chap, $replaceArray) {
		$vrsNumPattern = BLIB_VRS_START . '(' . BLIB_VERSE_NUMBER_START . '\d+[\-\d]*' . BLIB_VERSE_NUMBER_END . ')';
		
		// Move red letter to outermost end.
		$pattern = $vrsNumPattern . BLIB_RED_LTR_START . '([^' . BLIB_RED_LTR_END . ']*)' . BLIB_RED_LTR_END . BLIB_VRS_END;
		$chap = preg_replace ( "/$pattern/um", BLIB_RED_LTR_START . BLIB_VRS_START . '$1$2' . BLIB_VRS_END . BLIB_RED_LTR_END, $chap ); // Try to move redlettring above verse so that it can be coupled
		$chap = str_replace ( BLIB_RED_LTR_END . BLIB_RED_LTR_START, '', $chap );
		
		// Then Proceed
		foreach ( $replaceArray as $value ) {
			$pattern = $vrsNumPattern . $value;
			$chap = preg_replace ( "/$pattern/um", $value . BLIB_VRS_START . '$1', $chap );
		}
	}

	/**
	 * Returns formated verses after retriving it from database
	 *
	 * @param string $vd
	 *        	- Properly formated/padded verse code;
	 * @return string - Formatted verses
	 */
	private function getFormatedVerses($vd) {
		$ret = BLIB_P_START; // Return Value
		
		$vers = $this->database->select ( BLIB_VIEW, '*', array (
				'verse_id[~]' => $vd . '%',
					'ORDER' => array (
						"verse_id" => "ASC",
						"type" => "DESC"
				)
		) );
		
		$inst = array ();
		if (BLIB_RED_LTR) {
			$inst = $this->database->select ( BLIB_REDLTR, '*', array (
					'id_from[~]' => $vd . '%' 
			) );
		}
		
		$redLetter = new RedLetter ( $inst );
		$total_ver = count ( $vers );
		
		for($cnt = 0; $cnt < $total_ver; $cnt ++) {
			$ver = $vers [$cnt];
			
			switch ($ver ['type']) {
				case 'T' : // Title
					$ret .= $this->seperateHeader ( $ver ['txt'] );
					break;
				case 'V' : // Verse
					if (0 === strcmp ( 'Same as above', $ver ['txt'] )) {
						continue;
					}
					
					$ver ["txt"] = $redLetter->colorRedLetter ( $ver ["txt"], $ver ["verse_id"] );
					
					if (strpos ( $ver ['txt'], BLIB_TITLE_PT ) === false) {
						$ret .= $this->seperateVerse ( $ver ["verse_id"], $ver ["txt"] );
					} else {
						if ($vers [$cnt + 1] ['type'] !== 'T') {
							print_r ( $vers [$cnt] );
							die ( 'Title Missing!' );
						}
						$tt = explode ( BLIB_TITLE_PT, $vers [++ $cnt] ['txt'] . BLIB_TITLE_PT );
						$vt = explode ( BLIB_TITLE_PT, $ver ['txt'] );
						$total_title = count ( $vt );
						for($i = 0; $i < $total_title; $i ++) {
							$ret .= $this->seperateVerse ( $ver ["verse_id"], $vt [$i] );
							$ret .= $this->seperateHeader ( $tt [$i] );
						}
					}
					break;
				default :
					break;
			}
		}
		
		$ret .= $this->formatCrossRefandFootNote ( 'CROSSREF', $vd );
		$ret .= $this->formatCrossRefandFootNote ( 'FOOTNOTE', $vd );
		
		return $ret;
	}

	/**
	 * It tags (Not HTML tags) verses (continious et al.) so that it can be properly tagged.
	 *
	 * @param string $no
	 *        	- Verse Number
	 * @param string $vrs
	 *        	- Verse Text
	 * @return string Formated bible verse
	 *        
	 */
	private function seperateVerse($no, $vrs) {
		$this->SwapConsecutiveCharacters ( BLIB_PARA_BK, BLIB_RED_LTR_END, $vrs ); // If red lettering is next to a parabreak then swap. Mat 5:11
		
		if (empty ( $vrs ))
			return '';
		else
			$vrs = trim ( $vrs );
		
		if (0 === strpos ( $vrs, BLIB_VERSE_NUMBER_START )) { // Continuous Verses
			$no = explode ( BLIB_VERSE_NUMBER_END, $vrs );
			$no = $no [0];
			$vrs = str_replace ( $no . BLIB_VERSE_NUMBER_END, '', $vrs );
			$no = substr ( $no, 3 );
		} else {
			$no = intval ( substr ( $no, - 3 ) );
		}
		
		$vrs = preg_replace ( "/\[([a-z](-[a-z])*)\]\s*/u", BLIB_VERSE_NUMBER_START . $no . '$1' . BLIB_VERSE_NUMBER_END, $vrs );
		
		if (false === strpos ( $vrs, BLIB_VERSE_NUMBER_START )) { // If already atrats with a verse number, then don't add one.
			$vrs = BLIB_VERSE_NUMBER_START . $no . BLIB_VERSE_NUMBER_END . $vrs;
		}
		
		if (false !== strpos ( $vrs, BLIB_PARA_BK )) { // If it has para break, then make it as two verse
			$vrs = str_replace ( BLIB_PARA_BK, BLIB_VRS_END . BLIB_PARA_BK . BLIB_VRS_START, $vrs );
		}
		
		$vrs = str_replace ( BLIB_PARA_BK, BLIB_P_END . BLIB_P_START, $vrs );
		
		$vrs = BLIB_VRS_START . $vrs . BLIB_VRS_END;
		$vrs = str_replace ( BLIB_VRS_START . BLIB_VRS_END, '', $vrs );
		
		$vrs = str_replace ( BLIB_P_START . BLIB_P_END, '', $vrs );
		
		return $vrs;
	}

	/**
	 * Tags (Not HTML tags) header according to its level.
	 *
	 * @param string $hdr
	 *        	Contains header with breakpoints for each level, as stored in database
	 * @return string Properly tagged (Not HTML tags) headder
	 */
	private function seperateHeader($hdr) {
		if (empty ( $hdr ))
			return '';
		
		$hdr = explode ( BLIB_TITLE_PT, $hdr );
		
		foreach ( $hdr as &$value ) {
			$val = explode ( BLIB_HEADER_PT, $value );
			if (sizeof ( $val ) > 1) {
				$value = BLIB_H1_START . $val [0] . BLIB_H1_END . BLIB_H2_START . $val [1] . BLIB_H2_END;
			} else {
				$value = BLIB_H2_START . $val [0] . BLIB_H2_END;
			}
		}
		$hdr = implode ( '', $hdr );
		
		$hdr = BLIB_P_END . $hdr . BLIB_P_START;
		
		return $hdr;
	}

	/**
	 * Returns the tagged footnote or cross reference block
	 *
	 * @param $type -
	 *        	Can be <code>CROSSREF</code> or <code>FOOTNOTE</code> (Case sensitive).
	 * @param $bkCh -
	 *        	Book and Chapter number for which CrossRef / FootNote is generated
	 * @return string The footnote or crossref bock
	 */
	private function formatCrossRefandFootNote($type, $bkCh) {
		$ret = '';
		
		$table = constant ( "BLIB_$type" );
		$stTag = constant ( "BLIB_{$type}_START" );
		$edTag = constant ( "BLIB_{$type}_END" );
		
		$dats = $this->database->select ( $table, "*", array (
				"OR" => array (
						"id_from[~]" => $bkCh . '%',
						"id_to[~]" => $bkCh . '%',
						"AND" => array (
								"id_from[<=]" => $bkCh . '000',
								"id_to[>=]" => $bkCh . '999' 
						) 
				),
				"ORDER" => array (
						"id_from" => "ASC" 
				) 
		) );
				
		foreach ( $dats as $val ) {
			if (0 === strpos ( $val ['note'], BLIB_VERSE_NUMBER_START )) { // For verses where tag is continious or has subdivisions
				$chapRef = explode ( BLIB_VERSE_NUMBER_END, $val ['note'] );
				$chapRef = $chapRef [0];
				$val ['note'] = str_replace ( $chapRef . BLIB_VERSE_NUMBER_END, '', $val ['note'] );
				$chapRef = substr ( $chapRef, 3 );
			} else {
				$frm_ = $this->convertCode2BkCh ( $val ['id_from'] );
				$to_ = $this->convertCode2BkCh ( $val ['id_to'] );
				
				if ($frm_ [2] === 0) { // Whole chapter has this reference/footnote eg: Psalms 142
					$chapRef = $frm_ [1];
				} else {
					$chapRef = $frm_ [1] . ':' . $frm_ [2];
					
					if (intval ( $val ["id_to"] ) !== 0) { // Continuous reference
						$chapRef .= '-';
						if ($frm_ [1] == $to_ [1]) { // Continuous reference within a single chapter
							$chapRef .= $to_ [2];
						} else { // Continuous reference accross multiple chapters Eg. Nahum 1:1-3:19
							$chapRef .= $to_ [1] . ':' . $to_ [2];
						}
					}
				}
			}
			
			$ret .= "$stTag<b>$chapRef</b> <i>{$val ['note']}.</i>$edTag";
		}
		
		if (! empty ( $chapRef )) {
			$ret = '<hr style="background-color: red; height: 1px; border: 0;"/>' . $ret;
		}
		
		return $ret;
	}

	/**
	 * Swaps the positions of two consecutive strings
	 *
	 * @param string $first        	
	 * @param string $second        	
	 * @param string $chap
	 *        	- The output is stored in this variable
	 * @return The parameter $chap with modified string
	 */
	private function SwapConsecutiveCharacters($first, $second, &$chap) { // swap Consecutive Characters ab -> ba within a string.
		$chap = str_replace ( $first . $second, $second . $first, $chap );
	}

	/**
	 *
	 * @param $bk -
	 *        	Book Number
	 * @param $ch -
	 *        	Chapter Number
	 * @param $vs -
	 *        	Verse Number
	 * @return string - Formated book, chapter and verse code
	 */
	public function convertBkChVS2Code($bk, $ch, $vs) {
		$code = str_pad ( $bk, 2, '0', STR_PAD_LEFT ) . str_pad ( $ch, 3, '0', STR_PAD_LEFT ) . str_pad ( $vs, 3, '0', STR_PAD_LEFT );
		return str_replace ( '00i000', 'i', $code );
	}

	/**
	 *
	 * @param $bk -
	 *        	Book Number
	 * @param $ch -
	 *        	Chapter Number
	 * @return string - Formated book and chapter code
	 */
	public function convertBkCh2Code($bk, $ch) {
		$code = str_pad ( $bk, 2, '0', STR_PAD_LEFT ) . str_pad ( $ch, 3, '0', STR_PAD_LEFT );
		return str_replace ( '00i', 'i', $code );
	}

	/**
	 *
	 * @param $vrs -
	 *        	Properly formated/padded verse code;
	 * @return string - Array with book, chapter, verse number. <br/> Note: Chapter will be 'i' if it is introduction of a book.
	 */
	public function convertCode2BkCh($vrs) {
		if (empty ( $vrs ))
			return $vrs;
		
		$vrs = strtolower ( $vrs );
		$rt = array ();
		
		if (substr ( $vrs, - 1 ) == 'i') {
			$vrs = rtrim ( $vrs, "i" );
			$rt [0] = intval ( $vrs );
			$rt [1] = 'i';
		} elseif (strlen ( $vrs ) <= 5) {
			$vrs = str_pad ( $vrs, 5, '0', STR_PAD_LEFT ); // Minimum 5 chars should be available
			$rt = array (
					0 => intval ( substr ( $vrs, 0, 2 ) ),
					1 => intval ( substr ( $vrs, 2, 3 ) ) 
			);
		} elseif (strlen ( $vrs ) == 8) {
			$vrs = str_pad ( $vrs, 8, '0', STR_PAD_LEFT ); // Minimum 5 chars should be available
			$rt = array (
					0 => intval ( substr ( $vrs, 0, 2 ) ),
					1 => intval ( substr ( $vrs, 2, 3 ) ),
					2 => intval ( substr ( $vrs, 5, 3 ) ) 
			);
		}
		return $rt;
	}

	/**
	 * Will convert input into human readable bible eference
	 *
	 * @param $vrs -
	 *        	Properly formated/padded verse code;
	 * @param $type -
	 *        	The formating of the book name in return text. Types available are :<br/>
	 *        	0 - Full name eg. யோவான் எழுதிய முதல் திருமுகம் 4:8<br/>
	 *        	1 - Short name eg. 1 யோவான் 4:8 (Default)<br/>
	 *        	2 - Abreviation eg. 1 யோவா 4:8<br/>
	 *        	3 - Old Name eg. அருளப்பர் எழுதிய முதல் திருமுகம் 4:8<br/>
	 * @return string - Reference String.
	 */
	public function convertCode2Ref($vrs, $type = 1) {
		if (empty ( $vrs ))
			return $vrs;
		
		$type = intval ( $type ); // For safty
		
		$bookNameType = array (
				'tn_f',
				'tn_s',
				'tn_a',
				'tn_o' 
		);
		
		if ($type >= sizeof ( $bookNameType ))
			$type = 1;
		
		$bkFrag = $this->convertCode2BkCh ( $vrs );
		
		$bookName = $this->database->get ( "t_bookkey", $bookNameType [$type], array (
				"bn" => $bkFrag [0] 
		) );
		
		return $bookName . ' ' . $bkFrag [1] . ':' . $bkFrag [2];
	}

	public $bookList = array (
			1 => "தொடக்க நூல்",
			2 => "விடுதலைப் பயணம்",
			3 => "லேவியர்",
			4 => "எண்ணிக்கை",
			5 => "இணைச் சட்டம்",
			6 => "யோசுவா",
			7 => "நீதித் தலைவர்கள்",
			8 => "ரூத்து",
			9 => "1 சாமுவேல்",
			10 => "2 சாமுவேல்",
			11 => "1 அரசர்கள்",
			12 => "2 அரசர்கள்",
			13 => "1 குறிப்பேடு",
			14 => "2 குறிப்பேடு",
			15 => "எஸ்ரா",
			16 => "நெகேமியா",
			17 => "எஸ்தர்",
			18 => "யோபு",
			19 => "திருப்பாடல்கள்",
			20 => "நீதிமொழிகள்",
			21 => "சபை உரையாளர்",
			22 => "இனிமைமிகு பாடல்",
			23 => "எசாயா",
			24 => "எரேமியா",
			25 => "புலம்பல்",
			26 => "எசேக்கியேல்",
			27 => "தானியேல்",
			28 => "ஒசேயா",
			29 => "யோவேல்",
			30 => "ஆமோஸ்",
			31 => "ஒபதியா",
			32 => "யோனா",
			33 => "மீக்கா",
			34 => "நாகூம்",
			35 => "அபக்கூக்கு",
			36 => "செப்பனியா",
			37 => "ஆகாய்",
			38 => "செக்கரியா",
			39 => "மலாக்கி",
			40 => "தோபித்து",
			41 => "யூதித்து",
			42 => "எஸ்தர் (கி)",
			43 => "சாலமோனின் ஞானம்",
			44 => "சீராக்",
			45 => "பாரூக்கு",
			46 => "தானியேல் (இ)",
			47 => "1 மக்கபேயர்",
			48 => "2 மக்கபேயர்",
			49 => "மத்தேயு",
			50 => "மாற்கு",
			51 => "லூக்கா",
			52 => "யோவான்",
			53 => "திருத்தூதர் பணிகள்",
			54 => "உரோமையர்",
			55 => "1 கொரிந்தியர்",
			56 => "2 கொரிந்தியர்",
			57 => "கலாத்தியர்",
			58 => "எபேசியர்",
			59 => "பிலிப்பியர்",
			60 => "கொலோசையர்",
			61 => "1 தெசலோனிக்கர்",
			62 => "2 தெசலோனிக்கர்",
			63 => "1 திமொத்தேயு",
			64 => "2 திமொத்தேயு",
			65 => "தீத்து",
			66 => "பிலமோன்",
			67 => "எபிரேயர்",
			68 => "யாக்கோபு",
			69 => "1 பேதுரு",
			70 => "2 பேதுரு",
			71 => "1 யோவான்",
			72 => "2 யோவான்",
			73 => "3 யோவான்",
			74 => "யூதா",
			75 => "திருவெளிப்பாடு",
			100 => "திருவிவிலிய முன்னுரை",
			400 => "இணைத் திருமுறை நூல்கள்",
			540 => "திருமுகங்கள்",
			680 => "பொதுத் திருமுகங்கள்" 
	);
}

?>