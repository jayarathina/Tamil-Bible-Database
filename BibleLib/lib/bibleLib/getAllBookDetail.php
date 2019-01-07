<?php
/**
 * Resurns book name and total chapters in a book. This function is too slow. So a static array is declared below.
 *
 * @param string $bkNo - Books number
 * @return string[] the Name, Start Chapter and Total Chapters of a Book
 *
 **/
function getAllBookDetail($bkNo = NULL) {
	$sql = "SELECT	`bn`, `tn_s`,
 (	SELECT count(DISTINCT (SUBSTRING(id,1,5)))
 FROM " . BLIB_VRS . "
 WHERE SUBSTRING(id,1,2) = bn
 GROUP BY SUBSTRING(id,1,2)
 ) AS `totalChapters`
 FROM `" . BLIB_INDEX . "` ";
	if (! is_null ( $bkNo ))
		$sql .= " WHERE bn = $bkNo";
	
	$bks = $this->database->query ( $sql )->fetchAll ( PDO::FETCH_ASSOC );
	
	if (is_null ( $bkNo )) {
		$bks [43] ['StartChapter'] = 0; // சீராக்கின் ஞானம்
		$bks [43] ['totalChapters'] = 51; // சீராக்கின் ஞானம்
	} else {
		if ($bkNo == 44) {
			$bks [0] ['StartChapter'] = 0; // சீராக்கின் ஞானம்
			$bks [0] ['totalChapters'] = 51; // சீராக்கின் ஞானம்
		} else {
			$bks [0] ['StartChapter'] = 1;
			$bks [0] ['totalChapters'] = intval ( $bks [0] ['totalChapters'] );
		}
		$bks = $bks [0];
	}
	
	return $bks;
} /* */

