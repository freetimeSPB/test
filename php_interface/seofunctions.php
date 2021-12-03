<?php
namespace Raduga\SeoFunctions;

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/iblock/lib/template/functions/fabric.php");

\Bitrix\Main\EventManager::getInstance()->addEventHandler(
    "iblock",
    "OnTemplateGetFunctionClass",
    "\Raduga\SeoFunctions\getFunctionClass"
);

/*
sections — родительские секции начиная с верхнего уровня. Пример: {=this.sections.name} — Названия родительских разделов.
iblock — инфоблок текущего элемента или раздела. Пример: {=iblock.PreviewText} — описание инфоблока
property — свойство. Пример: у меня есть свойство инфоблока Наценка — 'EXTRA_PAY' {=this.property.EXTRA_PAY}.
Чтобы получить значение пользовательского свойства раздела 'UF_COLOR' {=this.parent.property.color}
Данные торгового каталога(в редакции выше, чем ‘Малый бизнес’):
this.catalog.sku.property.COLOR — свойство торгового предложения ‘Цвет’. Пример: {=distinct this.catalog.sku.property.COLOR}.
this.catalog.sku.price.BASE — цены торговых предложений типа ‘BASE’. Пример: {=min this.catalog.sku.price.BASE}.
this.catalog.weight — вес товара. Пример: {=this.catalog.weight}.
this.catalog.measure — единица измерения товара. Пример: {=this.catalog.measure}.
catalog.store — склады. Пример: {=concat catalog.store ", "}.
3. Функции:
lower — приведет значение к нижнему регистру. Пример: {=lower this.Name}
upper — приведет значение к верхнему регистру. Пример: {=upper this.Name}
limit — ограничить элементы по разделителю. Пример: {=limit {=this.PreviewText} "." 2} оставить текст до второй точки, начиная с начала.
concat — задаётся разделитель и несколько строк объединяются через разделитель. Пример: {=concat this.sections.name " / "} — все названия родительских разделов будут соединены с помощью слэша. На выходе ‘Одежда/Обувь/кеды’.
min — находит минимальный элемент. Пример: {=min this.catalog.sku.price.BASE}.
max — находит максимальный элемент. Пример: {=max this.catalog.sku.price.BASE}.
distinct — оставит только значения без повторения(уникальные).
translit — транслитерация значения. Пример: {=translit this.Name}
*/


function getFunctionClass(\Bitrix\Main\Event $event) 
{

    $arParams = $event->getParameters();

    $functionName = $arParams[0];

    switch ($functionName)
    {

        case 'pricenotnull':

            $result = new \Bitrix\Main\EventResult(1,'\Raduga\SeoFunctions\pricenotnull');

            break;

		case 'path':

            $result = new \Bitrix\Main\EventResult(1,'\Raduga\SeoFunctions\path');

            break;
			
		case 'pathbreaks':

            $result = new \Bitrix\Main\EventResult(1,'\Raduga\SeoFunctions\pathbreaks');

            break;
		case 'framestring':

            $result = new \Bitrix\Main\EventResult(1,'\Raduga\SeoFunctions\framestring');

            break;
		case 'getvodoemtype':

            $result = new \Bitrix\Main\EventResult(1,'\Raduga\SeoFunctions\getvodoemtype');

            break;	
		case 'getparentname':

            $result = new \Bitrix\Main\EventResult(1,'\Raduga\SeoFunctions\getparentname');

            break;
		case 'getparentnameornotice':

            $result = new \Bitrix\Main\EventResult(1,'\Raduga\SeoFunctions\getparentnameornotice');

            break;	
		case 'getparentnamewhere':

            $result = new \Bitrix\Main\EventResult(1,'\Raduga\SeoFunctions\getparentnamewhere');

            break;
		case 'getparentnamewhere1':

            $result = new \Bitrix\Main\EventResult(1,'\Raduga\SeoFunctions\getparentnamewhere1');

            break;
		case 'getelementparentnamewhere':

            $result = new \Bitrix\Main\EventResult(1,'\Raduga\SeoFunctions\getelementparentnamewhere');

            break;
		case 'getnamewithnotice':

            $result = new \Bitrix\Main\EventResult(1,'\Raduga\SeoFunctions\getnamewithnotice');

            break;
		case 'getparentnameclear':

            $result = new \Bitrix\Main\EventResult(1,'\Raduga\SeoFunctions\getparentnameclear');

            break;	
		case 'getparentnamethen':

            $result = new \Bitrix\Main\EventResult(1,'\Raduga\SeoFunctions\getparentnamethen');

            break;	
		case 'getratingname':

            $result = new \Bitrix\Main\EventResult(1,'\Raduga\SeoFunctions\getratingname');

            break;	
		case 'makenewtitle':

            $result = new \Bitrix\Main\EventResult(1,'\Raduga\SeoFunctions\makenewtitle');

            break;
		case 'makenewkeywords':

            $result = new \Bitrix\Main\EventResult(1,'\Raduga\SeoFunctions\makenewkeywords');

            break;
		case 'makenewdescription':

            $result = new \Bitrix\Main\EventResult(1,'\Raduga\SeoFunctions\makenewdescription');

            break;
		case 'makenewsectiontitle':

            $result = new \Bitrix\Main\EventResult(1,'\Raduga\SeoFunctions\makenewsectiontitle');

            break;
		case 'makenewsectionchain':

            $result = new \Bitrix\Main\EventResult(1,'\Raduga\SeoFunctions\makenewsectionchain');

            break;	
		case 'year':

            $result = new \Bitrix\Main\EventResult(1,'\Raduga\SeoFunctions\year');

            break;
        // здесь можно добавить описания других функций

    }

    return $result;
}

