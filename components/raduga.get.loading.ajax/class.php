<?php
defined('B_PROLOG_INCLUDED') and (B_PROLOG_INCLUDED === true) or die();

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class radugaGetLoadingAjaxComponent extends \CBitrixComponent
{
    protected $errors = array();
	

    public function onPrepareComponentParams($arParams)
    {
		if( empty($arParams['COMPONENT_NAME'])){
            $this->errors[] = Loc::getMessage("RADUGA_GET_LOADING_AJAX_COMPONENT_NAME_NULL");
        }
		if( empty($arParams['COMPONENT_TEMPLATE'])){
            $this->errors[] = Loc::getMessage("RADUGA_GET_LOADING_AJAX_COMPONENT_TEMPLATE_NULL");
        }
       if(empty($arParams['COMPONENT_ONLY_MOBILE'])){
            $arParams['COMPONENT_ONLY_MOBILE']="N";
        }
		if(empty($arParams['COMPONENT_ONLY_DESKTOP'])){
            $arParams['COMPONENT_ONLY_DESKTOP']="N";
        }
        $arParams["CACHE_TIME"]    = isset($arParams["CACHE_TIME"]) ? $arParams["CACHE_TIME"] : 36000000;
        return $arParams;
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
    

    /**
     * Execute component
     */
    public function executeComponent()
    {
		$this->arResult=array();
        try
        {
            if ($this->hasErrors()) {
                $this->showErrors();
            } else {
				if ($this->startResultCache()) {
				   $this->includeComponentTemplate();
				}
			}
			} catch (SystemException $e) {
            ShowError($e->getMessage());
        }
    }
}
