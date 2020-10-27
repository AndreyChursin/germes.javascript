function changeContragent(id)
{
	if(id == undefined || !Number.isInteger(id) ) return false;
	let change = confirm("Вы действительно хотите сменить активного контрагента?");
	if(change)
	{
		BX.setCookie('ACTIVE_CONTRAGENT_ID',id,{'path':'/'});
	}
}
if(0)
{
	window.onbeforeunload = function (e)
	{
		// Ловим событие для Interner Explorer
		var e = e || window.event;
		var myMessage = "Вы действительно хотите покинуть страницу, не сохранив данные?";
		// Для Internet Explorer и Firefox
		if (e)
		{
			e.returnValue = myMessage;
		}
		// Для Safari и Chrome
		return myMessage;
	};
}
