<?
AddEventHandler("forum", "onBeforePMSend", Array("MessagesHandlerClass", "onBeforePMSendHandler"));
AddEventHandler("main", "OnBeforeEventAdd", array("MessagesHandlerClass", "OnBeforeEventAddHandler"));
AddEventHandler("sale", "OnOrderStatusSendEmail", array("MessagesHandlerClass", "MyOnOrderStatusSendEmail"));
AddEventHandler("sale", "OnOrderNewSendEmail", array("MessagesHandlerClass", "OnOrderNewSendEmailHandler"));

class MessagesHandlerClass
   {
	   function onBeforePMSendHandler(&$arFields)
		{
			 $arFields['POST_SUBJ'] = str_replace("Re:", "Re: ", $arFields['POST_SUBJ']);
			 $arFields['POST_SUBJ'] = preg_replace('/[\s]{2,}/', ' ', $arFields['POST_SUBJ']);
		}
		
		function OnBeforeEventAddHandler(&$event, &$lid, &$arFields)
		{
			switch ($event) {
					case "NEW_FORUM_PRIVATE_MESSAGE":
						if($lid=="s1"){
							$arFields['SUBJECT'] = str_replace("Re:", "Re: ", $arFields['SUBJECT']);
			                $arFields['SUBJECT'] = preg_replace('/[\s]{2,}/', ' ', $arFields['SUBJECT']);
			                $arFields['TO_NAME'] = preg_replace('/[\s]{2,}/', ' ', $arFields['TO_NAME']);
			                $arFields['MESSAGE_LINK'] = trim(str_replace("http", "https", $arFields['MESSAGE_LINK']));
							
							if(isset($arFields['TO_USER_ID']) && $arFields['TO_USER_ID']>0){
								$arFields['MESSAGE_LINK']=$arFields['MESSAGE_LINK']."&USER_ID=".$arFields['TO_USER_ID']."&autologin=".\UserHandlerClass::GetAutologinHash(intval($arFields['TO_USER_ID']));
							}
							$arFields['MESSAGE']=TruncateText($arFields['MESSAGE'], 70);
						}
						break;
				}
		}
		
		function MyOnOrderStatusSendEmail($ID, &$eventName, &$arFields, $val)
		{
				CModule::IncludeModule("sale");
				CModule::IncludeModule("iblock");

					$arOrder = CSaleOrder::GetByID($ID);
					
					$arFields["USER_ID"]=$arOrder["USER_ID"];
					$arFields["USER_NAME"]=$arOrder["USER_NAME"];
					$arFields["HASH"]= \UserHandlerClass::GetAutologinHash(intval($arFields["USER_ID"]));

					$dbOrderProps = CSaleOrderPropsValue::GetList(
						array("SORT" => "ASC"),
						array("=ORDER_ID" => $arOrder["ID"], "CODE"=>array("COMPANY_UF_CRM_1580730957"))
					);
					
					while ($arOrderProps = $dbOrderProps->GetNext()){
						if($arOrderProps["VALUE"]){
						   $arFields["COMPANY_ID"]=$arOrderProps["VALUE"];
						}
					}

					if(!empty($arFields["COMPANY_ID"])){
					$resParentElement=array();
					$arParentElement=array();
					
							$resParentElement = CIBlockElement::GetList(
							Array(),
							Array(
								"IBLOCK_ID"=>CATALOG_IBLOCK_ID,
								"=ID"=>$arFields["COMPANY_ID"],
							), 
							false,
							false,
							array("ID", "NAME", "IBLOCK_SECTION_ID", "PROPERTY_ACCOMMODATION_TYPE", "DETAIL_PAGE_URL")
							);
							
							if($arParentElement = $resParentElement->GetNext()){
								if(!empty($arParentElement["PROPERTY_ACCOMMODATION_TYPE_ENUM_ID"])){
									$property_enums = CIBlockPropertyEnum::GetList(Array(), Array("IBLOCK_ID"=>CATALOG_IBLOCK_ID, "ID"=>$arParentElement["PROPERTY_ACCOMMODATION_TYPE_ENUM_ID"]));
									if ($enum_fields = $property_enums->GetNext()) {
										if(!empty($enum_fields["XML_ID"]))
											$arParentElement["PROPERTY_ACCOMMODATION_TYPE_VALUE"]=$enum_fields["XML_ID"];
									}
									unset($enum_fields, $property_enums);
								}
								$arFields["COMPANY_ID"]=$arOrderProps["VALUE"];
								$arFields["COMPANY_URL"]="/personal/accommodations/detail.php?elementID=".$arParentElement["ID"];
								$arFields["COMPANY_DETAIL_PAGE_URL"]=$arParentElement["DETAIL_PAGE_URL"];
								$arFields["COMPANY_NAME"]=$arParentElement["NAME"];
								$arFields["COMPANY_ACCOMMODATION_TYPE"]=$arParentElement["PROPERTY_ACCOMMODATION_TYPE_VALUE"];
							}
				  }
		}
		
		function OnOrderNewSendEmailHandler($ID, &$eventName, &$arFields)
		{
		    	CModule::IncludeModule("sale");
				CModule::IncludeModule("iblock");
				
					$arOrder = CSaleOrder::GetByID($ID);

					$arFields["USER_ID"]=$arOrder["USER_ID"];
					$arFields["USER_NAME"]=$arOrder["USER_NAME"];
					$arFields["HASH"]= \UserHandlerClass::GetAutologinHash(intval($arFields["USER_ID"]));

					$dbOrderProps = CSaleOrderPropsValue::GetList(
						array("SORT" => "ASC"),
						array("=ORDER_ID" => $arOrder["ID"], "CODE"=>array("COMPANY_UF_CRM_1580730957"))
					);
					
					while ($arOrderProps = $dbOrderProps->GetNext()){
						if($arOrderProps["VALUE"]){
						   $arFields["COMPANY_ID"]=$arOrderProps["VALUE"];
						}
					}

					if(!empty($arFields["COMPANY_ID"])){
					$resParentElement=array();
					$arParentElement=array();
					
							$resParentElement = CIBlockElement::GetList(
							Array(),
							Array(
								"IBLOCK_ID"=>CATALOG_IBLOCK_ID,
								"=ID"=>$arFields["COMPANY_ID"],
							), 
							false,
							false,
							array("ID", "NAME", "IBLOCK_SECTION_ID", "PROPERTY_ACCOMMODATION_TYPE", "DETAIL_PAGE_URL")
							);
							
							if($arParentElement = $resParentElement->GetNext()){
								if(!empty($arParentElement["PROPERTY_ACCOMMODATION_TYPE_ENUM_ID"])){
									$property_enums = CIBlockPropertyEnum::GetList(Array(), Array("IBLOCK_ID"=>CATALOG_IBLOCK_ID, "ID"=>$arParentElement["PROPERTY_ACCOMMODATION_TYPE_ENUM_ID"]));
									if ($enum_fields = $property_enums->GetNext()) {
										if(!empty($enum_fields["XML_ID"]))
											$arParentElement["PROPERTY_ACCOMMODATION_TYPE_VALUE"]=$enum_fields["XML_ID"];
									}
									unset($enum_fields, $property_enums);
								}
								$arFields["COMPANY_ID"]=$arOrderProps["VALUE"];
								$arFields["COMPANY_URL"]="/personal/accommodations/detail.php?elementID=".$arParentElement["ID"];
								$arFields["COMPANY_DETAIL_PAGE_URL"]=$arParentElement["DETAIL_PAGE_URL"];
								$arFields["COMPANY_NAME"]=$arParentElement["NAME"];
								$arFields["COMPANY_ACCOMMODATION_TYPE"]=$arParentElement["PROPERTY_ACCOMMODATION_TYPE_VALUE"];
							}
				  }
		}
		
   }
?>