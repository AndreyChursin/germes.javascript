jQuery(document).on('click','[data-toggle="triggerBonus"]',function(){
    jQuery("input[name*='PAYED_FROM_ACCOUNT']").val(jQuery(this).data('value'));
    BX.Sale.OrderAjaxComponent.sendRequest();
});
jQuery(document).on('click',function () {
    jQuery('[data-toggle="tooltip"]').tooltip();
});
/* BX.ready(function(){
    BX.findChildren(BX(document), {'tag':'input','property' : {'data-toggle':'triggerBonus'}}, true).forEach(element => function(){
        element.value = 0
    });
    BX.bind(titleNode, 'click', BX.delegate(function(){
        this.animateScrollTo(this.authBlockNode);
        this.addAnimationEffect(this.authBlockNode, 'bx-step-good');
    }, this));
});
// BX.findChildren(BX(document), {"tag":"input","property" : {'name':"PAYED_FROM_ACCOUNT"}}, true);
 */
