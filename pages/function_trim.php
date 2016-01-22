<?php
function func_trim($str){
    /*$str = htmlspecialchars($str);
    $str = mysql_escape_string($str);

    $quotes = array ("\x27", "\x22", "\x60", "\t", "\n", "\r", "*", "%", "<", ">", "?", "!" );
    $goodquotes = array ("-", "+", "#" );
    $repquotes = array ("\-", "\+", "\#" );
    $str = trim( strip_tags( $str ) );
    $str = str_replace( $quotes, '', $str );
    $str = str_replace( $goodquotes, $repquotes, $str );
    $str = ereg_replace(" +", " ", $str);

    $str = str_replace("\r\n", ' ', $str);
    $str = str_replace(chr(9), ' ', $str);
    $str = str_replace('[+', '\[\+', $str);
    $str = str_replace('+]', '\+\]', $str);
    $str = str_replace("'", "", $str);
    $str = str_replace('"', "", $str);*/

    $str = str_replace("\\\\", '', $str);
    $str = str_replace("\\", '', $str);
//  $str = str_replace('?', '\?', $str);
//  $str = str_replace(";", "", $str);
//  $str = str_replace("″", "", $str);
    $str = str_replace("@INHERIT", "", $str);
    $str = str_replace("@SELECT", "", $str);
    $str = str_replace("@EVAL", "", $str);
    $str = str_replace("INHERIT", "", $str);
    $str = str_replace("SELECT", "", $str);
    $str = str_replace("EVAL", "", $str);
    $str = str_replace(" @", "", $str);
    $str = str_replace("return", "", $str);
    return $str;
}
function func_trim_snip($str){
    $str = htmlspecialchars($str);
    //$str = mysql_escape_string($str);

    $quotes = array ("\x27", "\x22", "\x60", "\t", "\n", "\r", "*", "%", "<", ">", "?", "!" );
    $goodquotes = array ("-", "+", "#" );
    $repquotes = array ("\-", "\+", "\#" );
    $str = trim( strip_tags( $str ) );
    $str = str_replace( $quotes, '', $str );
    $str = str_replace( $goodquotes, $repquotes, $str );
    // $str = ereg_replace(" +", " ", $str);

    $str = str_replace("\r\n", ' ', $str);
    $str = str_replace(chr(9), ' ', $str);
    $str = str_replace('[+', '\[\+', $str);
    $str = str_replace('+]', '\+\]', $str);
    $str = str_replace("'", "", $str);
    $str = str_replace('"', "", $str);

    $str = str_replace("\\\\", '', $str);
    $str = str_replace("\\", '', $str);
    $str = str_replace('?', '\?', $str);
    $str = str_replace(";", "", $str);
    $str = str_replace("″", "", $str);
    $str = str_replace("@INHERIT", "", $str);
    $str = str_replace("@SELECT", "", $str);
    $str = str_replace("@EVAL", "", $str);
    $str = str_replace("INHERIT", "", $str);
    $str = str_replace("SELECT", "", $str);
    $str = str_replace("EVAL", "", $str);
    $str = str_replace(" @", "", $str);
    $str = str_replace("return", "", $str);
    return $str;
}
?>