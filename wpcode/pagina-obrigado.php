<?php
/**
 * Muricy Motors - pagina /obrigado/ (pos-envio do formulario de veiculo)
 *
 * Snippet PHP do WPCode, NOVO e SEPARADO do snippet "Tema" (ID 3801, o
 * "mecanismo de preview do tema filho"). Nunca colar este codigo dentro
 * daquele: ele e quem mantem no ar o Estoque, o Inicio e as paginas de veiculo.
 * No WPCode o codigo comeca neste comentario, sem a tag de abertura do PHP.
 *
 * Nao altera nenhum arquivo do tema nem o layout de nenhuma pagina existente.
 * Desligar este snippet devolve o site exatamente ao comportamento anterior.
 *
 * Fluxo: no formulario "Interessado neste veiculo?", este snippet troca so o
 * que o envio faz. Em vez de abrir o WhatsApp direto (o que o veiculo.js do
 * tema faz), grava o lead na planilha do Google (MURICY_LEADS_URL), guarda a
 * mensagem no sessionStorage (chave muricy_wpp_msg) e leva a pessoa para
 * /obrigado/. Esta pagina:
 *  - dispara a conversao "Enviou Forms" do GTM so por carregar (acionador
 *    Page Path = /obrigado/; nada a fazer aqui);
 *  - mostra o link "Pedir Orçamento no WhatsApp" (id btn-obrigado-whatsapp),
 *    que o GTM escuta para a conversao "Clicou botao Wpp apos enviar forms";
 *  - depois de 5 s sem clique, faz dataLayer.push obrigado_redirect_whatsapp,
 *    espera 300 ms e abre o WhatsApp na mesma aba clicando no proprio botao
 *    (assim o acionador de clique do GTM tambem conta esse caso).
 *
 * Regras do GTM que NAO podem mudar (senao a conversao para de contar):
 * texto exato do botao e sem text-transform; id do link; target="_self";
 * sem preventDefault no clique; nenhum dado pessoal na URL; nenhum codigo de
 * GTM/gtag/pixel aqui (o GTM ja carrega no site inteiro pelo wp_head).
 *
 * Usa o header.php/footer.php e o CSS do tema filho, achados pelas constantes
 * do snippet "Tema". Se aquele snippet estiver desligado, a pagina cai no
 * header/footer do Hello Elementor, sem o visual do site, mas o botao e o
 * redirecionamento continuam funcionando.
 *
 * Pre-requisito: uma pagina publicada com o slug "obrigado" (conteudo vazio).
 * INTERRUPTOR DE EMERGENCIA: WPCode > este snippet > desativar.
 */

if ( ! defined( 'MURICY_OBRIGADO_SLUG' ) ) {
	define( 'MURICY_OBRIGADO_SLUG', 'obrigado' );
}

// O acionador do GTM "Clicou Botao - Pedir Orcamento (Apos envio do forms)"
// (dispara as tags "02 - ...") so reconhece cliques em links cujo endereco
// CONTEM exatamente MURICY_OBRIGADO_WPP_URL. Por isso a mensagem do WhatsApp
// sempre comeca com esta frase, e o link e montado colando os dados do
// formulario depois desse endereco, sem recodificar a parte inicial. Nao
// mudar uma letra sem mudar o acionador no GTM junto.
// Planilha "Leads do site - Muricy Motors" (Google Apps Script publicado como
// App da Web; codigo em google-sheets/leads-apps-script.gs no repositorio).
// O token tem que ser igual ao do script. Ele aparece no codigo da pagina:
// serve so para barrar robos que nao leem o site, nao e senha.
if ( ! defined( 'MURICY_LEADS_URL' ) ) {
	define( 'MURICY_LEADS_URL', 'https://script.google.com/macros/s/AKfycbxJnebneIwQszJQsjFaAzS_fjMP0fiC7sJamzpdegD7CuJJifZ3aA4fNirmzqQXsDS9/exec' );
	define( 'MURICY_LEADS_TOKEN', '40b0ead5df0313d439c15932' );
}

if ( ! defined( 'MURICY_OBRIGADO_ABERTURA' ) ) {
	define( 'MURICY_OBRIGADO_ABERTURA', 'Olá! Vim do site e tenho interesse nos carros da Motors.' );
	define( 'MURICY_OBRIGADO_WPP_URL', 'https://wa.me/5511912899610?text=Ol%C3%A1!%20Vim%20do%20site%20e%20tenho%20interesse%20nos%20carros%20da%20Motors.' );
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
' );
}, 20 );

