<?
define("NO_KEEP_STATISTIC", true);
use Bitrix\Main\Application;
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
global $APPLICATION;

$context  = Application::getInstance()->getContext();
$request  = $context->getRequest();

$isPost = ($request->isPost() && $request->get('get_loading_ajax') == 'Y' && check_bitrix_sessid());

$return = array();
 
if ($isPost) {
	
	$ComponentName = $request->get('name');
	$ComponentTamplate = $request->get('template');
	$ComponentParams = $request->get('data');
	
	$APPLICATION->ShowAjaxHead();
	$APPLICATION->IncludeComponent($ComponentName, $ComponentTamplate,  $ComponentParams, false);

}
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
?>