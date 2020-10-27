<?
define("ADMIN_MODULE_NAME", "germes.multibasket");
//require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

$curModuleName = ADMIN_MODULE_NAME?:"germes.multibasket";
Loader::includeModule($curModuleName);

IncludeModuleLangFile(__FILE__);

/**
 * @global CUser $USER
 * @global CMain $APPLICATION
 **/

/* $canRead = $USER->CanDoOperation('imagecompress_list_read');
$canWrite = $USER->CanDoOperation('imagecompress_list_write');
if(!$canRead && !$canWrite)
    $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));

 */
$EDITION_RIGHT = $APPLICATION->GetGroupRight($curModuleName);
if ($EDITION_RIGHT=="D") $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));

$aTabs = array(
    array(
        "DIV" => "main",
        "TAB" => GetMessage("SEC_MAIN_TAB"),
        "ICON"=>"main_user_edit",
        "TITLE"=>'TITLE'.GetMessage("SEC_MAIN_TAB_TITLE"),
    ),
);

$tabControl = new CAdminTabControl("tabControl", $aTabs, true, true);

$bVarsFromForm = false;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");

$lAdmin = new CAdminList($curModuleName);
$lAdmin->generalKey = 'ID';
$lAdmin->SetContextMenu(false);
$lAdmin->SetRights();
$lAdmin->SetTitle(GetMessage('GERMES_MULTIBASKET_TITLE'));
$lAdmin->SetGroupAction(array(
    'Удалить' => function($hash) {
        echo "<pre>";
        print_r($hash);
        echo "</pre>";
    },
));
$lAdmin->SetHeaders(array(
    'ID' => "ID",
    'BASKET_ITEM' => GetMessage('GERMES_MULTIBASKET_BASKET_ITEM'),
    'SORT' => GetMessage("GERMES_MULTIBASKET_SORT"),
    'TIMESTAMP' => GetMessage("GERMES_MULTIBASKET_TIMESTAMP"),
));

// dataset
$rsData = CIBlockElement::WF_GetHistoryList($ELEMENT_ID, $by, $order, $arFilter, $is_filtered);
$rsData = new CAdminResult($rsData, $sTableID);
$rsData->NavStart();

// navigation
$lAdmin->NavText($rsData->GetNavPrint(GetMessage("IBLOCK_ADM_HISTORY_PAGER")));

// list headers
$lAdmin->AddHeaders(array(
	array(
		"id" => "ID",
		"content" => GetMessage("IBLOCK_FIELD_ID"),
		"sort" => "s_id",
		"default" => true,
	),
	array(
		"id" => "NAME",
		"content" => GetMessage("IBLOCK_FIELD_NAME"),
		"sort" => "s_name",
		"default" => true,
	),
	array(
		"id" => "WF_STATUS_ID",
		"content" => GetMessage("IBLOCK_FIELD_STATUS"),
		"sort" => "s_status",
		"default" => true,
	),
	array(
		"id" => "MODIFIED_BY",
		"content" => GetMessage("IBLOCK_FIELD_USER_NAME"),
		"sort" => "s_modified_by",
		"default" => true,
	),
	array(
		"id" => "TIMESTAMP_X",
		"content" => GetMessage("IBLOCK_FIELD_TIMESTAMP_X"),
		"sort" => "s_timestamp_x",
		"default" => true,
	),
));

// list
while($arRes = $rsData->NavNext(true, "f_"))
{
	$row =& $lAdmin->AddRow($f_ID, $arRes);

	if($f_MODIFIED_BY>0)
		$row->AddViewField("MODIFIED_BY", '[<a href="user_edit.php?lang='.LANG.'&ID='.$f_MODIFIED_BY.'">'.$f_MODIFIED_BY.'</a>] '.$f_USER_NAME.'</a>');

	$row->AddViewField("WF_STATUS_ID", '[<a href="workflow_status_edit.php?ID='.$f_WF_STATUS_ID.'&lang='.LANG.'">'.$f_WF_STATUS_ID.'</a>] '.htmlspecialcharsex(CIBlockElement::WF_GetStatusTitle($f_WF_STATUS_ID)));

	$arActions = Array();
	$arActions[] = array(
		"ICON"=>"view",
		"DEFAULT"=>true,
		"TEXT"=>GetMessage("IBLOCK_ADM_HISTORY_VIEW"),
		"TITLE"=>GetMessage("IBLOCK_ADM_HISTORY_VIEW_ALT"),
		"ACTION"=>$lAdmin->ActionRedirect('iblock_element_edit.php?type='.$type.'&ID='.$f_ID.'&lang='.LANG.'&IBLOCK_ID='.$IBLOCK_ID.'&view=Y&find_section_section='.$find_section_section)
		);

	$arActions[] = array("SEPARATOR"=>true);
	$arActions[] = array(
		"ICON"=>"delete",
		"TEXT"=>GetMessage('IBLOCK_ADM_HISTORY_DELETE'),
		"TITLE"=>GetMessage("IBLOCK_ADM_HISTORY_DELETE_ALT"),
		"ACTION"=>"if(confirm('".GetMessageJS("IBLOCK_ADM_HISTORY_CONFIRM_DEL")."')) ".$lAdmin->ActionDoGroup($f_ID, "delete", 'type='.htmlspecialcharsbx($type).'&ELEMENT_ID='.$ELEMENT_ID.'&IBLOCK_ID='.$IBLOCK_ID.'&find_section_section='.$find_section_section)
		);
	$arActions[] = array(
		"ICON"=>"restore",
		"TEXT"=>GetMessage('IBLOCK_ADM_HISTORY_RESTORE'),
		"TITLE"=>GetMessage("IBLOCK_ADM_HISTORY_RESTORE_ALT"),
		"ACTION"=>"if(confirm('".GetMessageJS("IBLOCK_ADM_HISTORY_RESTORE_CONFIRM")."')) ".$lAdmin->ActionDoGroup($f_ID, "restore", 'type='.htmlspecialcharsbx($type).'&ELEMENT_ID='.$ELEMENT_ID.'&IBLOCK_ID='.$IBLOCK_ID.'&find_section_section='.$find_section_section)
		);

	$row->AddActions($arActions);
}


$lAdmin->SetFooter(array(
    array("title"=>GetMessage("MAIN_ADMIN_LIST_SELECTED"), "value"=> $selectedCount),
	array("counter"=>true, "title"=>GetMessage("MAIN_ADMIN_LIST_CHECKED"), "value"=>"0"),
));
$lAdmin->Output();
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
?>
