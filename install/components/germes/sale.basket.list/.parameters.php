<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$arComponentParameters = array(
    "GROUPS" => array(),
    "PARAMETERS" => array(
        "SEF_MODE" => array(
			"file_id" => array(
				"NAME" => GetMessage("FILE_PAGE"),
				"DEFAULT" => "#ID#",
				"VARIABLES" => array(),
			),
		),
        "FILE_URL" => array(
            "PARENT" => "BASE",
            "NAME" => "URL запроса",
            "TYPE" => "STRING",
            "MULTIPLE" => "N",
            "DEFAULT" => "",
        ),
    ),
);
?>
