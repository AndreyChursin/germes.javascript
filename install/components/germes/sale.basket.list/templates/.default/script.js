jQuery(document).ready(function(){
	jQuery(document).on("click","[data-target*='click']", function(){
		var url = this.dataset.url||window.location.pathname
			,data = JSON.parse(this.dataset.datajson)||[]
			,callback = this.dataset.callback
			,conf = true
		;
		if(data["DEL_BASKET"]){
			conf = confirm("Уверены, что хотите удалить корзину?");
		}
		if(this.dataset.request=="ajax" && conf){
			BX.showWait("baskets-table");
			data = Object.assign({'AJAX':'Y'},data);
			BX.ajax.post(
				url,
				data,
				function(){eval(callback);BX.closeWait("baskets-table");}
			);
		}
		event.stopPropagation();
		// return false;
	});
	jQuery(document).on("mouseenter","*[data-target*='hover'],*[data-target*='mouseenter']", function(){
		console.log("hover|mouseenter");
		// console.log(this.dataset);
	});
	jQuery(document).on("mouseleave","*[data-target*='hover'],*[data-target*='mouseleave']", function(){
		console.log("hover|mouseleave");
		// console.log(this.dataset);
	});
});
