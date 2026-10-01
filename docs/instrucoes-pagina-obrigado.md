# Tarefa: criar página /obrigado/ e redirecionar o formulário "Interessado neste veículo?"

## Contexto (leia antes de mexer)

- Site: **muricymotors.com.br**, um WordPress com o tema **Hello Elementor** e o **Elementor**.
- O site foi construído "em código": HTML/CSS/JS colado em páginas do Elementor e em snippets (existe um plugin de snippets de código instalado). Antes de alterar qualquer coisa, **descubra onde está o código do formulário**, se nos arquivos que compartilhei com você ou em algum snippet/widget, e me diga.
- O rastreamento é feito pelo **Google Tag Manager, contêiner GTM-TXTXKBT6**, que já está carregando no site.
  - **NÃO adicione, remova ou altere nenhum código do GTM, gtag, Meta Pixel ou Clarity.**
  - O plugin "GTM4WP" está instalado com o campo de contêiner vazio. **Deixe assim.**
- O GTM já tem tags prontas, montadas para um fluxo antigo que precisamos recriar:
  1. A pessoa envia o formulário e é levada para a página **`/obrigado/`**. Isso dispara a conversão "Enviou Forms" (acionador: *Page Path* = `/obrigado/`).
  2. Na página `/obrigado/`, a pessoa clica no botão de WhatsApp. Isso dispara a conversão "Clicou botão Wpp após enviar forms".

## Mapa de rastreamento: o que o site precisa "entregar" para cada tag do GTM

O site precisa gerar **exatamente** os sinais abaixo. Não invente outros nomes de evento, IDs ou textos.

| Momento | Sinal que o site gera | Tag no GTM que dispara | Conversão |
|---|---|---|---|
| Formulário válido enviado e chegada na página de obrigado | Página carregada com caminho **`/obrigado/`** | `01 - GADS - Enviou Forms no Site` (acionador *Page Path* `/obrigado/`) | Google Ads: envio do formulário |
| Clique no botão de WhatsApp da página de obrigado | Clique num **link `<a>`** com `href` começando em `https://wa.me/`, `id="btn-obrigado-whatsapp"` e texto **"Pedir Orçamento no WhatsApp"**, na página `/obrigado/` | `02 - GADS - Clicou botão Wpp após Enviar Forms no Site` e `02 - Meta - Clicou botão WPP após enviar forms` (acionador *Clicou Botão - Pedir Orçamento (Após envio do forms)*) | Google Ads "Clicou Pedir orçamento WPP – Após enviar forms" + Meta |
| Redirecionamento automático para o WhatsApp (a pessoa não clicou) | `dataLayer.push({ event: 'obrigado_redirect_whatsapp' })` | As mesmas tags `02 - ...` (o acionador do GTM será ajustado por mim para também ouvir esse evento) | Mesmas da linha acima |

Regras importantes para esses sinais funcionarem:

- O texto do botão deve estar escrito literalmente **"Pedir Orçamento no WhatsApp"**, com essa capitalização, no HTML.
  - **Não use `text-transform: uppercase`** no CSS. O GTM lê o texto já transformado e a regra deixaria de bater. Para destacar o botão, use `font-weight`, `letter-spacing` e cor, sem mudar as letras.
- O botão **não pode** ter elementos que "roubem" o clique, como `<span>` ou `<svg>` cobrindo o link. Se tiver ícone, use `pointer-events: none` nele.
- **Não** use `event.preventDefault()` no clique do botão de obrigado. O link deve navegar normalmente.

## Situação atual do formulário

Nas páginas de veículo existe a seção **"Interessado neste veículo?"**, com os campos:

- Nome * (obrigatório)
- E-mail * (obrigatório)
- Telefone * (obrigatório)
- Mensagem (opcional)
- Botão **"ENVIAR PELO WHATSAPP"**
- Texto abaixo do botão: "Ao enviar, abrimos o WhatsApp com os seus dados já preenchidos."

Hoje, ao clicar no botão, o JS monta uma mensagem com os dados e abre o WhatsApp direto (`https://wa.me/5511912899610?text=...`). Não existe nenhuma página intermediária.

## O que precisa ser feito

### 1. Alterar o envio do formulário

Quando o formulário for **válido** (campos obrigatórios preenchidos), o botão deve fazer o seguinte:

1. Montar o texto do WhatsApp **exatamente como já monta hoje**, incluindo o nome do veículo.
2. Guardar esse texto no **`sessionStorage`**, com a chave `muricy_wpp_msg`.
   - **Não coloque nome, e-mail, telefone ou mensagem na URL.** Dados pessoais na URL vão para o Google Analytics e o Google Ads, e isso viola as políticas do Google.
