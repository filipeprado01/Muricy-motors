# Próxima sessão: Muricy Motors

> Para começar a próxima sessão: **"Leia o PROXIMA-SESSAO.md e continue de onde parou."**
>
> Atualizado em 01/10/2026. O dono do site **não é programador**: responder em português, com passos clique por clique.

## 1. Situação

| Frente | Estado |
|---|---|
| MCP do Elementor (`elementor-muricy-motors`) | Configurado e testado: login aceito, 20 ferramentas. Logo depois foi **bloqueado pelo firewall cPGuard** da hospedagem (ver seção 2). |
| Página `/obrigado/` + novo envio do formulário | Código analisado e plano montado. **Nada foi alterado no site.** Faltam 4 respostas (seção 4) e os arquivos (seção 5). |

## 2. MCP do WordPress/Elementor

### Como está montado
- **`.mcp.json`** (neste repositório) define o servidor `elementor-muricy-motors` em `https://muricymotors.com.br/wp-json/elementor/mcp/`, **sem senha**.
- **`.claude/settings.json`** aprova esse servidor automaticamente (`enabledMcpjsonServers`).
- **A senha fica fora da sessão**, numa **Credencial de API** do ambiente de nuvem:
  - Nome: "WordPress Muricy". Site: `muricymotors.com.br`.
  - Cabeçalho `Authorization` com prefixo `Basic`.
  - O proxy da Anthropic acrescenta esse cabeçalho sozinho, e a credencial também libera o acesso ao domínio. Por isso o "Acesso à rede" continua em **Confiável**.
- **Descartado:** variável de ambiente e script de configuração (a tela avisa para não pôr senhas ali). O `~/.claude.json` da sessão anterior não importa mais.

### O que foi testado
- `initialize` respondeu HTTP 200 (servidor "Elementor MCP" v1.0.0).
- `tools/list` devolveu 20 ferramentas: `elementor-get-page-structure`, `elementor-update-page-settings`, `elementor-create-page`, `elementor-create-preview-link`, `elementor-publish-document`, `elementor-manage-global-variable`, `elementor-manage-classes`, `elementor-manage-default-styles`, `elementor-get-default-styles`, `elementor-reorder-classes`, `elementor-get-widget-schema`, `elementor-list-widget-schemas`, `elementor-build-composition`, `elementor-manage-elements`, `elementor-list-assets`, `elementor-list-resources`, `elementor-read-resource`, `elementor-list-components`, `elementor-manage-component`, `elementor-list-posts`.
- O MCP mexe só no conteúdo do Elementor. Ele **não** lê nem edita arquivos do tema nem snippets.

### Problema em aberto: firewall cPGuard da hospedagem
- **Sintoma:**
  - Pedidos GET recebem uma página HTML "Security Check Required / Verify you are human" (captcha ALTCHA, da OPSShield/cPGuard).
  - Pedidos POST recebem `405 Not Allowed` (nginx).
  - No Claude Code aparece `HTTP 405: Error POSTing to endpoint`.
- **Causa:** depois de uns 15 pedidos de teste em poucos minutos, o cPGuard marcou o IP de saída (rede da Anthropic) como "atividade incomum".
- **Solução:**
  - Esperar: o bloqueio é temporário.
  - Ou, no cPanel: **cPGuard → logs → whitelist do IP**. O IP pode mudar entre sessões.

### Primeiros passos na próxima sessão
1. Rodar `claude mcp list` **uma vez**. Se o servidor aparecer conectado, as ferramentas `mcp__elementor-muricy-motors__*` estarão disponíveis.
2. Se voltar 405 ou a página de captcha, avisar o usuário e **não repetir**, porque cada tentativa pode prolongar o bloqueio.
3. Usar o MCP com moderação. Rajadas de chamadas podem disparar o cPGuard de novo.
4. A senha de aplicativo atual apareceu no chat da sessão anterior. Recomendar gerar uma nova (WordPress → Usuários → Perfil → Senhas de aplicativo) e **recriar** a credencial, que não pode ser editada depois de salva.

## 3. Tarefa: página `/obrigado/` + redirecionamento do formulário