class path extends \Bitrix\Iblock\Template\Functions\FunctionBase
{
    public function onPrepareParameters(\Bitrix\Iblock\Template\Entity\Base $entity, $parameters)
    {
        $arguments = array();
		
        /** @var \Bitrix\Iblock\Template\NodeBase $parameter */
		
        foreach ($parameters as $parameter)
        {
            $arguments[] = $parameter->process($entity);
        }
        return $arguments;
    }
    public function calculate(array $arParams)
    {
        /*
		{=path parent.Name this.Name}
		
		[0] parent.Name
        [1] this.Name
        [2] bool tolowercase
		*/
		
        if(\CModule::IncludeModule("iblock")){
            $arResult = array();
            if(!empty($arParams[0]) && !empty($arParams[1])){
                $db = \CIBlock::GetByID(23);
                $arIBlock = $db->Fetch();
                if($arIBlock["NAME"] == $arParams[0]){
                    $arParams[0] = $arParams[1];
                    unset($arParams[1]);
                }
            }
            if(!empty($arParams[2])){
                $arParams[0] = strtolower($arParams[0]);
                unset($arParams[2]);
            }

              $arResult = implode(', ',$arParams);
			  
            return $arResult;
        }
        return "";
    }
}

class framestring extends \Bitrix\Iblock\Template\Functions\FunctionBase
{
    public function onPrepareParameters(\Bitrix\Iblock\Template\Entity\Base $entity, $parameters)
    {
        $arguments = array();

		
        foreach ($parameters as $parameter)
        {
            $arguments[] = $parameter->process($entity);
        }
        return $arguments;
    }
    public function calculate(array $arParams)
    {
		if(!empty($arParams[1])){
			if(!empty($arParams[2]) && !empty($arParams[0]))
			   return $arParams[2].$arParams[0].$arParams[1];
		    else
			   return $arParams[0].$arParams[1];
        }
        return "";
    }
}

class getvodoemtype extends \Bitrix\Iblock\Template\Functions\FunctionBase
{
    public function onPrepareParameters(\Bitrix\Iblock\Template\Entity\Base $entity, $parameters)
    {
        $arguments = array();
		
	
        foreach ($parameters as $parameter)
        {
            $arguments[] = $parameter->process($entity);
        }
        return $arguments;
    }
    public function calculate(array $arParams)
    {
		if(!empty($arParams[0]) && !in_array($arParams[0], array("18", "17", "20")) &&\CModule::IncludeModule("iblock")){
			return UserFieldValue(" ".$arParams[0]);
        }
        return "";
    }
}

class getratingname extends \Bitrix\Iblock\Template\Functions\FunctionBase
{
    public function onPrepareParameters(\Bitrix\Iblock\Template\Entity\Base $entity, $parameters)
    {
        $arguments = array();
		
		$this->data['id'] = $entity->getId();
		
        foreach ($parameters as $parameter)
        {
            $arguments[] = $parameter->process($entity);
        }
        return $arguments;
    }
	
