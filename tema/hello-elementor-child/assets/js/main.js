/* =========================================================================
   Muricy Motors — comportamentos da página
   ========================================================================= */
(function () {
  'use strict';

  var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* ------------------------- Header com fundo ao rolar ------------------- */
  var header = document.getElementById('site-header');

  function onScroll() {
    header.classList.toggle('is-stuck', window.scrollY > 40);
  }
  onScroll();
  window.addEventListener('scroll', onScroll, { passive: true });

  /* ------------------------------ Menu mobile ---------------------------- */
  var burger = document.getElementById('burger');
  var nav = document.getElementById('nav');

  function closeMenu() {
    nav.classList.remove('is-open');
    burger.setAttribute('aria-expanded', 'false');
    burger.setAttribute('aria-label', 'Abrir menu');
    document.body.classList.remove('nav-open');
  }

  burger.addEventListener('click', function () {
    var open = nav.classList.toggle('is-open');
    burger.setAttribute('aria-expanded', String(open));
    burger.setAttribute('aria-label', open ? 'Fechar menu' : 'Abrir menu');
    document.body.classList.toggle('nav-open', open);
  });

  nav.addEventListener('click', function (e) {
    if (e.target.closest('a')) closeMenu();
  });

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && nav.classList.contains('is-open')) {
      closeMenu();
      burger.focus();
    }
  });

  /* --------------------- Link ativo conforme a seção --------------------- */
  var navLinks = Array.prototype.slice.call(document.querySelectorAll('.nav__link'));
  // Nas páginas internas o menu aponta para "../index.html#secao", que não é um
  // seletor válido — só seguimos as âncoras desta mesma página.
  var sections = navLinks
    .map(function (link) {
      var href = link.getAttribute('href') || '';
      return href.charAt(0) === '#' && href.length > 1 ? document.querySelector(href) : null;
    })
    .filter(Boolean);

  if ('IntersectionObserver' in window && sections.length) {
    var spy = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (!entry.isIntersecting) return;
        navLinks.forEach(function (link) {
          link.classList.toggle('is-active', link.getAttribute('href') === '#' + entry.target.id);
        });
      });
    }, { rootMargin: '-45% 0px -50% 0px', threshold: 0 });

    sections.forEach(function (section) { spy.observe(section); });
  }

  /* ------------------------- Animação de entrada ------------------------- */
  var revealables = document.querySelectorAll('.reveal');

  if (reduceMotion || !('IntersectionObserver' in window)) {
    revealables.forEach(function (el) { el.classList.add('is-visible'); });
  } else {
    var revealer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (!entry.isIntersecting) return;
        entry.target.classList.add('is-visible');
        revealer.unobserve(entry.target);
      });
    }, { rootMargin: '0px 0px -8% 0px', threshold: 0.08 });

    revealables.forEach(function (el) { revealer.observe(el); });
  }

  /* ------------------------ Busca no estoque por texto -------------------
     Só existe na home; nas páginas de veículo este bloco é ignorado.        */
  var cards = Array.prototype.slice.call(document.querySelectorAll('#stock-grid .card'));
  var form = document.getElementById('search-form');
  var input = document.getElementById('search-input');
  var empty = document.getElementById('stock-empty');
  var stockSection = document.getElementById('estoque');
  // Na Home, a busca so tem os 3-4 carros em destaque pra filtrar - nao faz
  // sentido buscar "BMW" ali e ouvir "nenhum carro encontrado" quando o
  // Estoque real tem varios. Se o form tiver esse atributo, o clique em
  // "Buscar" pula direto pro Estoque completo com o termo already digitado,
  // em vez de filtrar s\u00f3 a vitrine da Home.
  var redirectTo = form ? form.dataset.redirect : null;

  var hasFinder = form && input && empty && stockSection && cards.length > 0;

  function normalize(value) {
    return (value || '')
      .toLowerCase()
      .normalize('NFD')
      .replace(/[\u0300-\u036f]/g, '')
      .trim();
  }

  function applyFilter() {
    var term = normalize(input.value);
    var shown = 0;

    cards.forEach(function (card) {
      var haystack = normalize(card.dataset.name) + ' ' + normalize(card.textContent);
      var visible = !term || haystack.indexOf(term) !== -1;

      card.classList.toggle('is-hidden', !visible);
      if (visible) shown++;
    });

    empty.hidden = shown !== 0;
  }

  if (form && input && redirectTo) {
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      var term = input.value.trim();
      window.location.href = redirectTo + (term ? '?busca=' + encodeURIComponent(term) : '');
    });
  } else if (hasFinder) {
    input.addEventListener('input', applyFilter);

    form.addEventListener('submit', function (e) {
      e.preventDefault();
      applyFilter();
      stockSection.scrollIntoView({ behavior: reduceMotion ? 'auto' : 'smooth', block: 'start' });
    });

    // Permite chegar pelo link ?busca=... usado no SearchAction do JSON-LD.
    var queryTerm = new URLSearchParams(window.location.search).get('busca');
    if (queryTerm) {
      input.value = queryTerm;
      applyFilter();
    }
  }

  /* --------------------------- Card de vídeo ------------------------------
     Mostra a capa do YouTube e só carrega o player quando alguém clica —
     assim a página não paga o peso nem os cookies do YouTube à toa.         */
  Array.prototype.forEach.call(document.querySelectorAll('.video-card'), function (card) {
    var id = (card.dataset.youtube || '').trim();
    var title = card.dataset.title || 'Vídeo';

    var play =
      '<span class="video-card__play" aria-hidden="true">' +
      '<svg viewBox="0 0 24 24" focusable="false"><path d="M8 5.5v13l11-6.5z"></path></svg>' +
      '</span>';

    // Sem um ID válido do YouTube o card fica no estado "Vídeo em breve".
    if (!/^[A-Za-z0-9_-]{11}$/.test(id)) {
      card.classList.add('video-card--empty');
      card.innerHTML = play + '<p>Vídeo em breve</p>';
      return;
    }

    var titleAttr = title
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;');

    var btn = document.createElement('button');
    btn.type = 'button';
    btn.className = 'video-card__btn';
    btn.setAttribute('aria-label', 'Assistir: ' + title);
    // Sem legenda escrita sobre a capa: muitos vídeos já trazem texto queimado
    // na imagem e os dois se atropelam. O nome fica no aria-label do botão.
    btn.innerHTML =
      '<img class="video-card__thumb" src="https://i.ytimg.com/vi/' + id + '/sddefault.jpg"' +
      ' alt="' + titleAttr + '" width="640" height="480" loading="lazy" decoding="async">' +
      play;

    // Quando uma capa não existe, o YouTube não devolve erro: devolve uma
    // imagem cinza de 120x90. Por isso não dá para confiar só no evento
    // "error" — o jeito certo é olhar o tamanho que chegou.
    var thumb = btn.querySelector('img');
    function capaPadrao() {
      if (thumb.dataset.fallback) return;
      thumb.dataset.fallback = '1';
      thumb.src = 'https://i.ytimg.com/vi/' + id + '/hqdefault.jpg';
    }
    thumb.addEventListener('load', function () {
      if (this.naturalWidth <= 120) capaPadrao();
    });
    thumb.addEventListener('error', capaPadrao);

    // Aberto direto do arquivo (file://) o YouTube recusa o embed com
    // "Erro 153", porque não existe origem HTTP. Nesse caso abrimos o vídeo
    // no YouTube. Publicado (ou num servidor local) o player roda normalmente.
    var semServidor = window.location.protocol === 'file:';
    if (semServidor) {
      btn.setAttribute('aria-label', 'Assistir no YouTube: ' + title);
    }

    btn.addEventListener('click', function () {
      if (semServidor) {
        window.open('https://www.youtube.com/watch?v=' + id, '_blank', 'noopener');
        return;
      }
      var frame = document.createElement('iframe');
      frame.src = 'https://www.youtube-nocookie.com/embed/' + id + '?autoplay=1&rel=0';
      frame.title = title;
      frame.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture';
      frame.allowFullscreen = true;
      card.replaceChildren(frame);
      frame.focus();
    });

    card.replaceChildren(btn);
  });

  /* ------------------------------ Ano do rodapé -------------------------- */
  var year = document.getElementById('year');
  if (year) year.textContent = String(new Date().getFullYear());
})();
