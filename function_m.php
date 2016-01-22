<?php
//функция создания недостающих таблиц
function create_table($prefix){
    global $modx,$mod_page,$lang,$type,$info, $error, $log;
    $modx->db->query('
	CREATE TABLE IF NOT EXISTS `'.$prefix.'mform_fields` (
	  	`id` int(11) NOT NULL AUTO_INCREMENT,
	  	`id_form` int(11) NOT NULL,
	  	`name` text NOT NULL,
	  	`type` text NOT NULL,
		`type_main` int(11) NOT NULL,
	  	`obligat` tinyint(1) NOT NULL,
	  	`posit` int(11) NOT NULL,
	  	`l_min` int(11) NOT NULL,
	  	`l_max` int(11) NOT NULL,
	  	`default` text NOT NULL,
	  	`options` text NOT NULL,
	  	PRIMARY KEY (`id`),
  		KEY `id_form` (`id_form`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0;
		');
    $modx->db->query('
	CREATE TABLE IF NOT EXISTS `'.$prefix.'mform_forms` (
  		`id` int(11) NOT NULL AUTO_INCREMENT,
		`name` text NOT NULL,
		`email` text NOT NULL,
		`message` text NOT NULL,
		`ch_form` text NOT NULL,
		`ch_field` text NOT NULL,
		`lang` tinyint(4) NOT NULL,
		`captcha` tinyint(4) NOT NULL,
		`settings` text NOT NULL,
		PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0;
		');
    $modx->db->query('
	CREATE TABLE IF NOT EXISTS `'.$prefix.'mform_status` (
		`id` int(11) NOT NULL AUTO_INCREMENT,
  		`name` text NOT NULL,
  		`color` text NOT NULL,
  		PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0;
		');
    $modx->db->query('
	CREATE TABLE IF NOT EXISTS `'.$prefix.'mform_value` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `id_form` int(11) NOT NULL,
	  `id_page` int(11) NOT NULL,
	  `id_status` int(11) NOT NULL,
	  `id_user` int(11) NOT NULL,
	  `value` text NOT NULL,
	  `id_admin` int(11) NOT NULL,
	  `date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	  `comment` text NOT NULL,
	  `date_com` timestamp NOT NULL DEFAULT "0000-00-00 00:00:00",
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;
	');
    $result = $modx->db->query('SELECT COUNT(*)as count
				FROM '.$modx->getFullTableName('site_snippets').'
				WHERE `name`="showFields"');
    while($row = $modx->db->getRow($result)){
        $res=$row['count'];
    }
    if($res==0){
        $modx->db->query("INSERT INTO ".$modx->getFullTableName('site_snippets')."
				(`id`,`name`,`snippet`,`moduleguid`) 
				VALUES (NULL, 'showFields','if(!defined(\'MODX_BASE_PATH\')) {die(\'What are you doing? Get out of here!\');}include MODX_BASE_PATH.\'assets/modules/mform/snippets/show_fields.php\';','');");

    }
    return "Созданы таблицы";}

//функция проверки на существование таблицы
function mysql_table_seek($tablename, $dbname)
{
    global $modx,$mod_page,$lang,$type,$info, $error, $log;
    $table_list = mysql_query("SHOW TABLES FROM `".$dbname."`");
    while ($row = mysql_fetch_row($table_list)) {
        if ($tablename==$row[0]) {
            return true;
        }
    }
    return false;
}

//функция подсчета имеющихся таблиц
function get_tables_count($prefix)
{
    global $modx,$mod_page,$lang,$type,$info, $error, $log;
    $addres = $modx->getFullTableName('manager_users');//выбор адреса обяз. таблицы
    $addr_m = explode(".", $addres);
    $base = str_replace('`', '', $addr_m[0]);

    //cоздаються переменные с результатом, полученным из функции
    $exist1=mysql_table_seek("".$prefix."mform_fields",$base);
    $exist2=mysql_table_seek("".$prefix."mform_forms",$base);
    $exist3=mysql_table_seek("".$prefix."mform_status",$base);
    $exist4=mysql_table_seek("".$prefix."mform_value",$base);
    if($exist1==true and $exist2==true and $exist3==true and $exist4==true){
        return true;
    }else{
        return false;
    }

    //return $exist1+$exist2+$exist3+$exist4;
}
