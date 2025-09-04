/*----------------------------- Product Image Zoom --------------------------------*/

$('[data-fancybox="gallery"]').fancybox();
var swiper = new Swiper(".SwiperProductsImgthum", {
    loop: true,
    spaceBetween: 10,
    slidesPerView: 4,
    freeMode: true,
    watchSlidesProgress: true,
    autoHeight: true,
    navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
    },
    breakpoints: {
        768: {
            slidesPerView: 5,
            spaceBetween: 10,
        },
    }
});
var swiper2 = new Swiper(".SwiperProductsImg", {
    loop: true,
    spaceBetween: 10,
    autoHeight: true,
    navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
    },
    thumbs: {
        swiper: swiper,
    },
});
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
	var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
		return new bootstrap.Tooltip(tooltipTriggerEl)
	})

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

    // Itera sobre cada contenedor de variaciones de producto
    $('.product-variations-wrapper .product-variation').each(function () {
        // Verifica si ya existe un <li> con la clase 'active'
        if (!$(this).find('ul.range-variant li.active').length) {
            // Si no existe, agrega la clase 'active' al primer <li>
            $(this).find('ul.range-variant li').first().addClass('active');
        }
    });


    /*cookie*/
	// Seleccionar elementos del DOM una sola vez
const cookieBox = document.querySelector("#idxrcookies");
const cookieBoxmd = document.querySelector("#modal_cookies");
const acceptBtn = cookieBox.querySelector("#idxrcookiesOK");
const saveBtn = cookieBoxmd.querySelector("#save_manage_cookie");

// Función para establecer la cookie
function setCookie(name, value, days) {
    const domain = window.location.hostname;
    const cookieString = `${name}=${value}; max-age=${days * 24 * 60 * 60}; path=/; domain=${domain}`;
    document.cookie = cookieString;
}

// Función para verificar si existe la cookie
function checkCookieExists(name) {
    return document.cookie.split(';').some(cookie => cookie.trim().startsWith(name + '='));
}

// Manejador para el botón de aceptar
acceptBtn.addEventListener('click', () => {
    setCookie('CookieBy', 'Vkive', 30); // 30 días
    cookieBox.classList.add("hide");
});

// Manejador para el botón de guardar
saveBtn.addEventListener('click', () => {
    setCookie('CookieBy', 'Vkive', 30);
    cookieBox.classList.add("hide");
    $('#modal_cookies').modal('hide');
});

// Verificar el estado de la cookie al cargar
if (checkCookieExists('CookieBy')) {
    cookieBox.classList.add("hide");
} else {
    cookieBox.classList.remove("hide");
}