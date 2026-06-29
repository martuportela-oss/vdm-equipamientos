(function () {
    'use strict';

    function markReady(selector) {
        var blocks = document.querySelectorAll(selector);

        blocks.forEach(function (block) {
            block.classList.add('is-ready');
        });
    }

    function init() {
        markReady('[data-vdm-equipamientos]');
        markReady('[data-vdm-categorias]');
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
