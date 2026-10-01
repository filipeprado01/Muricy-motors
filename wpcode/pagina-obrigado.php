<?php
/**
 * Muricy Motors - pagina /obrigado/ (pos-envio do formulario de veiculo)
 *
 * Snippet PHP do WPCode, separado do "mecanismo de preview do tema filho".
 * No WPCode o codigo comeca neste comentario, sem a tag de abertura do PHP.
 *
 * Fluxo: o formulario "Interessado neste veiculo?" (assets/js/veiculo.js)
 * guarda a mensagem do WhatsApp no sessionStorage (chave muricy_wpp_msg) e
 * leva a pessoa para /obrigado/. Esta pagina:
 *  - dispara a conversao "Enviou Forms" do GTM so por carregar (acionador
 *    Page Path = /obrigado/; nada a fazer aqui);
 *  - mostra o link "Pedir Orçamento no WhatsApp" (id btn-obrigado-whatsapp),
 *    que o GTM escuta para a conversao "Clicou botao Wpp apos enviar forms";
 *  - depois de 5 s sem clique, faz dataLayer.push obrigado_redirect_whatsapp,
 *    espera 300 ms e abre o WhatsApp na mesma aba.
 *
 * Regras do GTM que NAO podem mudar (senao a conversao para de contar):
 * texto exato do botao e sem text-transform; id do link; target="_self";
 * sem preventDefault no clique; nenhum dado pessoal na URL; nenhum codigo de
 * GTM/gtag/pixel aqui (o GTM ja carrega no site inteiro pelo wp_head).
 *
 * Usa o header.php/footer.php e o CSS do tema filho, achados pelas constantes
 * do snippet de preview. Se aquele snippet estiver desligado, a pagina cai no
 * header/footer do Hello Elementor, sem o visual do site, mas o botao e o
 * redirecionamento continuam funcionando.
 *
 * Pre-requisito: uma pagina publicada com o slug "obrigado" (conteudo vazio).
 * INTERRUPTOR DE EMERGENCIA: WPCode > este snippet > desativar.
 */

if ( ! defined( 'MURICY_OBRIGADO_SLUG' ) ) {
	define( 'MURICY_OBRIGADO_SLUG', 'obrigado' );
}

// Mesmo CSS/JS das paginas ja cortadas ao vivo (mesmos handles do snippet de
// preview, entao nada carrega duas vezes), mais o CSS proprio desta pagina.
add_action( 'wp_enqueue_scripts', function () {
	if ( ! is_page( MURICY_OBRIGADO_SLUG ) ) {
		return;
	}

	$deps = array();

	if ( defined( 'MURICY_CHILD_THEME_DIR' ) && defined( 'MURICY_CHILD_THEME_URI' ) ) {
		$css_file     = MURICY_CHILD_THEME_DIR . '/assets/css/reference.css';
		$main_js_file = MURICY_CHILD_THEME_DIR . '/assets/js/main.js';

		wp_enqueue_style(
			'muricy-preview-fonts',
			'https://fonts.googleapis.com/css2?family=Bai+Jamjuree:ital,wght@0,300;0,400;0,500;0,600;0,700;1,600;1,700&display=swap',
			array(),
			null
		);
		wp_enqueue_style( 'muricy-preview-reference', MURICY_CHILD_THEME_URI . '/assets/css/reference.css', array(), file_exists( $css_file ) ? filemtime( $css_file ) : '1.0.0' );
		wp_enqueue_script( 'muricy-preview-main', MURICY_CHILD_THEME_URI . '/assets/js/main.js', array(), file_exists( $main_js_file ) ? filemtime( $main_js_file ) : '1.0.0', true );

		$deps = array( 'muricy-preview-reference' );
	}

	wp_register_style( 'muricy-obrigado', false, $deps );
	wp_enqueue_style( 'muricy-obrigado' );
	wp_add_inline_style( 'muricy-obrigado', '
.obrigado { padding-block: clamp(56px, 10vw, 120px); }
.obrigado__card {
  max-width: 640px;
  margin-inline: auto;
  padding: clamp(32px, 6vw, 56px) clamp(22px, 5vw, 48px);
  border: 1px solid var(--line);
  border-top: 3px solid var(--gold);
  background: var(--surface-1);
  text-align: center;
}
.obrigado__icone {
  display: block;
  width: 56px; height: 56px;
  margin: 0 auto 20px;
  fill: none; stroke: var(--gold);
  stroke-width: 1.6; stroke-linecap: round; stroke-linejoin: round;
}
.obrigado__titulo { font-size: clamp(1.8rem, 4.2vw, 2.6rem); font-weight: 700; line-height: 1.1; letter-spacing: -0.02em; }
.obrigado__texto { margin-top: 14px; color: var(--text-dim); }
.obrigado__contagem { margin-top: 22px; color: var(--gold); font-weight: 600; }
/* O GTM compara o texto do botao como aparece na tela: sem text-transform
   (o .btn do site usa uppercase), destaque so com peso, espacamento e cor. */
#btn-obrigado-whatsapp {
  width: 100%;
  max-width: 420px;
  margin-top: 26px;
  font-size: 1.05rem;
  font-weight: 700;
  letter-spacing: 0.02em;
  text-transform: none;
}
/* O icone nao pode "roubar" o clique do link (o GTM olha o elemento clicado). */
#btn-obrigado-whatsapp svg {
  width: 22px; height: 22px;
  pointer-events: none;
  fill: none; stroke: currentColor;
  stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round;
}
/* O WhatsApp flutuante abriria uma segunda conversa enquanto a contagem
   continua rodando. So some nesta pagina. */
body:has(#btn-obrigado-whatsapp) .whatsapp-float { display: none; }
' );
}, 20 );