// Pagina de veiculo: so troca o que o envio do formulario faz. Nao imprime
// nada visivel (so este script no rodape), entao o layout fica identico.
add_action( 'wp_footer', function () {
	if ( ! is_singular( 'veiculos' ) ) {
		return;
	}
	?>
<script data-no-optimize="1" data-no-defer="1">
(function () {
  'use strict';

  // Escuta na fase de captura da window: roda antes do listener que o
  // veiculo.js poe no proprio formulario e impede que ele abra o WhatsApp.
  // O submit so dispara com o formulario valido (validacao nativa do
  // navegador), entao formulario incompleto nunca chega aqui.
  window.addEventListener('submit', function (e) {
    var form = e.target;
    if (!form || form.id !== 'lead-form') return;
    e.preventDefault();
    e.stopImmediatePropagation();

    // Mesmos dados que o veiculo.js manda, mas comecando pela frase que o
    // acionador do GTM reconhece (ver MURICY_OBRIGADO_ABERTURA).
    var data = new FormData(form);
    var lines = [<?php echo wp_json_encode( MURICY_OBRIGADO_ABERTURA ); ?>, ''];
    if (form.dataset.vehicle) lines.push('Veículo: ' + form.dataset.vehicle);
    lines.push(
      'Nome: ' + (data.get('nome') || '').trim(),
      'E-mail: ' + (data.get('email') || '').trim(),
      'Telefone: ' + (data.get('telefone') || '').trim()
    );
    var message = (data.get('mensagem') || '').trim();
    if (message) lines.push('', 'Mensagem: ' + message);

    // Grava o lead na planilha agora, antes de sair da pagina: mesmo que a
    // pessoa desista do WhatsApp, o vendedor tem o contato. sendBeacon foi
    // feito para isso (o navegador entrega mesmo com a troca de pagina) e vai
    // direto para o Google, sem passar pelo servidor do site.
    var lead = JSON.stringify({
      token: <?php echo wp_json_encode( MURICY_LEADS_TOKEN ); ?>,
      nome: (data.get('nome') || '').trim(),
      email: (data.get('email') || '').trim(),
      telefone: (data.get('telefone') || '').trim(),
      veiculo: form.dataset.vehicle || '',
      mensagem: message,
      pagina: window.location.href
    });
    var leadsUrl = <?php echo wp_json_encode( MURICY_LEADS_URL ); ?>;
    var enviado = false;
    try {
      enviado = !!(navigator.sendBeacon && navigator.sendBeacon(leadsUrl, new Blob([lead], { type: 'text/plain;charset=UTF-8' })));
    } catch (err) { enviado = false; }
    if (!enviado) {
      try {
        fetch(leadsUrl, { method: 'POST', mode: 'no-cors', keepalive: true, headers: { 'Content-Type': 'text/plain;charset=UTF-8' }, body: lead });
      } catch (err) { /* sem envio: segue para a /obrigado/ assim mesmo */ }
    }

    // Nome, e-mail e telefone nunca vao na URL (o Google Analytics/Ads
    // registra a URL). Sem sessionStorage, a /obrigado/ usa a mensagem padrao.
    try {
      window.sessionStorage.setItem('muricy_wpp_msg', lines.join('\n'));
    } catch (err) { /* segue sem a mensagem */ }

    // So o slug do carro vai junto (nao e dado pessoal). Caminho relativo para
    // continuar no mesmo dominio: o sessionStorage e separado por dominio.
    var slug = window.location.pathname.split('/').filter(Boolean).pop();
    window.location.href = '/obrigado/' + (slug ? '?veiculo=' + encodeURIComponent(slug) : '');
  }, true);
})();
</script>
	<?php
}, 100 );

function muricy_obrigado_conteudo() {
	// href padrao (pagina aberta direto, ou sem JavaScript): so a frase que o
	// acionador do GTM reconhece. O script abaixo acrescenta os dados que o
	// formulario guardou, quando houver.
	$href = MURICY_OBRIGADO_WPP_URL;
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
  var ABERTURA = <?php echo wp_json_encode( MURICY_OBRIGADO_ABERTURA ); ?>;
  var WPP_URL = <?php echo wp_json_encode( MURICY_OBRIGADO_WPP_URL ); ?>;

  // Mensagem montada pelo formulario do veiculo. Usada uma vez e apagada.
  // O link comeca sempre por WPP_URL, copiado letra por letra do acionador do
  // GTM; so o que vem depois da frase de abertura e codificado aqui.
  try {
    var msg = window.sessionStorage.getItem('muricy_wpp_msg');
    if (msg) {
      var resto = msg.indexOf(ABERTURA) === 0 ? msg.slice(ABERTURA.length) : '\n\n' + msg;
      btn.href = WPP_URL + encodeURIComponent(resto);
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
    // Abre o WhatsApp "clicando" no proprio botao: o acionador de clique do GTM
    // ve esse clique e as tags "02 - ..." disparam tambem para quem nao clicou,
    // sem precisar de acionador novo no GTM.
    redirecionar = window.setTimeout(function () { redirecionar = null; btn.click(); }, 300);
  }, 1000);

  // Clique antes dos 5 s: cancela o automatico, para abrir uma vez so e nao
  // disparar o evento. Sem preventDefault: o link navega normalmente e o GTM
  // registra o clique.
  function cancelar() {
    if (contagem) { window.clearInterval(contagem); contagem = null; }
    if (redirecionar) { window.clearTimeout(redirecionar); redirecionar = null; }
  }

  btn.addEventListener('click', function () {
    cancelar();
    aviso.textContent = 'Abrindo o WhatsApp...';
  });

  // O WhatsApp flutuante do rodape continua como no resto do site. Se a
  // pessoa clicar nele (abre outra aba), cancela o automatico para nao abrir
  // uma segunda conversa. Escuta no document porque o rodape ainda nao existe
  // quando este script roda.
  document.addEventListener('click', function (e) {
    if (e.target.closest && e.target.closest('.whatsapp-float')) {
      cancelar();
      aviso.hidden = true;
    }
  }, true);
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
