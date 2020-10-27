<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main
	,\Bitrix\Main\Localization\Loc;

\Bitrix\Main\UI\Extension::load("ui.fonts.ruble");

/**
 * @var array $arParams
 * @var array $arResult
 * @var string $templateFolder
 * @var string $templateName
 * @var CMain $APPLICATION
 * @var CBitrixBasketComponent $component
 * @var CBitrixComponentTemplate $this
 * @var array $giftParameters
 */
if($arResult["BASKETS"]){
	$arBaskets = $arResult["BASKETS"];
	$curFuser_id = $arResult["FUSER_ID"];?>
	<div class="card-body table-responsive">
		<table id="baskets-table" class="table table-hover">
			<tr>
				<th><?=Loc::getMessage('BASKET_LIST_NUM');?></th>
				<th><?=Loc::getMessage('BASKET_LIST_ID');?></th>
				<th><?=Loc::getMessage('BASKET_LIST_DATE');?></th>
				<th><?=Loc::getMessage('BASKET_LIST_COUNT');?></th>
				<th><?=Loc::getMessage('BASKET_LIST_COUNT_SUM');?></th>
				<th><?=Loc::getMessage('BASKET_LIST_SUMM');?></th>
				<th><?=Loc::getMessage('BASKET_LIST_CONTRAGENT');?></th>
				<th></th>
			</tr>
			<?foreach($arBaskets as $fuser_id=>$basket){?>
				<tr <?=$fuser_id==$curFuser_id?'class="table-active"':'';?>>
					<td style="cursor:pointer;"
						onclick="$('#baskets-table').find('i.fas[class*=fa-toggle-]').addClass('fa-toggle-off').removeClass('fa-toggle-on');
							$(this).find('i.fas').toggleClass('fa-toggle-off').toggleClass('fa-toggle-on');"
						data-target="click"
						data-request="ajax"
						data-datajson='<?=json_encode(['CHANGE_BASKET'=>$fuser_id])?>'
						data-callback="BX.setCookie('ACTIVE_CONTRAGENT_ID',<?=$basket["ACTIVE_CONTRAGENT"]["ID"]?>,{'path':'/'});window.location.reload();"
						data-change_basket="<?=$fuser_id?>"
						>
						<?=$fuser_id==$curFuser_id?'<i class="fas fa-toggle-on"></i>':'<i class="fas fa-toggle-off"></i>';?>
					</td>
					<td><?=$basket["FUSER_TABLE"]["ID"]?></td>
					<td><?=($basket["FUSER_TABLE"]["DATE_UPDATE"])//table-hover("","YY-mm-dd H:i","RU")?></td>
					<td><?=count($basket["ITEMS"])//table-hover("","YY-mm-dd H:i","RU")?></td>
					<td><?=intval($basket["BASKET_TOTAL_COUNT"])?></td>
					<td><?=CurrencyFormat($basket["BASKET_SUM"],($basket["ACTIVE_CONTRAGENT"]['UF_VALYUTAOTGRUZKI']["CURRENCY"]?:"RUB"))?></td>
					<td><?=$basket["ACTIVE_CONTRAGENT"]["UF_NAIMENOVANIESOKRA"]?:"-?-"?></td>
					<td>
						<span
							data-target="click"
							data-request="ajax"
							data-datajson='<?=json_encode(['DEL_BASKET'=>$fuser_id])?>'
							data-callback="window.location.reload();"
							data-del_basket="<?=$fuser_id?>"
							class="btn py-0 btn-dell del_basket">
							<i class="fas fa-trash-alt"></i>
						</span>
					</td>
				</tr>
			<?}?>
		</table>
	</div>
<?}
else
{
	ShowError($arResult['ERROR_MESSAGE']);
}
