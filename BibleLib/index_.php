<meta charset="UTF-8">
<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
<link rel="stylesheet" href="chapter.css" />

<?php

require_once 'config.php';
require_once 'lib/medoo.php';
require_once 'lib/bibleLib/bibleLib.php';

$bLib = new bibleLib();

echo $bLib->getChapterHTML(1, 2);