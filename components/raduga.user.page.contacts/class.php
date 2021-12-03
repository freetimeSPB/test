<?php
defined('B_PROLOG_INCLUDED') and (B_PROLOG_INCLUDED === true) or die();

use Bitrix\Iblock\Component\Tools;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\UserTable;
use Bitrix\Main\SystemException;

Loc::loadMessages(__FILE__);
	
class radugaUserPageContactsComponent extends CBitrixComponent
{
    protected $errors = array();

    public function onPrepareComponentParams($arParams): array
    {
        $arParams["IBLOCK_ID"] = intval($arParams["IBLOCK_ID"]);
        if ($arParams["IBLOCK_ID"] <= 0) {
            $this->errors[] = Loc::getMessage("RADUGA_USER_PAGE_CONTACTS_CLASS_IBLOCK_NULL");
        }

        $arParams["PROPERTY_CODE"] = (strlen($arParams["PROPERTY_CODE"]) > 0) ? trim($arParams["PROPERTY_CODE"]) : false;

        if (!$arParams["PROPERTY_CODE"]) {
            $this->errors[] = Loc::getMessage("RADUGA_USER_PAGE_CONTACTS_CLASS_PROPERTY_NULL");
        }

		$arParams["ELEMENT_ID"] = intval($arParams["ELEMENT_ID"]);
		
		$arParams["USER_ID"] = intval($arParams["USER_ID"]);
		
		if (!$arParams["USER_ID"]>0) {
            $this->Set404();
        }
		
        $arParams["CACHE_TIME"]    = $arParams["CACHE_TIME"] ?? 36000;
		
        return $arParams;
    }

    private function _checkModules(): bool
    {
        if (!Loader::includeModule('iblock')) {
            throw new Exception(Loc::getMessage("RADUGA_USER_PAGE_CONTACTS_MODULE_IBLOCK_NULL"));
        }

        return true;
    }

    protected function hasErrors(): bool
    {
        return (count($this->errors) > 0);
    }
	
	private function Set404()
    {
		Tools::process404(
									''
									,true
									,true
									,false
									,''
								);
	}
	
    protected function showErrors()
    {
        if (count($this->errors) <= 0) {
            return;
        }

        foreach ($this->errors as $error) {
            ShowError($error);
        }

    }

	private function getUserObjectsCnt()
    {
	  $resObjects = CIBlockElement::GetList(
		  array(),
		  array("IBLOCK_ID"=>$this->arParams["IBLOCK_ID"], "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y", "GLOBAL_ACTIVE"=>"Y", "=PROPERTY_".$this->arParams["PROPERTY_CODE"]=>$this->arResult["USER"]["ID"]),
		  false,
		  false,
		  array("ID")
	  );
	  $this->arResult["USER_OBJECTS_CNT"]=intval($resObjects->SelectedRowsCount());
	  unset($resObjects);
	}
	
	private function getUser()
    {
        $rsUsers = UserTable::getList(array(
			 'filter' => array('=ID' => $this->arParams["USER_ID"], "Bitrix\Main\UserGroupTable:USER.GROUP_ID"=>10),
			 'select' => array(
			 'ID',
			 'NAME',
			 'LAST_NAME',
			 'PERSONAL_PHOTO',
			 'DATE_REGISTER',
			 'UF_ABOUT'
			 ),
		));
		if($arUser = $rsUsers->fetch()) {
			$arUser['OWNER_URL'] = "/owner/".$arUser["ID"]."/";
			$arUser["DATE_REGISTER"]=$arUser["DATE_REGISTER"]->format("d.m.Y");
			
			if(!empty($arUser['LAST_NAME']))
				$arUser['NAME']=$arUser['NAME']." ".strtoupper(mb_substr($arUser['LAST_NAME'],0,1,"UTF-8")).".";
			
			if($arUser["PERSONAL_PHOTO"]){
				$arUser["PERSONAL_PHOTO_SOCIAL"]=CFile::GetPath($arUser["PERSONAL_PHOTO"]);
				$arFileTmp = CFile::ResizeImageGet($arUser["PERSONAL_PHOTO"], array("width" => 95, "height" => 95), BX_RESIZE_IMAGE_EXACT, true);
				$arFileTmp['src'] = CUtil::GetAdditionalFileURL($arFileTmp['src'], true);
				$arUser["PERSONAL_PHOTO"] = array_change_key_case($arFileTmp, CASE_UPPER);
				$arUser["ONLINE"]=false;
			}
		} else {
			 $this->Set404();
		}
		$this->arResult["USER"]=$arUser;
		unset($arUser, $rsUsers, $arFileTmp);
    }
	
    /**
     * Execute component
     */
    public function executeComponent()
    {
        try
        {
            if ($this->hasErrors()) {
                $this->showErrors();
                $this->arResult = array();
            }

			$this->arResult["USER_OBJECTS_CNT"]=0;
			$this->arResult["USER"]=array();
			
            if ($this->startResultCache()) {
                $this->_checkModules();
				$this->getUser();

                if (empty($this->arResult['USER'])) {
                    $this->AbortResultCache();
                } else {
					$this->getUserObjectsCnt();
				}

                $this->SetResultCacheKeys(array(
                    "USER",
					"USER_OBJECTS_CNT"
                ));

                $this->includeComponentTemplate();
            }
        } catch (SystemException $e) {
            ShowError($e->getMessage());
        }
    }
}