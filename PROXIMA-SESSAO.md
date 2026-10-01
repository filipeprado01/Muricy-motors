# Próxima sessão: Muricy Motors

> Para começar a próxima sessão: **"Leia o PROXIMA-SESSAO.md e continue de onde parou."**
>
> Atualizado em 01/10/2026 (segunda sessão do dia). O dono do site **não é programador**: responder em português, com passos clique por clique.

## 1. Situação

| Frente | Estado |
|---|---|
| Página `/obrigado/` + novo envio do formulário | **Código pronto e testado** neste repositório. Guia clique por clique entregue ao usuário. **Falta o usuário aplicar no painel** e rodar o checklist. |
| MCP do Elementor (`elementor-muricy-motors`) | Continua **bloqueado pelo cPGuard** (ver seção 3). Não é necessário para a `/obrigado/`. |

## 2. Página `/obrigado/`: o que foi feito

Especificação do cliente: **[`docs/instrucoes-pagina-obrigado.md`](docs/instrucoes-pagina-obrigado.md)**.

### Arquivos no repositório
- **`tema/hello-elementor-child/`**: cópia do tema filho "Muricy Motors Child".
  - O commit `e04254e` é o zip que o usuário confirmou estar no ar. O commit seguinte tem as mudanças, então `git diff e04254e` mostra exatamente o que mudou.
  - `assets/js/veiculo.js`: o submit monta a mesma mensagem, guarda em `sessionStorage` (`muricy_wpp_msg`) e vai para `/obrigado/?veiculo=<slug>` na mesma aba. O caminho é relativo de propósito: o `sessionStorage` é separado por domínio. Nenhum dado pessoal vai na URL.
  - `single-veiculos.php`: só a frase da linha 185 ("Ao enviar, você será direcionado para o nosso WhatsApp com os seus dados já preenchidos.").
- **`wpcode/pagina-obrigado.php`**: um snippet PHP **novo e separado** no WPCode ("Muricy Motors - página obrigado"). O snippet de preview **não** foi tocado.
  - Renderiza a `/obrigado/` em `template_redirect` com prioridade 1 (`require` header.php + conteúdo + footer.php + `exit`), usando as constantes `MURICY_CHILD_THEME_DIR`/`_URI` do snippet de preview. Sem elas, cai no `get_header()`/`get_footer()` do Hello Elementor.
  - Carrega `reference.css`/`main.js` com os mesmos handles do snippet de preview, mais um CSS próprio inline.
  - Botão: `<a id="btn-obrigado-whatsapp" target="_self">`, texto literal "Pedir Orçamento no WhatsApp", `text-transform: none`, SVG com `pointer-events: none`.
  - Contagem de 5 s, depois `dataLayer.push({ event: 'obrigado_redirect_whatsapp' })`, 300 ms e redirecionamento. Um clique antes cancela o intervalo e o timeout.
  - `noindex, nofollow` via `wp_robots`: o site não tem plugin de SEO (nenhum namespace yoast/rankmath/aioseo no `/wp-json/`).
  - A página fica fora do sitemap.
  - O `.whatsapp-float` fica escondido **só** nessa página (`body:has(#btn-obrigado-whatsapp)`), para não abrir uma segunda conversa durante a contagem.
  - O `<script>` inline tem `data-no-optimize="1" data-no-defer="1"`, porque o site usa **LiteSpeed Cache**.
- O snippet de preview **não está no repositório**: ele tem o token secreto. O usuário colou o código na sessão de 01/10.

### Testes feitos (todos passaram)
- Foi montada uma imitação mínima do WordPress em PHP, que renderiza o header.php/footer.php e o CSS reais do tema e o snippet real. Os testes rodaram com Playwright (Chromium), em 31 checagens:
  - formulário vazio ou e-mail inválido não redireciona;
  - mensagem idêntica à antiga;
  - URL sem dados pessoais;
  - `sessionStorage` limpo depois de usar;
  - contagem 5→1;
  - evento uma vez e WhatsApp ~300 ms depois (~5,3 s no total);
  - clique antes dos 5 s abre uma vez e não dispara o evento;
  - `innerText` exato, `text-transform: none`, clique no ícone cai no `<a>`;
  - mensagem padrão quando a página é aberta direto;
  - `noindex`;
  - sem rolagem lateral no celular;
  - fallback sem o snippet de preview.