    public function calculate(array $arParams)
    {
        if(\CModule::IncludeModule("iblock")){
            $arResult = array();
			
			if(!empty($arParams[0])){

					$dbRes = \CIBlockSection::GetList(array(), array("IBLOCK_ID"=>23, "=ID"=>$this->data['id']), false, array("UF_REGION_BINDING", "IBLOCK_SECTION_ID", "UF_NAME_WHERE"));
					if ($arCurSection = $dbRes->Fetch()){
						if(intval($arCurSection["IBLOCK_SECTION_ID"])>0){
						  $rsMainSections = \CIBlockSection::GetList(array("SORT"=>"asc"), array(
									"IBLOCK_ID" => 23,
									"=ID" => $arCurSection["IBLOCK_SECTION_ID"],
									"GLOBAL_ACTIVE" => "Y",
									"ACTIVE" => "Y"
								), false, array("NAME", "UF_NAME_THEN", "UF_NAME_WHERE"));
								
								if($arMainSection = $rsMainSections->Fetch()){
									if(!empty($arParams[3]) && $arParams[3]=="WHERE"){
										if(!empty($arMainSection["UF_NAME_WHERE"]))
											return strtolower($arParams[0])." ".$arMainSection["UF_NAME_WHERE"];
										else
											return $arParams[0];
									} else{
										if(!empty($arMainSection["UF_NAME_THEN"]))
									    return strtolower($arParams[0])." ".$arMainSection["UF_NAME_THEN"];
									else
									 return $arParams[0];
									}
								}else{
									return $arParams[0];
								}
								
						} else {
							if(count($arCurSection["UF_REGION_BINDING"])==1){
							  $rsMainSections = \CIBlockSection::GetList(array("SORT"=>"asc"), array(
									"IBLOCK_ID" => 23,
									"=ID" => $arCurSection["UF_REGION_BINDING"][0],
									"GLOBAL_ACTIVE" => "Y",
									"ACTIVE" => "Y"
								), false, array("NAME"));
								
								if($arMainSection = $rsMainSections->Fetch()){
									if($arParams[1]=="Y"){
										 if(!empty($arParams[2]))
											return $arParams[2] . $arParams[0].", ".$arMainSection["NAME"];
										else
											return $arParams[0].", ".$arMainSection["NAME"];
									} else {
										if(!empty($arParams[2]))
											return $arParams[2] . $arParams[0];
										else
											return $arParams[0];
									}
								}else{
									 if(!empty($arParams[2]))
										return $arParams[2] . $arParams[0];
									else
										return $arParams[0];
								}
						  } else {
							    if(!empty($arParams[2]))
									return $arParams[2] . $arParams[0];
								else
									return $arParams[0];
						  }
						  }
					}
            }
        }
		return "";
    }
}

class getparentnameornotice extends \Bitrix\Iblock\Template\Functions\FunctionBase
{
    public function onPrepareParameters(\Bitrix\Iblock\Template\Entity\Base $entity, $parameters)
    {
        $arguments = array();
		
		$this->data['id'] = $entity->getId();
		
        foreach ($parameters as $parameter)
        {
            $arguments[] = $parameter->process($entity);
        }
        return $arguments;
    }
	
    public function calculate(array $arParams)
    {
        if(\CModule::IncludeModule("iblock")){
            $arResult = array();
            if(empty($arParams[0])){
					$dbRes = \CIBlockSection::GetList(array(), array("IBLOCK_ID"=>23, "=ID"=>$this->data['id']), false, array("UF_REGION_BINDING", "IBLOCK_SECTION_ID"));
					if ($arCurSection = $dbRes->Fetch()){
						if(intval($arCurSection["IBLOCK_SECTION_ID"])>0){
						  $rsMainSections = \CIBlockSection::GetList(array("SORT"=>"asc"), array(
									"IBLOCK_ID" => 23,
									"=ID" => $arCurSection["IBLOCK_SECTION_ID"],
									"GLOBAL_ACTIVE" => "Y",
									"ACTIVE" => "Y"
								), false, array("NAME"));
								
								if($arMainSection = $rsMainSections->Fetch()){
									    return $arMainSection["NAME"];
								}else{
									return '';
								}
								
						} else {
							if(count($arCurSection["UF_REGION_BINDING"])==1){
							  $rsMainSections = \CIBlockSection::GetList(array("SORT"=>"asc"), array(
									"IBLOCK_ID" => 23,
									"=ID" => $arCurSection["UF_REGION_BINDING"][0],
									"GLOBAL_ACTIVE" => "Y",
									"ACTIVE" => "Y"
								), false, array("NAME"));
								
								if($arMainSection = $rsMainSections->Fetch()){
									return $arMainSection["NAME"];
								}else{
									return '';
								}
						  } else {
							  return '';
						  }
						  }
					}
			} else {
				return $arParams[0];
			}
        }
		return "";
    }
}

class getparentnameclear extends \Bitrix\Iblock\Template\Functions\FunctionBase
{
    public function onPrepareParameters(\Bitrix\Iblock\Template\Entity\Base $entity, $parameters)
    {
        $arguments = array();

		$this->data['id'] = $entity->getId();
		
        foreach ($parameters as $parameter)
        {
            $arguments[] = $parameter->process($entity);
        }
        return $arguments;
    }
	
