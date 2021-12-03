<?php
defined('B_PROLOG_INCLUDED') and (B_PROLOG_INCLUDED === true) or die();

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$arComponentDescription = [
    'NAME' => Loc::getMessage('RADUGA_CATALOG_USER_PAGE_CONTACTS_NAME'),
    'DESCRIPTION' => Loc::getMessage('RADUGA_CATALOG_USER_PAGE_CONTACTS_DESCRIPTION'),
    'SORT' => 100,
    'CACHE_PATH' => 'Y',
	"PATH" => array(
		"ID" => "raduga",
		"NAME" => Loc::getMessage('RADUGA_DESC_SECTION'),
		"CHILD" => array(
			"ID" => "raduga_user",
			"NAME" => Loc::getMessage('RADUGA_DESC_USER_SECTION'),
			"SORT" => 70,
		),
	)
];

