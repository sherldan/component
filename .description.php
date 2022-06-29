<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => "Стандартизация информации о кафедрах ВолГУ",
	"DESCRIPTION" => "Обратная связь по институтам. Список институтов генерируется на основе данных из 1С.",
	"ICON" => "/images/icon.gif",
	"SORT" => 10,
	"CACHE_PATH" => "Y",
	"PATH" => array(
		"ID" => "Компоненты ВолГУ: Кафедры",
	),
	"COMPLEX" => "N",	
);