A especificação completa do cliente está em **[`docs/instrucoes-pagina-obrigado.md`](docs/instrucoes-pagina-obrigado.md)** (cópia exata). Ler antes de tudo. As regras do GTM são rígidas:
- texto literal "Pedir Orçamento no WhatsApp", sem `text-transform`;
- `<a id="btn-obrigado-whatsapp">` com `target="_self"`;
- sem `preventDefault`;
- `dataLayer.push({ event: 'obrigado_redirect_whatsapp' })`, esperar 300 ms e só então redirecionar;
- nenhum dado pessoal na URL.

### Onde está o código hoje
O código fica no tema filho "Muricy Motors Child", na pasta `wp-content/themes/hello-elementor-child*`. O tema está **inativo**: quem carrega os arquivos dele é o snippet.

- **`single-veiculos.php`, linhas ~153–189:** seção "Interessado neste veículo?".
  - Formulário: `<form class="form" id="lead-form" data-vehicle="<?php the_title_attribute(); ?>" data-phone="5511912899610">`.
  - Campos: `nome`, `email` e `telefone`, todos `required`, e `mensagem`, opcional.
  - Botão: `<button class="btn btn--gold form__submit" type="submit">` com ícone SVG e o texto "Enviar pelo WhatsApp".
  - Linha ~185: `<p class="form__note">Ao enviar, abrimos o WhatsApp com os seus dados já preenchidos.</p>`.
- **`assets/js/veiculo.js`, linhas ~103–131:** no `submit`, faz `e.preventDefault()`, monta a mensagem e chama `window.open('https://wa.me/' + phone + '?text=' + encodeURIComponent(...), '_blank', 'noopener')`.
  - A mensagem atual, que deve continuar igual (o veículo vem de `form.dataset.vehicle` e o padrão é `'um veículo do site'`):
    ```
    Olá! Vim pelo site e tenho interesse no {veículo}.

    Nome: {nome}
    E-mail: {email}
    Telefone: {telefone}

    Mensagem: {mensagem}   ← só se preenchida (com linha em branco antes)
    ```
  - **Validação:** só a nativa do navegador (`required`, `type="email"`). O `submit` só dispara com o formulário válido.
- **`assets/css/reference.css`:**
  - `.btn` (~linha 99) tem `text-transform: uppercase`. Por isso o print mostra "ENVIAR PELO WHATSAPP". O botão da `/obrigado/` precisa de `text-transform: none` explícito.
  - `.btn svg` (~linha 1285) não tem `pointer-events: none`. Acrescentar no ícone do botão novo.
  - Cores: `--gold #e5a427`, `--gold-light #f2c25c`, `--gold-dark #c6820f`, `--text #f4f4f4`, `--text-dim #a8a8a8`, `--text-ink #0a0a0a`.
  - O botão dourado usa `linear-gradient(100deg, var(--gold-light), var(--gold) 45%, var(--gold-dark))`.
- **`header.php` / `footer.php`:** chamam `wp_head()`, `wp_body_open()` e `wp_footer()`.
  - O GTM **não** está escrito no tema: entra por esses ganchos (plugin ou snippet).
  - Reaproveitar o header e o footer garante o GTM na `/obrigado/`. Mesmo assim, conferir no código-fonte que `GTM-TXTXKBT6` aparece.

### O snippet "Muricy Motors - mecanismo de preview do tema filho"
O usuário colou o snippet no chat. Ele **não** está neste repositório porque contém o token secreto do preview: pedir para colar de novo. Pontos que importam:
- **Constantes:**
  - `MURICY_CHILD_THEME_DIR`/`_URI`: auto-detecta a pasta do tema com `glob`, pegando a primeira que tem `single-veiculos.php` + `inc-helpers.php`.
  - `MURICY_ESTOQUE_LIVE_PAGE_ID = 114`, `MURICY_INICIO_PAGE_ID = 2766` e `MURICY_ESTOQUE_PAGE_ID = 2983` (só preview).
