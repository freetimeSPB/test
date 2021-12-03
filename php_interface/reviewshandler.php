<?php

use Api\Reviews\ReviewsTable;
use Bitrix\Main\Event;
use Bitrix\Main\EventManager;

$eventManager = EventManager::getInstance();

//==============================================================================
// api.reviews events
//==============================================================================
$eventManager->addEventHandler('api.reviews', 'ReviewsOnAfterAdd', array('MyReviewsEvents', 'OnAfterAdd'));
$eventManager->addEventHandler('api.reviews', 'ReviewsOnDelete', array('MyReviewsEvents', 'OnDelete'));


class MyReviewsEvents
{

   public static function OnAfterAdd(Event $event)
    {

        $params = $event->getParameters();
	
		if(intval($params['fields']['ELEMENT_ID'])>0){
			
			$iblock_id=CIBlockElement::GetIBlockByID($params['fields']['ELEMENT_ID']);
			
			if($iblock_id==23 && CModule::IncludeModule("api.reviews") && CModule::IncludeModule("iblock")){
				//Получаем текущий рейтинг
				$arRaing = CApiReviews::getElementRating($params['fields']['ELEMENT_ID'], 's1');
				CIBlockElement::SetPropertyValuesEx($params['fields']['ELEMENT_ID'], $iblock_id, array('rating' => $arRaing['RATING'], 'vote_count' => $arRaing['COUNT']));

			}
		}
    }


	public static function OnDelete(Event $event)
    {
		$params = $event->getParameters();
	
		if(intval($params['id']['ID'])>0){
			
				if(CModule::IncludeModule("api.reviews")){
						$element_id=Api\Reviews\ReviewsTable::getById($params['id']['ID'])->Fetch();
								
								if(intval($element_id['ELEMENT_ID'])>0){
									$iblock_id=CIBlockElement::GetIBlockByID($element_id['ELEMENT_ID']);
									
									if($iblock_id==23){
											$filter = array(
											 '=ACTIVE' => 'Y',
											 '=ELEMENT_ID' => $element_id['ELEMENT_ID'],
											 '=SITE_ID' => 's1',
											 '!ID' => $params['id']['ID']
										);
										$rsRating = ReviewsTable::getList(array(
											 'select' => array('RATING'),
											 'filter' => $filter,
										));

										$rating  = 0;
										$arItems = array();

										while($arItem = $rsRating->fetch()) {
											$rating += (int)$arItem['RATING'];

											$arItems[] = $arItem;
										}
										$countItems    = count($arItems);
										$averageRating = ($countItems > 0) ? round(($rating / $countItems), 1) : $countItems;
										
										CIBlockElement::SetPropertyValuesEx($element_id['ELEMENT_ID'], $iblock_id, array('rating' => $averageRating, 'vote_count' => $countItems));
									}
								}			
			
			}
		}
    }

}
