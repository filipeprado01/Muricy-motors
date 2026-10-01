<?php
/**
 * Muricy Motors Child - page-inicio.php
 * Portado do site de referencia (index.html). A secao "Escolhidos a dedo"
 * e dinamica: mostra os veiculos publicados com o campo "destaque" = true.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

require_once MURICY_CHILD_THEME_DIR . '/inc-helpers.php';

// Carrega o header/footer direto pelo caminho do tema filho (ver nota em
// single-veiculos.php sobre por que nao usamos get_header()/get_footer()).
require MURICY_CHILD_THEME_DIR . '/header.php';

$destaques = new WP_Query( array(
	'post_type'      => 'veiculos',
	'posts_per_page' => -1,
	'post_status'    => 'publish',
	'orderby'        => 'date',
	'order'          => 'DESC',
	'meta_query'     => array(
		array(
			'key'   => 'destaque',
			'value' => 'true',
		),
	),
) );
?>

<!-- ============================== HERO ============================== -->
<section class="hero" id="inicio">
  <picture class="hero__media">
    <source media="(max-width: 800px)" srcset="<?php echo esc_url( MURICY_CHILD_THEME_URI . '/assets/img/hero-showroom-800.jpg' ); ?>">
    <source media="(max-width: 1280px)" srcset="<?php echo esc_url( MURICY_CHILD_THEME_URI . '/assets/img/hero-showroom-1200.jpg' ); ?>">
    <img src="<?php echo esc_url( MURICY_CHILD_THEME_URI . '/assets/img/hero-showroom-1920.jpg' ); ?>" alt="Fachada iluminada do showroom da Muricy Motors, no Tatuapé, com carros premium expostos" width="1920" height="1072" fetchpriority="high" decoding="async">
  </picture>

  <div class="shell hero__inner">
    <h1 class="hero__title">Exclusividade<br>sobre quatro rodas</h1>
    <p class="hero__text">Uma seleção exclusiva de carros premium, escolhidos a dedo, com atendimento <br class="br-desk">personalizado do começo ao fim.</p>
    <div class="hero__actions">
      <a class="btn btn--gold btn--lg" href="<?php echo esc_url( home_url( '/#contato' ) ); ?>">Visitar showroom</a>
      <a class="btn btn--ghost btn--lg" href="<?php echo esc_url( home_url( '/estoque/' ) ); ?>">Conheça a nossa seleção completa</a>
    </div>
  </div>
</section>

<!-- ============================== BUSCA ============================== -->
<section class="finder" aria-labelledby="finder-title">
  <div class="shell">
    <div class="finder__panel">
      <h2 class="sr-only" id="finder-title">Encontre seu carro por marca ou modelo</h2>

      <form class="search" id="search-form" role="search" action="<?php echo esc_url( home_url( '/estoque/' ) ); ?>" data-redirect="<?php echo esc_url( home_url( '/estoque/' ) ); ?>">
        <label class="sr-only" for="search-input">Digite marca ou modelo do carro</label>
        <svg class="search__icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
          <circle cx="11" cy="11" r="7"></circle><path d="M20 20l-3.6-3.6"></path>
        </svg>
        <input class="search__input" id="search-input" name="busca" type="search" placeholder="Digite marca ou modelo do carro" autocomplete="off">
        <button class="search__submit" type="submit">Buscar</button>
      </form>
    </div>
  </div>
</section>

<?php if ( $destaques->have_posts() ) : ?>
<!-- ============================== ESTOQUE (DESTAQUES) ============================== -->
<section class="section section--stock section--featured" id="estoque" aria-labelledby="estoque-title">
  <div class="shell">
    <h2 class="section__title reveal" id="estoque-title">Escolhidos a dedo</h2>

    <div class="stock" id="stock-grid">
      <?php
      // Carros vendidos sempre por ultimo na lista (a pedido do usuario, 2026-09-14).
      $destaques_ordenados = muricy_sort_vendidos_por_ultimo( $destaques->posts );
      foreach ( $destaques_ordenados as $destaque_post ) :
        echo muricy_vehicle_card_html( $destaque_post->ID );
      endforeach;
      ?>
    </div>

    <p class="stock__empty" id="stock-empty" hidden>
      Nenhum carro do estoque em destaque corresponde a essa busca.
      <a href="https://wa.me/5511912899610?text=Ol%C3%A1%21%20Estou%20procurando%20um%20carro%20espec%C3%ADfico." target="_blank" rel="noopener">Fale com a nossa equipe</a>:
      temos novas unidades chegando toda semana.
    </p>

    <div class="section__cta reveal">
      <a class="btn btn--gold btn--lg" href="<?php echo esc_url( home_url( '/estoque/' ) ); ?>">Conheça a nossa seleção completa</a>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ============================== FINANCIAMENTO ============================== -->
<section class="section section--finance" id="financiamento" aria-labelledby="financiamento-title">
  <div class="shell">
    <p class="eyebrow reveal">Financiamento</p>
    <h2 class="section__title reveal" id="financiamento-title">A gente cuida da parte burocrática</h2>
    <p class="section__lead reveal">Trabalhamos com os principais bancos do país para encontrar a melhor condição
      para o seu perfil. Você escolhe o carro; nós cuidamos da simulação, da documentação e da aprovação.</p>

    <ul class="steps reveal" role="list">
      <li class="step">
        <span class="step__num">01</span>
        <h3 class="step__title">Simulação sem compromisso</h3>
        <p class="step__text">Envie o carro que você quer e o valor de entrada. Retornamos com as opções de parcela.</p>
      </li>
      <li class="step">
        <span class="step__num">02</span>
        <h3 class="step__title">Aprovação com os melhores bancos</h3>
        <p class="step__text">Comparamos as taxas disponíveis e apresentamos a proposta mais vantajosa para você.</p>
      </li>
      <li class="step">
        <span class="step__num">03</span>
        <h3 class="step__title">Documentação e entrega</h3>
        <p class="step__text">Transferência, laudo e entrega do carro revisado, tudo resolvido pela nossa equipe.</p>
      </li>
    </ul>

    <div class="section__cta reveal">
      <a class="btn btn--gold btn--lg" href="https://wa.me/5511912899610?text=Ol%C3%A1%21%20Quero%20simular%20um%20financiamento." target="_blank" rel="noopener">Simular financiamento</a>
    </div>
  </div>
</section>

<!-- ===== AVALIAÇÕES ===== -->
<section class="section section--reviews" aria-labelledby="depoimentos-title">
  <div class="shell">
    <p class="eyebrow reveal">Depoimentos</p>
    <h2 class="section__title reveal" id="depoimentos-title">Quem comprou, recomenda</h2>

    <div class="reviews__summary reveal">
      <p class="reviews__score">5,0</p>
      <div class="reviews__summary-text">
        <span class="review__stars" role="img" aria-label="Nota média: 5 de 5 estrelas">★★★★★</span>
        <p>Avaliações de clientes no Google</p>
      </div>
      <a class="btn btn--outline btn--sm reviews__link"
         href="https://www.google.com/maps/search/?api=1&amp;query=Muricy+Motors+Rua+Coelho+Lisboa%2C+861+-+Tatuap%C3%A9%2C+S%C3%A3o+Paulo+-+SP"
         target="_blank" rel="noopener">Ver todas no Google</a>
    </div>

    <div class="reviews">
      <figure class="review reveal">
        <div class="review__head">
          <span class="review__avatar" aria-hidden="true">V</span>
          <div class="review__who">
            <strong>
              <a class="review__link" href="https://maps.app.goo.gl/3dNnbx2zUfEfsFVB9" target="_blank" rel="noopener">
                Vitor Fernandes<span class="sr-only">, ler a avaliação no Google</span>
              </a>
            </strong>
            <span>Avaliação no Google</span>
          </div>
        </div>
        <div class="review__stars" role="img" aria-label="Avaliação: 5 de 5 estrelas">★★★★★</div>
        <blockquote class="review__quote">
          <p>"Agradeço a equipe da muricy motors, pela transparência, e qualidade dos carros, aqui realizo o meu sonho de ter meu primeiro importado, e podem ter certeza que vocês terão um parceiro por longos anos obrigado pessoal!!!"</p>
        </blockquote>
      </figure>

      <figure class="review reveal">
        <div class="review__head">
          <span class="review__avatar" aria-hidden="true">I</span>
          <div class="review__who">
            <strong>
              <a class="review__link" href="https://maps.app.goo.gl/WZPtnRGyQ5Cztev38" target="_blank" rel="noopener">
                Iago Souza<span class="sr-only">, ler a avaliação no Google</span>
              </a>
            </strong>
            <span>Avaliação no Google</span>
          </div>
        </div>
        <div class="review__stars" role="img" aria-label="Avaliação: 5 de 5 estrelas">★★★★★</div>
        <blockquote class="review__quote">
          <p>"experiência impecável, a Muricy Motors entrega um atendimento de altíssimo padrão, digno do segmento premium. quero deixar um destaque especial para a consultora Marília, que foi absolutamente excepcional: atenciosa, extremamente profissional, conduziu todo o processo com total transparência e excelência. Recomendo de olhos fechados a loja"</p>
        </blockquote>
      </figure>

      <figure class="review reveal">
        <div class="review__head">
          <span class="review__avatar" aria-hidden="true">Z</span>
          <div class="review__who">
            <strong>
              <a class="review__link" href="https://maps.app.goo.gl/KK68V113xvjWAft27" target="_blank" rel="noopener">
                Zanetich<span class="sr-only">, ler a avaliação no Google</span>
              </a>
            </strong>
            <span>Avaliação no Google</span>
          </div>
        </div>
        <div class="review__stars" role="img" aria-label="Avaliação: 5 de 5 estrelas">★★★★★</div>
        <blockquote class="review__quote">
          <p>"Atendimento e experiência nota 10, vendedores capacitados e estrutura top ! Recomendo."</p>
        </blockquote>
      </figure>
    </div>
  </div>
</section>

<!-- ============================== CORRENTE DO BEM ============================== -->
<section class="section section--cause" id="corrente" aria-labelledby="corrente-title">
  <div class="shell cause">
    <div class="cause__text">
      <p class="eyebrow reveal">Corrente do Bem</p>
      <h2 class="section__title section__title--left reveal" id="corrente-title">Cada carro vendido move algo maior</h2>
      <p class="section__lead section__lead--left reveal">A Corrente do Bem é o projeto social da Muricy Motors:
        parte do resultado de cada negócio fechado é revertida em ações para famílias e instituições da
        nossa região. Vender bem, para nós, é também devolver.</p>
      <blockquote class="cause__verse reveal">
        <p>"Para que ao nome de Jesus se dobre todo joelho dos que estão nos céus, e na terra, e debaixo da terra e toda língua confesse que Jesus Cristo é o Senhor, para a glória de Deus Pai."</p>
        <cite>Filipenses 2:10-11</cite>
      </blockquote>
    </div>
    <div class="video-card reveal"
         data-youtube="kh0H8xl5QnU"
         data-title="Corrente do Bem, Muricy Motors">
    </div>
  </div>
</section>

<!-- ============================== REDES SOCIAIS ============================== -->
<section class="section section--social" aria-labelledby="social-title">
  <div class="shell">
    <p class="eyebrow reveal">Siga a Muricy</p>
    <h2 class="section__title reveal" id="social-title">A Muricy Motors também <br class="br-desk">está no seu feed</h2>

    <ul class="social-grid reveal" role="list">
      <li>
        <a class="social-tile" href="https://www.instagram.com/muricymotors" target="_blank" rel="noopener me">
          <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><rect x="3" y="3" width="18" height="18" rx="5"></rect><circle cx="12" cy="12" r="4"></circle><circle cx="17.2" cy="6.8" r="1.2" fill="currentColor" stroke="none"></circle></svg>
          <span>@muricymotors</span>
          <span class="sr-only">no Instagram</span>
        </a>
      </li>
      <li>
        <a class="social-tile" href="https://www.youtube.com/@muricymotors" target="_blank" rel="noopener me">
          <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><rect x="2.5" y="5" width="19" height="14" rx="4"></rect><path d="M10.5 9.2l4.6 2.8-4.6 2.8z" fill="currentColor" stroke="none"></path></svg>
          <span>@muricymotors</span>
          <span class="sr-only">no YouTube</span>
        </a>
      </li>
      <li>
        <a class="social-tile" href="https://www.tiktok.com/@muricymotors" target="_blank" rel="noopener me">
          <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M14.2 3v9.6a3.4 3.4 0 1 1-2.8-3.35"></path><path d="M14.2 3c.3 2.2 1.7 3.7 4 3.9"></path></svg>
          <span>@muricymotors</span>
          <span class="sr-only">no TikTok</span>
        </a>
      </li>
      <li>
        <a class="social-tile" href="https://www.mercadolivre.com.br/pagina/muricymotors" target="_blank" rel="noopener">
          <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><ellipse cx="12" cy="12" rx="11" ry="8.2"></ellipse><path d="M2.4 10.4h2.7l2.7-1.7a2 2 0 0 1 2.1 0l1.8 1.1"></path><path d="M21.6 10.4h-2.6l-3.1-1.9a2.1 2.1 0 0 0-2.2 0l-4.2 2.7a1.3 1.3 0 0 0 1.4 2.2l2.1-1.3"></path><path d="M12.7 12.2l3.8 3.2M11 13.5l3.3 2.8M9.4 14.8l2.6 2.2"></path></svg>
          <span>@muricymotors</span>
          <span class="sr-only">no Mercado Livre</span>
        </a>
      </li>
      <li>
        <a class="social-tile" href="https://www.facebook.com/profile.php?id=61556635142470" target="_blank" rel="noopener me">
          <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><circle cx="12" cy="12" r="9.4"></circle><path d="M13.6 20.6v-6.9h2.3l.35-2.7h-2.65V9.3c0-.78.22-1.31 1.34-1.31h1.43V5.57c-.25-.03-1.1-.11-2.09-.11-2.07 0-3.48 1.26-3.48 3.58v2h-2.34v2.7h2.34v6.86z"></path></svg>
          <span>@muricymotors</span>
          <span class="sr-only">no Facebook</span>
        </a>
      </li>
    </ul>
  </div>
</section>

<!-- ============================== LOCALIZAÇÃO / CONTATO ============================== -->
<section class="section section--location" id="contato" aria-labelledby="local-title">
  <div class="shell location">
    <div class="location__text">
      <p class="eyebrow reveal">Localização</p>
      <h2 class="section__title section__title--left reveal" id="local-title">Venha nos visitar</h2>
      <p class="section__lead section__lead--left reveal">Estamos no coração do Tatuapé, em São Paulo.
        Passe aqui para conhecer o showroom e ver de perto cada carro do nosso estoque.</p>

      <ul class="contact-list reveal" role="list">
        <li>
          <svg class="contact-list__icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M12 21s7-5.6 7-11a7 7 0 1 0-14 0c0 5.4 7 11 7 11z"></path><circle cx="12" cy="10" r="2.6"></circle></svg>
          <a href="https://www.google.com/maps/search/?api=1&amp;query=Rua+Coelho+Lisboa%2C+861+-+Tatuap%C3%A9%2C+S%C3%A3o+Paulo+-+SP" target="_blank" rel="noopener">
            <span class="contact-list__label">Rua Coelho Lisboa, 861, Tatuapé</span>
            <span class="contact-list__sub">São Paulo, SP</span>
          </a>
        </li>
        <li>
          <svg class="contact-list__icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M6.5 3.5h3l1.6 4-2 1.4a12 12 0 0 0 5.5 5.5l1.4-2 4 1.6v3a2 2 0 0 1-2.2 2A16.5 16.5 0 0 1 4.5 5.7a2 2 0 0 1 2-2.2z"></path></svg>
          <a href="tel:+5511912899610"><span class="contact-list__label">(11) 91289-9610</span></a>
        </li>
        <li>
          <svg class="contact-list__icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><rect x="3" y="5" width="18" height="14" rx="2.5"></rect><path d="M3.6 6.5L12 12.6l8.4-6.1"></path></svg>
          <a href="mailto:muricymotors@gmail.com"><span class="contact-list__label">muricymotors@gmail.com</span></a>
        </li>
      </ul>
    </div>

    <div class="location__map reveal">
      <iframe
        title="Mapa com a localização da Muricy Motors na Rua Coelho Lisboa, 861, Tatuapé, São Paulo"
        src="https://www.google.com/maps?q=Rua%20Coelho%20Lisboa%2C%20861%20-%20Tatuap%C3%A9%2C%20S%C3%A3o%20Paulo%20-%20SP&amp;output=embed"
        loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
    </div>
  </div>
</section>

<!-- ============================== CTA FINAL ============================== -->
<section class="section section--cta" aria-labelledby="cta-title">
  <div class="shell">
    <h2 class="section__title reveal" id="cta-title">Seu carro novo está te esperando</h2>
    <p class="section__lead reveal">Fale agora com a nossa equipe ou <br class="br-desk">explore o estoque completo do site.</p>
    <div class="section__cta section__cta--pair reveal">
      <a class="btn btn--gold btn--lg" href="<?php echo esc_url( home_url( '/estoque/' ) ); ?>">Conheça a nossa seleção completa</a>
      <a class="btn btn--outline btn--lg" href="https://wa.me/5511912899610?text=Ol%C3%A1%21%20Vim%20pelo%20site%20da%20Muricy%20Motors." target="_blank" rel="noopener">Chamar no WhatsApp</a>
    </div>
  </div>
</section>

<?php require MURICY_CHILD_THEME_DIR . '/footer.php'; ?>
