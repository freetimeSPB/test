<?defined('B_PROLOG_INCLUDED') and (B_PROLOG_INCLUDED === true) or die();

use Bitrix\Main\Localization\Loc;
Loc::loadMessages(dirname(__FILE__) . '/template.php');

global $APPLICATION;
	
	
	$word_limit=30;
	
	$title=$arResult['USER']['NAME'].".".Loc::getMessage('RADUGA_CATALOG_PAGE_CONTACT_BROWSER_TITLE_1').$arResult['USER']["DATE_REGISTER"].Loc::getMessage('RADUGA_CATALOG_PAGE_CONTACT_BROWSER_TITLE_2')." ".$arResult["USER_OBJECTS_CNT"];
	$description =$arResult['USER']['NAME'].".".Loc::getMessage('RADUGA_CATALOG_PAGE_CONTACT_BROWSER_TITLE_3').$arResult['USER']["DATE_REGISTER"].Loc::getMessage('RADUGA_CATALOG_PAGE_CONTACT_BROWSER_TITLE_2').$arResult["USER_OBJECTS_CNT"];
	
	$APPLICATION->SetTitle($title);
	$APPLICATION->SetPageProperty("title", $title);
	if(!empty($arResult['USER']['UF_ABOUT'])){
		$str=strip_tags($str);
		preg_match("/.{".$word_limit."}[^.!;?]*[.!;?]/si", $arResult['USER']['UF_ABOUT'].". ", $matches);
		$description=$matches[0]."...";
	}
	$APPLICATION->SetPageProperty("description", $description);
	$APPLICATION->SetPageProperty("keywords", '');
	$APPLICATION->AddChainItem($arResult['USER']['NAME'], "");
	
	$APPLICATION->AddHeadString('<meta property="og:title" content="' . $title . '" />');
	$APPLICATION->AddHeadString('<meta property="og:description" content="' . htmlspecialcharsbx($description) . '" />');
	$APPLICATION->AddHeadString('<meta property="og:url" href="https://poraduge.ru' . $APPLICATION->GetCurPage() . '" />');
	
	if($arResult["USER"]["PERSONAL_PHOTO_SOCIAL"]){
		$APPLICATION->AddHeadString('<meta property="og:image" content="https://poraduge.ru' .$arResult["USER"]["PERSONAL_PHOTO_SOCIAL"]. '" />');
	} else {
		$APPLICATION->AddHeadString('<meta property="og:image" content="https://poraduge.ru' .$templateFolder.'/img/img-profile.jpeg" />');
	}
?>