(function () {
  'use strict';

  var data = window.VDM_EQUIPAMIENTOS_DATA || [];
  var grid = document.getElementById('equip-grid');
  var tabs = document.getElementById('equip-tabs');
  var searchInput = document.getElementById('equip-search');
  var description = document.getElementById('equip-description');
  var count = document.getElementById('equip-count');
  var empty = document.getElementById('equip-empty');
  var menuButton = document.querySelector('[data-menu-button]');
  var nav = document.querySelector('[data-nav]');
  var activeSlug = getSlugFromHash() || (data[0] && data[0].slug);
  var lightbox;
  var lightboxImage;
  var lightboxTitle;
  var lightboxClose;
  var lastFocusedElement;
  var revealObserver;

  function normalize(value) {
    return String(value || '')
      .toLowerCase()
      .normalize('NFD')
      .replace(/[\u0300-\u036f]/g, '')
      .trim();
  }

  function getSlugFromHash() {
    return window.location.hash.replace('#categoria-', '');
  }

  function createElement(tagName, className, text) {
    var element = document.createElement(tagName);

    if (className) {
      element.className = className;
    }

    if (text) {
      element.textContent = text;
    }

    return element;
  }

  function getWhatsAppUrl(productName) {
    return 'https://wa.me/541171258531?text=' + encodeURIComponent('Hola, quiero consultar por ' + productName + '.');
  }

  function createLightbox() {
    lightbox = createElement('div', 'equip-lightbox');
    var panel = createElement('div', 'equip-lightbox__panel');
    var header = createElement('div', 'equip-lightbox__header');

    lightboxTitle = createElement('h2', '', 'Producto');
    lightboxClose = createElement('button', 'equip-lightbox__close', 'Cerrar');
    lightboxImage = createElement('img');

    lightbox.hidden = true;
    lightbox.setAttribute('role', 'dialog');
    lightbox.setAttribute('aria-modal', 'true');
    lightbox.setAttribute('aria-labelledby', 'equip-lightbox-title');
    lightboxTitle.id = 'equip-lightbox-title';
    lightboxClose.type = 'button';

    header.appendChild(lightboxTitle);
    header.appendChild(lightboxClose);
    panel.appendChild(header);
    panel.appendChild(lightboxImage);
    lightbox.appendChild(panel);
    document.body.appendChild(lightbox);

    lightboxClose.addEventListener('click', closeLightbox);
    lightbox.addEventListener('click', function (event) {
      if (event.target === lightbox) {
        closeLightbox();
      }
    });

    document.addEventListener('keydown', function (event) {
      if (event.key === 'Escape' && lightbox && !lightbox.hidden) {
        closeLightbox();
      }
    });
  }

  function openLightbox(item) {
    if (!lightbox) {
      createLightbox();
    }

    lastFocusedElement = document.activeElement;
    lightboxImage.src = item.image;
    lightboxImage.alt = item.name;
    lightboxTitle.textContent = item.name;
    lightbox.hidden = false;
    document.body.classList.add('has-lightbox');
    lightboxClose.focus();
  }

  function closeLightbox() {
    lightbox.hidden = true;
    document.body.classList.remove('has-lightbox');

    if (lastFocusedElement && typeof lastFocusedElement.focus === 'function') {
      lastFocusedElement.focus();
    }
  }

  function setupRevealObserver() {
    if (!('IntersectionObserver' in window)) {
      return;
    }

    revealObserver = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (!entry.isIntersecting) {
          return;
        }

        entry.target.classList.add('is-visible');
        revealObserver.unobserve(entry.target);
      });
    }, { rootMargin: '0px 0px -12% 0px', threshold: 0.08 });
  }

  function observeReveals() {
    var revealItems = document.querySelectorAll('.equip-reveal:not(.is-visible)');

    if (!revealObserver) {
      revealItems.forEach(function (item) {
        item.classList.add('is-visible');
      });

      return;
    }

    revealItems.forEach(function (item) {
      revealObserver.observe(item);
    });
  }

  function bindMenu() {
    if (!menuButton || !nav) {
      return;
    }

    menuButton.addEventListener('click', function () {
      var isOpen = nav.classList.toggle('is-open');

      menuButton.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    });

    nav.addEventListener('click', function (event) {
      if (event.target.tagName !== 'A') {
        return;
      }

      nav.classList.remove('is-open');
      menuButton.setAttribute('aria-expanded', 'false');
    });

    document.addEventListener('keydown', function (event) {
      if (event.key !== 'Escape') {
        return;
      }

      nav.classList.remove('is-open');
      menuButton.setAttribute('aria-expanded', 'false');
    });
  }

  function getActiveCategory() {
    return data.find(function (category) {
      return category.slug === activeSlug;
    }) || data[0];
  }

  function getFilteredItems(category) {
    var term = normalize(searchInput.value);

    if (!term) {
      return category.items;
    }

    return category.items.filter(function (item) {
      return normalize(category.title + ' ' + item.name + ' ' + item.description).indexOf(term) !== -1;
    });
  }

  function renderTabs() {
    tabs.replaceChildren();

    data.forEach(function (category) {
      var button = createElement('button', 'equip-tab');
      var label = createElement('span', '', category.title);
      var total = createElement('strong', '', category.items.length + ' productos');

      button.type = 'button';
      button.setAttribute('aria-pressed', category.slug === activeSlug ? 'true' : 'false');

      if (category.slug === activeSlug) {
        button.classList.add('is-active');
      }

      button.addEventListener('click', function () {
        activeSlug = category.slug;
        window.location.hash = 'categoria-' + category.slug;
        render();
      });

      button.appendChild(label);
      button.appendChild(total);
      tabs.appendChild(button);
    });
  }

  function renderGrid(items) {
    var fragment = document.createDocumentFragment();

    grid.replaceChildren();

    items.forEach(function (item) {
      var card = createElement('article', 'equip-card');
      var media = createElement('div', 'equip-card__media');
      var zoomButton = createElement('button', 'equip-card__zoom');
      var image = createElement('img');
      var body = createElement('div', 'equip-card__body');
      var title = createElement('h2', '', item.name);
      var text = createElement('p', '', item.description);
      var link = createElement('a', 'card-action', 'Consultar producto');

      card.classList.add('equip-reveal');
      image.src = item.image;
      image.alt = item.name;
      image.loading = 'lazy';
      zoomButton.type = 'button';
      zoomButton.setAttribute('aria-label', 'Ver imagen ampliada de ' + item.name);
      link.href = getWhatsAppUrl(item.name);
      link.target = '_blank';
      link.rel = 'noopener';

      zoomButton.appendChild(image);
      zoomButton.addEventListener('click', function () {
        openLightbox(item);
      });

      media.appendChild(zoomButton);
      body.appendChild(title);
      body.appendChild(text);
      body.appendChild(link);
      card.appendChild(media);
      card.appendChild(body);
      fragment.appendChild(card);
    });

    grid.appendChild(fragment);
    observeReveals();
  }

  function render() {
    var category = getActiveCategory();
    var items = getFilteredItems(category);

    renderTabs();
    renderGrid(items);

    description.textContent = category.description;
    count.textContent = items.length === 1 ? '1 producto' : items.length + ' productos';
    empty.hidden = items.length > 0;
  }

  function init() {
    bindMenu();
    setupRevealObserver();
    render();

    searchInput.addEventListener('input', render);
    window.addEventListener('hashchange', function () {
      activeSlug = getSlugFromHash() || activeSlug;
      render();
    });
  }

  init();
})();
