(function () {
  'use strict';

  var menuButton = document.querySelector('[data-menu-button]');
  var nav = document.querySelector('[data-nav]');

  if (!menuButton || !nav) {
    return;
  }

  menuButton.addEventListener('click', function () {
    var isOpen = nav.classList.toggle('is-open');

    menuButton.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
  });

  nav.querySelectorAll('a').forEach(function (link) {
    link.addEventListener('click', function () {
      nav.classList.remove('is-open');
      menuButton.setAttribute('aria-expanded', 'false');
    });
  });

  document.addEventListener('keydown', function (event) {
    if (event.key !== 'Escape') {
      return;
    }

    nav.classList.remove('is-open');
    menuButton.setAttribute('aria-expanded', 'false');
  });
})();
