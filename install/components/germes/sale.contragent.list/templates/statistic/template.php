<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main;
use Bitrix\Main\Localization\Loc;

/**
 * @var array $arParams
 * @var array $arResult
 * @var CMain $APPLICATION
 * @var CUser $USER
 * @var SaleOrderAjax $component
 * @var string $templateFolder
 */

if (strlen($arParams["MAIN_CHAIN_NAME"]) > 0)
{
	$APPLICATION->AddChainItem(htmlspecialcharsbx($arParams["MAIN_CHAIN_NAME"]), $arResult['SEF_FOLDER']);
}
if($arResult["USER"]["UF_CONTRAGENTS"]){?>
	<section class="uf_contragents">
		<?$arResult["USER"]["UF_CONTRAGENTS"] = $arResult["USER"]["CONTRAGENTS"];
		if(!empty($arResult["USER"]["UF_CONTRAGENTS"])){?>
			<div id="accordion">
				<?foreach ($arResult["USER"]["UF_CONTRAGENTS"] as $key => $contragent) {
					// DEV информация из 1С
					// статистика
					$rsSection = CIBlockSection::GetList(array(), array("IBLOCK_ID"=>'17',"DEPTH_LEVEL"=>1,"ELEMENT_SUBSECTIONS"=>"Y","CNT_ACTIVE"=>"Y"), true, array(),array("nTopCount"=>15));
					while($arSection = $rsSection->GetNext()) {
						$contragent["STATISTICS"]["BY_SECTIONS"][$arSection['ID']] = [
							"NAME" => $arSection['NAME'],
							"COUNT" => $arSection['ELEMENT_CNT'],
							"MAX" => $arSection['ELEMENT_CNT']*rand(1,3), //$section["COUNT"]*1.15;
						];
					}
					$month = ['Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'];
					$contragent["STATISTICS"]["NOW"]["DATE"] = date("Y");
					$contragent["STATISTICS"]["NOW"]["VALUE_SEND"] = CurrencyFormat(rand(0,1000000),$contragent["UF_VALYUTAOTGRUZKI"]["CURRENCY"]);
					$contragent["STATISTICS"]["NOW"]["VALUE_PAYED"] = CurrencyFormat(rand(0,1000000),$contragent["UF_VALYUTAOTGRUZKI"]["CURRENCY"]);
					$contragent["STATISTICS"]["NOW"]["CHART"]["VALUE_SEND"] = [0, 10, 5, 2, 20, 30, 45, 50, 35, 17, 25, 15, 30, 45];
					$contragent["STATISTICS"]["NOW"]["CHART"]["VALUE_PAYED"] = [0, 10, 5, 2, 10, 20, 25, 50, 35, 17, 23, 15, 20, 25];
					$contragent["STATISTICS"]["NOW"]["CHART"]["SEGMENTATION"] = $month;
					$contragent["STATISTICS"]["NOW"]["SALE"] = ($contragent["UF_OSNOVNAYASKIDKA"]?:0)."%";
					$contragent["STATISTICS"]["NOW"]["SALE_MAX"] = "20%";
					$contragent["STATISTICS"]["PREV"]["DATE"] = date("Y", strtotime("-1 year", time()));
					$contragent["STATISTICS"]["PREV"]["VALUE_SEND"] = CurrencyFormat(rand(0,100000),$contragent["UF_VALYUTAOTGRUZKI"]["CURRENCY"]);
					$contragent["STATISTICS"]["PREV"]["VALUE_PAYED"] = CurrencyFormat(rand(0,100000),$contragent["UF_VALYUTAOTGRUZKI"]["CURRENCY"]);
					$contragent["STATISTICS"]["PREV"]["CHART"]["VALUE_SEND"] = [0, 10, 5, 2, 20, 30, 45, 50, 35, 17, 25, 15, 30, 45];
					$contragent["STATISTICS"]["PREV"]["CHART"]["VALUE_PAYED"] = [0, 10, 5, 2, 10, 20, 25, 50, 35, 17, 23, 15, 20, 25];
					$contragent["STATISTICS"]["PREV"]["CHART"]["SEGMENTATION"] = $month;
					$contragent["STATISTICS"]["PREV"]["SALE"] = ($contragent["UF_OSNOVNAYASKIDKA"]?:0)."%";
					$contragent["STATISTICS"]["PREV"]["SALE_MAX"] = "20%";
					//
					$isActive = $arResult["USER"]["ACTIVE_CONTRAGENT"]["ID"]==$contragent["ID"];?>
					<div class="card">
						<div class="card-header" id="headingOne">
							<input type="radio" name="contragent" class="mr-3 pull-left" value="<?=$contragent['ID']?>" <?=$isActive?'checked title="Активная запись"':""?>
								onclick="changeContragent('<?=$contragent["ID"]?>')">
							<div class="contragent-name cursor-pointer" data-toggle="collapse" data-target="#collapse-<?=$key?>" aria-expanded="<?=$isActive?"false":"true"?>" aria-controls="collapse-<?=$key?>">
								<h3 class="h5 my-0 mt-2"><?=$contragent["UF_NAIMENOVANIESOKRA"]?></h3>
							</div>
						</div>
						<div id="collapse-<?=$key?>" class="collapse<?=$isActive?" show":""?>" aria-labelledby="headingOne" data-parent="#accordion">
							<div class="card-body row px-3 pb-3">
								<div class="col-sm-12">
									<div>Наименование: <?=$contragent["UF_NAIMENOVANIESOKRA"]?:'-'?></div>
									<div title="Основная скидка">Основная скидка контрагента: <?=$contragent["UF_OSNOVNAYASKIDKA"]?:0?>%</div>
									<div title="Валюта отгрузки">Валюта отгрузки: <?=$contragent["UF_VALYUTAOTGRUZKI"]["CURRENCY_NAME"]?:'-'?></div>
									<?if($USER->IsAdmin()){?>
										<h6>Админу:</h6>
										<div title="Наименование(админ)">Наименование: <?=$contragent["UF_NAME"]?></div>
										<div title="Источник(админ)">Источник: <?=$contragent["UF_ISTOCHNIK"]?:'error'?></div>
										<div title="Пометка удаления(админ)"><?=$contragent["UF_POMETKAUDALENIYA"]?></div>
									<?}?>
								</div>
								<?if($contragent["UF_SKLAD"]){?>
									<div class="col-sm col-md-3">
										<?//получим Значения
										$contragent["UF_SKLAD"] = \Bitrix\Catalog\StoreTable::getRow(['select' => ['*'],'filter' => ['XML_ID' => $contragent["UF_SKLAD"],]]);?>
										<?if(!empty($contragent["UF_SKLAD"])){?>
											<div class='row'>
												<div class="col-sm-12">
													<div class="card bg-light">
														<div class="px-3"><h4>Склад обслуживания</h4></div>
														<div class="operator-photo card-img-top rounded mx-auto d-block"><?=$photo?></div>
														<div class="card-body">
															<div class='operator-name card-title' title="Название"><?=$contragent["UF_SKLAD"]["TITLE"]?></div>
															<div class="card-text">
																<div class="operator-description" title="Описание">Описание: <?=$contragent["UF_SKLAD"]["DESCRIPTION"]?></div>
																<div class="operator-delivery-address" title="Адрес расположения">Адрес: <?=$contragent["UF_SKLAD"]["ADDRESS"]?></div>
															</div>
															<!-- <a href="tel:<?=preg_replace("#\D*#iu","",$contragent["UF_SKLAD"]["UF_NOMERTELEFONA"])?>" class="btn btn-warning">Связаться</a> -->
														</div>
													</div>
												</div>
											</div>
										<?}?>
									</div>
								<?}?>
								<?if($contragent["UF_PODRAZDELENIYA"]){?>
									<div class="col-sm col-md-3">
										<?//получим Значения
										$contragent["UF_PODRAZDELENIYA"] = getReferences(HL_ID_CONTRPODRAZDELENIYA,["ID"=>$contragent["UF_PODRAZDELENIYA"]]);
										if(!empty($contragent["UF_PODRAZDELENIYA"])){?>
											<div class='row'>
												<div class="col-12"><h4>Подразделенния</h4></div>
												<?foreach ($contragent["UF_PODRAZDELENIYA"] as $i => $podrazdeleniya) {?>
													<div class="col-sm-4">
														<div class="card bg-light">
															<div class="operator-photo card-img-top rounded mx-auto d-block"><?=$photo?></div>
															<div class="card-body">
																<div class='operator-name card-title'><?=$podrazdeleniya["UF_NAME"]?></div>
																<div class="card-text">
																	<div class="operator-adresdoss">Адрес доставки: <?=$podrazdeleniya["UF_ADRESDOSTAVKI"]?></div>
																	<!-- <div class="operator-email"><?=$podrazdeleniya["UF_EMAIL"]?></div> -->
																</div>
																<!-- <a href="tel:<?=preg_replace("#\D*#iu","",$podrazdeleniya["UF_NOMERTELEFONA"])?>" class="btn btn-warning">Связаться</a> -->
															</div>
														</div>
													</div>
												<?}?>
											</div>
										<?}?>
									</div>
								<?}?>
								<?if($contragent["UF_MENEDZHER"]){?>
									<div class="col-sm col-md-3">
										<?//получим Значения
										$contragent["UF_MENEDZHER"] = getReferences(HL_ID_MENEDZHERY,["ID"=>$contragent["UF_MENEDZHER"]]);
										if(!empty($contragent["UF_MENEDZHER"])){?>
											<div class='row'>
												<div class="col-12"><h4>Менеджеры</h4></div>
												<?foreach ($contragent["UF_MENEDZHER"] as $i => $menedzhery) {?>
													<div class="col-sm-6">
														<div class="card bg-light">
															<div class="menedzher-photo card-img-top rounded mx-auto d-block"><?=$photo?></div>
															<div class="card-body">
																<h5 class='menedzher-name card-title'><?=$menedzhery["UF_NAME"]?></h5>
																<div class="card-text">
																	<div class="menedzher-telephone"><?=$menedzhery["UF_NOMERTELEFONA"]?></div>
																	<div class="menedzher-email"><?=$menedzhery["UF_EMAIL"]?></div>
																</div>
																<a href="tel:<?=preg_replace("#\D*#iu","",$menedzhery["UF_NOMERTELEFONA"])?>" class="btn btn-warning">Связаться</a>
															</div>
														</div>
													</div>
												<?}?>
											</div>
										<?}?>
									</div>
								<?}?>
								<?if($contragent["UF_OPERATOR"]){?>
									<div class="col-sm col-md-3">
										<?//получим Значения
										$contragent["UF_OPERATOR"] = getReferences(HL_ID_OPERATORY,["ID"=>$contragent["UF_OPERATOR"]]);
										if(!empty($contragent["UF_OPERATOR"])){?>
											<div class='row'>
												<div class="col-12"><h4>Операторы</h4></div>
												<?foreach ($contragent["UF_OPERATOR"] as $i => $operatory) {?>
													<div class="col-sm-6">
														<div class="card bg-light">
															<div class="operator-photo card-img-top rounded mx-auto d-block"><?=$photo?></div>
															<div class="card-body">
																<div class='operator-name card-title'><?=$operatory["UF_NAME"]?></div>
																<div class="card-text">
																	<div class="operator-telephone"><?=$operatory["UF_NOMERTELEFONA"]?></div>
																	<div class="operator-email"><?=$operatory["UF_EMAIL"]?></div>
																</div>
																<a href="tel:<?=preg_replace("#\D*#iu","",$operatory["UF_NOMERTELEFONA"])?>" class="btn btn-warning">Связаться</a>
															</div>
														</div>
													</div>
												<?}?>
											</div>
										<?}?>
									</div>
								<?}?>
								<?if($contragent["BONUS"]){// TODO вывести бонусы?>
									<div class="col-sm col-md-3">
										<?//получим Значения
										if(!empty($contragent["BONUS"]['LIST'])){?>
											<div class='row'>
												<div class="col-12"><h4>Бонусы</h4></div>
												<?foreach ($contragent["BONUS"]['LIST'] as $i => $bonus) {?>
													<div class="col-sm-6">
														<div class="card bg-light">
															<div class="card-body">
																<div class='bonus-name card-title'><?=$bonus["NAME"]?></div>
																<div class="card-text">
																	<div class="bonus-curs"><?=GetMessage("BONUS_CURS")?>: <?=$bonus["CURS"]?></div>
																	<div class="bonus-value"><?=GetMessage("BONUS_VALUE")?>: <?=$bonus["VALUE"]?></div>
																	<div class="bonus-date"><?=GetMessage("BONUS_DATE")?>: <?=$bonus["DATE"]?></div>
																	<meta name="timestamp" value="<?=$bonus["TIMESTAMP"]?>"/>
																	<meta name="max_value" value="<?=$bonus["MAX_VALUE"]?>"/>
																</div>
															</div>
														</div>
													</div>
												<?}?>
											</div>
										<?}?>
									</div>
								<?}?>
								<?if($contragent["STATISTICS"]){?>
									<div class="col-12">
										<hr>
										<h4>Статистика</h4>
										<div class="small description">
											Данные обновляются каждые 30 минут. Оборот компании указан в шт. При наличии в заказе некольких позиций разной SKU данные суммируются.
										</div>
										<div class="row">
											<div class="col-12">
												<h5 class="mt-5 font-weight-bold">Общая</h5>
												<?$sale = abs($contragent["UF_OSNOVNAYASKIDKA"]);?>
												<i class="<?=($sale>=5)?'fas':'far'?> fa-star text-warning"></i>
												<i class="<?=($sale>=8)?'fas':'far'?> fa-star text-warning"></i>
												<i class="<?=($sale>=10)?'fas':'far'?> fa-star text-warning"></i>
												<i class="<?=($sale>=12)?'fas':'far'?> fa-star text-warning"></i>
												<i class="<?=($sale>=18)?'fas':'far'?> fa-star text-warning"></i> — Скидка <?=$contragent["UF_OSNOVNAYASKIDKA"]?:0?>%
												<div class="progress mb-3 mb-sm-0">
													<div class="bg-warning progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="<?=($sale*100/20)?:0?>" aria-valuemin="0" aria-valuemax="20" style="width: <?=($sale*100/20)?:0?>%"></div>
												</div>
												<div class="row">
													<div class="col small text-left">Оборот компании по оплате за <?=$contragent["STATISTICS"]["PREV"]["DATE"]?> год</div>
													<div class="col small text-right">Скидка <?=$contragent["STATISTICS"]["PREV"]["SALE_MAX"]?></div>
												</div>
											</div>
											<div class="col-12">
												<h5 class="mt-5 font-weight-bold">Объёмы закупок (данные актуальны на <?=date("d.m.Y H:i")?>)</h5>
												<div class="table">
													<table class="table table-flex">
														<?foreach ($contragent["STATISTICS"]["BY_SECTIONS"] as $section_ID => $section) {
															$section["COUNT_PROCENT"] = abs($section["COUNT"]*100/$section["MAX"])?:0?>
															<tr>
																<th class="col-sm-3" scope="row"><?=$section["NAME"]?></th>
																<td>
																	<div class="progress mb-3 mb-sm-0">
																		<span class="text-left" style="width: 30px;"><?=$section["COUNT"]?> шт.</span>
																		<div data-toggle="tooltip" data-placement="auto" title="<?=$section["COUNT"]?> шт. из <?=$section["MAX"]?> от максимального оборота контрагента." class="bg-warning progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="<?=$section["COUNT_PROCENT"]?>" aria-valuemin="0" aria-valuemax="<?=$max?>" style="width: <?=$section["COUNT_PROCENT"]?>%"></div>
																	</div>
																</td>
															</tr>
														<?}?>
													</table>
												</div>
											</div>
											<div class="col-12">
												<h5 class="mt-5 font-weight-bold">Объем продаж по месяцам</h5>
												<div class="row">
													<div class="col-3">
														<div class="font-weight-bold"><?=$contragent["STATISTICS"]["PREV"]["DATE"]?> - постановка</div>
														<div><?=$contragent["STATISTICS"]["PREV"]["VALUE_SEND"]?></div>
													</div>
													<div class="col-3">
														<div class="font-weight-bold"><?=$contragent["STATISTICS"]["PREV"]["DATE"]?> - оплата</div>
														<div><?=$contragent["STATISTICS"]["PREV"]["VALUE_PAYED"]?></div>
													</div>
													<div class="col-3">
														<div class="font-weight-bold"><?=$contragent["STATISTICS"]["PREV"]["DATE"]?> - постановка</div>
														<div><?=$contragent["STATISTICS"]["NOW"]["VALUE_SEND"]?></div>
													</div>
													<div class="col-3">
														<div class="font-weight-bold"><?=$contragent["STATISTICS"]["PREV"]["DATE"]?> - оплата</div>
														<div><?=$contragent["STATISTICS"]["NOW"]["VALUE_PAYED"]?></div>
													</div>
												</div>
												<div class="">
													<?$APPLICATION->AddHeadScript("//cdn.jsdelivr.net/npm/chart.js@2.8.0");?>
													<canvas id="myChart-<?=$key?>" width="400" height="50"></canvas>
													<script>
														var ctx = document.getElementById('myChart-<?=$key?>').getContext('2d');
														var chart = new Chart(ctx, {
															// The type of chart we want to create
															type: 'line',
															// The data for our dataset
															data: {
																labels: ['<?=implode("', '",$contragent["STATISTICS"]["PREV"]["CHART"]["SEGMENTATION"])?>'],
																datasets: [{
																	label: 'постановка',
																	backgroundColor: 'transparent',
																	borderColor: 'rgba(253, 202, 90,.5)',
																	data: [<?=implode(", ",$contragent["STATISTICS"]["PREV"]["CHART"]["VALUE_SEND"])?>],
																	type: 'line',
																	// this dataset is drawn on top
																	order: 1
																}, {
																	label: 'оплата',
																	backgroundColor: 'transparent',
																	borderColor: 'rgb(253, 202, 90)',
																	data: [<?=implode(", ",$contragent["STATISTICS"]["PREV"]["CHART"]["VALUE_PAYED"])?>],
																	type: 'line',
																	// this dataset is drawn on top
																	order: 2
																}]
															},
															// Configuration options go here
															options: {}
														});
													</script>
												</div>
											</div>
										</div>
										<hr>
									</div>
								<?}?>
							</div>
						</div>
					</div>
				<?}?>
			</div>
		<?}?>
	</section>
<?}?>
