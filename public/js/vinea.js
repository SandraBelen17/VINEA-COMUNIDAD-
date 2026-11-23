/* Vinea Comunidad — comportamiento básico del sitio */
document.addEventListener('DOMContentLoaded', function () {

    // 1. Íconos Lucide: convierte <i data-lucide="nombre"></i> en SVG
    if (window.lucide) {
        window.lucide.createIcons();
    }

    // 2. Menú del celular (botón de tres rayas)
    var botonMenu = document.querySelector('.boton-menu');
    var menu = document.getElementById('menu-principal');
    if (botonMenu && menu) {
        botonMenu.addEventListener('click', function () {
            var abierto = menu.classList.toggle('abierto');
            botonMenu.setAttribute('aria-expanded', abierto ? 'true' : 'false');
        });
    }

    // 3. Slider de fotos de inicio
    var slider = document.querySelector('[data-slider]');
    if (slider) {
        var fotos = slider.querySelectorAll('.slider-foto');
        var puntos = slider.querySelectorAll('.slider-punto');
        var actual = 0;
        var temporizador;

        function mostrar(i) {
            actual = (i + fotos.length) % fotos.length;
            fotos.forEach(function (foto, k) {
                foto.classList.toggle('activa', k === actual);
                foto.setAttribute('aria-hidden', k === actual ? 'false' : 'true');
            });
            puntos.forEach(function (punto, k) {
                punto.classList.toggle('activo', k === actual);
                punto.setAttribute('aria-current', k === actual ? 'true' : 'false');
            });
        }

        function reiniciar() {
            clearInterval(temporizador);
            temporizador = setInterval(function () { mostrar(actual + 1); }, 6000);
        }

        slider.querySelector('[data-anterior]').addEventListener('click', function () { mostrar(actual - 1); reiniciar(); });
        slider.querySelector('[data-siguiente]').addEventListener('click', function () { mostrar(actual + 1); reiniciar(); });
        puntos.forEach(function (punto, k) {
            punto.addEventListener('click', function () { mostrar(k); reiniciar(); });
        });

        mostrar(0);
        if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            reiniciar();
        }
    }

    // 4. Pestañas de horarios en Inicio (Misas / Confesiones / Oficina).
    //    Sin JavaScript se ven las tres semanas; aquí se muestra solo la elegida.
    document.querySelectorAll('[data-pestanas]').forEach(function (bloque) {
        var botones = bloque.querySelectorAll('[data-pestanas-botones] button');
        var paneles = bloque.querySelectorAll('[data-pestana-panel]');

        function elegir(i) {
            botones.forEach(function (boton, k) {
                boton.setAttribute('aria-pressed', k === i ? 'true' : 'false');
            });
            paneles.forEach(function (panel, k) {
                panel.hidden = k !== i;
            });
        }

        bloque.querySelector('[data-pestanas-botones]').hidden = false;
        bloque.classList.add('con-pestanas'); // oculta los títulos de cada semana
        botones.forEach(function (boton, k) {
            boton.addEventListener('click', function () { elegir(k); });
        });
        elegir(0);
    });
});
