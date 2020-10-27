<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main;
use Bitrix\Main\Localization\Loc;

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
if($arResult["USER"]["CONTRAGENTS"]){?>
	<div class="bx-basket-block">
		<div style="margin: -5px 0 0 -10px;">
			<?if($arResult["USER"]['ACTIVE_CONTRAGENT']["BONUS"]["LIST"])
			{?>
				<div class="active_contragent-bonus-list">
					<div class="btn-group dropright">
						<button class="btn-link dropdown-toggle float-none text-color-danger bg-none" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<!--
							<?//=GetMessage("BONUS_LIST_SUMM").":"?> <?//=$arResult["USER"]['ACTIVE_CONTRAGENT']["BONUS_SUMM"]?>
							<i class="fas fa-certificate wow pulse h3 pl-2 mt-0" data-wow-iteration="infline" data-wow-duration="0.5s"></i>
							<i class="fab fa-blogger-b wow pulse h3 pl-2 mt-0" data-wow-iteration="infline" data-wow-duration="0.5s"></i>
							<i class="fab fa-bitcoin wow pulse h3 pl-2 mt-0" data-wow-iteration="infline" data-wow-duration="0.5s"></i>
							<i class="fas fa-bahai wow pulse h3 pl-2 mt-0" data-wow-iteration="infline" data-wow-duration="0.5s"></i>
							<i class="fas fa-atom wow pulse h3 pl-2 mt-0" data-wow-iteration="infline" data-wow-duration="0.5s"></i>
							<i class="fas fa-coins wow pulse h3 pl-2 mt-0" data-wow-iteration="infline" data-wow-duration="0.5s"></i>
							-->
							<i class="fas fa-gift wow pulse h3 pl-2 mt-0 bg-none text-color-danger" data-wow-iteration="infline" data-wow-duration="0.5s"
								data-toggle="tooltip"
								title="<?=GetMessage("SHOW_BONUS_LIST",["#BONUS_SUMM#"=>$arResult["USER"]['ACTIVE_CONTRAGENT']["BONUS_SUMM"]])?>"
							></i>
						</button>
						<div class="dropdown-menu">
							<div class="text-center border-bottom"><?=GetMessage("BONUS_LIST")?></div>
							<?foreach ($arResult["USER"]['ACTIVE_CONTRAGENT']["BONUS"]["LIST"] as $key => $bonus) {?>
								<span data-toggle="tooltip" title="<?=$bonus["NAME"]." ".$bonus["VALUE"]?>" class="dropdown-item">
									<b><?=GetMessage("BONUS_VALUE",["#BONUS_VALUE#"=>$bonus["VALUE"]])?></b> <?=$bonus["NAME"]?> (<?=$bonus['TIMESTAMP']?GetMessage("UNTIL")." ".ConvertTimeStamp(strtotime($bonus['TIMESTAMP']), 'SHORT'):GetMessage("NO_UNTIL")?>)
								</span>
							<?}?>
						</div>
					</div>
				</div>
			<?}?>
			<div class="btn-group dropright">
				<?if(count($arResult["USER"]["CONTRAGENTS"])>1){?>
					<button class="btn-link float-none py-1 px-3 border-0 dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="<?=GetMessage("CHANGE_CONTRAGENT")?>">
						<i class="fas fa-users"></i>
						<!-- <span class="fas fa-truck-moving"></span> -->
						<?=$arResult["USER"]["ACTIVE_CONTRAGENT"]["UF_NAIMENOVANIESOKRA"]?> / <?=$arResult["USER"]["ACTIVE_CONTRAGENT"]["UF_TIPTSEN"]["CURRENCY_NAME"]?:$arResult["USER"]["ACTIVE_CONTRAGENT"]["UF_TIPTSEN"]["UF_VALYUTA"]?>
						<?=$arResult["USER"]["ACTIVE_CONTRAGENT"]["BONUS"]["SUMM"]?>
					</button>
					<div class="dropdown-menu">
						<div class="text-center border-bottom"><?=GetMessage("CHANGE_TO")?></div>
						<?foreach ($arResult["USER"]["CONTRAGENTS"] as $key => $contragent) {?>
							<a title="<?=GetMessage("CHANGE_CONTRAGENT_TO")." ".$contragent["UF_NAIMENOVANIESOKRA"]?>" class="dropdown-item<?=$arResult["USER"]["ACTIVE_CONTRAGENT"]["ID"]==$contragent["ID"]?" active":""?>" href="#" onclick="BX.setCookie('ACTIVE_CONTRAGENT_ID',<?=$contragent['ID']?>,{'path':'/'});location.reload(); return false;">
								<?=$contragent["UF_NAIMENOVANIESOKRA"]?> / <?=$contragent["UF_TIPTSEN"]["CURRENCY_NAME"]?:$contragent["UF_TIPTSEN"]["UF_VALYUTA"]?>
							</a>
						<?}?>
					</div>
				<?}elseif($arResult["USER"]["CONTRAGENTS"]){?>
					<span class="btn-link float-none py-1 px-3 border-0" title="<?=GetMessage("ONE_CONTRAGENT")?>">
						<i class="fas fa-users"></i>
						<!-- <span class="fas fa-truck-moving"></span> -->
						<?=$arResult["USER"]["ACTIVE_CONTRAGENT"]["UF_NAIMENOVANIESOKRA"]?> / <?=$arResult["USER"]["ACTIVE_CONTRAGENT"]["UF_TIPTSEN"]["CURRENCY_NAME"]?:$arResult["USER"]["ACTIVE_CONTRAGENT"]["UF_TIPTSEN"]["UF_VALYUTA"]?>
						<?=$arResult["USER"]["ACTIVE_CONTRAGENT"]["BONUS"]["SUMM"]?>
					</span>
				<?}?>
			</div>
		</div>
	</div>
<?}
else
{
	ShowError($arResult['ERROR_MESSAGE']);
}
