<?php
/**
 * Bitrix Framework
 * @package germes
 * @subpackage javascript
 * @copyright 2019 ra-germes
 */
use Bitrix\Main;
use Bitrix\Main\UserTable;
use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\EventManager;
use Bitrix\Main\Application;
use Bitrix\Main\IO\Directory;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;
use Bitrix\Sale\Internals\FuserTable;


Class germes_js extends CModule
{
	public function __construct()
	{
		Loc::loadMessages(__FILE__);

		$arModuleVersion = array();
		$path = str_replace("\\", "/", __FILE__);
		$path = substr($path, 0, strlen($path) - strlen("/index.php"));
		include($path."/version.php");
		$this->MODULE_ID 		   = str_replace("_", ".", get_class($this));
		$this->MODULE_VERSION 	   = $arModuleVersion["VERSION"];
		$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
		if (is_array($arModuleVersion) && array_key_exists("VERSION", $arModuleVersion)){
			$this->MODULE_VERSION = $arModuleVersion["VERSION"];
			$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
		}
		$this->MODULE_NAME = Loc::getMessage("MODULE_NAME_MULTIBASKET");
		$this->MODULE_DESCRIPTION = Loc::getMessage("MODULE_DESCRIPTION_MULTIBASKET");
		$this->PARTNER_NAME = Loc::getMessage("PARTNER_NAME_MULTIBASKET");
		$this->PARTNER_URI = "https://ra-germes.ru";
	}
	/**
	 * InstallEvents
	 *  - регистрация событий
	 *
	 * @return void
	 */
	public function InstallEvents()
	{
		EventManager::getInstance()->registerEventHandler('sale', 'OnSaleBasketItemRefreshData', $this->MODULE_ID, "\Germes\MultiBasket\Handlers\Event","onSaleBasketItemRefreshData",101);
		EventManager::getInstance()->registerEventHandler('sale', 'OnSaleBasketItemEntitySaved', $this->MODULE_ID, "\Germes\MultiBasket\Handlers\Event","OnSaleBasketItemEntitySaved",101);
		EventManager::getInstance()->registerEventHandler('sale', 'OnSaleBasketItemDeleted', $this->MODULE_ID, "\Germes\MultiBasket\Handlers\Event","OnSaleBasketItemDeleted",10);
		EventManager::getInstance()->registerEventHandler('sale', 'onBeforeBasketAdd', $this->MODULE_ID, "\Germes\MultiBasket\Handlers\Event","onBeforeBasketAdd",100);
		EventManager::getInstance()->registerEventHandler('sale', 'OnSaleComponentOrderJsData', $this->MODULE_ID, "\Germes\MultiBasket\Handlers\Event","OnSaleComponentOrderJsData",100);
		EventManager::getInstance()->registerEventHandler('sale', 'OnSaleComponentOrderResultPrepared', $this->MODULE_ID, "\Germes\MultiBasket\Handlers\Event","OnSaleComponentOrderResultPrepared",100);
		EventManager::getInstance()->registerEventHandler('sale', 'OnSaleComponentOrderCreated', $this->MODULE_ID, "\Germes\MultiBasket\Handlers\Event","OnSaleComponentOrderCreated",100);
		EventManager::getInstance()->registerEventHandler('sale', 'OnSaleOrderBeforeSaved', $this->MODULE_ID, "\Germes\MultiBasket\Handlers\Event","OnSaleOrderBeforeSaved",100);
		EventManager::getInstance()->registerEventHandler('main', 'OnPageStart', $this->MODULE_ID, "\Germes\MultiBasket\Handlers\Event","onPageStart");
		EventManager::getInstance()->registerEventHandler('main', 'OnBeforeProlog', $this->MODULE_ID, "\Germes\MultiBasket\Handlers\Event","OnBeforeProlog");
		//
		EventManager::getInstance()->registerEventHandler('main', 'OnBuildGlobalMenu', $this->MODULE_ID, "germes_multibasket","BuildGlobalMenu_Handler");
		EventManager::getInstance()->registerEventHandler('main', 'OnBuildGlobalMenu', $this->MODULE_ID, "germes_multibasket","DoBuildGlobalMenu_Handler");

		// AddEventHandler("main", "OnBuildGlobalMenu", Array(get_class($this),"BuildGlobalMenu_Handler"));
		// AddEventHandler("main", "OnBuildGlobalMenu", Array(get_class($this),"DoBuildGlobalMenu_Handler"));
		return true;
	}
	private function BuildGlobalMenu_Handler(&$aGlobalMenu, &$aModuleMenu)
	{
		foreach($aModuleMenu as $k => $v)
		{
			if($v["parent_menu"] == "global_menu_settings" && $v["items_id"] == "csalemultibasket")
			{
				$aModuleMenu[$k]["items"][] = Array(
					"text" => "Мультикорзина",
					"url" => "csalemultibasket.php?lang=".LANG,
					"title" => "Мультикорзина"
					);
			}
		}
	}
	private function DoBuildGlobalMenu_Handler(&$aGlobalMenu, &$aModuleMenu) {
		$aModuleMenu[] = array(
			"parent_menu" => "global_menu_settings",
			"icon" => "dev2fun_compressimage_menu_icon",
			"page_icon" => "dev2fun_compressimage_page_icon",
			"sort"=>"900",
			"text"=> Loc::getMessage("GERMES_MULTIBASKET_MENU_TEXT").'2222',
			"title"=> Loc::getMessage("GERMES_MULTIBASKET_MENU_TITLE").'2222',
			"url"=>"/bitrix/admin/multibasket.php",
			"items_id" => "menu_dev2fun_compressimage",
			"section" => "multibasket",
			"more_url"=>array(),
			/* "items" => array(
				array(
					"text" => GetMessage("DEV2FUN_IMAGECOMPRESS_SUB_SETINGS_MENU_TEXT"),
					"title"=> GetMessage("DEV2FUN_IMAGECOMPRESS_SUB_SETINGS_MENU_TITLE"),
					"url"=>"/bitrix/admin/dev2fun_imagecompress_files.php",
					"sort"=>"100",
					"icon" => "sys_menu_icon",
					"page_icon" => "default_page_icon",
				),
			) */
		);
	}
	/**
	 * UnInstallEvents
	 *
	 * @return void
	 */
	public function UnInstallEvents()
	{
		EventManager::getInstance()->unRegisterEventHandler('sale', 'OnSaleBasketItemRefreshData', $this->MODULE_ID, "\Germes\MultiBasket\Handlers\Event","onSaleBasketItemRefreshData",101);
		EventManager::getInstance()->unRegisterEventHandler('sale', 'OnSaleBasketItemEntitySaved', $this->MODULE_ID, "\Germes\MultiBasket\Handlers\Event","OnSaleBasketItemEntitySaved",101);
		EventManager::getInstance()->unRegisterEventHandler('sale', 'OnSaleBasketItemDeleted', $this->MODULE_ID, "\Germes\MultiBasket\Handlers\Event","OnSaleBasketItemDeleted",10);
		EventManager::getInstance()->unRegisterEventHandler('sale', 'onBeforeBasketAdd', $this->MODULE_ID, "\Germes\MultiBasket\Handlers\Event","onBeforeBasketAdd",100);
		EventManager::getInstance()->unRegisterEventHandler('sale', 'OnSaleComponentOrderJsData', $this->MODULE_ID, "\Germes\MultiBasket\Handlers\Event","OnSaleComponentOrderJsData",100);
		EventManager::getInstance()->unRegisterEventHandler('sale', 'OnSaleComponentOrderResultPrepared', $this->MODULE_ID, "\Germes\MultiBasket\Handlers\Event","OnSaleComponentOrderResultPrepared",100);
		EventManager::getInstance()->unRegisterEventHandler('sale', 'OnSaleComponentOrderCreated', $this->MODULE_ID, "\Germes\MultiBasket\Handlers\Event","OnSaleComponentOrderCreated",100);
		EventManager::getInstance()->unRegisterEventHandler('sale', 'OnSaleOrderBeforeSaved', $this->MODULE_ID, "\Germes\MultiBasket\Handlers\Event","OnSaleOrderBeforeSaved",100);
		EventManager::getInstance()->unRegisterEventHandler('main', 'OnPageStart', $this->MODULE_ID, "\Germes\MultiBasket\Handlers\Event","onPageStart");
		EventManager::getInstance()->unRegisterEventHandler('main', 'OnBeforeProlog', $this->MODULE_ID, "\Germes\MultiBasket\Handlers\Event","OnBeforeProlog");
		//
		EventManager::getInstance()->unRegisterEventHandler('main', 'OnBuildGlobalMenu', $this->MODULE_ID, "germes_multibasket","BuildGlobalMenu_Handler");
		EventManager::getInstance()->unRegisterEventHandler('main', 'OnBuildGlobalMenu', $this->MODULE_ID, "germes_multibasket","DoBuildGlobalMenu_Handler");
		return true;
	}
	/**
	 * InstallFiles
	 *
	 * @return void
	 */
	public function InstallFiles()
	{
		CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/local/modules/".$this->MODULE_ID."/install/admin",$_SERVER["DOCUMENT_ROOT"]."/bitrix/admin", true, true);
		CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/local/modules/".$this->MODULE_ID."/install/components",$_SERVER["DOCUMENT_ROOT"]."/local/components", true, true);
		return true;
	}
	/**
	 * UnInstallFiles
	 *
	 * @return void
	 */
	public function UnInstallFiles()
	{
		DeleteDirFilesEx("/local/components/bitrix/".$this->MODULE_ID."");
		DeleteDirFiles($_SERVER["DOCUMENT_ROOT"]."/local/modules/".$this->MODULE_ID."/install/admin", $_SERVER["DOCUMENT_ROOT"]."/bitrix/admin");
		return true;
	}
	/**
	 * InstallDB
	 *
	 * @return void
	 */
	public function InstallDB()
	{
		global $DB;
		$this->errors = false;
		$this->errors = $DB->RunSQLBatch($_SERVER['DOCUMENT_ROOT'] . "/local/modules/".$this->MODULE_ID."/install/db/mysql/install.sql");
		if (!$this->errors) {
			return true;
		} else {
			return $this->errors;
		}
	}
	/**
	 * UnInstallDB
	 *
	 * @return void
	 */
	public function UnInstallDB()
	{
		global $DB;
		$this->errors = false;
		$this->errors = $DB->RunSQLBatch($_SERVER['DOCUMENT_ROOT'] . "/local/modules/".$this->MODULE_ID."/install/db/mysql/uninstall.sql");
		if (!$this->errors) {
			return true;
		} else {
			return $this->errors;
		}
	}
	/**
	 * DoInstall
	 *
	 * @return void
	 */
	public function DoInstall()
	{
		global $DOCUMENT_ROOT, $APPLICATION;
		if (!IsModuleInstalled($this->MODULE_ID))
		{
			$this->InstallDB();
			$this->InstallEvents();
			$this->InstallFiles();
		}
		// $APPLICATION->IncludeAdminFile("Установка модуля germes.multibasket", $DOCUMENT_ROOT."/local/modules/germes.multibasket/install/step.php");
		RegisterModule($this->MODULE_ID);
		$this->ShowThanksNotice($this->MODULE_ID);
	}
	/**
	 * DoUninstall
	 *
	 * @return void
	 */
	public function DoUninstall()
	{
		global $DOCUMENT_ROOT, $APPLICATION;
		$this->UnInstallDB();
		$this->UnInstallEvents();
		$this->UnInstallFiles();
		// $APPLICATION->IncludeAdminFile("Деинсталляция модуля germes.multibasket", $DOCUMENT_ROOT."/local/modules/germes.multibasket/install/unstep.php");
		UnRegisterModule($this->MODULE_ID);
	}
	/**
	 * ShowThanksNotice
	 *
	 * @return void
	 */
	private static function ShowThanksNotice($MODULE_ID="") {
		\CAdminNotify::Add([
			'MESSAGE' => \Bitrix\Main\Localization\Loc::getMessage(
				'GERMES_MULTIBASKET_DONATE_MESSAGE',
				['#URL#'=>'/bitrix/admin/settings.php?mid='.$MODULE_ID.'']
			),
			'TAG' => 'multibasket',
			'MODULE_ID' => 'germes.multibasket',
		]);
	}
}
