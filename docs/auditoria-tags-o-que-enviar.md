# Auditoria das tags da Muricy Motors: o que me enviar

> **Objetivo:** deixar todo o rastreamento do site 100% funcional (Google Ads, Google Analytics, Meta e Clarity).
>
> **Por que preciso disso:** eu não consigo entrar no GTM, no Google Ads nem no Meta, e o firewall da hospedagem bloqueia meu acesso ao site. Então tudo chega até mim pelo que você me enviar.
>
> **Como enviar:** anexe aqui no chat, aos poucos, na ordem dos blocos. Também pode pôr tudo numa pasta do Google Drive chamada `Muricy - tags` e me avisar, porque eu consigo ler o seu Drive.
>
> **Nunca envie senhas.** Prints e arquivos exportados não têm senha, então podem ser enviados.

---

## Comece aqui (o mínimo para eu começar)

- [ ] **1. O arquivo exportado do GTM** (seção A1). É o mais importante de todos.
- [ ] **2. O print das conversões do Google Ads** (seção B1).
- [ ] **3. Onde você viu as tags "com problema"** (seção F, pergunta 1).

Com esses três, eu já consigo dizer o que está quebrado. O resto completa o quadro.

---

## O que eu já sei (antes de você enviar nada)

| | O quê | Situação | Certeza |
|---|---|---|---|
| 🔴 | Conversões **"01 - GADS - Enviou Forms"** e **"02 - GADS/Meta - Clicou Wpp após forms"** | **Não conseguem disparar.** Elas dependem da página `/obrigado/`, que não existe no site atual: hoje o formulário abre o WhatsApp direto. | Alta: está na especificação da própria equipe |
| 🟡 | Tag do Google Ads **AW-16834399743** | Funciona, mas está carregando **duas vezes**: pelo GTM (tag "TG - Muricy Motors") e pelo snippet. Falta o teste de desligar o snippet. | Alta: visto no Tag Assistant |
| 🟡 | Cliques nos outros botões de WhatsApp | **Podem ter quebrado** quando o site foi refeito, porque textos, classes e IDs dos botões mudaram. | Hipótese: só confirmo com o arquivo do GTM |
| ⚪ | Google Analytics **G-2P8YNKJCQK** | Aparece no site. Não sei se os eventos estão chegando. | Falta informação |
| ⚪ | Meta Pixel e Microsoft Clarity | Não sei a situação. | Falta informação |
| ⚪ | Plugins GTM4WP e Site Kit | Instalados. Preciso confirmar que não duplicam nenhuma tag. | Falta informação |

---

## A. Google Tag Manager (GTM)

> Sua conta do Google **não tem acesso** ao GTM. Por isso, quem faz os itens A1 e A2 é **a pessoa da equipe que administra o GTM**. Pode encaminhar esta seção para ela.

### A1. Exportar o contêiner (arquivo .json)
1. Entrar em **tagmanager.google.com** e abrir o contêiner **GTM-TXTXKBT6**.
2. No topo, clicar em **Administrador**.
3. Na coluna do contêiner, clicar em **Exportar contêiner**.
4. Em **Escolher uma versão ou um espaço de trabalho**, escolher a versão marcada como **Publicada** (Live).
5. Clicar em **Exportar**. O navegador baixa um arquivo `.json`.
6. Me enviar esse arquivo.

> O arquivo traz todas as tags, acionadores e variáveis do GTM. Ele não tem senha.

### A2. Dar acesso de leitura para você (opcional, mas ajuda)
1. **Administrador** → coluna do contêiner → **Gerenciamento de usuários**.
2. Clicar no **+** → **Adicionar usuários**.
3. Colocar o seu e-mail e marcar a permissão **Leitura**.
4. Clicar em **Convidar**.

Com isso você consegue ver o GTM e conferir comigo, sem risco de mudar nada.

---

## B. Google Ads

### B1. Lista de conversões (print)
1. Entrar em **ads.google.com**.
2. Menu da esquerda → **Metas** (ícone de troféu) → **Conversões** → **Resumo**.
3. Tirar print da **tabela inteira** (role para baixo se precisar). Preciso ver as colunas **Ação de conversão**, **Otimização da ação**, **Origem**, **Status** e **Todas as conv.**

