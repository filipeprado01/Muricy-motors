<?php
/**
 * Muricy Motors Child - header.php
 * Portado do site de referencia (index.html), markup identico.
 * Partes dinamicas: body_class, wp_head, estado "ativo" do menu, wp_body_open.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

$logo_url = wp_get_attachment_image_url( 2831, 'full' );
if ( ! $logo_url ) { $logo_url = 'https://muricymotors.com.br/wp-content/uploads/2026/09/logo-muricy-motors-white.png'; }

// is_front_page() so vai ser verdadeiro depois do corte final (quando a
// pagina Inicio virar a front page de verdade). Ate la, reconhecemos a
// pagina de preview do Inicio pelo ID mesmo, para o hero/header ficarem
// com o visual certo (header transparente sobre a foto) desde ja.
$is_home    = is_front_page() || ( defined( 'MURICY_INICIO_PAGE_ID' ) && is_page( MURICY_INICIO_PAGE_ID ) );
$is_estoque = is_page( 'estoque' ) || is_page( 'estoque-novo' );
$is_veiculo = is_singular( 'veiculos' );

// Meta description + Open Graph/Twitter Card (auditoria - "precisa de mais
// tempo"): sem isso, o Google monta o resultado sozinho e o preview de link
// no WhatsApp/Instagram sai sem foto, nome ou preço - só a URL crua.
$meta_description = 'Muricy Motors: seminovos premium em São Paulo (Tatuapé), com procedência garantida, financiamento facilitado e showroom para visita.';
$og_title         = get_bloginfo( 'name' ) . ' — Carros premium com procedência';
$og_type          = 'website';
$og_image         = MURICY_CHILD_THEME_URI . '/assets/img/hero-showroom-1200.jpg';
$og_url           = home_url( '/' );

if ( $is_estoque ) {
	$meta_description = 'Estoque completo de carros premium da Muricy Motors em São Paulo: importados e nacionais, com procedência e fotos reais de cada veículo.';
	$og_title         = 'Estoque completo — ' . get_bloginfo( 'name' );
	$og_url           = home_url( '/estoque/' );
} elseif ( $is_veiculo ) {
	$hd_id     = get_the_ID();
	$hd_marca  = '';
	$hd_marca_terms = get_the_terms( $hd_id, 'marca' );
	if ( $hd_marca_terms && ! is_wp_error( $hd_marca_terms ) ) {
		$hd_marca = $hd_marca_terms[0]->name;
	}
	$hd_versao  = get_post_meta( $hd_id, 'versao', true );
	$hd_preco   = get_post_meta( $hd_id, 'preco', true );
	$hd_ano     = get_post_meta( $hd_id, '_ano', true );
	$hd_preco_fmt = $hd_preco !== '' ? 'R$ ' . number_format( (float) $hd_preco, 2, ',', '.' ) : 'Sob consulta';

	// O titulo do post ja costuma incluir a marca (ex: "PORSCHE CAYENNE...") -
	// só prefixa se realmente não estiver lá, pra não sair "PORSCHE PORSCHE...".
	$og_title = get_the_title( $hd_id );
	if ( $hd_marca && stripos( $og_title, $hd_marca ) !== 0 ) {
		$og_title = $hd_marca . ' ' . $og_title;
	}
	$meta_description = trim( $og_title . ( $hd_ano ? ' ' . $hd_ano : '' ) ) . ' — Muricy Motors, Tatuapé/SP. ' . $hd_preco_fmt . '.' . ( $hd_versao ? ' ' . $hd_versao . '.' : '' );
	$og_type          = 'product';
	$og_url           = get_permalink( $hd_id );

	$hd_galeria = muricy_galeria_ids( $hd_id );
	if ( ! empty( $hd_galeria ) ) {
		$hd_foto = wp_get_attachment_image_url( $hd_galeria[0], 'large' );
		if ( $hd_foto ) {
			$og_image = $hd_foto;
		}
	}
}
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="theme-color" content="#0a0a0a">
<meta name="description" content="<?php echo esc_attr( $meta_description ); ?>">
<meta property="og:type" content="<?php echo esc_attr( $og_type ); ?>">
<meta property="og:site_name" content="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
<meta property="og:title" content="<?php echo esc_attr( $og_title ); ?>">
<meta property="og:description" content="<?php echo esc_attr( $meta_description ); ?>">
<meta property="og:image" content="<?php echo esc_url( $og_image ); ?>">
<meta property="og:url" content="<?php echo esc_url( $og_url ); ?>">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?php echo esc_attr( $og_title ); ?>">
<meta name="twitter:description" content="<?php echo esc_attr( $meta_description ); ?>">
<meta name="twitter:image" content="<?php echo esc_url( $og_image ); ?>">
<?php wp_head(); ?>
</head>

<?php
// No site de referencia, so a Inicio tem hero (header flutua por cima dela).
// Toda pagina interna (estoque, veiculo, etc.) usa "page-inner" pra empurrar
// o conteudo pra baixo do header fixo (ver reference.css: body.page-inner).
$body_classes = $is_home ? array() : array( 'page-inner' );
?>
<body <?php body_class( $body_classes ); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#conteudo">Pular para o conteúdo</a>

<header class="site-header" id="site-header">
  <div class="shell site-header__inner">
    <a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="Muricy Motors, página inicial">
      <img src="<?php echo esc_url( $logo_url ); ?>" alt="Muricy Motors" width="551" height="369" class="brand__logo">
    </a>

    <nav class="nav" id="nav" aria-label="Navegação principal">
      <ul class="nav__list">
        <li><a class="nav__link<?php echo $is_home ? ' is-active' : ''; ?>" href="<?php echo esc_url( home_url( '/' ) ); ?>">Início</a></li>
        <li><a class="nav__link<?php echo ( $is_estoque || $is_veiculo ) ? ' is-active' : ''; ?>" href="<?php echo esc_url( home_url( '/estoque/' ) ); ?>">Estoque</a></li>
        <li><a class="nav__link" href="<?php echo esc_url( home_url( '/#financiamento' ) ); ?>">Financiamento</a></li>
        <li><a class="nav__link" href="<?php echo esc_url( home_url( '/#corrente' ) ); ?>" title="Corrente do Bem">Corrente</a></li>
        <li><a class="nav__link" href="<?php echo esc_url( home_url( '/#contato' ) ); ?>">Contato</a></li>
      </ul>
      <a class="btn btn--gold nav__cta" href="<?php echo esc_url( home_url( '/estoque/' ) ); ?>">Conheça a nossa seleção completa</a>
    </nav>

    <a class="btn btn--gold header__cta" href="<?php echo esc_url( home_url( '/estoque/' ) ); ?>">Conheça a nossa seleção completa</a>

    <button class="burger" id="burger" type="button" aria-label="Abrir menu" aria-expanded="false" aria-controls="nav">
      <span></span><span></span><span></span>
    </button>
  </div>
</header>

<main id="conteudo" tabindex="-1">
