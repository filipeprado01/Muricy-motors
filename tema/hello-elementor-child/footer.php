</main>

<?php
/**
 * Muricy Motors Child - footer.php
 * Portado do site de referencia (index.html), markup identico.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
$logo_url = wp_get_attachment_image_url( 2831, 'full' );
if ( ! $logo_url ) { $logo_url = 'https://muricymotors.com.br/wp-content/uploads/2026/09/logo-muricy-motors-white.png'; }
?>

<footer class="site-footer">
  <div class="shell site-footer__grid">

    <div class="site-footer__brand">
      <img src="<?php echo esc_url( $logo_url ); ?>" alt="Muricy Motors" width="551" height="369" loading="lazy">
      <blockquote class="site-footer__verse">
        <p>“Para que ao nome de Jesus se dobre todo joelho dos que estão nos céus, e na terra, e debaixo da terra e toda língua confesse que Jesus Cristo é o Senhor, para a glória de Deus Pai.”</p>
      </blockquote>
    </div>

    <nav class="site-footer__col" aria-labelledby="foot-nav">
      <h2 class="site-footer__title" id="foot-nav">Navegação</h2>
      <ul role="list">
        <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Início</a></li>
        <li><a href="<?php echo esc_url( home_url( '/#contato' ) ); ?>">Contato</a></li>
        <li><a href="<?php echo esc_url( home_url( '/#corrente' ) ); ?>">Corrente do Bem</a></li>
        <li><a href="<?php echo esc_url( home_url( '/estoque/' ) ); ?>">Estoque</a></li>
        <li><a href="<?php echo esc_url( home_url( '/#financiamento' ) ); ?>">Financiamento</a></li>
        <li><a href="https://wa.me/5511912899610?text=Ol%C3%A1!%20Gostaria%20de%20enviar%20meu%20curr%C3%ADculo." target="_blank" rel="noopener">Trabalhe Conosco</a></li>
        <li><a href="https://wa.me/5511912899610?text=Ol%C3%A1!%20Gostaria%20de%20vender%20meu%20carro." target="_blank" rel="noopener">Venda seu Carro</a></li>
      </ul>
    </nav>

    <div class="site-footer__col">
      <h2 class="site-footer__title">Contato</h2>
      <ul role="list">
        <li><a href="tel:+5511912899610">(11) 91289-9610</a></li>
        <li><a href="mailto:muricymotors@gmail.com">muricymotors@gmail.com</a></li>
        <li>
          <a href="https://www.google.com/maps/search/?api=1&amp;query=Rua+Coelho+Lisboa%2C+861+-+Tatuap%C3%A9%2C+S%C3%A3o+Paulo+-+SP" target="_blank" rel="noopener">
            Rua Coelho Lisboa, 861, Tatuapé<br>São Paulo, SP
          </a>
        </li>
      </ul>
    </div>

    <div class="site-footer__col">
      <h2 class="site-footer__title">Redes Sociais</h2>
      <ul class="site-footer__social" role="list">
        <li><a href="https://www.instagram.com/muricymotors" target="_blank" rel="noopener me" aria-label="Instagram da Muricy Motors"><svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><rect x="3" y="3" width="18" height="18" rx="5"></rect><circle cx="12" cy="12" r="4"></circle><circle cx="17.2" cy="6.8" r="1.2" fill="currentColor" stroke="none"></circle></svg></a></li>
        <li><a href="https://www.youtube.com/@muricymotors" target="_blank" rel="noopener me" aria-label="YouTube da Muricy Motors"><svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><rect x="2.5" y="5" width="19" height="14" rx="4"></rect><path d="M10.5 9.2l4.6 2.8-4.6 2.8z" fill="currentColor" stroke="none"></path></svg></a></li>
        <li><a href="https://www.tiktok.com/@muricymotors" target="_blank" rel="noopener me" aria-label="TikTok da Muricy Motors"><svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M14.2 3v9.6a3.4 3.4 0 1 1-2.8-3.35"></path><path d="M14.2 3c.3 2.2 1.7 3.7 4 3.9"></path></svg></a></li>
        <li><a href="https://www.mercadolivre.com.br/pagina/muricymotors" target="_blank" rel="noopener" aria-label="Mercado Livre da Muricy Motors"><svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><ellipse cx="12" cy="12" rx="11" ry="8.2"></ellipse><path d="M2.4 10.4h2.7l2.7-1.7a2 2 0 0 1 2.1 0l1.8 1.1"></path><path d="M21.6 10.4h-2.6l-3.1-1.9a2.1 2.1 0 0 0-2.2 0l-4.2 2.7a1.3 1.3 0 0 0 1.4 2.2l2.1-1.3"></path><path d="M12.7 12.2l3.8 3.2M11 13.5l3.3 2.8M9.4 14.8l2.6 2.2"></path></svg></a></li>
        <li><a href="https://www.facebook.com/profile.php?id=61556635142470" target="_blank" rel="noopener me" aria-label="Facebook da Muricy Motors"><svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><circle cx="12" cy="12" r="9.4"></circle><path d="M13.6 20.6v-6.9h2.3l.35-2.7h-2.65V9.3c0-.78.22-1.31 1.34-1.31h1.43V5.57c-.25-.03-1.1-.11-2.09-.11-2.07 0-3.48 1.26-3.48 3.58v2h-2.34v2.7h2.34v6.86z"></path></svg></a></li>
      </ul>
    </div>
  </div>

  <div class="shell site-footer__bar">
    <p>© <?php echo esc_html( date_i18n( 'Y' ) ); ?> Muricy Motors. Todos os direitos reservados.</p>
    <p>Rua Coelho Lisboa, 861, Tatuapé, São Paulo/SP</p>
  </div>
</footer>

<a class="whatsapp-float" href="https://wa.me/5511912899610?text=Ol%C3%A1%21%20Vim%20pelo%20site%20da%20Muricy%20Motors." target="_blank" rel="noopener" aria-label="Falar com a Muricy Motors no WhatsApp">
  <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
    <path d="M12 2a10 10 0 0 0-8.6 15L2 22l5.2-1.3A10 10 0 1 0 12 2z"></path>
    <path d="M8.6 7.4c.3 0 .6 0 .8.5l.8 1.9c.1.3 0 .5-.2.7l-.6.6c.7 1.4 1.6 2.3 3 3l.6-.7c.2-.2.4-.3.7-.2l1.9.8c.4.2.5.4.5.8 0 1.2-1 2-2.1 2-3.3 0-7.3-4-7.3-7.3 0-1.1.8-2.1 1.9-2.1z" fill="currentColor" stroke="none"></path>
  </svg>
</a>

<?php wp_footer(); ?>
</body>
</html>
