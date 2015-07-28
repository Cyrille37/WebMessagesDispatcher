<?php

error_reporting ( E_ALL );
date_default_timezone_set ( 'Europe/Paris' );

$db_dsn='mysql:host=localhost;dbname=webmsgdisp';
$db_user='root';
$db_password='root';
$db_table = 'swswall_words' ;

$dbh = new \PDO( $db_dsn, $db_user, $db_password );

$limit = intval( (isset($_GET['limit'])?$_GET['limit']:0) );
if( $limit > 0 ){
	$sth = $dbh->query( 'select wrd_word from `'.$db_table.'` order by wrd_updated_at desc limit '.$limit );
} else {
	$sth = $dbh->query( 'select wrd_word from `'.$db_table.'` order by wrd_updated_at desc ' );
}

header('text/plain;;charset=utf-8');

$s = '' ;
foreach( $sth->fetchAll( \PDO::FETCH_NUM ) as $row )
{
	//$s .=  str_repeat ( $row[0].' ' , $row[1]);
	$s .= $row[0] . ' ';
}

echo $s;
