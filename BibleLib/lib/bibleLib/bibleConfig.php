<?php

const BLIB_DB_VERSION = '2.0';

// Names of the tables where tables are retrived
const BLIB_INDEX = "t_bookkey";
const BLIB_CROSSREF = "t_crossref";
const BLIB_FOOTNOTE = "t_footnotes";
const BLIB_REDLTR = "t_redletter";
const BLIB_HDR = "t_verseheaders";
const BLIB_VRS = "t_verses";
const BLIB_VIEW = "mybibleview";

// User preferences
const BLIB_ERR_MSG = 'Something is wrong. Please contact Admin';
const BLIB_RED_LTR = true; // Should Jesuss' words be colored?
// TODO Add option for the color of the words here rather than css
                           
// Tags in Database
const BLIB_BREAK_PT = '§'; // {br}';
const BLIB_PARA_BK = '⒫'; // {pb}; UPDATE `t_verses` SET `verse`=concat(`verse`, '⒫' ) WHERE id in (select t_parabreak.id from t_parabreak)
const BLIB_TITLE_PT = '⒯'; // {t}';
const BLIB_HEADER_PT = '⒣'; // {h}';

//
const BLIB_VERSE_NUMBER_START = '❮';
const BLIB_VERSE_NUMBER_END = '❯';
const BLIB_POEM1_START = '⁽';
const BLIB_POEM1_END = '⁾';
const BLIB_POEM2_START = '₍';
const BLIB_POEM2_END = '₎';
const BLIB_POEM_BREAK = '␢';
const BLIB_INDENT_START = '⦃'; // {i}
const BLIB_INDENT_END = '⦄';
const BLIB_OUTDENT_START = '⦅'; // {o}
const BLIB_OUTDENT_END = '⦆';

// Tags in Code for HTML Generation
const BLIB_H1_START = '〖';
const BLIB_H1_END = '〗';
const BLIB_H2_START = '〘';
const BLIB_H2_END = '〙';
const BLIB_P_START = '【';
const BLIB_P_END = '】';
const BLIB_VRS_START = '❰';
const BLIB_VRS_END = '❱';
const BLIB_RED_LTR_START = '⦓';
const BLIB_RED_LTR_END = '⦔';
const BLIB_FOOTNOTE_START = '⧘';
const BLIB_FOOTNOTE_END = '⧙';
const BLIB_CROSSREF_START = '⧚';
const BLIB_CROSSREF_END = '⧛';
