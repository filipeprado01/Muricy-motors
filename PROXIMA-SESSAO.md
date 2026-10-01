# Próxima sessão: Muricy Motors

> Para começar a próxima sessão: **"Leia o PROXIMA-SESSAO.md e continue de onde parou."**
>
> Atualizado em 01/10/2026 (segunda sessão do dia). O dono do site **não é programador**: responder em português, com passos clique por clique.

## 1. Situação

| Frente | Estado |
|---|---|
| Página `/obrigado/` + novo envio do formulário | Código pronto e testado neste repositório, **mas NÃO está no site**: a primeira tentativa de aplicar quebrou o site e foi desfeita (ver "Incidente" abaixo). O site está **como antes** de 01/10. Só retomar se o usuário pedir, **um passo por vez, com print e confirmação** antes de cada próximo passo. |
| MCP do Elementor (`elementor-muricy-motors`) | Continua **bloqueado pelo cPGuard** (ver seção 3). Não é necessário para a `/obrigado/`. |

### Incidente de 01/10 (ler antes de retomar)
- **O que aconteceu:** ao seguir o guia, o código do snippet novo (`wpcode/pagina-obrigado.php`) foi colado **dentro do snippet existente** "Muricy Motors - mecanismo de preview do tema filho", substituindo o código dele.
- **Efeito:** sem as constantes e os `template_redirect` desse snippet, o Estoque (114), o Início (2766) e as páginas de veículo voltaram para as versões antigas do Elementor: o formulário sumiu e os cards do "Escolhidos a dedo" pararam de funcionar. O usuário ficou com razão muito irritado.
- **Como foi desfeito:** o usuário colou de volta o snippet original, a partir da cópia que tinha enviado no chat. Mandei um arquivo de restauração com a linha do www corrigida, mas ele disse que usou "o que te enviei". Também voltou o `veiculo.js` e o `single-veiculos.php` pelos backups. Confirmou: "o site voltou".
- **Correção no guia (versão 3 do artifact):** o passo 1 agora tem um aviso vermelho para não mexer no snippet existente, backup desse snippet no Bloco de Notas, conferência de que o snippet novo está vazio, conferência de que a lista mostra os dois snippets ativos, conferência do Estoque/veículo depois de salvar, e um item de "Se algo der errado" para esse caso.
- **Lição:** com esse usuário, não entregar vários passos de uma vez. Guiar um passo por mensagem e pedir print do resultado antes do seguinte.
- **Pendente:** o usuário ainda pode ter, no Code Snippets, o snippet "Muricy Motors - página obrigado" (desligado) e a página "Obrigado" criada. Ambos são inofensivos com o formulário antigo. A linha do www no snippet restaurado pode ter ficado com a formatação de link do chat: testar `https://www.muricymotors.com.br` quando for oportuno.

## 2. Página `/obrigado/`: o que foi feito

Especificação do cliente: **[`docs/instrucoes-pagina-obrigado.md`](docs/instrucoes-pagina-obrigado.md)**.

### Arquivos no repositório (versão 2, depois do incidente)
- **Pedido do usuário:** "não quero que o layout seja alterado, apenas essas funções acrescentadas; o botão vai mudar, só isso". Por isso a versão 2 **não edita nenhum arquivo do tema** e mantém a frase abaixo do botão como está.
- **Por que mudou:** a versão 1 mandava trocar o `single-veiculos.php`/`veiculo.js` **inteiros** pela versão do zip. O usuário relatou "alterações de layout". A causa provável é que os arquivos no ar tinham ajustes posteriores ao zip. Não repetir troca de arquivo inteiro.
- **`tema/hello-elementor-child/`**: cópia do zip (commit `e04254e`). Hoje está idêntica ao zip. Não serve para trocar arquivos no ar sem antes comparar com o que está lá.
- **`wpcode/pagina-obrigado.php`**: um único snippet PHP novo e separado, "Muricy Motors - página obrigado". Desligá-lo devolve o site exatamente ao de hoje.
  - **Páginas de veículo:** `wp_footer` (prioridade 100) imprime um script com um listener de `submit` na **fase de captura da window**. Ele faz `preventDefault` + `stopImmediatePropagation` para o listener do `veiculo.js` não abrir o WhatsApp, monta a mesma mensagem, guarda em `sessionStorage` (`muricy_wpp_msg`) e vai para `/obrigado/?veiculo=<slug>`. Não imprime nada visível. Efeito colateral: um acionador de "Envio de formulário" do GTM nesse form deixaria de ver o submit, mas a especificação não usa esse acionador.
  - **`/obrigado/`:** `template_redirect` com prioridade 1 renderiza header.php + conteúdo + footer.php do tema e sai. Também: CSS inline, `noindex` via `wp_robots`, fora do sitemap. O botão segue as regras do GTM; a contagem é de 5 s, com push e 300 ms; um clique antes cancela.
  - **WhatsApp flutuante** na `/obrigado/`: continua visível (não esconder: pedido de não mexer no layout). Um clique nele cancela a contagem, para não abrir duas conversas.
  - `data-no-optimize`/`data-no-defer` nos scripts inline, por causa do LiteSpeed.

