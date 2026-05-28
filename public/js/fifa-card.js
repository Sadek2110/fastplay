/**
 * FastPlay · Efecto tilt para la carta FIFA del jugador.
 * Sigue el cursor en escritorio y se desactiva en movil para evitar
 * gestos accidentales.
 */
(function () {
  'use strict';
  document.addEventListener('pointermove', function (event) {
    var card = event.target.closest('.fp-card-fifa');
    if (!card || window.matchMedia('(max-width: 768px)').matches) return;
    var rect = card.getBoundingClientRect();
    var x = ((event.clientX - rect.left) / rect.width - 0.5) * 10;
    var y = ((event.clientY - rect.top) / rect.height - 0.5) * -10;
    card.style.transform = 'perspective(900px) rotateX(' + y.toFixed(2) + 'deg) rotateY(' + x.toFixed(2) + 'deg) translateY(-4px)';
  });
  document.addEventListener('pointerleave', function (event) {
    var card = event.target.closest && event.target.closest('.fp-card-fifa');
    if (card) card.style.transform = '';
  }, true);
})();
