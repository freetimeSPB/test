<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */

use Bitrix\Main\Localization\Loc; 

if(method_exists($this, 'setFrameMode'))
  $this->setFrameMode(true);
$id=$this->randString();

$obName='ajax_loading_'.$id;
$obDIV='ajax_loading_div'.$id;
?>
<div data-entity="<?=$obDIV?>">
</div>
<script>
	var <?=$obName?> = new JCCatalogAjaxLoadingComponent({
		siteId: '<?=CUtil::JSEscape($component->getSiteId())?>',
		templatePath: '<?=CUtil::JSEscape($this->GetFolder())?>',
		componentName: '<?=CUtil::JSEscape($arParams["COMPONENT_NAME"])?>',
		componentTemplate: '<?=CUtil::JSEscape($arParams["COMPONENT_TEMPLATE"])?>',
		componentParams: <?=CUtil::PhpToJSObject($arParams["COMPONENT_PARAMS"])?>,
		container: '<?=$obDIV?>',
		runTimeout: '<?=CUtil::JSEscape($arParams["COMPONENT_TIMEOUT"])?>',
		onlyMobile: '<?=CUtil::JSEscape($arParams["COMPONENT_ONLY_MOBILE"])?>',
		onlyDesktop: '<?=CUtil::JSEscape($arParams["COMPONENT_ONLY_DESKTOP"])?>',
		functionsAfterInit: <?=CUtil::PhpToJSObject($arParams["COMPONENT_FUNCTIONS_AFTER_INIT"])?>
	});
</script>