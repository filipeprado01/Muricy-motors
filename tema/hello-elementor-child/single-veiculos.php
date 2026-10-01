<?php
/**
 * Muricy Motors Child - single-veiculos.php
 * Portado do site de referencia (estoque/porsche-cayenne-e-hybrid.html).
 * Dados dinamicos via campos do JetEngine (get_post_meta) e taxonomia "marca".
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

require_once MURICY_CHILD_THEME_DIR . '/inc-helpers.php';

// Carrega o header direto pelo caminho do tema filho, sem passar por
// get_header()/locate_template() — essas funcoes resolvem o arquivo pelo
// tema ATIVO (Hello Elementor), que continua ativo de proposito, entao
// nunca achariam nosso header.php de qualquer forma.
require MURICY_CHILD_THEME_DIR . '/header.php';

while ( have_posts() ) : the_post();
	$post_id = get_the_ID();

	$marca_terms = get_the_terms( $post_id, 'marca' );
	$marca       = ( $marca_terms && ! is_wp_error( $marca_terms ) ) ? $marca_terms[0]->name : '';

	$versao   = get_post_meta( $post_id, 'versao', true );
	$preco    = get_post_meta( $post_id, 'preco', true );
	$ano      = get_post_meta( $post_id, '_ano', true );
	$km       = get_post_meta( $post_id, '_km', true );
	$combust  = get_post_meta( $post_id, '_combustivel', true );
	$cambio   = get_post_meta( $post_id, '_cambio', true );
	$cor      = get_post_meta( $post_id, '_cormenu', true );
	$descricao = get_post_meta( $post_id, 'descricao', true );
	$blindado  = get_post_meta( $post_id, '_blindagem', true ) === 'Sim';

	$preco_fmt = $preco !== '' ? 'R$ ' . number_format( (float) $preco, 2, ',', '.' ) : 'Sob consulta';
	$km_fmt    = $km !== '' ? number_format( (float) $km, 0, ',', '.' ) . ' KM' : '';

	$galeria_ids = muricy_galeria_ids( $post_id );
	$total_fotos = count( $galeria_ids );

	$whats_texto = 'Olá! Tenho interesse no ' . get_the_title() . ( $preco !== '' ? ' (' . $preco_fmt . ').' : '.' );
	$whats_url   = 'https://wa.me/5511912899610?text=' . rawurlencode( $whats_texto );
	?>

<div class="shell">

  <nav class="breadcrumb" aria-label="Você está em">
    <ol role="list">
      <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Início</a></li>
      <li><a href="<?php echo esc_url( home_url( '/estoque/' ) ); ?>">Estoque</a></li>
      <li aria-current="page"><?php the_title(); ?></li>
    </ol>
  </nav>

  <section class="vehicle" aria-labelledby="veiculo-title">

    <figure class="gallery">
      <?php if ( $total_fotos > 0 ) :
        $primeira = wp_get_attachment_image_url( $galeria_ids[0], 'large' );
      ?>
      <div class="gallery__stage">
        <img src="<?php echo esc_url( $primeira ); ?>" alt="<?php the_title_attribute(); ?>, foto 1 de <?php echo (int) $total_fotos; ?> no showroom da Muricy Motors" width="900" height="1125" fetchpriority="high" decoding="async">
        <button class="gallery__nav gallery__nav--prev" type="button" aria-label="Foto anterior"><svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M15 5l-7 7 7 7"></path></svg></button>
        <button class="gallery__nav gallery__nav--next" type="button" aria-label="Próxima foto"><svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M9 5l7 7-7 7"></path></svg></button>
        <span class="gallery__counter">1/<?php echo (int) $total_fotos; ?></span>
      </div>
      <ul class="gallery__thumbs" role="list">
        <?php foreach ( $galeria_ids as $i => $att_id ) :
          $full  = wp_get_attachment_image_url( $att_id, 'large' );
          $thumb = wp_get_attachment_image_url( $att_id, 'thumbnail' );
        ?>
        <li><button class="gallery__thumb<?php echo 0 === $i ? ' is-active' : ''; ?>" type="button" data-full="<?php echo esc_url( $full ); ?>"><img src="<?php echo esc_url( $thumb ); ?>" alt="<?php the_title_attribute(); ?>, foto <?php echo (int) $i + 1; ?> de <?php echo (int) $total_fotos; ?> no showroom da Muricy Motors" width="200" height="250" loading="lazy"></button></li>
        <?php endforeach; ?>
      </ul>
      <?php else : ?>
      <div class="gallery__stage gallery__stage--empty">
        <p style="padding:40px; text-align:center; color:var(--ink-dim);">Fotos deste veículo ainda não foram adicionadas.</p>
      </div>
      <?php endif; ?>
    </figure>

    <div class="vehicle__info">
      <p class="vehicle__brand"><?php echo esc_html( $marca ); ?></p>
      <h1 class="vehicle__model" id="veiculo-title"><?php the_title(); ?></h1>
      <?php if ( $versao ) : ?><p class="vehicle__version"><?php echo esc_html( $versao ); ?></p><?php endif; ?>
      <p class="vehicle__price"><?php echo esc_html( $preco_fmt ); ?></p>

      <div class="vehicle__highlight">
        <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M3.5 9.5L12 4l8.5 5.5"></path><path d="M5.5 10v8M18.5 10v8M9.5 10v8M14.5 10v8M3.5 20h17"></path></svg>
        <div>
          <strong>Financiamento facilitado</strong>
          <p>Simulamos com os principais bancos e buscamos a melhor parcela para o seu perfil.</p>
        </div>
      </div>

      <ul class="specs" role="list">
        <?php if ( $ano ) : ?><li class="spec"><svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><rect x="3.5" y="5" width="17" height="15" rx="2.5"></rect><path d="M8 3v4M16 3v4M3.5 10h17"></path></svg><span><span class="spec__label">Ano</span><span class="spec__value"><?php echo esc_html( $ano ); ?></span></span></li><?php endif; ?>
        <?php if ( $km_fmt ) : ?><li class="spec"><svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M4 17a8 8 0 1 1 16 0"></path><path d="M12 17l4-5"></path></svg><span><span class="spec__label">Quilometragem</span><span class="spec__value"><?php echo esc_html( $km_fmt ); ?></span></span></li><?php endif; ?>
        <?php if ( $combust ) : ?><li class="spec"><svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><rect x="4" y="3.5" width="10" height="17" rx="2"></rect><path d="M14 9h3a2 2 0 0 1 2 2v5a1.6 1.6 0 0 0 1.6 1.6"></path><path d="M6.5 8.5h5"></path></svg><span><span class="spec__label">Combustível</span><span class="spec__value"><?php echo esc_html( $combust ); ?></span></span></li><?php endif; ?>
        <?php if ( $cambio ) : ?><li class="spec"><svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M7 4v16M17 4v16M7 4h10M7 12h10"></path></svg><span><span class="spec__label">Câmbio</span><span class="spec__value"><?php echo esc_html( $cambio ); ?></span></span></li><?php endif; ?>
        <?php if ( $cor ) : ?><li class="spec"><svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M12 3a9 9 0 1 0 0 18c1.2 0 1.8-.8 1.8-1.7 0-1.4-1-1.8-1-2.8 0-.8.7-1.5 1.6-1.5H16a5 5 0 0 0 5-5c0-3.9-4-7-9-7z"></path><circle cx="8.2" cy="10" r="1.1"></circle><circle cx="12" cy="7.4" r="1.1"></circle><circle cx="15.8" cy="10" r="1.1"></circle></svg><span><span class="spec__label">Cor</span><span class="spec__value"><?php echo esc_html( $cor ); ?></span></span></li><?php endif; ?>
      </ul>

      <div class="vehicle__actions">
        <a class="btn btn--whats" href="<?php echo esc_url( $whats_url ); ?>" target="_blank" rel="noopener"><svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M12 2a10 10 0 0 0-8.6 15L2 22l5.2-1.3A10 10 0 1 0 12 2z"></path><path d="M8.6 7.4c.3 0 .6 0 .8.5l.8 1.9c.1.3 0 .5-.2.7l-.6.6c.7 1.4 1.6 2.3 3 3l.6-.7c.2-.2.4-.3.7-.2l1.9.8c.4.2.5.4.5.8 0 1.2-1 2-2.1 2-3.3 0-7.3-4-7.3-7.3 0-1.1.8-2.1 1.9-2.1z" fill="currentColor" stroke="none"></path></svg>WhatsApp</a>
        <a class="btn btn--outline btn--call" href="tel:+5511912899610"><svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M6.5 3.5h3l1.6 4-2 1.4a12 12 0 0 0 5.5 5.5l1.4-2 4 1.6v3a2 2 0 0 1-2.2 2A16.5 16.5 0 0 1 4.5 5.7a2 2 0 0 1 2-2.2z"></path></svg>Ligar agora</a>
      </div>

      <ul class="assurances" role="list">
        <li><svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M12 21s7-5.6 7-11a7 7 0 1 0-14 0c0 5.4 7 11 7 11z"></path><circle cx="12" cy="10" r="2.6"></circle></svg><span>Showroom no Tatuapé, agende sua visita</span></li>
        <li><svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M3.5 9.5L12 4l8.5 5.5"></path><path d="M5.5 10v8M18.5 10v8M9.5 10v8M14.5 10v8M3.5 20h17"></path></svg><span>Financiamento com os principais bancos</span></li>
      </ul>
    </div>
  </section>

  <section class="tabs" aria-labelledby="abas-title">
    <h2 class="sr-only" id="abas-title">Informações do veículo</h2>

    <div class="tabs__list" role="tablist" aria-label="Informações do veículo">
      <button class="tabs__btn" id="tab-descricao" type="button" role="tab" aria-selected="true" aria-controls="painel-descricao" tabindex="0">Descrição</button>
      <button class="tabs__btn" id="tab-especificacoes" type="button" role="tab" aria-selected="false" aria-controls="painel-especificacoes" tabindex="-1">Especificações</button>
    </div>

    <div class="tabs__panel" id="painel-descricao" role="tabpanel" aria-labelledby="tab-descricao" tabindex="0">
      <h3 class="tabs__title">Sobre este veículo</h3>
      <p><?php echo esc_html( $descricao ? $descricao : 'Descrição em breve.' ); ?></p>

      <h4 class="subhead">Por que comprar na Muricy?</h4>
      <ul class="feature-list" role="list">
        <li><svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><circle cx="12" cy="12" r="9"></circle><path d="M8.5 12.3l2.4 2.4 4.6-4.9"></path></svg><span>Atendimento personalizado do começo ao fim</span></li>
        <li><svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><circle cx="12" cy="12" r="9"></circle><path d="M8.5 12.3l2.4 2.4 4.6-4.9"></path></svg><span>Showroom no Tatuapé para ver o carro de perto antes de decidir</span></li>
        <li><svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><circle cx="12" cy="12" r="9"></circle><path d="M8.5 12.3l2.4 2.4 4.6-4.9"></path></svg><span>Assessoria completa de financiamento com os principais bancos</span></li>
        <li><svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><circle cx="12" cy="12" r="9"></circle><path d="M8.5 12.3l2.4 2.4 4.6-4.9"></path></svg><span>Documentação e transferência resolvidas pela nossa equipe</span></li>
      </ul>
    </div>

    <div class="tabs__panel" id="painel-especificacoes" role="tabpanel" aria-labelledby="tab-especificacoes" tabindex="0" hidden>
      <h3 class="tabs__title">Especificações</h3>
      <table class="spec-table">
        <tbody>
          <tr><th scope="row">Marca</th><td><?php echo esc_html( $marca ); ?></td></tr>
          <tr><th scope="row">Modelo</th><td><?php the_title(); ?></td></tr>
          <?php if ( $versao ) : ?><tr><th scope="row">Versão</th><td><?php echo esc_html( $versao ); ?></td></tr><?php endif; ?>
          <?php if ( $ano ) : ?><tr><th scope="row">Ano</th><td><?php echo esc_html( $ano ); ?></td></tr><?php endif; ?>
          <?php if ( $km_fmt ) : ?><tr><th scope="row">Quilometragem</th><td><?php echo esc_html( $km_fmt ); ?></td></tr><?php endif; ?>
          <?php if ( $combust ) : ?><tr><th scope="row">Combustível</th><td><?php echo esc_html( $combust ); ?></td></tr><?php endif; ?>
          <?php if ( $cambio ) : ?><tr><th scope="row">Câmbio</th><td><?php echo esc_html( $cambio ); ?></td></tr><?php endif; ?>
          <?php if ( $cor ) : ?><tr><th scope="row">Cor</th><td><?php echo esc_html( $cor ); ?></td></tr><?php endif; ?>
          <tr><th scope="row">Preço</th><td><?php echo esc_html( $preco_fmt ); ?></td></tr>
        </tbody>
      </table>
    </div>
  </section>

  <section class="lead" aria-labelledby="lead-title">
    <div class="lead__grid">
      <div class="lead__text">
        <h2 class="related__title" id="lead-title">Interessado neste veículo?</h2>
        <p>Preencha o formulário e nossa equipe entra em contato para agendar uma visita ou tirar suas dúvidas.</p>
        <ul class="feature-list" role="list">
          <li><svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><circle cx="12" cy="12" r="9"></circle><path d="M8.5 12.3l2.4 2.4 4.6-4.9"></path></svg><span>Resposta rápida</span></li>
          <li><svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><circle cx="12" cy="12" r="9"></circle><path d="M8.5 12.3l2.4 2.4 4.6-4.9"></path></svg><span>Atendimento personalizado</span></li>
          <li><svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><circle cx="12" cy="12" r="9"></circle><path d="M8.5 12.3l2.4 2.4 4.6-4.9"></path></svg><span>Sem compromisso</span></li>
        </ul>
      </div>

      <form class="form" id="lead-form" data-vehicle="<?php the_title_attribute(); ?>" data-phone="5511912899610">
        <div class="form__row">
          <div class="field">
            <label for="nome">Nome <span aria-hidden="true">*</span></label>
            <input id="nome" name="nome" type="text" autocomplete="name" placeholder="Seu nome completo" required>
          </div>
          <div class="field">
            <label for="email">E-mail <span aria-hidden="true">*</span></label>
            <input id="email" name="email" type="email" autocomplete="email" placeholder="seu@email.com" required>
          </div>
        </div>
        <div class="field">
          <label for="telefone">Telefone <span aria-hidden="true">*</span></label>
          <input id="telefone" name="telefone" type="tel" autocomplete="tel" placeholder="(11) 90000-0000" required>
        </div>
        <div class="field">
          <label for="mensagem">Mensagem</label>
          <textarea id="mensagem" name="mensagem" placeholder="Como podemos ajudar?"></textarea>
        </div>
        <button class="btn btn--gold form__submit" type="submit"><svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M12 2a10 10 0 0 0-8.6 15L2 22l5.2-1.3A10 10 0 1 0 12 2z"></path><path d="M8.6 7.4c.3 0 .6 0 .8.5l.8 1.9c.1.3 0 .5-.2.7l-.6.6c.7 1.4 1.6 2.3 3 3l.6-.7c.2-.2.4-.3.7-.2l1.9.8c.4.2.5.4.5.8 0 1.2-1 2-2.1 2-3.3 0-7.3-4-7.3-7.3 0-1.1.8-2.1 1.9-2.1z" fill="currentColor" stroke="none"></path></svg>Enviar pelo WhatsApp</button>
        <p class="form__note">Ao enviar, você será direcionado para o nosso WhatsApp com os seus dados já preenchidos.</p>
      </form>
    </div>
  </section>

  <?php
  $related = new WP_Query( array(
    'post_type'      => 'veiculos',
    'posts_per_page' => 2,
    'post__not_in'   => array( $post_id ),
    'orderby'        => 'rand',
    'post_status'    => 'publish',
    // Nunca recomendar um carro ja vendido aqui (auditoria - "precisa de
    // mais tempo"): cobre tanto quem tem vendido="false" quanto quem nem
    // tem o campo preenchido.
    'meta_query'     => array(
      'relation' => 'OR',
      array( 'key' => 'vendido', 'value' => 'true', 'compare' => '!=' ),
      array( 'key' => 'vendido', 'compare' => 'NOT EXISTS' ),
    ),
  ) );
  if ( $related->have_posts() ) :
  ?>
  <section class="related" aria-labelledby="related-title">
    <h2 class="related__title" id="related-title">Outros carros no estoque</h2>
    <div class="stock">
      <?php while ( $related->have_posts() ) : $related->the_post();
        echo muricy_vehicle_card_html( get_the_ID() );
      endwhile; wp_reset_postdata(); ?>
    </div>
    <div class="section__cta">
      <a class="btn btn--gold btn--lg" href="<?php echo esc_url( home_url( '/estoque/' ) ); ?>">Conheça a nossa seleção completa</a>
    </div>
  </section>
  <?php endif; ?>

</div>

<?php endwhile; ?>

<?php require MURICY_CHILD_THEME_DIR . '/footer.php'; ?>