    public function calculate(array $arParams)
    {
        if(\CModule::IncludeModule("iblock")){
            $arResult = array();
					$dbRes = \CIBlockSection::GetList(array(), array("IBLOCK_ID"=>23, "=ID"=>$this->data['id']), false, array("UF_REGION_BINDING", "IBLOCK_SECTION_ID"));
					if ($arCurSection = $dbRes->Fetch()){
						if(intval($arCurSection["IBLOCK_SECTION_ID"])>0){
						  $rsMainSections = \CIBlockSection::GetList(array("SORT"=>"asc"), array(
									"IBLOCK_ID" => 23,
									"=ID" => $arCurSection["IBLOCK_SECTION_ID"]
								), false, array("NAME"));
								
								if($arMainSection = $rsMainSections->Fetch()){
									    return $arMainSection["NAME"];
								}else{
									return '';
								}
								
						} else {
							if(count($arCurSection["UF_REGION_BINDING"])==1){
							  $rsMainSections = \CIBlockSection::GetList(array("SORT"=>"asc"), array(
									"IBLOCK_ID" => 23,
									"=ID" => $arCurSection["UF_REGION_BINDING"][0]
								), false, array("NAME"));
								
								if($arMainSection = $rsMainSections->Fetch()){
									return $arMainSection["NAME"];
								}else{
									return '';
								}
						  } else {
							  return '';
						  }
						  }
					}
        }
		return "";
    }
}

class getparentname extends \Bitrix\Iblock\Template\Functions\FunctionBase
{
    public function onPrepareParameters(\Bitrix\Iblock\Template\Entity\Base $entity, $parameters)
    {
        $arguments = array();

		$this->data['id'] = $entity->getId();
		
        foreach ($parameters as $parameter)
        {
            $arguments[] = $parameter->process($entity);
        }
        return $arguments;
    }
	
    public function calculate(array $arParams)
    {
        if(\CModule::IncludeModule("iblock")){
            $arResult = array();
			
			if(!empty($arParams[0])){

					$dbRes = \CIBlockSection::GetList(array(), array("IBLOCK_ID"=>23, "=ID"=>$this->data['id']), false, array("UF_REGION_BINDING", "IBLOCK_SECTION_ID"));
					if ($arCurSection = $dbRes->Fetch()){
						if(intval($arCurSection["IBLOCK_SECTION_ID"])>0){
						  $rsMainSections = \CIBlockSection::GetList(array("SORT"=>"asc"), array(
									"IBLOCK_ID" => 23,
									"=ID" => $arCurSection["IBLOCK_SECTION_ID"],
									"GLOBAL_ACTIVE" => "Y",
									"ACTIVE" => "Y"
								), false, array("NAME", "UF_NAME_THEN"));
								
								if($arMainSection = $rsMainSections->Fetch()){
									if(!empty($arMainSection["UF_NAME_THEN"]))
									    return $arParams[0]." ".$arMainSection["UF_NAME_THEN"];
									else
									 return $arParams[0];
								}else{
									return $arParams[0];
								}
								
						} else {
							if(count($arCurSection["UF_REGION_BINDING"])==1){
							  $rsMainSections = \CIBlockSection::GetList(array("SORT"=>"asc"), array(
									"IBLOCK_ID" => 23,
									"=ID" => $arCurSection["UF_REGION_BINDING"][0],
									"GLOBAL_ACTIVE" => "Y",
									"ACTIVE" => "Y"
								), false, array("NAME"));
								
								if($arMainSection = $rsMainSections->Fetch()){
									return $arParams[0].", ".$arMainSection["NAME"];
								}else{
									return $arParams[0];
								}
						  } else {
							  return $arParams[0];
						  }
						  }
					}
            }
        }
		return "";
    }
}

class getnamewithnotice extends \Bitrix\Iblock\Template\Functions\FunctionBase
{
    public function onPrepareParameters(\Bitrix\Iblock\Template\Entity\Base $entity, $parameters)
    {
        $arguments = array();
		
        /** @var \Bitrix\Iblock\Template\NodeBase $parameter */
		$this->data['id'] = $entity->getId();
		
        foreach ($parameters as $parameter)
        {
            $arguments[] = $parameter->process($entity);
        }
        return $arguments;
    }
	
    public function calculate(array $arParams)
    {
        if(\CModule::IncludeModule("iblock")){
            $arResult = array();
					$dbRes = \CIBlockSection::GetList(array(), array("IBLOCK_ID"=>23, "=ID"=>$this->data['id']), false, array("NAME", "UF_NAME_NOTICE", "IBLOCK_SECTION_ID"));
					
					if ($arCurSection = $dbRes->Fetch()){
						
						if(intval($arCurSection["IBLOCK_SECTION_ID"])>0){
						  $rsMainSections = \CIBlockSection::GetList(array("SORT"=>"asc"), array(
									"IBLOCK_ID" => 23,
									"=ID" => $arCurSection["IBLOCK_SECTION_ID"],
								), false, array("NAME", "UF_NAME_NOTICE"));
								
								if($arMainSection = $rsMainSections->Fetch()){
                                    $arMainRegion =[];
									if(!empty($arMainSection["UF_NAME_NOTICE"])){
                                        return $arMainRegion["NAME"].", ".$arMainSection["UF_NAME_NOTICE"];
									} else {
											return $arMainRegion["NAME"];
											}

									} else
									    return "";
						} else {
							if(!empty($arCurSection["UF_NAME_NOTICE"])){
                                        return $arCurSection["NAME"].", ".$arCurSection["UF_NAME_NOTICE"];
									} else {
										return $arCurSection["NAME"];
							        }
						}
					}					
			}
		return "";
    }
}

