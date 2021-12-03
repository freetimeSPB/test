<?php
defined('B_PROLOG_INCLUDED') and (B_PROLOG_INCLUDED === true) or die();
use Bitrix\Main\Localization\Loc;


Loc::loadMessages(__FILE__);

$arComponentParameters = array(
	"GROUPS" => array(
		  "BASE" => array(
			 "NAME" => Loc::getMessage("RADUGA_ELEMENT_ADD_PHOTOS_AJAX_BASE_SECTION_NAME"),
		  ),
	   ),
    "PARAMETERS" => array(
        "IBLOCK_ID" => array(
			"PARENT" => "BASE",
            "NAME" => Loc::getMessage("RADUGA_ELEMENT_ADD_PHOTOS_AJAX_IBLOCK_ID"),
            "TYPE" => "INTEGER",
			"DEFAULT" => 0
        ),
		"ELEMENT_ID" => array(
			"PARENT" => "BASE",
            "NAME" => Loc::getMessage("RADUGA_ELEMENT_ADD_PHOTOS_AJAX_ELEMENT_ID"),
            "TYPE" => "INTEGER",
			"DEFAULT" => 0
        ),
		"PHOTOS_CODE" => array(
			"PARENT" => "BASE",
            "NAME" => Loc::getMessage("RADUGA_ELEMENT_ADD_PHOTOS_AJAX_PHOTOS_CODE"),
            "TYPE" => "STRING",
			"DEFAULT" => "ADD_PHOTOS"
        ),
        "MAX_PHOTOS_COUNT" => array(
            "PARENT" => "BASE",
            "NAME" => Loc::getMessage("RADUGA_ELEMENT_ADD_PHOTOS_AJAX_MAX_PHOTOS_COUNT"),
            "TYPE" => "STRING",
            "DEFAULT" => "2",
        ),
        "CACHE_TIME" => array(),
    ),
);
