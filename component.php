<?php

ini_set('max_execution_time', '180000'); //300 seconds = 5 minutes
set_time_limit(180000);

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

require_once $_SERVER["DOCUMENT_ROOT"] . $this->GetPath() . "/SoapConfig.php";
require_once $_SERVER["DOCUMENT_ROOT"] . $this->GetPath() . "/SoapClient.php";

use Volsu\Soap\SoapConfig;
use Volsu\Soap\SoapClient;

$cacheFileName = __DIR__ . "/cache";

$priority = [
	"Институт права" => 1,
	"Институт математики и информационных технологий" => 2,
	"Институт истории, международных отношений и социальных технологий" => 3,
	"Институт филологии и межкультурной коммуникации" => 4,
	"Институт приоритетных технологий" => 5,
	"Институт управления и региональной экономики" => 6,
	"Институт экономики и финансов" => 7,
	"Институт естественных наук" => 8,
];

$config = null;
$institutes = [];
$cache = [];
$cacheChange = false;

if ($_GET['clear_cache'] != '1') {
	$cache = (array)json_decode(file_get_contents($cacheFileName) ?? []);
} else {
	unlink($cacheFileName);
}


$configWS = new SoapConfig('*********************', '*********************', '*********************');
$configPriem = new SoapConfig('*********************', '*********************', '*********************');
$configEmp = new SoapConfig('*********************', '*********************', '*********************');



$employ = (new SoapClient($configEmp, '*********************', [
			 'КодПодразделения'=> $arParams['DEPARTMENT'],
			 'ФИО' => '',
			 'НомерНачала' => 0,
			 'НомерОкончания' => 999999,
			 'ТипСписка' => 'short',
			 'Язык' => 'Ru',
		 ]))->getResponse()->Сотрудники;
		  
$arResult['EMPLOY'] = $employ;

$directions = getDirections($configWS, $cache, $cacheChange);

$arResult['Kafedra'];


for ($i = 0; $i <= count($directions); $i++)
{
		$arResult['Kafedra'][$i] = $directions[$i]->Kafedra;
		$cache['Kafedra'] = $directions[$i]->Kafedra; 

}


foreach ($directions as $direction) {
	if ($direction->EducationLevelName == "Бакалавриат" || $direction->EducationLevelName == "Специалитет") {
		
		$direction->EducationLevelName = "Бакалавриат и специалитет";		
		
	}

	if (array_key_exists($direction->FacultetCode, $institutes[$direction->EducationLevelName])) {
		
		addDirectionToInstitute($direction, $institutes, $prices);
	} else {
		$institutes[$direction->EducationLevelName][$direction->FacultetCode] = [
			'name' => $direction->FacultetName,
			'priority' => $priority[$direction->FacultetName] ?? 1,
			'directions' => [],
		];

		addDirectionToInstitute($direction, $institutes, $prices);
	}
}

$arResult['INSTITUTES'] = $institutes;
$arResult['DIRECTIONS'] = $directions;
$arResult['PLACES'] = $cache['places'];
$arResult['EXAMS'] = $cache['exams'];
$arResult['DEPARTMENTS'] = $arParams["DEPARTMENTS"];
$arResult['FULL_NAME'] = $arParams["FULL_NAME"];
$arResult['PHOTO_OF_THE_HEAD_OF_THE_DEPARTMENTS'] = $arParams["PHOTO_OF_THE_HEAD_OF_THE_DEPARTMENTS"];
$arResult['ACADEMIC_DEGREE'] = $arParams["ACADEMIC_DEGREE"];
//$arResult['POSITION'] = $arParams["POSITION"];
$arResult['ADDRESS_OF_THE_DEPARTMENT'] = $arParams["ADDRESS_OF_THE_DEPARTMENT"];
$arResult['DEPARTMENT_TELEPHONE'] = $arParams["DEPARTMENT_TELEPHONE"];
$arResult['DEPARTMENT_EMAIL'] = $arParams["DEPARTMENT_EMAIL"];
$arResult['SITE_OF_THE_DEPARTMENT'] = $arParams["SITE_OF_THE_DEPARTMENT"];
$arResult['POSITION'] = $arParams["POSITION"];
$arResult['GENERAL_INFORMATION'] = $arParams["GENERAL_INFORMATION"];
//$arResult['COMPOSITION_OF_THE_DEPARTMENT'] = $arParams["COMPOSITION_OF_THE_DEPARTMENT"];
$arResult['GENERAL_PAGE'] = $arParams["GENERAL_PAGE"];
$arResult['RESPONSIBLE'] = $arParams["RESPONSIBLE"];