- **Não foi testado no site real** (sem acesso: ver seção 3).

### Guia entregue
- Artifact: https://claude.ai/artifact/CiBbWRzEWu2eixAGEKRegC ("Instalação da página Obrigado"), com botões de copiar, backup no Bloco de Notas e checklist.
- **Ordem do guia:** (1) snippet no WPCode, ativo e "Executar em todos os lugares"; (2) página "Obrigado", vazia, sem Elementor, slug `obrigado`, conferindo antes se já existe ou está na Lixeira; (3) trocar o `veiculo.js` e (4) o `single-veiculos.php` no Editor de arquivos de tema, arquivo inteiro com Ctrl+A/Ctrl+V; (5) limpar o cache do LiteSpeed; (6) checklist.
- **Se o usuário voltar com problema:** pedir print. Desfazer = desativar o snippet novo e/ou colar o backup no editor de tema.

### Respostas do usuário (01/10)
- O plugin de snippets é o **WPCode**. O menu dele aparece como "Code Snippets", e o comentário do snippet de preview fala em "Code Snippets".
- O zip é a versão no ar.
- Só existe uma pasta de tema filho instalada: a tela Temas mostra só "Hello Elementor" (ativo) e "Muricy Motors Child".
- **Não sabia se existe o "Editor de arquivos de tema".** O guia manda parar e avisar se não existir. Plano B: entregar um zip com pasta raiz igual ao nome da pasta instalada, para o WordPress oferecer "Substituir o instalado pelo enviado", ou mover a lógica do formulário para um snippet.

## 3. MCP do WordPress/Elementor

### Como está montado
- `.mcp.json` define o servidor `elementor-muricy-motors` em `https://muricymotors.com.br/wp-json/elementor/mcp/`, sem senha.
- `.claude/settings.json` aprova esse servidor automaticamente.
- A senha fica numa **Credencial de API** do ambiente ("WordPress Muricy", cabeçalho `Authorization: Basic`), injetada pelo proxy.

### Bloqueio do cPGuard: o que se sabe agora
- Em 01/10, ~14h27–14h30 (Brasília), o bloqueio continuava:
  - POST (em qualquer rota, não só no MCP) recebe `405 Not Allowed` do nginx;
  - GET em `/wp-json/elementor/mcp/` e `/wp-json/wp/v2/...` recebe "Security Check Required / Firewall Block" (ALTCHA do cPGuard), com a mensagem "Seu endereço IP foi temporariamente bloqueado ou sinalizado";
  - um GET em `/wp-json/` passou (devolveu o índice, sem captcha). Foi isso que mostrou os namespaces: LiteSpeed, sem plugin de SEO e sem `code-snippets/v1`.
- **Não aparece no painel do usuário:** o cPGuard do cPanel (abas "Ataques de bots" e "WAF") não tinha nenhum registro de 01/10. O bloqueio é no nível do servidor (LFD/lista temporária da hospedagem), então o usuário não consegue liberar sozinho.
- **Saída:** abrir chamado com a hospedagem pedindo para liberar o IP ou o caminho da API. O texto do chamado foi passado ao usuário na sessão de 01/10.
- Não foi possível descobrir o IP de saída: sites de "qual é meu IP" são bloqueados pela rede do ambiente.
- **Regra:** não ficar testando. Cada tentativa pode prolongar o bloqueio. No máximo uma checagem por sessão, e só se o usuário disser que a hospedagem liberou.

## 4. Pendências
1. Usuário aplicar o guia e mandar o resultado do checklist.
2. Quem cuida do GTM precisa adicionar o evento `obrigado_redirect_whatsapp` ao acionador das tags "02 - ...". O guia avisa.
3. **www:** no snippet de preview colado no chat, a linha do www aparece como `'[www.muricymotors.com.br](https://www.muricymotors.com.br)'`. Pode ser só formatação do chat. O guia pede para abrir `https://www.muricymotors.com.br` e ver se o endereço muda para o domínio sem www. Se não mudar, corrigir essa linha para `'www.muricymotors.com.br'`.
4. **Senha de aplicativo:** recomendar trocar (apareceu no chat da primeira sessão) e recriar a credencial do ambiente.
5. Chamado com a hospedagem, se o usuário quiser o MCP de volta.
