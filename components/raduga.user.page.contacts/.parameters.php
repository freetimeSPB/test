<?php
defined('B_PROLOG_INCLUDED') and (B_PROLOG_INCLUDED === true) or die();
use Bitrix\Iblock\IblockTable;
use Bitrix\Iblock\TypeTable;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

if (!Loader::includeModule("main")) {
    return;
}

$arTypesEx = array("-" => " ");
$result = TypeTable::getList(
    array(
        "select" => array("ID", "LANG_MESSAGE"),
        "filter" => array("IBLOCK_TYPE_LANG_MESSAGE_LANGUAGE_ID" => LANGUAGE_ID),
    )
);

while ($row = $result->fetch()) {
    $arTypesEx[$row["ID"]] = "[" . $row["ID"] . "] " . $row["IBLOCK_TYPE_LANG_MESSAGE_NAME"];
}

$arIBlocks = array();
$row = array();

$result = IblockTable::getList(
    array(
        "select" => array("ID", "NAME"),
        "filter" => array("IBLOCK_TYPE_ID" => ($arCurrentValues["IBLOCK_TYPE"] != "-" ? $arCurrentValues["IBLOCK_TYPE"] : "")),
        "order" => array("SORT" => "ASC"),
    )
);

while ($row = $result->fetch()) {
    $arIBlocks[$row["ID"]] = $row["NAME"];
}


$arComponentParameters = array(
    "GROUPS" => array(
    ),
    "PARAMETERS" => array(
        "IBLOCK_TYPE" => array(
            "PARENT" => "BASE",
            "NAME" => Loc::getMessage("RADUGA_CATALOG_USER_PAGE_CONTACTS_DESC_LIST_TYPE"),
            "TYPE" => "LIST",
            "VALUES" => $arTypesEx,
            "DEFAULT" => "",
            "REFRESH" => "Y",
        ),
        "IBLOCK_ID" => array(
            "PARENT" => "BASE",
            "NAME" => Loc::getMessage("RADUGA_CATALOG_USER_PAGE_CONTACTS_DESC_LIST_ID"),
            "TYPE" => "LIST",
            "VALUES" => $arIBlocks,
            "ADDITIONAL_VALUES" => "N",
            "REFRESH" => "Y",
        ),
		"PROPERTY_CODE" => array(
			"PARENT" => "BASE",
            "NAME" => Loc::getMessage("RADUGA_CATALOG_USER_PAGE_CONTACTS_DESC_PROPERTY_CODE"),
            "TYPE" => "STRING",
			"DEFAULT" => "OWNER"
        ),
		"ELEMENT_ID" => array(
			"PARENT" => "BASE",
            "NAME" => Loc::getMessage("RADUGA_CATALOG_USER_PAGE_CONTACTS_ELEMENT_ID"),
            "TYPE" => "STRING",
			"DEFAULT" => ""
        ),
		"USER_ID" => array(
			"PARENT" => "BASE",
            "NAME" => Loc::getMessage("RADUGA_CATALOG_USER_PAGE_CONTACTS_USER_ID"),
            "TYPE" => "STRING",
			"DEFAULT" => ""
        ),
        "CACHE_TIME" => array("DEFAULT" => 36000000),
    ),
);
