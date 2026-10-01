<?php
/**
 * Muricy Motors Child - functions.php
 *
 * Etapa 2: enfileira o estilo do tema pai (Hello Elementor).
 * Etapa 3a: os arquivos de referencia (assets/css/reference.css,
 * assets/js/main.js, assets/js/veiculo.js) ja estao dentro deste
 * tema, mas NAO sao carregados a partir daqui ainda - isso e feito
 * pelo snippet de preview (WPCode, Etapa 3b), que le esses arquivos
 * direto da pasta do tema independente dele estar ativo ou nao.
 * Isso mantem o tema 100% inativo/sem efeito ate o corte de verdade,
 * mais adiante no plano.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function muricy_child_enqueue_parent_style() {
	wp_enqueue_style(
		'muricy-child-parent-style',
		get_template_directory_uri() . '/style.css',
		array(),
		wp_get_theme( get_template() )->get( 'Version' )
	);
}
add_action( 'wp_enqueue_scripts', 'muricy_child_enqueue_parent_style' );
