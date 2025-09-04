<?= $this->extend('themes/'. $currentTheme .'/layout') ?>
<?= $this->section('content') ?>

<main class="main-wrapper">
    <!-- Ec checkout page -->
    <section class="axil-checkout-area axil-section-gap">
        <div class="container">
            <div class="row mb-3">
                <div class="d-flex" >
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-arrow-left" viewBox="0 0 16 16">
                            <path fill-rule="evenodd"
                                d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z" />
                        </svg>
                    </div>
                    <?php 
                        // Verificar si existe 'events_id' en $order_details[0]
                        if (isset($order_details[0]['events_id']) && !empty($order_details[0]['events_id'])) {
                            $event_id = $order_details[0]['events_id'];
                            $eventDetails = get_event_details($event_id);
                            ?>
                            <a href="<?= site_url().'event/'.$eventDetails['artist_slug']."/".$eventDetails['tour_slug']."/".$eventDetails['slug']."/".$eventDetails['date'];?>" class="ps-3 btn btn-volver"> 
                                <?= front_translate('General', 'back-event') ?>
                            </a>
                            <?php
                        }
                    ?>

                </div>
            </div>
            <div class="row">
                <div class="col">
                    <!-- Start Checkout Area  -->
                    <form class="needs-validation" id="frmcheckout" novalidate>
                        <input type="hidden" id="customer_id" value="<?=$customer['id'] ?>">
                        <div class="row">
                            <?= view('themes/'. $currentTheme .'/shared/flash_message') ?>
                            <div class="col-lg-6 mb-3">
                                <!-- cart content Start -->
                                <div class="ec-cart-content  margin-bottom-30">
                                    <div class="ec-cart-inner">
                                        <div class="row">
                                            <div class="table-content cart-table-content" id="checkout_cart_list"></div>
                                        </div>
                                    </div>
                                </div>
                                <!--cart content End -->
                                <div class="axil-checkout-billing frmcheckout">
                                    <h4 class="title mb--40"><?=front_translate('General','my-data')?></h4>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label><?=front_translate('General','first-name')?><span>*</span></label>
                                                <input class="form-control mb-0" type="text" id="firstname"
                                                    value="" pattern="^.+$" required />
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label><?=front_translate('General','last-name')?>
                                                    <span>*</span></label>
                                                <input class="form-control mb-0" type="text" id="lastname"
                                                    value="" required />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label><?=front_translate('General','address')?> <span>*</span></label>
                                        <input class="form-control mb-0" type="text" id="address"
                                            value="" required />
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label><?=front_translate('General','city')?><span>*</span></label>
                                                <input class="form-control mb-0" type="text" id="city"
                                                    value="" required />
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label><?= front_translate('General', 'country') ?><span>*</span></label>
                                                <select class="form-control mb-0" id="country" name="country" data-orderid="<?= $order['id'] ?>"  onchange="cartcountryupdate(this,event)" required>
                                                    <option value="">Selecciona un país</option>
                                                    <?php foreach ($selectcountry as $country): ?>
                                                        <option class="bacgr_tran" value="<?= $country['iso'] ?>" <?= $country['iso'] == $order['order_country']? 'selected' : '' ?>><?= $country['name'] ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label><?=front_translate('General','post-code')?>*</label>
                                                <input class="form-control mb-0" type="text" id="cp"
                                                    value="" required />
                                                <span id="error-postcode" style="display: none;">Lo sentimos, no realizamos envíos al destino seleccionado</span>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label><?=front_translate('General','phone')?><span>*</span></label>
                                                <input class="form-control mb-0" type="text" inputmode="numeric" id="phone"
                                                    value="" required />
                                                <span id="phone-error" style="display: none;">Por favor, ingrese un número de teléfono válido.</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label><?=front_translate('General','email')?><span>*</span></label>
                                        <input class="form-control mb-0" type="email" id="email"
                                            value="" required />
                                        <span id="email-error" style="display: none;">Por favor, ingrese un correo electrónico válido.</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <div class="axil-order-summery order-checkout-summery">
                                    <h5 class="title res mb--20"><?=front_translate('General','summary')?></h5>
                                    <div class="summery-table-wrap">
                                        <div class="totalproducts d-flex justify-content-between">
                                            <?=front_translate('General','articles')?> <span
                                            class="cartSummary_qty">0</span></div>
                                        <table class="table summery-table">
                                            <tbody>
                                                <tr class="order-subtotal cartAmount">
                                                    <td>Subtotal</td>
                                                    <td id="cartSummary_subamount">-</td>
                                                </tr>
                                                <tr class="order-shipping">
                                                    <td colspan="2">
                                                        <div class="shipping-amount">
                                                            <span class="title">Métodos de envío*</span>
                                                        </div>
                                                        <div id="shipping-items"></div>
                                                    </td>
                                                </tr>
                                                <tr class="order-total">
                                                    <td>Total</td>
                                                    <td class="order-total-amount" id="cartSummary_payment">-</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="order-payment-method">
                                        <div class="ec-register-wrap2 txt12 mb-2">
                                        </div>
                                    </div>
                                    <button class="custom-button d-none" id="btnconfirmarpedido"
                                        onclick="confirmorder(this,event,<?=$order['id']?>)"><i></i><?=front_translate('General','process-order')?></button>

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-12">
                            <div class="axil-checkout-methodpayment">
                                <button  class="custom-button btn_color_pay" id="payments"  name="payments" type="button" onclick="showAdyenPayment(event)"><?=front_translate('General','method-of-payment')?><i class="icon_flecha">
                                <svg viewBox="0 0 1024 1024"><path fill="#636363" d="M170.667 469.333v85.333h512L448 789.333l60.587 60.587L846.507 512l-337.92-337.92L448 234.667l234.667 234.667h-512z"></path></svg></i></button>
                                <h4 class="title mb-6" id="title_paymethod" style="display:none"><?=front_translate('General','method-pay')?></h4>
                                <div id="dropin-container"></div>
                                <small class="form-label m-0 mt-2 term_white" for="condiciones">
                                            <?=front_translate('General','info-term')?> <a class="fw-bold res" href="<?=base_url_locale('contenido/'.$allpage[1]['link_rewrite']) ?>" target="_blank"><?=$allpage[1]['name']?></a>
                                                        </small>
                            </div>

                            </div>
                        </div>
                    </form>
                    <!-- End Checkout Area  -->
                </div>
            </div>
    </section>
