<?php
$migrate = array();

/******* SYNTAX REFERENCE READ ME FIRST******

TABLE CREATION : 

CREATE TABLE IF NOT EXISTS table_name (
	id NOT NULL AUTO_INCREMENT,
	column_name data_type NOT NULL DEFAULT default_value,
	column_name2 data_type NOT NULL DEFAULT default_value,
	PRIMARY KEY (id)
)ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1

TABLE ALTERATION :

ALTER TABLE table_name ADD COLUMN  coulmn_name data_type


APPLICATION :
$query = 'QUERY STRING'
$array_push($migrate,$query)

******* REFERENCE END    ******/



/*** Create Table app_menu ***/
$query = "CREATE TABLE IF NOT EXISTS `app_menus` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `ids_module` varchar(25) NOT NULL,
 `name` varchar(100) NOT NULL,
 `icon` varchar(25) NOT NULL,
 `display_order` int(1) NOT NULL,
 `status` int(1) NOT NULL DEFAULT 1,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1";

array_push($migrate,$query);

/*** Alter Table app_menu ***/

$query = "ALTER TABLE app_menusdd ADD COLUMN  name varchar(50)";

array_push($migrate,$query);

$query = "ALTER TABLE app_menusdd ADDdd COLUMN  capital varchar(50)";

array_push($migrate,$query);


?>