class getparentnamewhere1 extends \Bitrix\Iblock\Template\Functions\FunctionBase
{
    public function onPrepareParameters(\Bitrix\Iblock\Template\Entity\Base $entity, $parameters)
    {
        $arguments = array();
		
		$this->data['id'] = $entity->getId();
		
        foreach ($parameters as $parameter)
        {
            $arguments[] = $parameter->process($entity);
        }
        return $arguments;
    }
	
    public function calculate(array $arParams)
    {
        if(\CModule::IncludeModule("iblock")){
            $arResult = array();
					$dbRes = \CIBlockSection::GetList(array(), array("IBLOCK_ID"=>23, "=ID"=>$this->data['id']), false, array("UF_REGION_BINDING", "IBLOCK_SECTION_ID"));
					if ($arCurSection = $dbRes->Fetch()){
						if(intval($arCurSection["IBLOCK_SECTION_ID"])>0){
						  $rsMainSections = \CIBlockSection::GetList(array("SORT"=>"asc"), array(
									"IBLOCK_ID" => 23,
									"=ID" => $arCurSection["IBLOCK_SECTION_ID"],
									"GLOBAL_ACTIVE" => "Y",
									"ACTIVE" => "Y"
								), false, array("NAME", "UF_NAME_WHERE_1", "UF_REGION_BINDING"));
								
								if($arMainSection = $rsMainSections->Fetch()){
									if(!empty($arMainSection["UF_NAME_WHERE_1"])){
										if(!empty($arParams[0]) && $arParams[0]=="FULL"){
											if(count($arMainSection["UF_REGION_BINDING"])==1){
												
												 $rsMainRegions = \CIBlockSection::GetList(array("SORT"=>"asc"), array(
													"IBLOCK_ID" => 23,
													"=ID" => $arMainSection["UF_REGION_BINDING"][0],
													"GLOBAL_ACTIVE" => "Y",
													"ACTIVE" => "Y"
												), false, array("NAME"));
												
												if($arMainRegion = $rsMainRegions->Fetch()){
													return $arMainSection["UF_NAME_WHERE_1"].", ".$arMainRegion["NAME"];
												}else{
													return $arMainSection["UF_NAME_WHERE_1"];
												}
											} else {
												return $arMainSection["UF_NAME_WHERE_1"];
											}
										} else{
											return $arMainSection["UF_NAME_WHERE_1"];
										}
									}
									else
									 return "";
								}else{
									return "";
								}
								
						} else {
							if(count($arCurSection["UF_REGION_BINDING"])==1){
							  $rsMainSections = \CIBlockSection::GetList(array("SORT"=>"asc"), array(
									"IBLOCK_ID" => 23,
									"=ID" => $arCurSection["UF_REGION_BINDING"][0],
									"GLOBAL_ACTIVE" => "Y",
									"ACTIVE" => "Y"
								), false, array("UF_NAME_WHERE_1"));
								
								if($arMainSection = $rsMainSections->Fetch()){
									return $arMainSection["UF_NAME_WHERE_1"];
								}else{
									return "";
								}
						  } else {
							  return "";
						  }
						  }
					}
        }
		return "";
    }
}

class getelementparentnamewhere extends \Bitrix\Iblock\Template\Functions\FunctionBase
{
    public function onPrepareParameters(\Bitrix\Iblock\Template\Entity\Base $entity, $parameters)
    {
        $arguments = array();
		
        /** @var \Bitrix\Iblock\Template\NodeBase $parameter */
		$this->data['id'] = $entity->getId();
		
        foreach ($parameters as $parameter)
        {
            $arguments[] = $parameter->process($entity);
        }
        return $arguments;
    }
	
    public function calculate(array $arParams)
    {
        if(\CModule::IncludeModule("iblock")){
            $arResult = array();
					$dbRes = \CIBlockElement::GetList(array(), array("IBLOCK_ID"=>23, "=ID"=>$this->data['id']), false, array("IBLOCK_SECTION_ID"));
					
					if ($arCurElement = $dbRes->Fetch()){
						if(!empty($arCurElement["IBLOCK_SECTION_ID"])){
							$rsMainSections = \CIBlockSection::GetList(array(), array(
												"IBLOCK_ID" => 23,
												"=ID" => $arCurElement["IBLOCK_SECTION_ID"],
											), false, array("NAME", "UF_NAME_WHERE"));
													
							if($arMainSection = $rsMainSections->Fetch()){
							  if(!empty($arMainSection["UF_NAME_WHERE"]))
								 return $arMainSection["UF_NAME_WHERE"];
							  else
								 return $arMainSection["NAME"];

							}
						}
					}
        }
		return "";
    }
}

