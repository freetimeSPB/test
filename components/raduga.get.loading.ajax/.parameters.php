<?php
defined('B_PROLOG_INCLUDED') and (B_PROLOG_INCLUDED === true) or die();
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

$arComponentParameters = array(
    "GROUPS" => array(
    ),
    "PARAMETERS" => array(
		 "COMPONENT_NAME" => array(
            "PARENT" => "BASE",
            "NAME" => Loc::getMessage("RADUGA_GET_LOADING_AJAX_COMPONENT_NAME"),
            "TYPE" => "STRING",
        ),
		"COMPONENT_TEMPLATE" => array(
            "PARENT" => "BASE",
            "NAME" => Loc::getMessage("RADUGA_GET_LOADING_AJAX_COMPONENT_TEMPLATE"),
            "TYPE" => "STRING",
        ),
		"COMPONENT_TIMEOUT" => array(
            "PARENT" => "BASE",
            "NAME" => Loc::getMessage("RADUGA_GET_LOADING_AJAX_COMPONENT_TIMEOUT"),
            "TYPE" => "STRING",
        ),
		"COMPONENT_PARAMS" => array(
            "PARENT" => "BASE",
            "NAME" => Loc::getMessage("RADUGA_GET_LOADING_AJAX_COMPONENT_PARAMS"),
            "TYPE" => "LIST",
        ),
		"COMPONENT_ONLY_MOBILE" => array(
            "PARENT" => "BASE",
            "NAME" => Loc::getMessage("RADUGA_GET_LOADING_AJAX_COMPONENT_ONLY_MOBILE"),
            "TYPE" => "CHECKBOX",
        ),
		"COMPONENT_ONLY_DESKTOP" => array(
            "PARENT" => "BASE",
            "NAME" => Loc::getMessage("RADUGA_GET_LOADING_AJAX_COMPONENT_ONLY_DESKTOP"),
            "TYPE" => "CHECKBOX",
        ),
		"COMPONENT_FUNCTIONS_AFTER_INIT" => array(
            "PARENT" => "BASE",
            "NAME" => Loc::getMessage("RADUGA_GET_LOADING_AJAX_COMPONENT_FUNCTIONS_AFTER_INIT"),
            "TYPE" => "LIST",
        ),
	    "CACHE_TIME" => array("DEFAULT" => 36000000),
    ),
);