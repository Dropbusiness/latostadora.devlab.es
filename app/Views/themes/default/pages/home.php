<?= $this->extend('themes/'. $currentTheme .'/layout') ?>
<?= $this->section('content') ?>
<main class="main-wrapper">
     <header class="hero">
        <div class="hero__content">
            <img class="hero__logo" src="/home/logo-easymerx.png" alt="Easymerx logo">
            <h1 class="hero__title">
                Merchandising sin complicaciones para bandas y eventos
            </h1>
            <p class="hero__subtitle">
                Vende productos personalizados sin preocuparte del stock, los envíos ni la atención al cliente.
            </p>
            <a href="/contacto" class="hero__cta">
                Quiero empezar
            </a>
        </div>
        <div class="hero__mask"></div>
    </header>

   <section class="feature-section">
        <div class="feature-section__content">
            <h2 class="feature-section__title">
                ¿Qué es EasyMerx?
            </h2>
            <p class="feature-section__text">
                <strong>EasyMerx</strong> es una solución integral de merchandising bajo demanda para artistas, bandas y organizadores de eventos.
            </p>
            <p class="feature-section__text">
                Creamos tu tienda online, producimos tus productos personalizados y los enviamos directamente a tus fans.
            </p>
            <p class="feature-section__text">
                Sin inversión inicial, sin pedidos mínimos y sin dolores de cabeza.
            </p>
        </div>
        <div class="feature-section__image-container">
            <img class="feature-section__image fadeInBottom" loading="lazy" src="/home/merchan-easymerx.webp" />
        </div>
   </section>
   
   <section class="how-it-works">
    <h2 class="how-it-works__title">
        ¿Cómo funciona?
    </h2>
    <div class="how-it-works__steps-container">
        <div class="step">
            <img class="step__icon" src="/home/step-1.svg" alt="Icono crear tienda">
            <h3 class="step__title">
                1. Crea tu tienda
            </h3>
            <p class="step__description">
                Nos encargamos de diseñar y lanzar tu tienda online personalizada, sin coste para ti.
            </p>
        </div>
        <div class="step">
            <img class="step__icon" src="/home/step-2.svg" alt="Icono enviar merchan">
            <h3 class="step__title">
                2. Tus fans compran
            </h3>
            <p class="step__description">
                Tus fans eligen tener un recuerdo del evento, nosotros lo estampamos, enviamos y gestionamos cualquier incidencia.
            </p>
        </div>
        <div class="step">
            <img class="step__icon" src="/home/step-3.svg" alt="Icono cobrar">
            <h3 class="step__title">
                3. Tú cobras
            </h3>
            <p class="step__description">
                Por unidad vendida, sin tener que hacer ninguna inversión ni pagar ninguna cuota. Fácil, transparente y sin complicaciones.
            </p>
        </div>
    </div>
   </section>

   <section class="benefits">
    <h2 class="benefits__title">
        Beneficios principales
    </h2>
    <div class="benefits__list-container">
        <ul class="benefits__list">
            <li class="benefits__item">Impresión bajo demanda</li>
            <li class="benefits__item">Tienda online incluida</li>
            <li class="benefits__item">Envíos a todo el mundo</li>
            <li class="benefits__item">Atención al cliente incluida</li>
            <li class="benefits__item">Beneficios desde la primera venta</li>
            <li class="benefits__item">Sin cuotas ni compromisos</li>
        </ul>
    </div>
   </section>

    <footer class="footer">
        <h2 class="footer__title">
            ¿Tienes una banda o gestionas un evento?
        </h2>
        <p class="footer__text">
            Solo necesitas tu logo o diseño. Nosotros nos ocupamos del resto.
        </p>
        <p class="footer__text">
            Escríbenos a 
            <a class="footer__link" href="mailto:ayuda@easymerx.com">ayuda@easymerx.com</a>
        </p>
        <a class="footer__cta" href="/contacto">
            Empieza ahora sin compromiso
        </a>
    </footer>
</main>
<script>
document.addEventListener("scroll", function () {
    const hero = document.querySelector(".hero__mask");
    const scrollY = window.scrollY; 
    const offset = scrollY * 0.3; // Cambia el 0.5 para ajustar la intensidad del parallax
    hero.style.backgroundPosition = `center calc(50% + ${offset}px)`;
});
</script>
<?= $this->endSection() ?>
<?php $this->section('style'); ?>
<?= link_tag('home/style.css?v='.time()) ?>
<?= link_tag('home/normalize.css?v='.time()) ?>
<?php $this->endSection(); ?>