### Testes feitos (todos passaram)
- **Versão 2:** 36 checagens passaram. Inclui: página do veículo **idêntica pixel a pixel e no HTML visível** com e sem o snippet; com o snippet desligado, o formulário volta a abrir o WhatsApp direto; o `veiculo.js` não abre popup com o snippet ligado; o flutuante continua visível e cancela a contagem.
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
- **Guia versão 4 do artifact (a atual):** (1) página "Obrigado", vazia, sem Elementor, slug `obrigado`, conferindo antes se já existe ou está na Lixeira; (2) backup do snippet de preview no Bloco de Notas, sem salvar; mandar para a Lixeira a sobra "Muricy Motors - página obrigado" da tentativa 1; criar o snippet novo, vazio, "Executar em todos os lugares", Ativo; conferir os dois na lista; (3) limpar o cache do LiteSpeed; (4) checklist com o layout primeiro; (5) GTM. Nenhum passo mexe em arquivos do tema.
- **Aplicação em andamento, um passo por mensagem:** primeiro pedi prints da lista do Code Snippets e da busca "obrigado" em Páginas, antes de qualquer mudança.
- **O que os prints mostraram (01/10):**
  - No WPCode, o snippet de preview se chama **"Tema"** (ID 3801), "Executar em qualquer lugar", php, ativo. Os outros dois snippets são exemplos inativos: 1639 (comentários) e 1638 (mensagem após parágrafo).
  - **Não existe** sobra "Muricy Motors - página obrigado". Isso confirma que, na tentativa 1, o código foi colado dentro do "Tema".
  - A página **"Obrigado" já existe**, publicada e feita no Elementor (sobra do fluxo antigo). Não apagar nem editar: o snippet sobrepõe a renderização; desligado, volta o conteúdo Elementor dela.
  - Início (2766) já é a "Página principal".
- **Guia versão 6 (atual, conferida lendo de volta a versão publicada):** salva o snippet **Inativo**, confere a lista por print e só então liga, como combinado no chat. O código do botão Copiar é idêntico a `wpcode/pagina-obrigado.php`.
- **Guia versão 5:** usa o nome "Tema", não abre o "Tema" em nenhum momento (o backup é a cópia que o usuário usou para restaurar) e usa o rótulo "Executar em qualquer lugar".
- **Andamento:** snippet **3875 "Muricy Motors - página obrigado"** criado, "Executar em qualquer lugar", php, **inativo**. O usuário relatou que o site "quebrou de novo", mas confirmou que a chave nunca foi ligada; o site está normal e ele pediu para ignorar esse erro. Com o snippet desligado, ele não roda, então a causa foi outra (desconhecida). Próximo: ligar a chave, limpar o cache e testar.
- **GTM (01/10):** o usuário **não tem permissão de edição** no GTM ("Modo somente leitura").
  - O acionador "Page Path | /obrigado/" é "Exibição de página, Page Path contém /obrigado/". A tag 01 já funciona, e o GTM aparece no código da /obrigado/.
  - O acionador das tags 02, "Clicou Botão - Pedir Orçamento (Após envio do forms)", é "Clique - Apenas links" com a regra **Click URL contém** `https://wa.me/5511912899610?text=Ol%C3%A1!%20Vim%20do%20site%20e%20tenho%20interesse%20nos%20carros%20da%20Motors.`. O link do botão novo não contém esse trecho, então as 02 **não disparam**.
  - Nenhuma tag está pausada.
