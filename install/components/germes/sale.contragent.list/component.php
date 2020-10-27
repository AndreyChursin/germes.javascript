<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/**
 * FIT vars
 *
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponent $this
 * @global CMain $APPLICATION
 * @global CUser $USER
 */
use \Germes\MultiBasket\Contragents
	,\Germes\MultiBasket\MultiBasket
	,\Germes\Sale\MultiPrice
	,\Bitrix\Main\Loader
	,\Bitrix\Main\Page\Frame
	,\Bitrix\Main\Data\Cache
	,\Bitrix\Main\Localization\Loc
;
Loc::loadMessages(__FILE__);

global $USER, $APPLICATION;
if($arParams["SET_TITLE"]=="Y")
{
	$APPLICATION->SetTitle($arParams["TITLE"]?:Loc::getMessage("TITLE"));
}
if(Loader::includeModule("germes.multiprice"))
{
	$cache = Cache::createInstance();
	if($arParams["CACHE_TYPE"]!="N" && !$arParams["CACHE_TIME"])
	{
		$arParams["CACHE_TIME"] = 3600;
	}
	// проверяем кеш и задаём настройки
	$arResult["USER"] = MultiPrice::getUserProperty();
	$arParams["USER_ID"] = $USER->GetID();
	$arParams["USER_CONTRAGENTS"] = array_column($arResult["USER"]['CONTRAGENTS'],"ID");
	$cache_id = sha1($arParams);
	if ($arParams["CACHE_TYPE"]!="N" && $cache->initCache($arParams["CACHE_TIME"], $cache_id, "/getBonus/"))
	{
		$arResult = $cache->getVars(); // достаем переменные из кеша
	}
	elseif ($cache->startDataCache())
	{
		if(Loader::includeModule("germes.multibasket"))
		{
			$tmp = MultiBasket::getUserArr()?:[];
			$arResult = array_merge($arResult, $tmp);
		}
		if($arResult["USER"]['ACTIVE_CONTRAGENT']['UF_KOD'])
		{
			foreach ($arResult["USER"]['CONTRAGENTS'] as $key => $contragent)
			{
				$arResult["USER"]['CONTRAGENTS'][$key]["BONUS"] = Contragents::getBonusList($contragent);
				$arResult["USER"]['CONTRAGENTS'][$key]["BONUS_SUMM"] = Contragents::countBonusSumm($arResult["USER"]['CONTRAGENTS'][$key]["BONUS"]["LIST"]);
			}
		}
		$cache->endDataCache($arResult);
	}
	foreach ($arResult["USER"]['CONTRAGENTS'] as $key => $contragent)
	{
		if($arResult["USER"]['ACTIVE_CONTRAGENT']["ID"] == $contragent["ID"])
		{
			$arResult["USER"]['ACTIVE_CONTRAGENT']["BONUS"] = $contragent["BONUS"];
			$arResult["USER"]['ACTIVE_CONTRAGENT']["BONUS_SUMM"] = $contragent["BONUS_SUMM"];
		}
	}
}


// Frame::getInstance()->startDynamicWithID("contragent_list");
$this->IncludeComponentTemplate($componentPage);
// Frame::getInstance()->finishDynamicWithID("contragent_list",'<div class="spinner-grow text-secondary" style="width: 2rem; height: 2rem;" role="status"><span class="sr-only">Loading...</span></div>');
