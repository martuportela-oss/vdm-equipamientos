(function () {
  'use strict';

  var data = window.VDM_INDUSTRIAL_DATA || [];
  var accordionRoot = document.getElementById('industrial-accordion');
  var searchInput = document.getElementById('industrial-search');
  var countNode = document.getElementById('industrial-count');
  var emptyNode = document.getElementById('industrial-empty');
  var menuButton = document.querySelector('[data-menu-button]');
  var nav = document.querySelector('[data-nav]');
  var carouselState = new Map();

  function normalize(value) {
    return String(value || '')
      .toLowerCase()
      .normalize('NFD')
      .replace(/[\u0300-\u036f]/g, '')
      .trim();
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

  function filterSections() {
    var term = normalize(searchInput.value);

    if (!term) {
      return data;
    }

    return data
      .map(function (section) {
        var sectionMatches = normalize(section.title).indexOf(term) !== -1;
        var items = section.items.filter(function (item) {
          return normalize(item.name + ' ' + (item.description || '')).indexOf(term) !== -1;
        });

        if (sectionMatches) {
          return section;
        }

        if (items.length) {
          return {
            title: section.title,
            items: items
          };
        }

        return null;
      })
      .filter(Boolean);
  }

  function getVisibleItems(items, index) {
    if (items.length <= 3) {
      return items;
    }

    return [0, 1, 2].map(function (offset) {
      return items[(index + offset) % items.length];
    });
  }

  function renderCarousel(panel, sectionIndex, items) {
    var stateKey = String(sectionIndex);
    var index = carouselState.get(stateKey) || 0;
    var visibleItems = getVisibleItems(items, index);
    var track = panel.querySelector('[data-track]');
    var dots = panel.querySelector('[data-dots]');

    track.replaceChildren();
    dots.replaceChildren();

    visibleItems.forEach(function (item) {
      var card = createElement('article', 'industrial-card');
      var image = createElement('img');
      var title = createElement('h3', 'industrial-card__title', item.name);
      var link = createElement('a', 'card-action', 'Consultar');

      image.src = item.image;
      image.alt = item.name;
      image.loading = 'lazy';
      link.href = getWhatsAppUrl(item.name);
      link.target = '_blank';
      link.rel = 'noopener';
      card.appendChild(image);
      card.appendChild(title);

      if (item.description) {
        card.appendChild(createElement('p', 'industrial-card__description', item.description));
      }

      card.appendChild(link);
      track.appendChild(card);
    });

    items.forEach(function (_, dotIndex) {
      var dot = createElement('button', 'carousel-dot');

      dot.type = 'button';
      dot.setAttribute('aria-label', 'Ver grupo ' + (dotIndex + 1));

      if (dotIndex === index) {
        dot.classList.add('is-active');
      }

      dot.addEventListener('click', function () {
        carouselState.set(stateKey, dotIndex);
        renderCarousel(panel, sectionIndex, items);
      });

      dots.appendChild(dot);
    });
  }

  function createPanel(section, sectionIndex, isOpen) {
    var item = createElement('article', 'accordion-item');
    var button = createElement('button', 'accordion-trigger');
    var sign = createElement('span', 'accordion-sign', isOpen ? '−' : '+');
    var title = createElement('span', '', section.title);
    var panel = createElement('div', 'accordion-panel');
    var track = createElement('div', 'industrial-carousel__track');
    var dots = createElement('div', 'carousel-dots');
    var previous = createElement('button', 'carousel-arrow carousel-arrow--prev', '←');
    var next = createElement('button', 'carousel-arrow carousel-arrow--next', '→');

    button.type = 'button';
    previous.type = 'button';
    next.type = 'button';
    previous.setAttribute('aria-label', 'Anterior');
    next.setAttribute('aria-label', 'Siguiente');
    track.dataset.track = 'true';
    dots.dataset.dots = 'true';

    button.appendChild(sign);
    button.appendChild(title);
    panel.hidden = !isOpen;

    button.addEventListener('click', function () {
      var willOpen = panel.hidden;

      panel.hidden = !willOpen;
      sign.textContent = willOpen ? '−' : '+';
    });

    previous.addEventListener('click', function () {
      var current = carouselState.get(String(sectionIndex)) || 0;
      var nextIndex = current === 0 ? Math.max(section.items.length - 1, 0) : current - 1;

      carouselState.set(String(sectionIndex), nextIndex);
      renderCarousel(panel, sectionIndex, section.items);
    });

    next.addEventListener('click', function () {
      var current = carouselState.get(String(sectionIndex)) || 0;
      var nextIndex = section.items.length ? (current + 1) % section.items.length : 0;

      carouselState.set(String(sectionIndex), nextIndex);
      renderCarousel(panel, sectionIndex, section.items);
    });

    panel.appendChild(previous);
    panel.appendChild(track);
    panel.appendChild(next);
    panel.appendChild(dots);
    item.appendChild(button);
    item.appendChild(panel);

    if (section.items.length) {
      renderCarousel(panel, sectionIndex, section.items);
    } else {
      track.appendChild(createElement('p', 'industrial-card__description', 'Categoria disponible para consulta.'));
      previous.hidden = true;
      next.hidden = true;
    }

    return item;
  }

  function render() {
    var sections = filterSections();
    var itemCount = sections.reduce(function (total, section) {
      return total + section.items.length;
    }, 0);
    var fragment = document.createDocumentFragment();

    accordionRoot.replaceChildren();

    sections.forEach(function (section, index) {
      fragment.appendChild(createPanel(section, index, index === 0));
    });

    accordionRoot.appendChild(fragment);
    emptyNode.hidden = sections.length > 0;
    countNode.textContent = itemCount === 1 ? '1 producto' : itemCount + ' productos';
  }

  function init() {
    bindMenu();
    render();
    searchInput.addEventListener('input', render);
  }

  init();
})();
