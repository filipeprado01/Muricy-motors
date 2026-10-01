<?php
/**
 * Muricy Motors Child - page-estoque.php
 * Portado do site de referencia (estoque.html). Grade dinamica via
 * WP_Query nos posts "veiculos" publicados (JetEngine).
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

require_once MURICY_CHILD_THEME_DIR . '/inc-helpers.php';

// Carrega o header/footer direto pelo caminho do tema filho (ver nota em
// single-veiculos.php sobre por que nao usamos get_header()/get_footer()).
require MURICY_CHILD_THEME_DIR . '/header.php';

$veiculos = new WP_Query( array(
	'post_type'      => 'veiculos',
	'posts_per_page' => -1,
	'post_status'    => 'publish',
	'orderby'        => 'date',
	'order'          => 'DESC',
) );
// Carros vendidos sempre por ultimo na lista (a pedido do usuario, 2026-09-14).
$veiculos_ordenados = muricy_sort_vendidos_por_ultimo( $veiculos->posts );
// O total exibido nao conta os vendidos - eles ainda aparecem na lista, mas
// nao estao "disponiveis".
$total = 0;
foreach ( $veiculos_ordenados as $veiculo_contado ) {
	if ( get_post_meta( $veiculo_contado->ID, 'vendido', true ) !== 'true' ) {
		$total++;
	}
}
?>

<div class="shell">

  <nav class="breadcrumb" aria-label="Você está em">
    <ol role="list">
      <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Início</a></li>
      <li aria-current="page">Estoque</li>
    </ol>
  </nav>

  <section class="section section--stock" id="estoque" aria-labelledby="estoque-title">
    <header class="estoque-head">
      <h1 class="section__title section__title--left" id="estoque-title">Conheça a nossa seleção completa</h1>
      <p class="section__lead section__lead--left"><?php echo (int) $total; ?> carro<?php echo $total === 1 ? '' : 's'; ?> disponíve<?php echo $total === 1 ? 'l' : 'is'; ?> agora no showroom do Tatuapé.
        Clique em qualquer um para ver todas as fotos e a ficha completa.</p>

      <form class="search search--dark" id="search-form" role="search" action="#estoque">
        <label class="sr-only" for="search-input">Buscar por marca, modelo ou ano</label>
        <svg class="search__icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><circle cx="11" cy="11" r="7"></circle><path d="M20 20l-3.6-3.6"></path></svg>
        <input class="search__input" id="search-input" name="busca" type="search" placeholder="Buscar por marca, modelo ou ano" autocomplete="off">
        <button class="search__submit" type="submit">Buscar</button>
      </form>

      <?php
      /**
       * PENDENTE (a pedido do usuario, 2026-09-14): a pagina antiga do
       * Estoque (id 114) tinha filtros de dropdown por Ano/Cor/Marca
       * (via JetSmartFilters), que este template ainda nao repoe - so tem
       * a busca por texto acima, igual ao site de referencia.
       *
       * O terreno ja esta preparado para adicionar isso depois, sem
       * precisar mexer nos cards:
       * - cada <article class="card"> ja sai com data-ano, data-cor e
       *   data-brand (ver muricy_vehicle_card_html() em inc-helpers.php).
       * - muricy_get_distinct_meta_values('_ano'|'_cormenu') e
       *   muricy_get_distinct_marcas() (mesmo arquivo) devolvem os valores
       *   possiveis, prontos para virar <option> de um <select>.
       *
       * Quando for construir: montar os 3 <select> aqui (Ano/Cor/Marca),
       * e um JS (em assets/js/main.js, junto do filtro de texto existente)
       * que esconda/mostre os .card comparando o valor escolhido com
       * card.dataset.ano / .cor / .brand.
       */
      ?>
    </header>

    <div class="stock" id="stock-grid">
      <?php foreach ( $veiculos_ordenados as $veiculo_post ) : ?>
        <?php echo muricy_vehicle_card_html( $veiculo_post->ID ); ?>
      <?php endforeach; ?>
    </div>

    <p class="stock__empty" id="stock-empty" hidden>
      Nenhum carro corresponde a essa busca.
      <a href="https://wa.me/5511912899610?text=Ol%C3%A1!%20Estou%20procurando%20um%20carro%20espec%C3%ADfico." target="_blank" rel="noopener">Fale com a nossa equipe</a>:
      temos novas unidades chegando toda semana.
    </p>
  </section>

</div>

<?php require MURICY_CHILD_THEME_DIR . '/footer.php'; ?>