</main>

<!-- En el head o antes de cerrar el body -->
<link rel="stylesheet" href="https://checkoutshopper-live.adyen.com/checkoutshopper/sdk/5.39.1/adyen.css" />
<script src="https://checkoutshopper-live.adyen.com/checkoutshopper/sdk/5.39.1/adyen.js"></script>

<script>
let checkout;
let dropinComponent;
let isInitialized = false;
let datasession = null;
// Función principal para mostrar/inicializar Adyen
async function showAdyenPayment(e) {
    e.preventDefault();
    e.stopPropagation();

    // Verificar validación del formulario
    const form = document.getElementById('frmcheckout');
    if (form.checkValidity() === false) {
        form.classList.add('was-validated');
        messaggeadd('Por favor, complete todos los campos obligatorios.','danger');
        return;
    }
    // comprobar si existe productos en el carrito .cartSummary_qty verificar NaN
    if (parseInt(document.querySelector('.cartSummary_qty').textContent) === 0) {
        messaggeadd('No hay productos en el carrito.','danger');
        return;
    }
   

    const response = await fetch('<?= base_url_locale("jsontools") ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            act: 'updateordercustomer',
            order_id: <?= $order['id'] ?>,
            customer_id: document.getElementById('customer_id').value,
            firstname: document.getElementById('firstname').value,
            lastname: document.getElementById('lastname').value,
            address: document.getElementById('address').value,
            city: document.getElementById('city').value,
            country: document.getElementById('country').value,
            cp: document.getElementById('cp').value,
            phone: document.getElementById('phone').value,
            email: document.getElementById('email').value,
            shipping_id: document.querySelector('input[name="shipping_id"]:checked').value
        })
    });
    const data = await response.json();
    if (data.error) {
        messaggeadd('Error al registrar cliente: ' + data.error,'danger');
        return;
    }


    const title_paymethod = document.getElementById('title_paymethod');
    const paymentButton = document.getElementById('payments');

    try {
        await initAdyenPayment();
        checkorderMonitoring(<?= $order['id'] ?>);
        if (paymentButton) paymentButton.style.display = 'none';
        if (title_paymethod) title_paymethod.style.display = 'block';
    } catch (error) {
        messaggeadd(`Error al mostrar el pago: ${error}`,'danger');
        console.error('Error al mostrar el pago:', error);
    }
}

