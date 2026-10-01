/* =========================================================================
   Muricy Motors — página de veículo
   Galeria de fotos, abas e envio do formulário (via página /obrigado/).
   ========================================================================= */
(function () {
  'use strict';

  /* ------------------------------ Galeria -------------------------------- */
  var gallery = document.querySelector('.gallery');

  if (gallery) {
    var stage = gallery.querySelector('.gallery__stage img');
    var faixa = gallery.querySelector('.gallery__thumbs');
    var thumbs = Array.prototype.slice.call(gallery.querySelectorAll('.gallery__thumb'));
    var counter = gallery.querySelector('.gallery__counter');
    var prev = gallery.querySelector('.gallery__nav--prev');
    var next = gallery.querySelector('.gallery__nav--next');
    var index = 0;

    // Com uma foto só não faz sentido mostrar setas, miniaturas e contador.
    if (thumbs.length < 2) gallery.classList.add('is-single');

    // A faixa de miniaturas rola na horizontal, então a miniatura ativa pode
    // estar fora da vista. Centralizamos a faixa nela (só a faixa: mexer em
    // scrollIntoView aqui puxaria a página inteira junto).
    function centralizar(thumb) {
      if (!faixa) return;
      var alvo = thumb.parentNode.getBoundingClientRect();
      var caixa = faixa.getBoundingClientRect();
      faixa.scrollLeft += (alvo.left + alvo.width / 2) - (caixa.left + caixa.width / 2);
    }

    function show(i) {
      if (!thumbs.length) return;
      index = (i + thumbs.length) % thumbs.length;

      var thumb = thumbs[index];
      var img = thumb.querySelector('img');

      stage.src = thumb.dataset.full || img.src;
      stage.alt = img.alt;

      thumbs.forEach(function (t, n) {
        t.classList.toggle('is-active', n === index);
        t.setAttribute('aria-current', n === index ? 'true' : 'false');
      });

      if (counter) counter.textContent = (index + 1) + '/' + thumbs.length;
      centralizar(thumb);
    }

    thumbs.forEach(function (thumb, i) {
      thumb.addEventListener('click', function () { show(i); });
    });

    if (prev) prev.addEventListener('click', function () { show(index - 1); });
    if (next) next.addEventListener('click', function () { show(index + 1); });

    gallery.addEventListener('keydown', function (e) {
      if (thumbs.length < 2) return;
      if (e.key === 'ArrowLeft') { e.preventDefault(); show(index - 1); }
      if (e.key === 'ArrowRight') { e.preventDefault(); show(index + 1); }
    });

    show(0);
  }

  /* -------------------------------- Abas ---------------------------------- */
  var tabList = document.querySelector('.tabs__list');

  if (tabList) {
    var tabs = Array.prototype.slice.call(tabList.querySelectorAll('.tabs__btn'));

    function select(tab) {
      tabs.forEach(function (t) {
        var on = t === tab;
        t.setAttribute('aria-selected', String(on));
        t.setAttribute('tabindex', on ? '0' : '-1');
        document.getElementById(t.getAttribute('aria-controls')).hidden = !on;
      });
    }

    tabs.forEach(function (tab) {
      tab.addEventListener('click', function () { select(tab); });
    });

    // Setas navegam entre as abas, como manda o padrão ARIA.
    tabList.addEventListener('keydown', function (e) {
      var i = tabs.indexOf(document.activeElement);
      if (i === -1) return;
      var to = null;
      if (e.key === 'ArrowRight') to = tabs[(i + 1) % tabs.length];
      if (e.key === 'ArrowLeft') to = tabs[(i - 1 + tabs.length) % tabs.length];
      if (e.key === 'Home') to = tabs[0];
      if (e.key === 'End') to = tabs[tabs.length - 1];
      if (!to) return;
      e.preventDefault();
      to.focus();
      select(to);
    });
  }

  /* ------------- Formulário -> página /obrigado/ -> WhatsApp --------------- */
  var form = document.getElementById('lead-form');

  if (form) {
    // O submit só dispara com o formulário válido (validação nativa do
    // navegador: required / type="email"), então formulário incompleto nunca
    // chega a ir para a /obrigado/.
    form.addEventListener('submit', function (e) {
      e.preventDefault();

      var data = new FormData(form);
      var vehicle = form.dataset.vehicle || 'um veículo do site';

      var lines = [
        'Olá! Vim pelo site e tenho interesse no ' + vehicle + '.',
        '',
        'Nome: ' + (data.get('nome') || '').trim(),
        'E-mail: ' + (data.get('email') || '').trim(),
        'Telefone: ' + (data.get('telefone') || '').trim(),
      ];

      var message = (data.get('mensagem') || '').trim();
      if (message) lines.push('', 'Mensagem: ' + message);

      // A mensagem fica guardada só nesta aba (sessionStorage) e a /obrigado/
      // monta o link do WhatsApp com ela. Nome, e-mail e telefone nunca vão na
      // URL: o Google Analytics/Ads registra a URL, e dado pessoal ali viola as
      // políticas do Google. Se o navegador bloquear o sessionStorage, a
      // /obrigado/ usa a mensagem padrão.
      try {
        window.sessionStorage.setItem('muricy_wpp_msg', lines.join('\n'));
      } catch (err) { /* segue sem a mensagem */ }

      // Só o slug do carro vai junto (não é dado pessoal). Caminho relativo
      // para continuar no mesmo domínio: o sessionStorage é separado por domínio.
      var slug = window.location.pathname.split('/').filter(Boolean).pop();
      window.location.href = '/obrigado/' + (slug ? '?veiculo=' + encodeURIComponent(slug) : '');
    });
  }
})();
