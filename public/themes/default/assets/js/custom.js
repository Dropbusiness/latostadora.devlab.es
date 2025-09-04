function messaggeadd(message, state) {/*danger / success */
	$.notify({
		title: '',
		message: message
	}, {
		type: state,
		/*danger,warning,success*/
		delay: 5000,
		placement: {
			from: "bottom",
			align: "right"
		},
		template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0} msgnotify" role="alert">' + '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' + '<span data-notify="icon"></span>' + '<span data-notify="title">{1}</span>' + '<span data-notify="message">{2}</span>' + '</div>'
	});
}
function formatCurrency(value, currency='EUR') {
	const floatValue = parseFloat(value);
	const options = {
	  style: 'currency',
	  currencyDisplay: 'symbol',
	  currency: currency
	};
	return floatValue.toLocaleString('es-ES', options);
  }
function mailalert(elem,id) {
	$.post(`/${LANG_CODE}/jsontools`, {act:'mailalert',id:id}, function(data){
		if(data.tipo=='modal'){
			waitingDialog.show(data.msg,'Iniciar sesión');
		}else{
			messaggeadd(data.msg,data.msgclass);
		}
	 });
}
function favoritos(elem,id) {
        $.post(`/${LANG_CODE}/jsontools`, {act:'favoritos',id:id}, function(data){
			if(data.tipo=='modal'){
				waitingDialog.show(data.msg,'Iniciar sesión');
			}else{
				messaggeadd(data.msg,data.msgclass);
				$(elem).children('span').addClass('active');
			}
         });
}

function addnotify(elem,id) {
	if(elem){
		$(elem).attr("disabled", true);
		$(elem).children('i').attr('class', 'fa fa-spinner fa-spin fa-1x fa-fw');
	}
	$.post(`/${LANG_CODE}/jsontools`, {act:'mailalert',id}, function(data){
		messaggeadd(data.msg,data.msgclass);
		if(elem){
			$(elem).attr("disabled", false);
			$(elem).children('i').attr('class', 'fa fa-check fa-1x fa-fw');
		}
	 });
}
function removeitem(elem,id,opt) {
	if(elem){
		$(elem).attr("disabled", true);
		$(elem).children('i').attr('class', 'fa fa-spinner fa-spin fa-1x fa-fw');
	}
	$.post(`/${LANG_CODE}/jsontools`, {act:'removeitem',id,opt}, function(data){
		messaggeadd(data.msg,data.msgclass);
		$(elem).parent().parent().remove();
		if(elem){
			$(elem).attr("disabled", false);
			$(elem).children('i').attr('class', 'fa fa-check fa-1x fa-fw');
		}
	 });
}