// Función para inicializar el pago
async function initAdyenPayment() {
    try {
        const response = await fetch('<?= base_url_locale("adyen") ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            }
        });
        
        const data = await response.json();
        if (data.error) {
            throw new Error(data.error);
        }
        // Guardar los datos de la sesión
        datasession = {
                    sessionId: data.session.id,
                    sessionData: data.session.sessionData,
                    session_code: data.session_code,
                    amount: data.amount,
                    returnUrl: data.returnUrl
                };
        const configuration = {
            environment: '<?= env('ADYEN_ENVIRONMENT') ?>',
            clientKey: '<?= env('ADYEN_CLIENT_KEY') ?>',
            analytics: { enabled: false },
            locale: "es-ES",
            showPayButton: true,
            session: {
                id: data.session.id,
                sessionData: data.session.sessionData
            },
            paymentMethodsConfiguration: {
                paypal: {
                    style: {
                        layout: 'vertical',
                        color: 'blue'
                    },
                    blockPayPalCreditButton: true, // Opcional: bloquear PayPal Credit
                    blockPayPalPayLaterButton: false, // Opcional: permitir PayPal Pay Later
                    onShippingChange: function(data, actions) {
                        // Manejar cambios en el envío si es necesario
                        return actions.resolve();
                    }
                }
            },
            onPaymentCompleted: async (result, component) => {
                console.log('Pago completado:', result);
                if (result.resultCode === "Authorised") {
                    try {
                        const verifyResponse = await fetch('<?= base_url_locale("adyenpaymentverify") ?>', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                order_id: <?= $order['id'] ?>,
                                datasession: datasession,
                                sessionData:result.sessionData,
                                sessionResult:result.sessionResult,
                                resultCode: result.resultCode,
                            })
                        });

                        const verifyData = await verifyResponse.json();
                        
                        if (verifyData.success) {
                            window.location.href = '<?= base_url_locale("orderconfirmation/" . encrypt_text($order['id'])) ?>';
                        } else {
                            messaggeadd(`Error en la verificación del pago: ${verifyData.message}`,'danger');
                        }
                    } catch (error) {
                        console.error('Error en verificación:', error);
                        messaggeadd(`Error al verificar el pago. Por favor, contacte con soporte.: ${error}`,'danger');
                    }
                } else {
                    messaggeadd(`El pago no pudo ser procesado: ${result.resultCode}`,'danger');
                    //pasado un 3 segundos debe reiniciar la forma de pago
                    setTimeout(() => {
                        //restartadyen();
                        //showAdyenPayment(event);
                        document.getElementById('payments').click();
                    },4000);
                }
            },
            onPaymentFailed: (result, component) => {
                console.log('Pago fallido:', result);
                messaggeadd(`El pago no pudo ser procesado: ${result.resultCode}`,'danger');
            },
            onError: (error, component) => {
                console.error('Error:', error);
                switch (error.name) {
                    case 'NETWORK_ERROR':
                        messaggeadd(`Error de conexión. Por favor, inténtelo de nuevo.`,'danger');
                        break;
                    case 'CANCEL':
                        messaggeadd(`Pago cancelado. Puede intentarlo de nuevo cuando lo desee.`,'danger');
                        break;
                    default:
                        messaggeadd(`Error en el proceso de pago: ${error.message}`,'danger');
                }
            }
        };

        // Crear instancia de AdyenCheckout
        checkout = await AdyenCheckout(configuration);
        
        // Configuración del dropin
        const dropinConfig = {
            openFirstPaymentMethod: true,
            openFirstStoredPaymentMethod: false,
            showStoredPaymentMethods: false,
            showRemovePaymentMethodButton: false,
            paymentMethodsConfiguration: {
                card: {
                    hasHolderName: true,
                    holderNameRequired: true,
                    enableStoreDetails: false,
                    hideCVC: false,
                    name: 'Tarjeta de crédito',
                }
            }
        };

        // Montar el componente
        const container = document.getElementById('dropin-container');
        if (container) {
            if (dropinComponent) {
                dropinComponent.unmount();
            }
            dropinComponent = checkout.create('dropin', dropinConfig).mount(container);
            container.style.display = 'block';
        }

    } catch (error) {
        console.error('Error de inicialización:', error);
        messaggeadd(`Error al inicializar el pago: ${error.message}`,'danger');
        const container = document.getElementById('dropin-container');
        if (container) container.style.display = 'none';
        throw error;
    }
}

