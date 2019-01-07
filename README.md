# Tamil-Bible-Database
This is a project to maintain Tamil Ecumenical Bible in Unicode. The aim of this project is to make online Tamil Bible resemble as much as possible to the print edition. More specifically poems and quotations to resemble the print edition. The bible that is used for this purpose is the 2012 edition.

A [live demo](http://bible.madharasan.com/live/toc.html) is available. You can also like us on [facebook](https://www.facebook.com/Thiruviviliam/).

## Features of this library include
* A MySQL database.
* A PHP library to access and retrieve information.
* Unlike other online Tamil Bibles, poems and quotations are displayed as they are in the print edition (that is they are formatted like poems and not like paragraphs).
* Options to enable [Red letter version](https://en.wikipedia.org/wiki/Red_letter_edition) of the bible is added. The logic to highlight red letter verses is in the code and not in the database.
 
## Requirements
* PHP 5.4+ and PDO extension (Installed by default)
* SQL database, preferably MySQL (but works with many others too)
* The [Medoo library](http://medoo.in) - (Included in the library)

## Points to Note
* `BibleLib` folder contains PHP library to access and display the database
* `MySQL` folder contains the table dumps. The table names are self explanatory.
* Unicode characters like ❮, ⁾, ₎, ␢, ⦃ and many others are used in the verses stored in the database to enable us to format the verses properly. The complete list can be found in [bibleConfig.php](BibleLib/lib/bibleLib/bibleConfig.php)

### Verse ID System:
After a long analysis, I decided to use verse ID system of  [scrollmapper/bible_databases](https://github.com/scrollmapper/bible_databases). It is simpler and more effecient. Each verse is accessed by a unique key, the combination of the BOOK+CHAPTER+VERSE id.
Example: 
* Genesis 1:1 (Genesis chapter 1, verse 1) = 01001001 (01 001 001)
* Exodus 2:3 (Exodus chapter 2, verse 3) = 02002003 (02 002 003)
The verse-id system is used for faster, simplified queries. For instance:
***01001001*** - ***02001005*** would capture all verses between **Genesis 1:1** through **Exodus 1:5**. 
Written simply:
```mysql
SELECT * FROM t_verses WHERE id BETWEEN 01001001 AND 02001005
```
## Known Issuse
* Since Unicode characters are in the middle of verses, search function will not work properly if more than on word is used.

## Suggestions or Comments
If you find any bug or suggest any improvement, please feel free to raise a pull request or contact me. You can also contribute by proofreading the bible. Check out [our home page](http://bible.madharasan.com/live/) for more information.
