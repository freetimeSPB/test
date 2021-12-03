<?
define("NO_KEEP_STATISTIC", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

use Bitrix\Main\Loader,
    Bitrix\Main\Application,
	Bitrix\Main\Localization\Loc,
	Bitrix\Main\Web\Json,
    Bitrix\Main\UserTable;

Loc::loadMessages(__FILE__);

$context  = Application::getInstance()->getContext();
$request  = $context->getRequest();

$isPost = ($request->isPost() && $request->get('ajax_contacts') == 'Y' && check_bitrix_sessid());

$return = '';
 
if ($isPost && Loader::includeModule('main')) {
  $action=$request->get('action');
  
  
  if($action == "contacts"){
	  $id = intval($request->get('uid'));
	  $cnt=0;
		$arUser=array();
		$rsUsers = UserTable::getList(array(
			 'filter' => array('=ID' => $id),
			 'select' => array(
			 'PERSONAL_MOBILE',
			 'UF_ADD_PHONE',
			 'UF_VIBER',
			 'UF_SKYPE',
			 'UF_WHATSAPP',
			 'PERSONAL_NOTES'
			 ),
		));
									
		if($arUser = $rsUsers->fetch()) {
		$return='<div class="owner-main-info-table table">';
				if($arUser['PERSONAL_MOBILE'] || $arUser['UF_ADD_PHONE']){
					$arPhones=array();
					$arPhones[]=$arUser['PERSONAL_MOBILE'];
					if(!empty($arUser['UF_ADD_PHONE']))
						$arPhones=array_merge($arPhones, $arUser['UF_ADD_PHONE']);
					$return.='<div class="trow">
					<div class="cell">
						<div class="owner-main-info__label"><span>'.Loc::getMessage('RADUGA_CATALOG_PAGE_CONTACT_PHONES').'</span></div>
					</div>
					<div class="cell">
							<div class="owner-main-info__value">';
							foreach($arPhones as $arPhone){
								$return.='<p><span>'.$arPhone.'</span></p>';
							}
					$return.='</div>
						</div>
					</div>';
					$cnt++;
				}
				if($arUser['UF_WHATSAPP']){
					$return.='<div class="trow">
						<div class="cell">
							<div class="owner-main-info__label"><span>WhatsApp</span></div>
						</div>
						<div class="cell">
							<div class="owner-main-info__value">
								<p><span>'.$arUser['UF_WHATSAPP'].'</span></p>
							</div>
						</div>
					</div>';
					$cnt++;
				}		
				if($arUser['UF_VIBER']){
					$return.='<div class="trow">
						<div class="cell">
							<div class="owner-main-info__label"><span>Viber</span></div>
						</div>
						<div class="cell">
							<div class="owner-main-info__value">
								<p><span>'.$arUser['UF_VIBER'].'</span></p>
							</div>
						</div>
					</div>';
					$cnt++;
				}
				if($arUser['UF_SKYPE']){
					$return.='<div class="trow">
						<div class="cell">
							<div class="owner-main-info__label"><span>Skype</span></div>
						</div>
						<div class="cell">
							<div class="owner-main-info__value">
								<p><span>'.$arUser['UF_SKYPE'].'</span></p>
							</div>
						</div>
					</div>';
					$cnt++;
				}
					$return.='</div>';
					
				if($cnt==0){
					$return.='<div class="personal_empty_contacts">
							<p>'.Loc::getMessage('RADUGA_CATALOG_PAGE_CONTACT_EMPTY').'</p>
					</div>';
				}
				
				if($arUser['PERSONAL_NOTES']){
					$return.='<div class="personal_notes">
							<p>'.$arUser['PERSONAL_NOTES'].'</p>
					</div>';
				}
				if($cnt>0){
				$return.='<div class="personal_portal_info">
							<p>'.Loc::getMessage('RADUGA_CATALOG_PAGE_CONTACT_ON_RADUGA_INFO').'</p>
					</div>';
				}
		}
	} elseif($action == "status"){
		$id = intval($request->get('uid'));
		$return = array(
			 'status'  => 'off',
		);
		
		if(CUser::IsOnLine($id, 120)){
			$return['status']='on';
		}
		
		if($return) {
			$APPLICATION->RestartBuffer();
			header('Content-Type: application/json');
			echo Json::encode($return);
			die();
		}
			
	} elseif($action == "getSendMessageForm"){
		
		$id = intval($request->get('uid'));
		$elementID = intval($request->get('elementID'));
		
		$return='<div class="popup-table table">
				<div class="cell">
					<div class="popup-content">
						<div class="popup-close fa fa-times"></div>
						<div class="popup-addclientm-body">
							<div class="popup-addclientm-content">
								<div class="popup-addclientm__title">'.Loc::getMessage('RADUGA_CATALOG_PAGE_CONTACT_NEW_MESSAGE_FORM_TITLE').'</div>
								<form action="#" class="popup-addclientm-form">
								<input type="hidden" name="OWNER_ID" value="'.$id.'">
								<input type="hidden" name="ELEMENT_ID" value="'.$elementID.'">
								<div class="form-input">
											<div class="form__label">'.Loc::getMessage('RADUGA_CATALOG_PAGE_CONTACT_NEW_MESSAGE_FORM_DETAIL_TEXT_NAME').'</div>
											<textarea name="DETAIL_TEXT" data-value="" class="input req"
											data-error="'.Loc::getMessage('RADUGA_CATALOG_PAGE_CONTACT_NEW_MESSAGE_FORM_DETAIL_TEXT_ERROR').'"
											></textarea>
										</div>';
										if ($USER->IsAuthorized()){
									$return.='<input type="hidden" name="CLIENT_ID" value="'.$USER->GetID().'">';
									$return.='<input type="hidden" name="CLIENT_NAME" value="'.$USER->GetFullName().'">';
									$return.='<input type="hidden" name="CLIENT_EMAIL" value="'.$USER->GetEmail().'">';
								} else {	
									$return.='<div class="form__label">'.Loc::getMessage('RADUGA_CATALOG_PAGE_CONTACT_NEW_MESSAGE_FORM_NAME_NAME').'</div>
													<div class="form-input">
																	<input type="text"
																	   class="input req"
																	   name="CLIENT_NAME"
																	   value=""
																	   data-error="'.Loc::getMessage('RADUGA_CATALOG_PAGE_CONTACT_NEW_MESSAGE_FORM_NAME_ERROR').'">
													</div>
													
									<div class="form__label">'.Loc::getMessage('RADUGA_CATALOG_PAGE_CONTACT_NEW_MESSAGE_FORM_EMAIL_NAME').'</div>
											<div class="form-input">
															<input type="text"
															   class="input email req"
															   name="CLIENT_EMAIL"
															   value=""
															   data-error="'.Loc::getMessage('RADUGA_CATALOG_PAGE_CONTACT_NEW_MESSAGE_FORM_EMAIL_ERROR').'">
											</div>';
										$return.='<input type="hidden" name="USER_ID" value="0">';	
									}
									
									$return.='<div class="form-privacy">
										<div class="check active"><a rel="nofollow" href="/" target="_blank">'.Loc::getMessage('RADUGA_CATALOG_PAGE_CONTACT_NEW_MESSAGE_FORM_PRIVACY_TITLE').'</a>
											<input type="checkbox" value="Y" checked name="PRIVACY_ACCEPTED">
										</div>
										<div class="api-rules-error api-privacy-error">'.Loc::getMessage('RADUGA_CATALOG_PAGE_CONTACT_NEW_MESSAGE_FORM_PRIVACY_ERROR').'</div>
									</div>
										
									<div class="popup-addclientm-form-buttons">
										<button type="submit" class="popup-addclientm-form-buttons__btn form__btn btn">'.Loc::getMessage('RADUGA_CATALOG_PAGE_CONTACT_NEW_MESSAGE_FORM_BTN').'</button>
										<a href="" class="popup-addclientm-form-buttons__btn form__cancel popup__close">'.Loc::getMessage('RADUGA_CATALOG_PAGE_CONTACT_NEW_MESSAGE_FORM_BTN_CANCEL').'</a>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>';
	} elseif($action == "sendform"){
				$data = $request->get('objectData');
		
		if (!empty($data) && Loader::includeModule('forum') && Loader::includeModule('iblock')) {
			
			$return=array('result'=>"ERROR", 'message'=>'');
			
			if(isset($data["OWNER_ID"]) && $data["OWNER_ID"]>0){
				$title = Loc::getMessage('RADUGA_CATALOG_PAGE_CONTACT_NEW_MESSAGE_FORM_MESSAGE_PRIVATE_TITLE');

				$data["DETAIL_TEXT"] = preg_replace( "/\n\s+/", "\n", rtrim(html_entity_decode(strip_tags($data["DETAIL_TEXT"]))));
	            $data["DETAIL_TEXT"]=HTMLToTxt($data["DETAIL_TEXT"]);
	
				if(!isset($data["CLIENT_ID"])){
					$data["CLIENT_ID"] = \UserHandlerClass::FindUser($data["CLIENT_EMAIL"], false);
					if($data["CLIENT_ID"]<=0){
						
						$pieces = explode(" ", $data["CLIENT_NAME"]);
						$arFields["NAME"] = $pieces[0];
						$arFields["LAST_NAME"] = isset($pieces[1]) ? $pieces[1] : '';
						// $arFields["SKIP_INFORM"]="Y";
						$arFields["EMAIL"]=$data["CLIENT_EMAIL"];
						$data["CLIENT_ID"] = \UserHandlerClass::MakeNewUser($arFields);
					}
				}
	            
				if($data["CLIENT_ID"] > 0){
					
				$arFields = array(
					"AUTHOR_ID" => (int)$data["CLIENT_ID"],
					"USER_ID" => (int)$data["OWNER_ID"],
					"POST_SUBJ" => $title,
					"POST_MESSAGE" => $data["DETAIL_TEXT"],
					"COPY_TO_OUTBOX" => "Y",
					"USE_SMILES" => "N");
			
				if($newMID = CForumPrivateMessage::Send($arFields))
				{
						BXClearCache(true, "/bitrix/forum/user/".$data["OWNER_ID"]."/");
						$arComponentPath = array("bitris:forum");
						foreach ($arComponentPath as $path)
						{
							$componentRelativePath = CComponentEngine::MakeComponentPath($path);
							$arComponentDescription = CComponentUtil::GetComponentDescr($path);
							if (strLen($componentRelativePath) <= 0 || !is_array($arComponentDescription)):
								continue;
							elseif (!array_key_exists("CACHE_PATH", $arComponentDescription)):
								continue;
							endif;
							$path = str_replace("//", "/", $componentRelativePath."/user".$data["OWNER_ID"]);
							if ($arComponentDescription["CACHE_PATH"] == "Y")
								$path = "/".SITE_ID.$path;
							if (!empty($path))
								BXClearCache(true, $path);
						}
						
							$arOwner = UserTable::getList(array(
								 'filter' => array('=ID' => (int)$data["OWNER_ID"]),
								 'select' => array('EMAIL', 'NAME', 'SECOND_NAME'),
							))->fetch();
					
						if (!empty($arOwner["EMAIL"]))
						{
							$event = new CEvent;
							$arFields = Array(
								"FROM_NAME" => $data["CLIENT_NAME"],
								"FROM_USER_ID" => (int)$data["CLIENT_ID"],
								"FROM_EMAIL" => $data["CLIENT_EMAIL"],
								"TO_NAME" => $arOwner["NAME"]." ".$arOwner["SECOND_NAME"],
								"TO_USER_ID" => (int)$data["OWNER_ID"],
								"TO_EMAIL" => $arOwner["EMAIL"],
								"SUBJECT" => $title,
								"MESSAGE" => $data["DETAIL_TEXT"],
								"MESSAGE_DATE" => date("d.m.Y H:i:s"),
								"MESSAGE_LINK" => "http://poraduge.ru/personal/messages/pm_read.php?MID=".$newMID,
							);
							$event->Send("NEW_FORUM_PRIVATE_MESSAGE", SITE_ID, $arFields);
						}
					$return=array('result'=>"OK", 'message'=>$newMID);
				}
			  }
			}
		  if($return) {
				$APPLICATION->RestartBuffer();
				header('Content-Type: application/json');
				echo Json::encode($return);
				die();
			}
		}
	}elseif($action == "getmessagebox"){
		$return='<div class="popup-table table">
				<div class="cell">
					<div class="popup-content">
						<div class="popup-close fa fa-times"></div>
						<div class="popup-addclientm-alert-body">
							<div class="popup-addclientm-alert-content">
								<span class="fa fa-check-circle"></span>
								<div class="popup-addclientm-alert-content-body">
								'.Loc::getMessage('RADUGA_CATALOG_PAGE_CONTACT_NEW_MESSAGE_FORM_THANK_MESSAGE').'
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>';
	}
}

echo $return;
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
?>