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
use \Bitrix\Main\Loader
	,\Bitrix\Main\Page\Frame
	,\Bitrix\Main\Data\Cache
	,\Bitrix\Main\Localization\Loc
	,\Germes\MultiBasket\MultiBasket
	,\Germes\Sale\MultiPrice
	;
Loc::loadMessages(__FILE__);

global $USER, $APPLICATION;
// TODO нормализовать компонент модуля мультикорзин
if($arParams["SET_TITLE"]=="Y")
{
	$APPLICATION->SetTitle($arParams["TITLE"]?:Loc::getMessage("TITLE"));
}
if(Loader::includeModule("germes.multibasket"))
{
	if($_REQUEST["AJAX"]=="Y"){
		if($_REQUEST["NEW_BASKET"]=="Y"){
			$GLOBALS['APPLICATION']->RestartBuffer();
			echo MultiBasket::changeUserBasket();
			die();
		}elseif(isset($_REQUEST["DEL_BASKET"]) && $_REQUEST["DEL_BASKET"]!=""){
			$GLOBALS['APPLICATION']->RestartBuffer();
			echo MultiBasket::deleteUserBasket(intval($_REQUEST["DEL_BASKET"]));
			die();
		}elseif(isset($_REQUEST["CHANGE_BASKET"]) && $_REQUEST["CHANGE_BASKET"]!=""){
			$GLOBALS['APPLICATION']->RestartBuffer();
			echo MultiBasket::changeUserBasket(intval($_REQUEST["CHANGE_BASKET"]));
			die();
		}
	}
	// TODO сомнительное сохранение корзины, тут нужно?
	MultiBasket::saveUserBasketInProfile();
	$arResult = MultiBasket::getUserArr();
	if($arResult["UF_B_SALE_FUSER"])
	{
		$arResult["BASKETS"] = \Bitrix\Main\Web\Json::decode($arResult["UF_B_SALE_FUSER"]);
		$arResult["FUSER_ID"] = \Bitrix\Sale\Fuser::getId();
		unset($arResult["UF_B_SALE_FUSER"]);
	}
}

$this->IncludeComponentTemplate($componentPage);