### B2. Tela da tag do Google (print)
> É a mesma tela onde você viu o aviso "A tag parou de enviar dados". Se souber chegar nela, pule para o passo 2.

1. Menu da esquerda → **Ferramentas** → **Gerenciador de dados** (ou **Fontes de dados**) → **Tag do Google**.
2. Tirar print da tela principal (aquela do aviso "A tag parou de enviar dados").
3. Clicar em **Domínios monitorados → Gerenciar** e tirar print da lista de domínios.

---

## C. Google Analytics (GA4)

### C1. Fluxo de dados (print)
1. Entrar em **analytics.google.com** e abrir a propriedade da Muricy.
2. Clicar na **engrenagem** (Administrador), no canto de baixo à esquerda.
3. **Coleta e modificação de dados** → **Fluxos de dados** → clicar no fluxo do site.
4. Tirar print. Quero ver o **ID da métrica** (deve ser `G-2P8YNKJCQK`) e se está recebendo dados.

### C2. Eventos (print)
1. Ainda no Administrador → **Exibição de dados** → **Eventos**.
2. Tirar print da lista, com a contagem dos **últimos 28 dias**.

---

## D. Meta (Facebook/Instagram)

### D1. Gerenciador de Eventos (2 prints)
1. Entrar em **business.facebook.com** → menu → **Gerenciador de Eventos**.
2. Clicar no **Pixel** da Muricy.
3. **Print 1:** aba **Visão geral**, com os eventos dos **últimos 7 dias**.
4. **Print 2:** aba **Diagnóstico**, com os avisos que aparecerem.

---

## E. WordPress

### E1. Lista de plugins (print)
**Plugins** → **Plugins instalados**. Tire print da lista inteira.

### E2. Lista de snippets (print + textos)
1. Abra o menu do plugin de snippets e tire print da **lista de snippets**, com os nomes e quais estão ativos.
2. Abra cada snippet que tenha a ver com rastreamento (GTM, Google, Meta, Clarity, pixel) e me envie o código.
   - **Cuidado:** o snippet **"Muricy Motors - mecanismo de preview do tema filho"** tem um **token secreto**. Se for enviar esse, troque o token por `XXXX` antes.

### E3. Site Kit (print)
**Site Kit** → **Configurações** → aba **Serviços conectados**. Tire print.

### E4. Código das páginas (arquivos)
Como o firewall me bloqueia, preciso que você salve o código de algumas páginas. Para cada página da lista abaixo:
1. Abra a página numa **janela anônima** (Ctrl + Shift + N).
2. Aperte **Ctrl + U**. Abre uma aba com o código.
3. Aperte **Ctrl + S** e salve o arquivo com o nome da página.

Páginas:
- [ ] Página inicial
- [ ] Estoque
- [ ] Uma página de veículo
- [ ] Venda seu Carro
- [ ] Simular financiamento
- [ ] Trabalhe Conosco
- [ ] Qualquer outra página que tenha botão de WhatsApp ou formulário

---

## F. Perguntas para você responder (pode ser em texto)

1. **Onde você viu as tags "com problema"?** Mande print da tela, ou diga em qual ferramenta foi (Google Ads, GA4, Meta, GTM).
2. **O que conta como lead para a Muricy?** Por exemplo: enviar o formulário, clicar no WhatsApp (em quais botões?), ligar.
3. **Quem da equipe é administrador** do GTM, do Google Ads, do GA4 e do Meta? Só os nomes ou cargos, não precisa de e-mail.
4. **O site tem aviso de cookies** (aquela faixa de LGPD)?
5. **Alguém mexeu no GTM nos últimos dias?** Principalmente na tag "TG - Muricy Motors".

---

## O que acontece depois

1. Eu analiso tudo e te entrego um **relatório da auditoria**, com cada tag marcada como funcionando, quebrada ou duplicada.
2. Para cada problema, o relatório diz **quem faz a correção** e traz o **passo a passo**:
   - a pessoa da equipe com acesso ao GTM, para mudanças no GTM;
   - você, no WordPress, para mudanças no site.
3. No fim, vem um **checklist de teste** para confirmar que tudo funciona.
