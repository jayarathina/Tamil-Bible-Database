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

require_once 'lib/medoo.php';
require_once 'lib/bibleLib/bibleConfig.php';
require_once 'lib/bibleLib/redletter.php';
require_once 'lib/bibleLib/bibleLib.php';

$bLib = new bibleLib ();

echo $bLib->getChapterHTML ( 1, 2 ); // Will return second chapter of Genesis
echo '<hr/>';
echo $bLib->getChapterHTML ( 50, 'i' ); // Will return introduction for Mark's Gospel
echo '<hr/>';

echo $bLib->convertBkChVS2Code ( 2, 5, 6 ); // Will return formatted ID of verse from Book 2 (Exodus), 5:6 => 02005006
echo '<hr/>';

print_r ( $bLib->convertCode2BkCh ( '02005006' ) ); // Will return array with Book number, chapter and verse => Array ( [0] => 2 [1] => 5 [2] => 6 )
echo '<hr/>';

echo $bLib->convertCode2Ref ( '71004008', 0 ) . '<br/>'; // Will convert input into human readable bible eference => விடுதலைப் பயணம் 5:6
echo '<hr/>';

