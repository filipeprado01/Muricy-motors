<?php
/**
 * Muricy Motors Child - funcoes compartilhadas entre single-veiculos.php e
 * page-estoque.php. Carregado via require_once (caminho absoluto), nao
 * depende do tema estar ativo.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

// O JetEngine guarda a galeria de fotos como string JSON (ex: ["123","456"]),
// nao como array PHP nativo — get_post_meta() nao decodifica isso sozinho.
if ( ! function_exists( 'muricy_galeria_ids' ) ) {
	function muricy_galeria_ids( $post_id ) {
		$valor = get_post_meta( $post_id, '_galeria-de-fotos', true );
		if ( is_array( $valor ) ) {
			return $valor;
		}
		if ( is_string( $valor ) && $valor !== '' ) {
			$decodificado = json_decode( $valor, true );
			if ( is_array( $decodificado ) ) {
				return $decodificado;
			}
			return array_filter( array_map( 'trim', explode( ',', $valor ) ) );
		}
		return array();
	}
}

// Renderiza um card de veiculo (usado na grade do Estoque e em "Outros
// carros no estoque" da ficha do veiculo) - markup identico ao site de
// referencia, com badges de Destaque/Blindado quando aplicavel.
if ( ! function_exists( 'muricy_vehicle_card_html' ) ) {
	function muricy_vehicle_card_html( $post_id ) {
		$marca_terms = get_the_terms( $post_id, 'marca' );
		$marca       = ( $marca_terms && ! is_wp_error( $marca_terms ) ) ? $marca_terms[0]->name : '';

		$titulo   = get_the_title( $post_id );
		$versao   = get_post_meta( $post_id, 'versao', true );
		$preco    = get_post_meta( $post_id, 'preco', true );
		$ano      = get_post_meta( $post_id, '_ano', true );
		$km       = get_post_meta( $post_id, '_km', true );
		$cor      = get_post_meta( $post_id, '_cormenu', true );
		$combust  = get_post_meta( $post_id, '_combustivel', true );
		$cambio   = get_post_meta( $post_id, '_cambio', true );
		$destaque = get_post_meta( $post_id, 'destaque', true ) === 'true';
		$blindado = get_post_meta( $post_id, '_blindagem', true ) === 'Sim';
		$vendido  = get_post_meta( $post_id, 'vendido', true ) === 'true';

		$km_fmt    = $km !== '' ? number_format( (float) $km, 0, ',', '.' ) . ' KM' : '';
		$preco_fmt = $preco !== '' ? 'R$ ' . number_format( (float) $preco, 2, ',', '.' ) : 'Sob consulta';

		$galeria = muricy_galeria_ids( $post_id );
		$img_url = ! empty( $galeria ) ? wp_get_attachment_image_url( $galeria[0], 'large' ) : '';

		$data_name = strtolower( trim( implode( ' ', array_filter( array( $marca, $titulo, $versao, $cor, $ano, $combust, $cambio ) ) ) ) );

		// data-ano/data-cor (junto com data-brand) ficam prontos para um
		// futuro filtro em dropdown (Ano/Cor/Marca) - hoje so alimentam a
		// busca por texto (data-name), mas um filtro por comparacao exata
		// pode ler esses atributos direto, sem precisar mudar o card.
		// Ver muricy_get_distinct_meta_values() para montar as opcoes.
		ob_start();
		?>
		<article class="card reveal<?php echo $vendido ? ' card--sold' : ''; ?>" data-brand="<?php echo esc_attr( strtolower( $marca ) ); ?>" data-ano="<?php echo esc_attr( $ano ); ?>" data-cor="<?php echo esc_attr( strtolower( $cor ) ); ?>" data-name="<?php echo esc_attr( $data_name ); ?>">
		  <div class="card__media">
		    <?php if ( $vendido ) : ?>
		    <ul class="card__badges" role="list">
		      <li class="badge badge--sold">Vendido</li>
		    </ul>
		    <?php elseif ( $destaque || $blindado ) : ?>
		    <ul class="card__badges" role="list">
		      <?php if ( $destaque ) : ?><li class="badge badge--featured">Destaque</li><?php endif; ?>
		      <?php if ( $blindado ) : ?><li class="badge badge--armored"><svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M12 2l8 3v6c0 5-3.4 9.3-8 11-4.6-1.7-8-6-8-11V5l8-3z"></path></svg>Blindado</li><?php endif; ?>
		    </ul>
		    <?php endif; ?>
		    <?php if ( $img_url ) : ?>
		    <img src="<?php echo esc_url( $img_url ); ?>" alt="<?php echo esc_attr( $titulo ); ?> no showroom da Muricy Motors" width="800" height="1000" loading="lazy" decoding="async">
		    <?php endif; ?>
		  </div>
		  <div class="card__body">
		    <p class="card__brand"><?php echo esc_html( $marca ); ?></p>
		    <h3 class="card__model">
		      <?php if ( $vendido ) : ?>
		      <?php echo esc_html( $titulo ); ?>
		      <?php else : ?>
		      <a class="card__link" href="<?php echo esc_url( get_permalink( $post_id ) ); ?>"><?php echo esc_html( $titulo ); ?><span class="sr-only"><?php echo $marca ? ', ' . esc_html( $marca ) : ''; ?>. Ver detalhes</span></a>
		      <?php endif; ?>
		    </h3>
		    <?php if ( $versao ) : ?><p class="card__version"><?php echo esc_html( $versao ); ?></p><?php endif; ?>
		    <p class="card__meta"><?php echo esc_html( $ano ); ?><?php if ( $ano && $km_fmt ) : ?><span aria-hidden="true">•</span><?php endif; ?><?php echo esc_html( $km_fmt ); ?></p>
		    <div class="card__foot">
		      <?php if ( $vendido ) : ?>
		      <p class="card__price--sold">Vendido</p>
		      <?php else : ?>
		      <p class="card__price<?php echo $preco === '' ? ' card__price--consulta' : ''; ?>"><?php echo esc_html( $preco_fmt ); ?></p>
		      <svg class="card__arrow" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M9 5l7 7-7 7"></path></svg>
		      <?php endif; ?>
		    </div>
		  </div>
		</article>
		<?php
		return ob_get_clean();
	}
}

// Reordena uma lista de posts de veiculo (WP_Post[]) para que os marcados
// como "vendido" sempre aparecam por ultimo, mantendo a ordem original
// entre os que tem o mesmo status (usort e estavel desde o PHP 8).
if ( ! function_exists( 'muricy_sort_vendidos_por_ultimo' ) ) {
	function muricy_sort_vendidos_por_ultimo( $posts ) {
		usort( $posts, function ( $a, $b ) {
			$a_vendido = get_post_meta( $a->ID, 'vendido', true ) === 'true';
			$b_vendido = get_post_meta( $b->ID, 'vendido', true ) === 'true';
			return $a_vendido <=> $b_vendido;
		} );
		return $posts;
	}
}

/**
 * PREPARACAO para um futuro filtro em dropdown (Ano/Cor/Marca) no Estoque,
 * como o que existe hoje na pagina antiga (via JetSmartFilters). Ainda NAO
 * esta ligado a nenhuma interface - so devolve os valores distintos que
 * existem entre os veiculos publicados, prontos para virar <option> de um
 * <select>. Os cards ja tem data-ano/data-cor/data-brand (ver
 * muricy_vehicle_card_html()) para um JS de filtro comparar sem precisar
 * de outra consulta ao banco.
 *
 * Uso pretendido, quando o filtro for construido:
 *   $anos   = muricy_get_distinct_meta_values( '_ano' );
 *   $cores  = muricy_get_distinct_meta_values( '_cormenu' );
 *   $marcas = muricy_get_distinct_marcas();
 */
if ( ! function_exists( 'muricy_get_distinct_meta_values' ) ) {
	function muricy_get_distinct_meta_values( $meta_key ) {
		global $wpdb;
		$valores = $wpdb->get_col(
			$wpdb->prepare(
				"SELECT DISTINCT pm.meta_value
				 FROM {$wpdb->postmeta} pm
				 INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id
				 WHERE pm.meta_key = %s
				   AND pm.meta_value != ''
				   AND p.post_type = 'veiculos'
				   AND p.post_status = 'publish'
				 ORDER BY pm.meta_value ASC",
				$meta_key
			)
		);
		return $valores ? $valores : array();
	}
}

if ( ! function_exists( 'muricy_get_distinct_marcas' ) ) {
	function muricy_get_distinct_marcas() {
		$termos = get_terms( array(
			'taxonomy'   => 'marca',
			'hide_empty' => true,
		) );
		if ( is_wp_error( $termos ) || ! $termos ) {
			return array();
		}
		return wp_list_pluck( $termos, 'name' );
	}
}
