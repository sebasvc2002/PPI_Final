<?php
$title="Acerca de nosotros - Las delicias Horneadas";
require 'layout/header.php';
 ?>
    <style>
        .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
      }
      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
    </style>
    <section class='hero-about'>
        <div class="container mb-5">
            <h1 class="hero-title font-playfair mb-4">Queremos ser su pastelería favorita</h1>
        </div>
    </section>
    <div class="container px-4 py-5 text-center">
        <h1 class="font-playfair">CREANDO DULZURA DESDE 1985</h1>
        <div class="mx-auto mt-2" style="width: 50px; height: 3px; background-color: var(--accent-color);"></div>
        <p class="p-5" >Desde hace 40 años hemos sido parte de los momentos más especiales para nuestros clientes: reuniones familiares, almuerzos con amigos, encuentros de trabajo y hasta en esos instantes acogedores durante la hora del cafecito. En la actualidad, estamos presentes con sucursales en Yucatán, Campeche, Quintana Roo, Tabasco y la Ciudad de México, extendiendo nuestra dulzura a cada rincón. Te invito a conocer un poco de nuestra historia y la evolución de lo que comenzó como una necesidad inminente, con el anhelo de proporcionar a mi familia mejores condiciones de las que yo tuve, lo cual me brindó la oportunidad de descubrir mi gran pasión: la repostería fina.</p>
    </div>

    <div class="container px-4 py-5" id="custom-cards">
        <div class="row row-cols-1 row-cols-lg-3 align-items-stretch g-4 py-5">
          <div class="col">
            <div class="card card-cover h-100 overflow-hidden text-bg-dark rounded-4 shadow-lg" style="background-image: url(&quot;img/about-1.jpg&quot;)">
              <div class="d-flex flex-column h-100 p-5 pb-3 text-white text-shadow-1">
                <h3 class="pt-5 mt-5 mb-4 display-6 lh-1 fw-bold">
                    Para disfrutar en familia
                </h3>
              </div>
            </div>
          </div>
          <div class="col">
            <div class="card card-cover h-100 overflow-hidden text-bg-dark rounded-4 shadow-lg" style="background-image: url(&quot;img/about-2.jpg&quot;)">
              <div class="d-flex flex-column h-100 p-5 pb-3 text-white text-shadow-1">
                <h3 class="pt-5 mt-5 mb-4 display-6 lh-1 fw-bold">
                  Horneando con amor
                </h3>
              </div>
            </div>
          </div>
          <div class="col">
            <div class="card card-cover h-100 overflow-hidden text-bg-dark rounded-4 shadow-lg" style="background-image: url(&quot;img/about-3.jpg&quot;)">
              <div class="d-flex flex-column h-100 p-5 pb-3 text-shadow-1">
                <h3 class="pt-5 mt-5 mb-4 display-6 lh-1 fw-bold">
                  Apoyando negocios pequeños
                </h3>
              </div>
            </div>
          </div>
        </div>
      </div>
    <?php require_once 'layout/footer.php'; ?>
</body>
</html>