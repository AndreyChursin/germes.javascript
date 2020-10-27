<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$arComponentDescription = array(
    "NAME" => GetMessage("Вывод списка контрагентов"),
    "DESCRIPTION" => GetMessage("Вывод списка контрагентов"),
    "PATH" => array(
        "ID" => "germes",
        "CHILD" => array(
            "ID" => "multibasketcontragentlist",
            "NAME" => "Вывод контрагентов"
        )
    ),
    "ICON" => "/images/icon.gif",
);
?>