class getparentnamewhere extends \Bitrix\Iblock\Template\Functions\FunctionBase
{
    public function onPrepareParameters(\Bitrix\Iblock\Template\Entity\Base $entity, $parameters)
    {
        $arguments = array();
		
        /** @var \Bitrix\Iblock\Template\NodeBase $parameter */
		$this->data['id'] = $entity->getId();
		
        foreach ($parameters as $parameter)
        {
            $arguments[] = $parameter->process($entity);
        }
        return $arguments;
    }
	
    public function calculate(array $arParams)
    {
        if(\CModule::IncludeModule("iblock")){
            $arResult = array();
					$dbRes = \CIBlockSection::GetList(array(), array("IBLOCK_ID"=>23, "=ID"=>$this->data['id']), false, array("UF_REGION_BINDING", "IBLOCK_SECTION_ID"));
					if ($arCurSection = $dbRes->Fetch()){
						if(intval($arCurSection["IBLOCK_SECTION_ID"])>0){
						  $rsMainSections = \CIBlockSection::GetList(array("SORT"=>"asc"), array(
									"IBLOCK_ID" => 23,
									"=ID" => $arCurSection["IBLOCK_SECTION_ID"],
									"GLOBAL_ACTIVE" => "Y",
									"ACTIVE" => "Y"
								), false, array("NAME", "UF_NAME_WHERE", "UF_REGION_BINDING"));
								
								if($arMainSection = $rsMainSections->Fetch()){
									if(!empty($arMainSection["UF_NAME_WHERE"])){
										if(!empty($arParams[0]) && $arParams[0]=="FULL"){
											if(count($arMainSection["UF_REGION_BINDING"])==1){
												
												 $rsMainRegions = \CIBlockSection::GetList(array("SORT"=>"asc"), array(
													"IBLOCK_ID" => 23,
													"=ID" => $arMainSection["UF_REGION_BINDING"][0],
													"GLOBAL_ACTIVE" => "Y",
													"ACTIVE" => "Y"
												), false, array("NAME"));
												
												if($arMainRegion = $rsMainRegions->Fetch()){
													return $arMainSection["UF_NAME_WHERE"].", ".$arMainRegion["NAME"];
												}else{
													return $arMainSection["UF_NAME_WHERE"];
												}
											} else {
												return $arMainSection["UF_NAME_WHERE"];
											}
										} else{
											return $arMainSection["UF_NAME_WHERE"];
										}
									}
									else
									 return "";
								}else{
									return "";
								}
								
						} else {
							if(count($arCurSection["UF_REGION_BINDING"])==1){
							  $rsMainSections = \CIBlockSection::GetList(array("SORT"=>"asc"), array(
									"IBLOCK_ID" => 23,
									"=ID" => $arCurSection["UF_REGION_BINDING"][0],
									"GLOBAL_ACTIVE" => "Y",
									"ACTIVE" => "Y"
								), false, array("UF_NAME_WHERE"));
								
								if($arMainSection = $rsMainSections->Fetch()){
									return $arMainSection["UF_NAME_WHERE"];
								}else{
									return "";
								}
						  } else {
							  return "";
						  }
						  }
					}
        }
		return "";
    }
}

class getparentnamethen extends \Bitrix\Iblock\Template\Functions\FunctionBase
{
    public function onPrepareParameters(\Bitrix\Iblock\Template\Entity\Base $entity, $parameters)
    {
        $arguments = array();
		
		$this->data['id'] = $entity->getId();
		
        foreach ($parameters as $parameter)
        {
            $arguments[] = $parameter->process($entity);
        }
        return $arguments;
    }
	