if ($cacheChange) {
	file_put_contents($cacheFileName, json_encode($cache));
}

$this->IncludeComponentTemplate();

/*
* Получаем информацию о направлениях подготовки из веб-сервиса личного кабинета
*/
function getDirections($configWS, &$cache, &$cacheChange) {
	if (!key_exists('directions', $cache) || $_GET['clear_cache'] == '1') {
		$client = new SoapClient($configWS, 'GetStringsPriema');
		$response = $client->getResponse();
		$cacheChange = true;
		$cache['directions'] = $response->StringsPlanPriema->StringPlanPriema;
		return $response->StringsPlanPriema->StringPlanPriema;
	} else {
		return $cache['directions'];
	}
}


function addDirectionToInstitute($direction, &$institutes, $prices) {
	if (key_exists($direction->SpecialityCodeOKSO, (array)$institutes[$direction->EducationLevelName][$direction->FacultetCode]['directions'])) {	
		$institutes[$direction->EducationLevelName]
			[$direction->FacultetCode]
			['directions']
			[$direction->SpecialityCodeOKSO]
			['forms']
			[$direction->EducationFormName]
			[$direction->ProfilName . '_' . $direction->ProfilCode]
			[$direction->FinanceName] = $prices[$direction->SpecialityCodeOKSO . "_" . $direction->EducationFormCode . "_" . $direction->ProfilCode . "_" . $direction->FinanceCode] ?? 0;
	} else {
		$institutes[$direction->EducationLevelName][$direction->FacultetCode]['directions'][$direction->SpecialityCodeOKSO] = [
			'name' => $direction->SpecialityName,
			'countPlace' => $direction->CountPlace,
			'exams' => $direction->Exams
		];

		$institutes[$direction->EducationLevelName]
			[$direction->FacultetCode]
			['directions']
			[$direction->SpecialityCodeOKSO]
			['forms']
			[$direction->EducationFormName]
			[$direction->ProfilName . '_' . $direction->ProfilCode]
			[$direction->FinanceName] = $prices[$direction->SpecialityCodeOKSO . "_" . $direction->EducationFormCode . "_" . $direction->ProfilCode . "_" . $direction->FinanceCode] ?? 0;
	}
}

/*
Получение дополнительной информации с помощью WS приемной компании.

*/ 
function getAddInfo(&$directions, $configPriem, $configWS, &$cache, &$cacheChange) {
	$addInfoArr = [];

	foreach ($directions as $idx => $direction) {
		// Получаям информацию о приемной кампании, если о ней еще нет информации
		if (!key_exists($direction->IdPK, $addInfoArr) || $_GET['clear_cache'] == '1') {
			// Ищим информацию в кэше
			if (key_exists($direction->IdPK, $cache['plans'])) {
				$addInfoArr[$direction->IdPK] = ((array)$cache['plans'])[$direction->IdPK];
			} else {
				// Получаем информацию из 1С если в кэше ее нет
				$client = new SoapClient($configPriem, 'ПланПриема', ['НомерПриемнойКампании' => $direction->IdPK]);
				
				$response = $client->getResponse(); 

				$cacheChange = true;

				// Кэшируем полученные значения
				$cache['plans']->{$direction->IdPK} = $response->ПланПриемаСтрока;
				$addInfoArr[$direction->IdPK] = $response->ПланПриемаСтрока;
			}
		}

		$direction->Priority = 1;

		$directions[$idx]->Exams = getExamInfo($configWS, $direction, $cache, $cacheChange);


		if (!key_exists($direction->SpecialityCodeOKSO . "_" . $direction->ProfilCode, $cache['places'])) {
			$cache['places']->{$direction->SpecialityCodeOKSO . "_" . $direction->ProfilCode} = 0;

			foreach ($addInfoArr[$direction->IdPK] as $key => $group) {
				// Удаление информации о договорной основе
				if ($group->КонкурснаяГруппа->ОснованиеПоступления == 'договорная основа') {
					unset($addInfoArr[$direction->IdPK][$key]);
					continue;
				}

				// Суммируем бюджетные места
				if ($group->КонкурснаяГруппа->Специальность->Код == $direction->SpecialityCode 
					&& ($group->КонкурснаяГруппа->КодПрофиля == '' || $group->КонкурснаяГруппа->КодПрофиля == $direction->ProfilCode)) {
					$cache['places']->{$direction->SpecialityCodeOKSO . "_" . $direction->ProfilCode} += $group->КоличествоМест;
					unset($addInfoArr[$direction->IdPK][$key]);
				}
			}
		}
	}
}

function debug($var) {
	echo "<pre>";
	
	echo print_r($var, true);
	
	echo "</pre>";
}

?>
