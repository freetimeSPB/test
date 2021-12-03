<?php
defined('B_PROLOG_INCLUDED') and (B_PROLOG_INCLUDED === true) or die();

$MESS = array_merge(!empty($MESS) ? $MESS : [], [
	'RADUGA_GET_LOADING_AJAX_COMPONENT_NAME'=> 'Имя компонента',
	'RADUGA_GET_LOADING_AJAX_COMPONENT_TEMPLATE'=> 'Шаблон компонента',
	'RADUGA_GET_LOADING_AJAX_COMPONENT_PARAMS'=> 'Параметры компонента',
	'RADUGA_GET_LOADING_AJAX_COMPONENT_TIMEOUT'=> 'Задержка загрузки',
	'RADUGA_GET_LOADING_AJAX_COMPONENT_ONLY_MOBILE'=> 'Только на мобильном',
	'RADUGA_GET_LOADING_AJAX_COMPONENT_ONLY_DESKTOP'=> 'Только на десктопе',
	'RADUGA_GET_LOADING_AJAX_COMPONENT_FUNCTIONS_AFTER_INIT'=> 'Функции js после загрузки',
]);