    public function calculate(array $arParams)
    {
        if(\CModule::IncludeModule("iblock")){
            $arResult = array();
					$dbRes = \CIBlockSection::GetList(array(), array("IBLOCK_ID"=>23, "=ID"=>$this->data['id']), false, array("UF_REGION_BINDING", "IBLOCK_SECTION_ID"));
					if ($arCurSection = $dbRes->Fetch()){
						if(intval($arCurSection["IBLOCK_SECTION_ID"])>0){
						  $rsMainSections = \CIBlockSection::GetList(array("SORT"=>"asc"), array(
									"IBLOCK_ID" => 23,
									"=ID" => $arCurSection["IBLOCK_SECTION_ID"],
									"GLOBAL_ACTIVE" => "Y",
									"ACTIVE" => "Y"
								), false, array("NAME", "UF_NAME_THEN", "UF_REGION_BINDING"));
								
								if($arMainSection = $rsMainSections->Fetch()){
									if(!empty($arMainSection["UF_NAME_THEN"])){
										if(!empty($arParams[0]) && $arParams[0]=="FULL"){
											if(count($arMainSection["UF_REGION_BINDING"])==1){
												 $rsMainRegions = \CIBlockSection::GetList(array("SORT"=>"asc"), array(
													"IBLOCK_ID" => 23,
													"=ID" => $arMainSection["UF_REGION_BINDING"][0],
													"GLOBAL_ACTIVE" => "Y",
													"ACTIVE" => "Y"
												), false, array("NAME"));
												
												if($arMainRegion = $rsMainRegions->Fetch()){
													return $arMainSection["UF_NAME_THEN"].", ".$arMainRegion["NAME"];
												}else{
													return $arMainSection["UF_NAME_THEN"];
												}
											} else {
												return $arMainSection["UF_NAME_THEN"];
											}
										} else{
											return $arMainSection["UF_NAME_THEN"];
										}
									}
									else
									 return "";
								}else{
									return "";
								}
								
						} else {
							if(count($arCurSection["UF_REGION_BINDING"])==1){
							  $rsMainSections = \CIBlockSection::GetList(array("SORT"=>"asc"), array(
									"IBLOCK_ID" => 23,
									"=ID" => $arCurSection["UF_REGION_BINDING"][0],
									"GLOBAL_ACTIVE" => "Y",
									"ACTIVE" => "Y"
								), false, array("UF_NAME_THEN"));
								
								if($arMainSection = $rsMainSections->Fetch()){
									return $arMainSection["UF_NAME_THEN"];
								}else{
									return "";
								}
						  } else {
							  return "";
						  }
						  }
					}
        }
		return "";
    }
}

class makenewdescription extends \Bitrix\Iblock\Template\Functions\FunctionBase
{
    public function onPrepareParameters(\Bitrix\Iblock\Template\Entity\Base $entity, $parameters)
    {
        $arguments = array();
		
        /** @var \Bitrix\Iblock\Template\NodeBase $parameter */
		$this->data['id'] = $entity->getId();
        foreach ($parameters as $parameter)
        {
            $arguments[] = $parameter->process($entity);
        }
        return $arguments;
    }
    public function calculate(array $arParams)
    {
        if($this->data['id'] >0){
		
		$ipropValues = new \Bitrix\Iblock\InheritedProperty\SectionValues(23, $this->data['id']);
		$arValues = $ipropValues->getValues();

			if(!empty($arValues["SECTION_META_DESCRIPTION"]) && !empty($arParams[0])){
				return $arValues["SECTION_META_DESCRIPTION"] . " " . $this->upFirstLetter($arParams[0]);
			}
		 }
        return "";
    }
	
	private function upFirstLetter($str, $encoding = 'UTF-8')
	{
		return mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding)
		. mb_substr($str, 1, null, $encoding);
	}
}

class makenewsectiontitle extends \Bitrix\Iblock\Template\Functions\FunctionBase
{
    public function onPrepareParameters(\Bitrix\Iblock\Template\Entity\Base $entity, $parameters)
    {
        $arguments = array();

		$this->data['id'] = $entity->getId();
        foreach ($parameters as $parameter)
        {
            $arguments[] = $parameter->process($entity);
        }
        return $arguments;
    }
    public function calculate(array $arParams)
    {
        if($this->data['id'] >0){
		
		$ipropValues = new \Bitrix\Iblock\InheritedProperty\SectionValues(23, $this->data['id']);
		$arValues = $ipropValues->getValues();

			if(!empty($arValues["SECTION_PAGE_TITLE"]) && !empty($arParams[0])){
				return $arValues["SECTION_PAGE_TITLE"] . " " . $arParams[0];
			}
		 }
        return "";
    }
	
}

class makenewsectionchain extends \Bitrix\Iblock\Template\Functions\FunctionBase
{
    public function onPrepareParameters(\Bitrix\Iblock\Template\Entity\Base $entity, $parameters)
    {
        $arguments = array();

		$this->data['id'] = $entity->getId();
        foreach ($parameters as $parameter)
        {
            $arguments[] = $parameter->process($entity);
        }
        return $arguments;
    }
    public function calculate(array $arParams)
    {
        if($this->data['id'] >0){
			$rsMainSections = \CIBlockSection::GetList(array(), array(
					"IBLOCK_ID" => 23,
					"=ID" => $this->data['id'],
				), false, array("NAME", "DEPTH_LEVEL"));
				
				if($arMainSection = $rsMainSections->Fetch()){
					if($arMainSection["DEPTH_LEVEL"] > 1){
					   return $arMainSection["NAME"] . " " . $arParams[0];
					} else{
						$ipropValues = new \Bitrix\Iblock\InheritedProperty\SectionValues(23, $this->data['id']);
		                $arValues = $ipropValues->getValues();
						
						if(!empty($arValues["SECTION_PAGE_TITLE"]) && !empty($arParams[0])){
							return $arValues["SECTION_PAGE_TITLE"] . " " . $arParams[0];
						} else {
							return $arMainSection["NAME"] . " " . $arParams[0];
						}
					}
				} else {
					return "";
				}
		 }
        return "";
    }
	
}