// Función para actualizar el carrito
function restartadyen() {
    // Tu código actual de actualización del carrito
    // Después de actualizar, resetear el formulario de pago
    const container = document.getElementById('dropin-container');
    const title_paymethod = document.getElementById('title_paymethod');
    const paymentButton = document.getElementById('payments');
    
    if (container) container.style.display = 'none';
    if (title_paymethod) title_paymethod.style.display = 'none';
    if (paymentButton) paymentButton.style.display = 'block';
    
    if (dropinComponent) {
        dropinComponent.unmount();
        dropinComponent = null;
    }
}
//revisar periodicamente si el pedido esta pagado
// Variables globales
let checkordervalidMonitorId = null;
// Función optimizada para verificar pedido válido
async function checkordervalid(order_id) {
    try {
        const response = await fetch(`/${LANG_CODE}/jsontools`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                act: 'checkordervalid',
                order_id: order_id
            })
        });
        
        const data = await response.json();
        
        if (!data.error && data.data?.id) {
            checkorderTimeouts();
            window.location.href = `/${LANG_CODE}/orderconfirmation/${data.data.encryptid}`;
            return true;
        }
        return false;
    } catch (error) {
        console.error('Error verificando pedido:', error);
        return false;
    }
}

// Función para limpiar timeouts
function checkorderTimeouts() {
    if (checkordervalidMonitorId) {
        clearInterval(checkordervalidMonitorId);
        checkordervalidMonitorId = null;
    }
}

// Función para iniciar monitoreo cada 3 segundos
function checkorderMonitoring(order_id) {
    checkorderTimeouts(); // Limpiar cualquier monitoreo previo
    
    checkordervalidMonitorId = setInterval(() => {
        checkordervalid(order_id);
    }, 3000);
}
// Función para escuchar cambios en el formulario
function listenFormChanges() {
    const formCheckout = document.querySelector('.frmcheckout');
    if (formCheckout) {
        const inputs = formCheckout.querySelectorAll('input, textarea, select');
        inputs.forEach(input => {
            input.addEventListener('change', function() {
                restartadyen();
            });

            // Para campos de texto, también escuchar mientras se escribe
            if (input.type === 'text' || input.type === 'textarea' || input.type === 'email') {
                input.addEventListener('input', function() {
                    restartadyen();
                });
            }
        });
    }
}
// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    listenFormChanges();

    const countrySelect = document.getElementById("country");
    const postalCodeInput = document.getElementById("cp");
    const errorMessage = document.getElementById("error-postcode");
    // Validar país seleccionado al cargar la página
    if (countrySelect.value === "ES") {
            postalCodeInput.addEventListener("input", validatePostalCode);
    }
    // Escuchar cambios en el campo país
    countrySelect.addEventListener("change", function () {
        const selectedCountry = countrySelect.value;
        if (selectedCountry === "ES") {
                postalCodeInput.addEventListener("input", validatePostalCode);
                postalCodeInput.value = ""; // Limpiar el campo postal
        } else {
                postalCodeInput.removeEventListener("input", validatePostalCode);
                errorMessage.style.display = "none"; // Ocultar el mensaje de error si cambia el país
                postalCodeInput.value = ""; // Limpiar el campo postal
        }
    });

    function validatePostalCode() {
        const postalCode = postalCodeInput.value;
        // Bloquear si comienza con 35 o 38 y eliminar caracteres adicionales
        if (/^(35|38|51|52)/.test(postalCode)) {
                postalCodeInput.value = postalCode.slice(0, 2); // Mantener solo los dos primeros caracteres
                errorMessage.style.display = "block";
            } else {
            errorMessage.style.display = "none";
        }
    }
    const emailInput = document.getElementById("email");
    const phoneInput = document.getElementById("phone");
    const emailError = document.getElementById("email-error");
    const phoneError = document.getElementById("phone-error");

    // Expresiones regulares para validación
    const emailRegex = /^[\w.-]+@([\w-]+\.)+[\w-]{2,8}$/;
    const phoneRegex = /^[+]*[\(]{0,1}[0-9]{1,4}[\)]{0,1}[\-\s\.\/0-9]*$/;

    // Validar email al salir del campo
    emailInput.addEventListener("blur", function () {
            if (emailInput.value === "") {
                emailError.style.display = "none"; // No mostrar error si está vacío
            } else if (!emailRegex.test(emailInput.value)) {
                emailError.style.display = "block";
            } else {
                emailError.style.display = "none";
            }
    });

    // Validar teléfono al salir del campo
    phoneInput.addEventListener("blur", function () {
            if (phoneInput.value === "") {
                phoneError.style.display = "none"; // No mostrar error si está vacío
            } else if (!phoneRegex.test(phoneInput.value)) {
                phoneError.style.display = "block";
            } else {
                phoneError.style.display = "none";
            }
    });    
});
</script>

<?= $this->endSection() ?>