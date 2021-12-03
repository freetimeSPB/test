<?php
defined('B_PROLOG_INCLUDED') and (B_PROLOG_INCLUDED === true) or die();

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$arComponentDescription = [
    'NAME' => Loc::getMessage('RADUGA_GET_LOADING_AJAX_NAME'),
    'DESCRIPTION' => Loc::getMessage('RADUGA_GET_LOADING_AJAX_DESCRIPTION'),
    'SORT' => 170,
    'CACHE_PATH' => 'Y',
	"PATH" => array(
		"ID" => "raduga",
		"NAME" => Loc::getMessage('RADUGA_DESC_SECTION'),
		"CHILD" => array(
			"ID" => "raduga_seo",
			"NAME" => Loc::getMessage('RADUGA_DESC_SEO_SECTION'),
			"SORT" => 70,
		),
	)];