- **Carregamento de CSS/JS:** o `wp_enqueue_scripts` (prioridade 20) carrega fontes, `reference.css`, `main.js` e `veiculo.js` só em veículos, na 114, na 2766 ou com preview ativo. **Incluir a `/obrigado/` nessa condição.**
- **Troca de template:** um `template_redirect` com prioridade 1 faz `require` + `exit` (veículos, 114, 2766), e há também um `template_include` 99999. Usar o mesmo padrão `require` + `exit` na `/obrigado/`, porque o Theme Builder do Elementor pode se impor por cima.
- **Também faz:** redireciona o www para o domínio sem www, ajusta o título da Home, remove o `wp_generator`, bloqueia o redirecionamento de `_wp_old_slug` para `/estoque-novo/` e tira os usuários do sitemap.
- **Conferir o www:** no texto colado, o redirecionamento do www aparecia como `'[www.muricymotors.com.br](https://www.muricymotors.com.br)'`. Deve ser formatação do chat, mas pedir para conferir que no original está `'www.muricymotors.com.br'`. Se não estiver, o redirecionamento do www não funciona.

### Plano proposto (ainda **não** aprovado pelo usuário)
1. **`veiculo.js`:**
   - Manter a montagem da mensagem.
   - Trocar o `window.open` por `sessionStorage.setItem('muricy_wpp_msg', texto)` dentro de `try/catch`, seguido de `window.location.href = 'https://muricymotors.com.br/obrigado/?veiculo=<slug>'`.
   - O slug sai de `location.pathname` e não é dado pessoal.
   - Se o `sessionStorage` falhar, redirecionar mesmo assim: a página usa a mensagem padrão.
2. **`single-veiculos.php`, linha ~185:** novo texto, "Ao enviar, você será direcionado para o nosso WhatsApp com os seus dados já preenchidos."
3. **Snippet:**
   - Renderizar a `/obrigado/` com `is_page('obrigado')`: `require` header.php, conteúdo + JS inline, `require` footer.php e `exit`.
   - Incluir a página na condição de CSS/JS.
   - `noindex` pelo filtro `wp_robots`, se não houver plugin de SEO.
   - O conteúdo fica dentro do snippet porque o Editor de arquivos de tema **não cria arquivos novos**.
4. **JS da `/obrigado/`:**
   - Ler o `sessionStorage` (`muricy_wpp_msg`); se estiver vazio, usar o padrão `Olá! Vim pelo site da Muricy Motors.`.
   - Montar o `href` do botão e limpar a chave.
   - Contagem de 5 segundos visível.
   - No fim da contagem: `dataLayer.push`, 300 ms e redirecionamento.
   - Se a pessoa clicar antes, cancelar o timer (sem evento e sem abrir duas vezes).
5. **WordPress:** o usuário cria a página "Obrigado" com o slug `obrigado` (Páginas → Adicionar nova).
6. **Entrega:** passo a passo clique por clique, com **backup antes** (copiar o código atual num bloco de notas), e o checklist de teste da especificação no final.

### Cuidados ao aplicar
- **Editar no painel, não reenviar o zip:** preferir Aparência → Editor de arquivos de tema → tema "Muricy Motors Child".
  - O zip não tem pasta raiz, por isso o WordPress cria `hello-elementor-child-1`, `-2`...
  - Com a pasta antiga ainda instalada, o `glob` do snippet pode continuar achando a antiga, que vem antes na ordem alfabética.
- **Cache:** o `veiculo.js` é versionado por `filemtime`, então o navegador pega a versão nova sozinho. Se houver plugin de cache de página, limpar o cache depois de mudar o PHP.
- **Não mexer** em GTM, gtag, Meta Pixel, Clarity, no GTM4WP (fica vazio), nos outros botões de WhatsApp nem no número `5511912899610`.

## 4. Perguntas pendentes para o usuário
1. O plugin de snippets é o **Code Snippets** ou o **WPCode**? Os comentários do código citam os dois.
2. Tem plugin de SEO instalado (Yoast, Rank Math, All in One SEO)?
3. Aparece **Aparência → Editor de arquivos de tema**?
4. O zip enviado é a versão que está no ar hoje?

## 5. Pedir no começo da próxima sessão
- **Reenviar o zip do tema filho** e **colar de novo o snippet**. Os dois ficaram só na sessão anterior.
- Responder as 4 perguntas acima.