- **Em andamento, NÃO aplicado no site, aguardando o OK do usuário:** `wpcode/pagina-obrigado.php` no repositório já tem a mudança para o link sempre começar por esse endereço exato (constantes `MURICY_OBRIGADO_ABERTURA`/`MURICY_OBRIGADO_WPP_URL`). A mensagem passa a começar com "Olá! Vim do site e tenho interesse nos carros da Motors.", seguida de "Veículo: …", nome, e-mail, telefone e mensagem. O redirecionamento automático passa a fazer `btn.click()`, para o acionador de clique contar.
  - Ainda **não testado**: os testes do harness esperam a mensagem antiga e o stub precisa de `wp_json_encode`.
  - **A versão no ar** (snippet 3875) é a do commit `6dac812`.
- **Planilha de leads (pedido novo, 01/10):** gravar os dados do formulário numa planilha Google **no momento do envio**, mesmo que a pessoa desista do WhatsApp. Para as tags 02, o usuário aceita a mudança da mensagem ("o vendedor se vira").
  - A planilha "Leads do site - Muricy Motors" foi criada pelo usuário na pasta "informações" do Drive dele, com acesso restrito e compartilhada com os vendedores. Não tenho conector Google nesta sessão.
  - O script está em `google-sheets/leads-apps-script.gs` (App da Web, doPost com JSON; token `40b0ead5df0313d439c15932`, que aparece na página e serve só contra robôs). Ele cria a aba "Leads" com cabeçalho, protege contra fórmulas e foi testado com mocks.
  - Plano de envio: no submit, `navigator.sendBeacon` direto para a URL /exec do Google, sem passar pelo servidor do site, por causa do cPGuard. Será somado à mudança das tags 02 numa única troca do snippet 3875.
- **Pronto para aplicar (commit `730c5c9`, guia versão 7, conferido lendo de volta):** o snippet manda o lead por sendBeacon para o Apps Script e o link sempre começa pelo endereço do acionador 02. O usuário criou uma **segunda implantação**; a URL em uso é `...AKfycbxJnebn.../exec`. A primeira (`AKfycbw0NOM...`) foi publicada sem o código e pode ser arquivada. Falta o usuário confirmar que a URL mostra "Leads Muricy: ok" e trocar o código do snippet 3875.
- **Não verificado de verdade:** que o GTM aceita o clique programático (`btn.click()`). O teste imita o acionador "Apenas links". Conferir com o Meta Pixel Helper.
- **Guia novo (o único que vale):** https://claude.ai/artifact/KHAFTaP446M5A4jcepSSeg ("Atualização da página Obrigado"). Tem 4 passos: conferir o /exec, trocar o código do snippet 3875, limpar o cache e testar (checklist, incluindo a planilha). O guia antigo (CiBbWRz...) recebeu um aviso de desatualizado apontando para o novo.
- **Próximo passo combinado:** conferir o slug da "Obrigado" pela Edição rápida, sem salvar. Depois, criar o snippet.
- **Passo 5 (GTM):** (A) conferir que o acionador da tag "01" usa Page Path = `/obrigado/`, e não Page URL igual, por causa do `?veiculo=`; (B) conferir as condições do acionador de clique (Click Text/ID/URL/Page Path), com "Aguardar tags" recomendado; (C) criar o acionador de Evento personalizado `obrigado_redirect_whatsapp` e adicioná-lo às duas tags "02" junto com o de clique; (D) testar no Visualizar/Tag Assistant e publicar. Também recomenda "Contagem: Uma" no Google Ads. **Não tenho acesso ao GTM**: as condições reais dos acionadores existentes não foram vistas.
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
1. `/obrigado/` **não aplicada** (ver Incidente). Só retomar a pedido do usuário, um passo por vez.
2. Ajuste do GTM (passo 7 do guia). Se o usuário mandar print de um acionador com condição diferente, ajustar a orientação.
3. **www:** no snippet de preview colado no chat, a linha do www aparece como `'[www.muricymotors.com.br](https://www.muricymotors.com.br)'`. Pode ser só formatação do chat. O guia pede para abrir `https://www.muricymotors.com.br` e ver se o endereço muda para o domínio sem www. Se não mudar, corrigir essa linha para `'www.muricymotors.com.br'`.
4. **Senha de aplicativo:** recomendar trocar (apareceu no chat da primeira sessão) e recriar a credencial do ambiente.
5. Chamado com a hospedagem, se o usuário quiser o MCP de volta.