$getAllBookDetail = [ 
		1 => [ 
				'bn' => '1',
				'tn_s' => 'தொடக்க நூல்',
				'totalChapters' => '50' 
		],
		2 => [ 
				'bn' => '2',
				'tn_s' => 'விடுதலைப் பயணம்',
				'totalChapters' => '40' 
		],
		3 => [ 
				'bn' => '3',
				'tn_s' => 'லேவியர்',
				'totalChapters' => '27' 
		],
		4 => [ 
				'bn' => '4',
				'tn_s' => 'எண்ணிக்கை',
				'totalChapters' => '36' 
		],
		5 => [ 
				'bn' => '5',
				'tn_s' => 'இணைச் சட்டம்',
				'totalChapters' => '34' 
		],
		6 => [ 
				'bn' => '6',
				'tn_s' => 'யோசுவா',
				'totalChapters' => '24' 
		],
		7 => [ 
				'bn' => '7',
				'tn_s' => 'நீதித் தலைவர்கள்',
				'totalChapters' => '21' 
		],
		8 => [ 
				'bn' => '8',
				'tn_s' => 'ரூத்து',
				'totalChapters' => '4' 
		],
		9 => [ 
				'bn' => '9',
				'tn_s' => '1 சாமுவேல்',
				'totalChapters' => '31' 
		],
		10 => [ 
				'bn' => '10',
				'tn_s' => '2 சாமுவேல்',
				'totalChapters' => '24' 
		],
		11 => [ 
				'bn' => '11',
				'tn_s' => '1 அரசர்கள்',
				'totalChapters' => '22' 
		],
		12 => [ 
				'bn' => '12',
				'tn_s' => '2 அரசர்கள்',
				'totalChapters' => '25' 
		],
		13 => [ 
				'bn' => '13',
				'tn_s' => '1 குறிப்பேடு',
				'totalChapters' => '29' 
		],
		14 => [ 
				'bn' => '14',
				'tn_s' => '2 குறிப்பேடு',
				'totalChapters' => '36' 
		],
		15 => [ 
				'bn' => '15',
				'tn_s' => 'எஸ்ரா',
				'totalChapters' => '10' 
		],
		16 => [ 
				'bn' => '16',
				'tn_s' => 'நெகேமியா',
				'totalChapters' => '13' 
		],
		17 => [ 
				'bn' => '17',
				'tn_s' => 'எஸ்தர்',
				'totalChapters' => '10' 
		],
		18 => [ 
				'bn' => '18',
				'tn_s' => 'யோபு',
				'totalChapters' => '42' 
		],
		19 => [ 
				'bn' => '19',
				'tn_s' => 'திருப்பாடல்கள்',
				'totalChapters' => '150' 
		],
		20 => [ 
				'bn' => '20',
				'tn_s' => 'நீதிமொழிகள்',
				'totalChapters' => '31' 
		],
		21 => [ 
				'bn' => '21',
				'tn_s' => 'சபை உரையாளர்',
				'totalChapters' => '12' 
		],
		22 => [ 
				'bn' => '22',
				'tn_s' => 'இனிமைமிகு பாடல்',
				'totalChapters' => '8' 
		],
		23 => [ 
				'bn' => '23',
				'tn_s' => 'எசாயா',
				'totalChapters' => '66' 
		],
		24 => [ 
				'bn' => '24',
				'tn_s' => 'எரேமியா',
				'totalChapters' => '52' 
		],
		25 => [ 
				'bn' => '25',
				'tn_s' => 'புலம்பல்',
				'totalChapters' => '5' 
		],
		26 => [ 
				'bn' => '26',
				'tn_s' => 'எசேக்கியேல்',
				'totalChapters' => '48' 
		],
		27 => [ 
				'bn' => '27',
				'tn_s' => 'தானியேல்',
				'totalChapters' => '12' 
		],
		28 => [ 
				'bn' => '28',
				'tn_s' => 'ஒசேயா',
				'totalChapters' => '14' 
		],
		29 => [ 
				'bn' => '29',
				'tn_s' => 'யோவேல்',
				'totalChapters' => '3' 
		],
		30 => [ 
				'bn' => '30',
				'tn_s' => 'ஆமோஸ்',
				'totalChapters' => '9' 
		],
		31 => [ 
				'bn' => '31',
				'tn_s' => 'ஒபதியா',
				'totalChapters' => '1' 
		],
		32 => [ 
				'bn' => '32',
				'tn_s' => 'யோனா',
				'totalChapters' => '4' 
		],
		33 => [ 
				'bn' => '33',
				'tn_s' => 'மீக்கா',
				'totalChapters' => '7' 
		],
		34 => [ 
				'bn' => '34',
				'tn_s' => 'நாகூம்',
				'totalChapters' => '3' 
		],
		35 => [ 
				'bn' => '35',
				'tn_s' => 'அபக்கூக்கு',
				'totalChapters' => '3' 
		],
		36 => [ 
				'bn' => '36',
				'tn_s' => 'செப்பனியா',
				'totalChapters' => '3' 
		],
		37 => [ 
				'bn' => '37',
				'tn_s' => 'ஆகாய்',
				'totalChapters' => '2' 
		],
		38 => [ 
				'bn' => '38',
				'tn_s' => 'செக்கரியா',
				'totalChapters' => '14' 
		],
		39 => [ 
				'bn' => '39',
				'tn_s' => 'மலாக்கி',
				'totalChapters' => '4' 
		],
		40 => [ 
				'bn' => '40',
				'tn_s' => 'தோபித்து',
				'totalChapters' => '14' 
		],
		41 => [ 
				'bn' => '41',
				'tn_s' => 'யூதித்து',
				'totalChapters' => '16' 
		],
		42 => [ 
				'bn' => '42',
				'tn_s' => 'எஸ்தர் (கி)',
				'totalChapters' => '10' 
		],
		43 => [ 
				'bn' => '43',
				'tn_s' => 'சாலமோனின் ஞானம்',
				'totalChapters' => '19' 
		],
		44 => [ 
				'bn' => '44',
				'tn_s' => 'சீராக்',
				'totalChapters' => 51,
				'StartChapter' => 0 
		],
		45 => [ 
				'bn' => '45',
				'tn_s' => 'பாரூக்கு',
				'totalChapters' => '6' 
		],
		46 => [ 
				'bn' => '46',
				'tn_s' => 'தானியேல் (இ)',
				'totalChapters' => '3' 
		],
		47 => [ 
				'bn' => '47',
				'tn_s' => '1 மக்கபேயர்',
				'totalChapters' => '16' 
		],
		48 => [ 
				'bn' => '48',
				'tn_s' => '2 மக்கபேயர்',
				'totalChapters' => '15' 
		],
		49 => [ 
				'bn' => '49',
				'tn_s' => 'மத்தேயு',
				'totalChapters' => '28' 
		],
		50 => [ 
				'bn' => '50',
				'tn_s' => 'மாற்கு',
				'totalChapters' => '16' 
		],
		51 => [ 
				'bn' => '51',
				'tn_s' => 'லூக்கா',
				'totalChapters' => '24' 
		],
		52 => [ 
				'bn' => '52',
				'tn_s' => 'யோவான்',
				'totalChapters' => '21' 
		],
		53 => [ 
				'bn' => '53',
				'tn_s' => 'திருத்தூதர் பணிகள்',
				'totalChapters' => '28' 
		],
		54 => [ 
				'bn' => '54',
				'tn_s' => 'உரோமையர்',
				'totalChapters' => '16' 
		],
		55 => [ 
				'bn' => '55',
				'tn_s' => '1 கொரிந்தியர்',
				'totalChapters' => '16' 
		],
		56 => [ 
				'bn' => '56',
				'tn_s' => '2 கொரிந்தியர்',
				'totalChapters' => '13' 
		],
		57 => [ 
				'bn' => '57',
				'tn_s' => 'கலாத்தியர்',
				'totalChapters' => '6' 
		],
		58 => [ 
				'bn' => '58',
				'tn_s' => 'எபேசியர்',
				'totalChapters' => '6' 
		],
		59 => [ 
				'bn' => '59',
				'tn_s' => 'பிலிப்பியர்',
				'totalChapters' => '4' 
		],
		60 => [ 
				'bn' => '60',
				'tn_s' => 'கொலோசையர்',
				'totalChapters' => '4' 
		],
		61 => [ 
				'bn' => '61',
				'tn_s' => '1 தெசலோனிக்கர்',
				'totalChapters' => '5' 
		],
		62 => [ 
				'bn' => '62',
				'tn_s' => '2 தெசலோனிக்கர்',
				'totalChapters' => '3' 
		],
		63 => [ 
				'bn' => '63',
				'tn_s' => '1 திமொத்தேயு',
				'totalChapters' => '6' 
		],
		64 => [ 
				'bn' => '64',
				'tn_s' => '2 திமொத்தேயு',
				'totalChapters' => '4' 
		],
		65 => [ 
				'bn' => '65',
				'tn_s' => 'தீத்து',
				'totalChapters' => '3' 
		],
		66 => [ 
				'bn' => '66',
				'tn_s' => 'பிலமோன்',
				'totalChapters' => '1' 
		],
		67 => [ 
				'bn' => '67',
				'tn_s' => 'எபிரேயர்',
				'totalChapters' => '13' 
		],
		68 => [ 
				'bn' => '68',
				'tn_s' => 'யாக்கோபு',
				'totalChapters' => '5' 
		],
		69 => [ 
				'bn' => '69',
				'tn_s' => '1 பேதுரு',
				'totalChapters' => '5' 
		],
		70 => [ 
				'bn' => '70',
				'tn_s' => '2 பேதுரு',
				'totalChapters' => '3' 
		],
		71 => [ 
				'bn' => '71',
				'tn_s' => '1 யோவான்',
				'totalChapters' => '5' 
		],
		72 => [ 
				'bn' => '72',
				'tn_s' => '2 யோவான்',
				'totalChapters' => '1' 
		],
		73 => [ 
				'bn' => '73',
				'tn_s' => '3 யோவான்',
				'totalChapters' => '1' 
		],
		74 => [ 
				'bn' => '74',
				'tn_s' => 'யூதா',
				'totalChapters' => '1' 
		],
		75 => [ 
				'bn' => '75',
				'tn_s' => 'திருவெளிப்பாடு',
				'totalChapters' => '22' 
		],
		100 => [ 
				'bn' => '100',
				'tn_s' => 'முன்னுரை',
				'totalChapters' => NULL 
		],
		400 => [ 
				'bn' => '400',
				'tn_s' => 'இணைத் திருமுறை நூல்கள்',
				'totalChapters' => NULL 
		],
		540 => [ 
				'bn' => '540',
				'tn_s' => 'திருமுகங்கள்',
				'totalChapters' => NULL 
		],
		680 => [ 
				'bn' => '680',
				'tn_s' => 'பொதுத் திருமுகங்கள்',
				'totalChapters' => NULL 
		] 
];