3. Redirecionar **na mesma aba** para `https://muricymotors.com.br/obrigado/`.
   - Se quiser, pode passar só o nome do veículo na URL, que não é dado pessoal. Exemplo: `/obrigado/?veiculo=civic-2020`.

Se o formulário for inválido, mantenha a validação que já existe e **não redirecione**.

Mantenha o resto do visual e do comportamento igual.

Ajuste também o texto abaixo do botão para algo como: "Ao enviar, você será direcionado para o nosso WhatsApp com os seus dados já preenchidos."

### 2. Criar a página `/obrigado/`

Crie uma página no WordPress com o **slug exatamente `obrigado`**, para que a URL final seja `https://muricymotors.com.br/obrigado/`.

Requisitos:

- **Visual** no mesmo estilo do site: fundo escuro e destaques em dourado, igual à seção do formulário.
- **Conteúdo:**
  - Título: "Recebemos seu interesse!"
  - Texto: "Estamos te levando para o nosso WhatsApp para finalizar o atendimento."
  - Uma contagem regressiva visível: "Abrindo o WhatsApp em 5... 4... 3..."
  - Um botão grande com o texto exato **"Pedir Orçamento no WhatsApp"** (veja as regras na seção "Mapa de rastreamento").
- **O botão precisa ser um link `<a>` de verdade**, não um `<button>` com JS:
  - `href` = `https://wa.me/5511912899610?text=` + o texto codificado com `encodeURIComponent`, lido do `sessionStorage` (`muricy_wpp_msg`).
  - Se o `sessionStorage` estiver vazio (a pessoa abriu a página direto), use a mensagem padrão: `Olá! Vim pelo site da Muricy Motors.`
  - Coloque `id="btn-obrigado-whatsapp"` no link.
  - Use `target="_self"`, para abrir na mesma aba.
- **Redirecionamento automático:**
  - Depois de **5 segundos**, redirecione para o mesmo link do botão.
  - **Imediatamente antes** de redirecionar, rode:
    ```js
    window.dataLayer = window.dataLayer || [];
    window.dataLayer.push({ event: 'obrigado_redirect_whatsapp' });
    ```
    Depois espere **300 ms** e só então faça o `window.location.href = ...`.
  - Se a pessoa clicar no botão antes dos 5 segundos, **cancele o timer**, para não abrir duas vezes e não disparar o evento.
- **Depois de usar a mensagem**, limpe o `sessionStorage` (`muricy_wpp_msg`).
- A página deve ter **`noindex`**, para não aparecer no Google. Se houver plugin de SEO, configure por ele. Se não houver, adicione `<meta name="robots" content="noindex, nofollow">` via snippet, só para essa página.
- **Não inclua** códigos de GTM, gtag ou pixel nessa página. O GTM já carrega no site inteiro.

### 3. O que NÃO fazer

- Não mexer nos outros botões de WhatsApp do site ("Chamar no WhatsApp", "Simular financiamento", "Venda seu Carro", "Trabalhe Conosco").
- Não mexer em nenhum código de rastreamento.
- Não mudar o número de WhatsApp (`5511912899610`).

## Como quero que você me responda

Eu **não sou programador**. Então:

1. Primeiro, me diga **onde está hoje o código do formulário** e o que você vai alterar.
2. Me passe as alterações **passo a passo, clique por clique**, dizendo exatamente:
   - em qual tela do WordPress entrar;
   - o que copiar, o que substituir e onde colar;
   - em qual botão clicar para salvar.
3. Antes de eu alterar algo existente, me diga **como fazer um backup** do código atual (por exemplo, copiar e colar num bloco de notas).
4. No final, me dê o **checklist de teste** abaixo.

## Checklist de teste

1. Abrir a página de um veículo, preencher o formulário e clicar em "Enviar pelo WhatsApp".
2. Confirmar que o navegador foi para `https://muricymotors.com.br/obrigado/` (olhar a barra de endereço).
3. Confirmar que a contagem aparece e que, depois de 5 segundos, o WhatsApp abre com a mensagem preenchida (nome, e-mail, telefone, veículo).
4. Repetir o teste clicando em "Pedir Orçamento no WhatsApp" antes dos 5 segundos. Deve abrir **uma vez só**.
5. Tentar enviar o formulário vazio. **Não** deve ir para `/obrigado/`.
6. Abrir `https://muricymotors.com.br/obrigado/` direto. O botão deve funcionar com a mensagem padrão.