class makenewkeywords extends \Bitrix\Iblock\Template\Functions\FunctionBase
{
    public function onPrepareParameters(\Bitrix\Iblock\Template\Entity\Base $entity, $parameters)
    {
        $arguments = array();

		$this->data['id'] = $entity->getId();
        foreach ($parameters as $parameter)
        {
            $arguments[] = $parameter->process($entity);
        }
        return $arguments;
    }
    public function calculate(array $arParams)
    {
        if($this->data['id'] >0){
		
		$ipropValues = new \Bitrix\Iblock\InheritedProperty\SectionValues(23, $this->data['id']);
		$arValues = $ipropValues->getValues();

			if(!empty($arValues["SECTION_META_KEYWORDS"]) && !empty($arParams[0])){
				return $arValues["SECTION_META_KEYWORDS"] . " " . $arParams[0];
			}
		 }
        return "";
    }
}

class makenewtitle extends \Bitrix\Iblock\Template\Functions\FunctionBase
{
    public function onPrepareParameters(\Bitrix\Iblock\Template\Entity\Base $entity, $parameters)
    {
        $arguments = array();
		
    	$this->data['id'] = $entity->getId();
        foreach ($parameters as $parameter)
        {
            $arguments[] = $parameter->process($entity);
        }
        return $arguments;
    }
    public function calculate(array $arParams)
    {
        if($this->data['id'] >0){
		
		$ipropValues = new \Bitrix\Iblock\InheritedProperty\SectionValues(23, $this->data['id']);
		$arValues = $ipropValues->getValues();

			if(!empty($arValues["SECTION_META_TITLE"]) && !empty($arParams[0])){
				
				if(!empty($arParams[1])){
					$pos = strpos($arValues["SECTION_META_TITLE"], $arParams[1]);
					if ($pos === false) {
						return $arValues["SECTION_META_TITLE"]." ".$arParams[0];
					} else {
						return substr($arValues["SECTION_META_TITLE"],0,$pos).$arParams[0].substr($arValues["SECTION_META_TITLE"],$pos-1);
					}
				} else {
					return $arValues["SECTION_META_TITLE"].$arParams[0];
				}
			} else {
				return $arValues["SECTION_META_TITLE"];
			}
		 }
        return "";
    }
}

class pathbreaks extends \Bitrix\Iblock\Template\Functions\FunctionBase
{
    public function onPrepareParameters(\Bitrix\Iblock\Template\Entity\Base $entity, $parameters)
    {
        $arguments = array();
		
        /** @var \Bitrix\Iblock\Template\NodeBase $parameter */
		
        foreach ($parameters as $parameter)
        {
            $arguments[] = $parameter->process($entity);
        }
        return $arguments;
    }
    public function calculate(array $arParams)
    {
        /*
		{=path parent.Name this.Name}
		
		[0] parent.Name
        [1] this.Name
        [2] bool tolowercase
		*/
		
        if(\CModule::IncludeModule("iblock")){
            $arResult = array();
            if(!empty($arParams[0]) && !empty($arParams[1])){
                $db = \CIBlock::GetByID(23);
                $arIBlock = $db->Fetch();
				
                if($arIBlock["NAME"] == $arParams[0]){
                    $arParams[0] = $arParams[1];
                    unset($arParams[1]);
					if(!empty($arParams[2])){
						$arParams[0] = strtolower($arParams[0]);
					}
					$arResult = $arParams[0];
					return $arResult;
                } else {
					
					$arParams[1] = strtolower($arParams[1]);
					
					$arResult=$arParams[1].", ".$arParams[0];
					
					return $arResult;
				}
            }
        }
        return "";
    }
}

class year extends \Bitrix\Iblock\Template\Functions\FunctionBase
{
    public function calculate()
    {
		$m=date('m');
		$y=date('Y');
		if(intval($m) > 8){
			$k=$y+1;
			$year=$y.'/'.$k;
		} else {
			$year=$y;
		}
        return (string)$year;
    }
}

class pricenotnull extends \Bitrix\Iblock\Template\Functions\FunctionBase
{
    public function calculate(array $parameters)
    {
        /* 
		
        {=pricenotnull this.catalog.price.BASE}
		
        параметры функции содержаться в $parameters 

        их может быть несколько, разделенных пробелами:

        {=functionname param1 "значение параметра 2"}

        */
        if(floatval($parameters[0])>0) 
        {
            return $parameters[0];
        }
        return "";
    }
}

function ClearIblockSEOCache() 
{
ini_set('max_execution_time', '0');
set_time_limit(0);

$iblockId=CATALOG_IBLOCK_ID;
$ipropIblockValues = new \Bitrix\Iblock\InheritedProperty\IblockValues($iblockId);
$ipropIblockValues->clearValues();

return "ClearIblockSEOCache();";
}
?>