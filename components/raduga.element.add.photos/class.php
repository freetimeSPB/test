<?php
defined('B_PROLOG_INCLUDED') and (B_PROLOG_INCLUDED === true) or die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Engine\Contract\Controllerable;

Loc::loadMessages(__FILE__);

class radugaElementAddPhotosAjaxComponent extends CBitrixComponent implements Controllerable
{
    protected $errors = array();

    public function configureActions()
    {
        return [
            'getElementPhotos' => [
                'prefilters' => [
                    new ActionFilter\HttpMethod(
                    array(ActionFilter\HttpMethod::METHOD_GET, ActionFilter\HttpMethod::METHOD_POST)
                ),
                    ],
            ],
        ];
    }
	
	public function getElementPhotosAction($params)
    {
	   $this->arParams = $params;
	   $this->arResult['ITEMS'] = array();
		try
		{
            if ($this->startResultCache()) {
                $this->_checkModules();
				$this->arResult['ITEMS'] = $this->getPhotos();

                if (count($this->arResult['ITEMS'])<3) {
                    $this->AbortResultCache();
                }

                $this->SetResultCacheKeys(array(
                    "ITEMS",
                ));
				
                $this->endResultCache();
            }
			
		} catch (SystemException $e) {
			ShowError($e->getMessage());
		}
		
	   return $this->arResult;
    }
	
    public function onPrepareComponentParams($arParams)
    {
        $arParams["IBLOCK_ID"] = intval($arParams["IBLOCK_ID"]);
        if ($arParams["IBLOCK_ID"] <= 0) {
            $this->errors[] = Loc::getMessage("RADUGA_ELEMENT_ADD_PHOTOS_IBLOCK_NULL");
        }

		$arParams["ELEMENT_ID"] = intval($arParams["ELEMENT_ID"]);
        if ($arParams["ELEMENT_ID"] <= 0) {
            $this->errors[] = Loc::getMessage("RADUGA_ELEMENT_ADD_PHOTOS_ELEMENT_ID_NULL");
        }
		
        $arParams["PHOTOS_CODE"] = (strlen($arParams["PHOTOS_CODE"]) > 0) ? trim($arParams["PHOTOS_CODE"]) : false;

        if (!$arParams["PHOTOS_CODE"]) {
            $this->errors[] = Loc::getMessage("RADUGA_ELEMENT_ADD_PHOTOS_PHOTOS_CODE_NULL");
        }

        $arParams["MAX_PHOTOS_COUNT"] = (intval($arParams["MAX_PHOTOS_COUNT"]) > 0) ? intval($arParams["MAX_PHOTOS_COUNT"]) : 2;
        $arParams["CACHE_TIME"]    = isset($arParams["CACHE_TIME"]) ? $arParams["CACHE_TIME"] : 36000000;
		
        return $arParams;
    }

    private function _checkModules()
    {
        if (!Loader::includeModule('iblock')) {
            throw new Exception(Loc::getMessage("RADUGA_ELEMENT_ADD_PHOTOS_MODULES_NULL"));
        }

    }

    protected function hasErrors()
    {
        return (count($this->errors) > 0);
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
	
    private function getPhotos()
    {
		$arItemPhotos= array();
		$resItems = CIBlockElement::GetList(
					 Array(),
					 array(
					   'IBLOCK_ID' => $this->arParams["IBLOCK_ID"],
					   '=ID' => $this->arParams["ELEMENT_ID"],
					   'ACTIVE'=>'Y', 
					   'GLOBAL_ACTIVE'=>'Y',
					 ),
					 false,
					 false,
					 Array(
					     "DETAIL_PICTURE",
						 "PROPERTY_".$this->arParams["PHOTOS_CODE"],
					 )
					);
					
					if ($arItem = $resItems->Fetch()){
						if($arItem["DETAIL_PICTURE"]){
							$fileTmp = CFile::ResizeImageGet(
									$arItem["DETAIL_PICTURE"],
									array('width' => 250, 'height' => 195),
									BX_RESIZE_IMAGE_EXACT,
									false
								);
							
								$fileTmp["src"] = CUtil::GetAdditionalFileURL($fileTmp["src"], true);
								$arItemPhotos[] = $fileTmp["src"];
						}
						//if(!empty($arItem["PROPERTY_ADD_PHOTOS_VALUE"])){
							$arPhotos = unserialize($arItem["PROPERTY_ADD_PHOTOS_VALUE"]);
			                $i=0;
							foreach($arPhotos["VALUE"] as $arPhoto){
								
								if($i >=  $this->arParams["MAX_PHOTOS_COUNT"])
									break;
								
								$fileTmp = CFile::ResizeImageGet(
									$arPhoto,
									array('width' => 250, 'height' => 195),
									BX_RESIZE_IMAGE_EXACT,
									false
								);
							
								$fileTmp["src"] = CUtil::GetAdditionalFileURL($fileTmp["src"], true);
								$arItemPhotos[] = $fileTmp["src"];
								 $i++;
							}
							unset($arPhotos);
						//}
					}
					unset($resItems, $arItem, $fileTmp);
					return $arItemPhotos;
   }

    /**
     * Execute component
     */
    public function executeComponent()
    {
       
    }
}