function muricy_obrigado_conteudo() {
	// href padrao (pagina aberta direto, ou sem JavaScript). O script abaixo
	// troca pela mensagem que o formulario guardou, quando houver.
	$href = 'https://wa.me/5511912899610?text=' . rawurlencode( 'Olá! Vim pelo site da Muricy Motors.' );
	?>
<section class="obrigado" aria-labelledby="obrigado-titulo">
  <div class="shell">
    <div class="obrigado__card">
      <svg class="obrigado__icone" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><circle cx="12" cy="12" r="9"></circle><path d="M8.5 12.3l2.4 2.4 4.6-4.9"></path></svg>
      <h1 class="obrigado__titulo" id="obrigado-titulo">Recebemos seu interesse!</h1>
      <p class="obrigado__texto">Estamos te levando para o nosso WhatsApp para finalizar o atendimento.</p>
      <p class="obrigado__contagem" id="obrigado-contagem">Abrindo o WhatsApp em <span id="obrigado-segundos">5</span>...</p>
      <a id="btn-obrigado-whatsapp" class="btn btn--gold btn--lg" href="<?php echo esc_url( $href ); ?>" target="_self"><svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M12 2a10 10 0 0 0-8.6 15L2 22l5.2-1.3A10 10 0 1 0 12 2z"></path><path d="M8.6 7.4c.3 0 .6 0 .8.5l.8 1.9c.1.3 0 .5-.2.7l-.6.6c.7 1.4 1.6 2.3 3 3l.6-.7c.2-.2.4-.3.7-.2l1.9.8c.4.2.5.4.5.8 0 1.2-1 2-2.1 2-3.3 0-7.3-4-7.3-7.3 0-1.1.8-2.1 1.9-2.1z" fill="currentColor" stroke="none"></path></svg>Pedir Orçamento no WhatsApp</a>
    </div>
  </div>
</section>
<script data-no-optimize="1" data-no-defer="1">
(function () {
  'use strict';

  var btn = document.getElementById('btn-obrigado-whatsapp');
  var aviso = document.getElementById('obrigado-contagem');
  var segundos = document.getElementById('obrigado-segundos');
  var url = btn.href;

  // Mensagem montada pelo formulario do veiculo. Usada uma vez e apagada.
  try {
    var msg = window.sessionStorage.getItem('muricy_wpp_msg');
    if (msg) {
      url = 'https://wa.me/5511912899610?text=' + encodeURIComponent(msg);
      btn.href = url;
    }
    window.sessionStorage.removeItem('muricy_wpp_msg');
  } catch (e) { /* sem sessionStorage: fica a mensagem padrao */ }

  var restante = 5;
  var redirecionar = null;
  var contagem = window.setInterval(function () {
    restante -= 1;
    if (restante > 0) {
      segundos.textContent = restante;
      return;
    }
    window.clearInterval(contagem);
    contagem = null;
    aviso.textContent = 'Abrindo o WhatsApp...';

    window.dataLayer = window.dataLayer || [];
    window.dataLayer.push({ event: 'obrigado_redirect_whatsapp' });
    redirecionar = window.setTimeout(function () { window.location.href = url; }, 300);
  }, 1000);

  // Clique antes dos 5 s: cancela o automatico, para abrir uma vez so e nao
  // disparar o evento. Sem preventDefault: o link navega normalmente e o GTM
  // registra o clique.
  btn.addEventListener('click', function () {
    if (contagem) { window.clearInterval(contagem); contagem = null; }
    if (redirecionar) { window.clearTimeout(redirecionar); redirecionar = null; }
    aviso.textContent = 'Abrindo o WhatsApp...';
  });
})();
</script>
	<?php
}

// Monta a pagina inteira e sai, no mesmo padrao do snippet de preview:
// template_redirect roda antes do Theme Builder do Elementor escolher o
// template, entao nada consegue trocar a pagina de volta.
add_action( 'template_redirect', function () {
	if ( ! is_page( MURICY_OBRIGADO_SLUG ) ) {
		return;
	}

	$tema = ( defined( 'MURICY_CHILD_THEME_DIR' ) && file_exists( MURICY_CHILD_THEME_DIR . '/header.php' ) ) ? MURICY_CHILD_THEME_DIR : '';

	if ( $tema ) {
		require $tema . '/header.php';
	} else {
		get_header();
	}

	muricy_obrigado_conteudo();

	if ( $tema ) {
		require $tema . '/footer.php';
	} else {
		get_footer();
	}
	exit;
}, 1 );

// Fora do Google: noindex/nofollow so nesta pagina (o site nao tem plugin de
// SEO, entao vai pelo filtro do proprio WordPress). Prioridade 99 para rodar
// depois do max-image-preview que o WordPress acrescenta sozinho.
add_filter( 'wp_robots', function ( $robots ) {
	if ( is_page( MURICY_OBRIGADO_SLUG ) ) {
		$robots['noindex']  = true;
		$robots['nofollow'] = true;
		unset( $robots['max-image-preview'] );
	}
	return $robots;
}, 99 );

// E fora do sitemap, para nao mandar o Google para uma pagina noindex.
add_filter( 'wp_sitemaps_posts_query_args', function ( $args, $post_type ) {
	if ( 'page' !== $post_type ) {
		return $args;
	}
	$pagina = get_page_by_path( MURICY_OBRIGADO_SLUG );
	if ( $pagina ) {
		$args['post__not_in'] = array_merge( isset( $args['post__not_in'] ) ? (array) $args['post__not_in'] : array(), array( $pagina->ID ) );
	}
	return $args;
}, 10, 2 );
