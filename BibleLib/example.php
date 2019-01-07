<meta charset="UTF-8">
<meta
	content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no'
	name='viewport'>

<link rel="stylesheet" href="chapter.css" />

<?php
const DB_NAME = 'YOURDBNAME';
const DB_HOST = 'localhost';
const DB_USER = 'root';
const DB_PASSWORD = '';

require_once 'lib/BibleLib/bibleConfig.php';
require_once 'lib/BibleLib/redletter.php';
require_once 'lib/BibleLib/bibleLib.php';

$bLib = new bibleLib ();

echo '<h2>' . $bLib->bookList[49] . ' அதிகாரம் 4</h2>'; 
echo $bLib->getChapterHTML ( 49, 4); // Will return second chapter of Mark's Gospel (See the red letters colored automatically)

echo '<hr/>';
echo '<h2>' . $bLib->bookList[1] . ' முன்னுரை</h2>';
echo $bLib->getChapterHTML ( 1, 'i' ); // Will return introduction for Genesis

echo '<hr/> Properly formated verse ID for Exodus Chapter 5 Verse 6 is: ' . $bLib->convertBkChVS2Code ( 2, 5, 6 ); // Will return formatted ID for verse 6 of Chapter 5 from Book 2 (Exodus), 5:6 => 02005006

echo '<hr/> Verse ID 02005006 seperated into book, chapter and verse number is: ';
echo '<pre>';
print_r ( $bLib->convertCode2BkCh ( '02005006' ) ); // Will return array with Book number, chapter and verse => Array ( [0] => 2 [1] => 5 [2] => 6 )
echo '</pre>';

echo '<hr/>Verse ID 71004008 in readable format is : ' . $bLib->convertCode2Ref ( '71004008', 0 ); // Will convert input into human readable bible eference => விடுதலைப் பயணம் 5:6

echo '<hr/>Search results for term <i>பல்லவி </i>:<pre>';
print_r ( $bLib->searchBible ( '%பல்லவி%' ) ); // Simple example search for the term 'அம்மா'
echo '</pre><br/><hr/>';