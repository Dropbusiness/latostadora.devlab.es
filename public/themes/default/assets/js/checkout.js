function removecartcheckout(elem,product_id='',product_sku='',combination_id='') {
    if( product_id!='' || product_sku!=''){
        $.post(`/${LANG_CODE}/jsontools`, {act:'removecart',product_id,product_sku,combination_id}, function(response){
			loadcheckout();
         });
    }
}
function cartaddressupdate(elem,address_id='',order_id='') {
    if( address_id>0 && order_id>0){
        $.post(`/${LANG_CODE}/jsontools`, {act:'cartaddressupdate',address_id,order_id}, function(response){
			//loadcheckout();
         });
    }
}
function cartshippingupdate(elem,shipping_id='',order_id='') {
    if( shipping_id>0 && order_id>0){
		$('#shipping'+shipping_id).prop('checked', true);
        $.post(`/${LANG_CODE}/jsontools`, {act:'cartshippingupdate',shipping_id,order_id}, function(response){
			loadcheckout();
         });
    }
}
function cartcountryupdate(elem,event) {
    event.preventDefault();
    event.stopPropagation();
    let order_id=$(elem).data('orderid');
	let country=$(elem).val();
    if( order_id>0){
        $.post(`/${LANG_CODE}/jsontools`, {act:'cartcountryupdate',order_id,country}, function(response){
			loadcheckout();
         });
    }
}
function cartpaymentupdate(elem,payment_id='',order_id='') {
    if( payment_id>0 && order_id>0){
        $.post(`/${LANG_CODE}/jsontools`, {act:'cartpaymentupdate',payment_id,order_id}, function(response){
			//loadcheckout();
         });
    }
}
function savecart(elem,order_id=''){
	if(elem){
		$(elem).attr("disabled", true);
		$(elem).children('i').attr('class', 'fa fa-spinner fa-spin fa-1x fa-fw');
	}
	waitingDialog.show('<div class="progress progress-striped active" style="margin-bottom:0;"><div class="progress-bar" style="width: 100%"></div></div>','Cargando datos...');
	let order_reference=$("#order_reference").val();
	let order_obs=$("#order_obs").val();
	$.post(`/${LANG_CODE}/jsontools`, {act:'savecart',order_id,order_reference,order_obs}, function(response){
		waitingDialog.hide();
		if(response.error==false){
			window.location.href=`/${LANG_URI}miscarritos`;
		}else{
			waitingDialog.show(response.msg,'Notificación');
		}
		if(elem){
			$(elem).attr("disabled", false);
			$(elem).children('i').attr('class', '');
		}
	 });
}
function confirmorder(elem,event,order_id=''){
	event.preventDefault()
	event.stopPropagation()
	const form = document.getElementById('frmcheckout');
	if (!form.checkValidity()) {
		form.classList.add('was-validated');
		return; 
	}

	if(elem){
		$(elem).attr("disabled", true);
		$(elem).children('i').attr('class', 'fa fa-spinner fa-spin fa-1x fa-fw');
	}
	waitingDialog.show('<div class="progress progress-striped active" style="margin-bottom:0;"><div class="progress-bar" style="width: 100%"></div></div>','Cargando datos...');
	let customer_id=$("#customer_id").val();
	let order_obs=$("#order_obs").val();
	let firstname=$("#firstname").val();
	let lastname=$("#lastname").val();
	let email=$("#email").val();
	let passwd=$("#passwd").val();
	let country=$("#country").val();
	let city=$("#city").val();
	let cp=$("#cp").val();
	let phone=$("#phone").val();
	let address=$("#address").val();
	let condiciones= $('#condiciones').is(':checked')?1:0;		
	$.post(`/${LANG_CODE}/jsontools`, {act:'confirmorder',
	customer_id,
	firstname,
	lastname,
	email,
	passwd,
	country,
	city,
	cp,
	phone,
	address,
	condiciones,
	order_id,
	order_obs
}, function(response){
		waitingDialog.hide();
		if(response.error==false && response.tipo=='confirmarpedido'){
			window.location.href=`/${LANG_URI}orderconfirmation/${response.data}`;//redsyspayment->orderconfirmation
		}else{
			waitingDialog.show(response.msg,'Notificación');
		}
		if(elem){
			$(elem).attr("disabled", false);
			$(elem).children('i').attr('class', '');
		}
	 });
}
function cartqtyupdate($this,product_id='',combination_id='') {
		let product_quantity = parseFloat($($this).val());
		$("#btnconfirmarpedido").prop("disabled", true);
		if( product_id>0 || newqty>0){
			$.post(`/${LANG_CODE}/jsontools`, {act:'updatecart',product_id,product_quantity,combination_id}, function(response){
				if(response.error==true){
					loadcheckout();
					messaggeadd(response.msg,'danger');
					//waitingDialog.show(response.msg,'Carrito de Compras');
				}else{
					loadcheckout();
				}
			});
		}
}
function loadcheckout(){
	waitingDialog.show('<div class="progress progress-striped active" style="margin-bottom:0;"><div class="progress-bar" style="width: 100%"></div></div>','Cargando datos...');
	$.post(`/${LANG_CODE}/jsontools`, {act:'loadcheckout'}, function(response){
		waitingDialog.hide();
		$("#btnconfirmarpedido").removeAttr('disabled');
		let html = '',img = '',html_mqty = '',html_disc = '';
		let lastProductLink = ''; 
		if(response.data){
			var products = response.data.cart_list;
			var methodshippings = response.data.methodshippings;
			var cart = response.data.cart;
			$('#checkout_cart_list').empty();
			html+='<table>'+
			'<thead>'+
			'<tr>'+
			'<th>'+LANG_PRODUCT+'</th>'+
			'<th>'+LANG_QUANTITY+'</th>'+
			'<th>Total</th>'+
			'<th></th>'+
			'</tr>'+
			'</thead>'+
			'<tbody>';
			$.each(products, function(i, product) {
				html_disc='';
				html_mqty=(product.mqty>1?'data-bs-toggle="tooltip" data-bs-html="true" title="Cantidad permitido en <b>multiplo</b> de <b>'+product.mqty+'</b>"':'');
				img='/uploads/products/small/'+(product.img!='' && product.img!=null?product.img:'default.jpg');
				let bgstock='';
				let msgstock='';
				if(parseFloat(product.quantity)>parseFloat(product.stock)){
					bgstock='alert-danger';
					if(parseFloat(product.stock)>0)
						msgstock='<div class="text-danger">Cantidad no disponible</div>';
				}
				let product_link=`/${LANG_URI}producto/${product.link_rewrite}?event=${product.events_id}`;
				lastProductLink = product_link; // Guardar el último enlace generado
				html += '<tr class="'+bgstock+'">'+
				'<td data-label="'+LANG_PRODUCT+'" class="ec-cart-pro-name"><a href="' + product_link + '">' + product.name + '</a>'+
				'<div class="optcart"><b>SKU:</b> ' + product.sku + ' <br/> <b>Precio unit.:</b> ' + formatCurrency(product.price,'EUR') + '</div>'+
				html_disc+
				'</td>'+
				'<td data-label="'+LANG_QUANTITY+'" class="ec-cart-pro-qty" style="text-align: center;">'+
				'<div class="cart-qty-plus-minus">'+
				'<input class="input_qty" type="text" name="qty_' + product.product_id + '" value="' + product.quantity + '" step="' + product.mqty + '"   min="' + product.mqty + '" '+html_mqty+' max="999" onchange="cartqtyupdate(this,\'' + product.product_id + '\',\'' + product.combination_id + '\')" />'+
				'</div>'+
				'<div class="optcart" data-stock="'+product.stock+'"><b></b> '  + msgstock + '</div>'+
				'</td>'+
				'<td data-label="'+LANG_TOTAL+'" class="ec-cart-pro-subtotal">' + formatCurrency(product.subtotal,'EUR') + '</td>'+
				'<td data-label="'+LANG_DELETE+'" class="ec-cart-pro-remove">'+
				'<a href="javascript:void(0)" onclick="removecartcheckout(this,\'' + product.product_id + '\',\'' + product.sku + '\',\'' + product.combination_id + '\')"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16"><path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5Zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5Zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6Z"/><path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1ZM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118ZM2.5 3h11V2h-11v1Z"/></svg></a>'+
				'</td>'+
				'</tr>';
			});
			html+='</tbody>'+
			'</table>';
			$('#checkout_cart_list').append(html);

			var html_shipping='';
			$.each(methodshippings, function(i, shipping) {
				html_shipping+=`<div class="input-group d-flex justify-content-between align-items-center"   onclick="cartshippingupdate(this,${shipping.id},${cart.id})">
									<input type="radio" id="shipping${shipping.id}" name="shipping_id" ${(cart.shipping_id==shipping.id?'checked':'')} value="${shipping.id}" required>
									<label for="radio1">${shipping.name} </label>
									<span class="amount" id="webEnvioStandard">${shipping.price.toFixed(2).replace('.', ',')} €</span>
									<div class="invalid-feedback ps-5 m-0">Seleccionar un método de envío</div>
								</div>`;
			});
			if(html_shipping=='')
				html_shipping='<div class="alert alert-warning">Selecciona un país para ver los métodos de envío</div>';
			
			$('#shipping-items').html(html_shipping);
			// Asignar evento click al id="volverEvento" con el enlace del último producto
			$('#volverEvento').off('click').on('click', function () {
				window.location.href = lastProductLink;
			});
			
			$('.cartSummary_qty').text( response.data.cart_nprod??0 );
			$('#cartSummary_subamount').text( formatCurrency(response.data.cart_subtotal??0,'EUR')  );
			$('#cartSummary_delivery').text( formatCurrency(response.data.cart_shipping_price,'EUR'));
			$('#cartSummary_amountTotal').text( formatCurrency(response.data.cart_subtotal,'EUR'));
			$('#cartSummary_tax').text( formatCurrency(response.data.cart_totaliva,'EUR')); 
			$('#cartSummary_payment').text( formatCurrency(response.data.cart_total,'EUR')); 
			$('#freeshippingmessage').html("");
			// Agrega las siguientes líneas para incluir las nuevas variables
			/*var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
			var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
				return new bootstrap.Tooltip(tooltipTriggerEl)
			})*/

			var qtyTriggerList = [].slice.call(document.querySelectorAll('input.input_qty'))
			var qtyList = qtyTriggerList.map(function (element) {
				let min=element.min;
				let step=element.step;
				var itemSpin={
					min,
					step,
					max:99999
				};
				$(element).TouchSpin(itemSpin);
			})
			//verifica si la funcion existe restartadyen()  y la ejecuta
			if (typeof restartadyen === "function") {
				restartadyen();
			}
		}
	 });
}

$(document).ready(function() {
	loadcheckout();
});