function addcart(elem,product_id,quantity,product_sku='',events_id='') {
	waitingDialog.show('<div class="progress progress-striped active" style="margin-bottom:0;"><div class="progress-bar" style="width: 100%"></div></div>','Cargando datos...');
	$.post(`/${LANG_CODE}/jsontools`, {act:'addcart',product_id,quantity,product_sku,events_id}, function(data){
		waitingDialog.hide();
		waitingDialog.show(data.msg,LANG_SHOP);
		getcart();
	 });
}
function quantityRanger() {
	$('.pro-qty').prepend('<span class="dec qtybtn">-</span>');
	$('.pro-qty').append('<span class="inc qtybtn">+</span>');
	$('.qtybtn').on('click', function() {
		var $button = $(this);
		var oldValue = $button.parent().find('input').val();
		if ($button.hasClass('inc')) {
			var newVal = parseFloat(oldValue) + 1;
		} else {
			if (oldValue > 0) {
				var newVal = parseFloat(oldValue) - 1;
			} else {
				newVal = 0;
			}
		}
		$button.parent().find('input').val(newVal);
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
function getcart() {
	let ajax   = $.ajax({
		url: `/${LANG_CODE}/jsontools`,
		method:'post',
		data: {act:'getcart'},
		dataType: 'json',
	});
	ajax.done(function(response){
		let html = '';
		var img = '';
		if(response.data){
			var products = response.data.cart_list;
			$('.cart-item-list').empty();
			if(products.length){
				$.each(products, function(i, product) {
					img='/uploads/products/small/'+(product.img!='' && product.img!=null?product.img:'default.jpg');
					let product_link=`/${LANG_URI}producto/${product.link_rewrite}?event=${product.events_id}`;
					html +=`
					<li class="cart-item boton-cart">
                        <div class="item-img">
                            <a href="${product_link}"><img src="${img}" alt=""></a>
                            <button class="close-btn" onclick="removecart(this,'${product.product_id}','${product.sku}','${product.combination_id}')"><i class="fas fa-times"></i></button>
                        </div>
                        <div class="item-content">
                            <h3 class="item-title"><a href="${product_link}">${product.name}</a></h3>
                            <div class="item-price">${product.price}</div>
                            <div class="cart-qty-plus-minus">
							<input class="input_qty" type="text" name="qty_${product.product_id}" value="${product.quantity}" step="${product.mqty}"   min="${product.mqty}" max="999" onchange="cartqtyupdate(this,${product.product_id},${product.combination_id})" />
							</div>
                        </div>
                    </li>
					`;
				});
			}else{
				html += '<li>No hay ningún producto en la cesta en este momento.</li>'
			}
			$('.cart-item-list').html(html);
			$('.cart-count').html(response.data.cart_items??0);
			quantityRanger();
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
		}
	});
}
// Función independiente para rastrear eventos en SealMetrics
function trackSealMetricsEvent(eventType, label, additionalData = {}) {
    // Configuración base para SealMetrics
    var options = {
        account: '6823a76ff0be2a6fcf15609b',
        event: eventType,
        label: label,
        use_session: 1,
        id: Math.floor((Math.random() * 999) + 1)
    };
    
    // Para eventos de microconversión como Add-To-Cart
    if (eventType === 'microconversion') {
        options.ignore_pageview = 1;
    }
    
    // Añadir datos adicionales al objeto options
    for (var key in additionalData) {
        if (additionalData.hasOwnProperty(key)) {
            options[key] = additionalData[key];
        }
    }
    
    // Verificar si el objeto sm está disponible
    if (window.sm) {
        var instance = new window.sm(options);
        instance.track(options.event);
        return true;
    } else {
        console.error("SealMetrics (sm2) plugin is not available");
        return false;
    }
}
function addcartdetails(elem,$this) {

    var variations = [];
    var allVariationsSelected = true;
    $('.product-variation').each(function() {
        var $variation = $(this);
        var $activeItem = $variation.find('li.active');
        if ($activeItem.length) {
            variations.push({
                attid: $activeItem.data('attid'),
                valid: $activeItem.data('valid')
            });
        } else {
            allVariationsSelected = false;
			messaggeadd('Por favor, seleccione una opción para: ' + $variation.find('.title').text().trim(), 'danger')
            return false;
        }
    });
    if (!allVariationsSelected)
        return;

	let product_id=$($this).data('id');
	let product_sku=$($this).data('sku');
	let events_id=$($this).data('event');
	let tour_id=$($this).data('tour');
	let quantity=$("#product_qty").val();

	if(elem){
		$(elem).attr("disabled", true);
		$(elem).children('i').attr('class', 'fa fa-spinner fa-spin fa-1x fa-fw');
	}

	waitingDialog.show('<div class="progress progress-striped active" style="margin-bottom:0;"><div class="progress-bar" style="width: 100%"></div></div>','Cargando datos...');
	$.post(`/${LANG_CODE}/jsontools`, {
		act:'addcart',product_id,product_sku,quantity,events_id,tour_id,variations}, function(response){
		waitingDialog.hide();
		if(response.error==false){
			// Rastrear evento de "Add to Cart" con la función independiente
            trackSealMetricsEvent('microconversion', 'Add-To-Cart', {
                sku: product_sku,
                product_id: product_id,
                quantity: quantity
            });
            
            // Pequeño delay para asegurar que el rastreo se complete antes de la redirección
            setTimeout(function() {
                window.location.href = `/${LANG_URI}checkout`;
            }, 300);
		}else{
			waitingDialog.show(response.msg,'Notificación');
		}
		if(elem){
			$(elem).attr("disabled", false);
			$(elem).children('i').attr('class', '');
		}
	 });
}
function removecart(elem,product_id='',product_sku='',combination_id='') {
    if( product_id!='' || product_sku!=''){
        $.post(`/${LANG_CODE}/jsontools`, {act:'removecart',product_id,product_sku,combination_id}, function(response){
			getcart();
         });
    }
}
function mostrartalla(elem, group_showsize) {
    // Si el modal no existe, lo crea dinámicamente
    if ($('#vertalla').length === 0) {
        $('body').append(`
            <div class="modal fade" id="vertalla" tabindex="-1" aria-labelledby="vertallaLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title" id="vertallaLabel">Tabla de Tallas</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body"></div>
                    </div>
                </div>
            </div>
        `);
    }

    let ajax = $.ajax({
        url: `/${LANG_CODE}/jsontools`,
        method: 'post',
        data: {
            act: 'get_guiatalla',
            page_id: group_showsize,
        },
        dataType: 'json'
    });
    ajax.done(function(response) {
        if (response.data) {
            var title = response.data.name || 'Tabla de Tallas';
            var description = response.data.description || '';
            $('#vertalla .modal-title').html(title);
            $('#vertalla .modal-body').html(description);
        } else {
            $('#vertalla .modal-title').html('Tabla de Tallas');
            $('#vertalla .modal-body').html('<div class="alert alert-warning">No hay información de tallas disponible.</div>');
        }
        var modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('vertalla'));
        modal.show();
    });
    ajax.fail(function() {
        $('#vertalla .modal-title').html('Tabla de Tallas');
        $('#vertalla .modal-body').html('<div class="alert alert-danger">Error al cargar la tabla de tallas.</div>');
        var modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('vertalla'));
        modal.show();
    });
}
$( document ).ready(function() {	
	getcart();

	$('.product-variation ul.range-variant li').on('click', function() {
        var $this = $(this);
        $this.siblings().removeClass('active');
        $this.addClass('active');
    });

	$('#events').on('change', function() {
        var url = $(this).find('option:selected').data('href');
        if (url) {
            window.location.href = url;
        } else {
            console.error('La URL no está definida para esta opción.');
        }
    });


	
});

