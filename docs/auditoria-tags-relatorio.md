# Auditoria das tags da Muricy Motors: relatório

> **Data:** 13/10/2026
>
> **Base da análise:**
> - exportação do **GTM-TXTXKBT6** (versão 10) e do **GTM-MVP62MSZ** (versão 1);
> - código da página inicial, lido hoje;
> - prints do Tag Assistant.
>
> **Limite:** não consegui abrir as páginas de veículo porque o firewall da hospedagem me bloqueia. Ainda não vi o Google Ads nem o Meta por dentro.

---

## Resumo em 30 segundos

- 🔴 **Nenhuma conversão está sendo contada hoje.** Nem no Google Ads, nem no Meta. Todas as tags de conversão do GTM foram montadas para o **site antigo**, com textos e endereços que o site novo não tem mais.
- ✅ **As tags básicas funcionam:** visitas no Google Ads (remarketing), Google Analytics, pixel do Meta (visualização de página) e Clarity.
- 🟡 **O snippet que você colou é repetido.** O GTM já carrega a mesma tag do Google Ads há pelo menos 7 meses.
- ⚪ **O segundo GTM (GTM-MVP62MSZ) está vazio** e não está no site. Ele não atrapalha nada.

---

## 1. Os dois GTMs

| | GTM-TXTXKBT6 | GTM-MVP62MSZ |
|---|---|---|
| Login que enxerga | conta **videos** (logo WEB) | conta **Marketing 2** |
| Está no site? | **Sim** | Não |
| Conteúdo | 11 tags | **Nenhuma tag** ("Empty Container") |
| Última publicação | versão 10, por volta de fev/mar de 2026 | versão 1, por volta de 1º/10/2026 |
| O que fazer | É **o GTM oficial**. Todas as correções são feitas nele. | Não instalar no site. Pode ser ignorado. |

> **Sobre o aviso "Urgente / A tag parou de enviar dados":** o GTM-TXTXKBT6 está no site e o Tag Assistant o encontrou funcionando. Por isso, o aviso quase certamente é do **GTM-MVP62MSZ**, que não está instalado. Confirme em qual dos dois você viu o aviso.

---

## 2. Tag por tag (GTM-TXTXKBT6)

### Tags básicas: funcionando ✅

| Tag | O que faz | Situação |
|---|---|---|
| 00 - Google Ads \| Tag do Google (`AW-16834399743`) | Base do Google Ads: visitas e remarketing | ✅ Funciona. O Tag Assistant confirmou. |
| 00 - Vinculador de Conversões | Guarda de qual anúncio a pessoa veio | ✅ Funciona |
| 00 - [GA4] Tag (`G-2P8YNKJCQK`) | Google Analytics | ✅ Funciona. Detalhe técnico pequeno no acionador (seção 4). |
| 00 - META Ads \| PageView (pixel `576829964909195`) | Visitas no Meta | ✅ Deve funcionar. Confirmo com o print do Meta. |
| 00 - Clarity (`vn4770jq8i`) | Gravação de sessões | ✅ Deve funcionar |

### Tags de conversão: todas paradas 🔴

| Tag | O acionador espera... | O site novo tem... | Situação |
|---|---|---|---|
| **01 - GADS - Enviou Forms no Site** | Visita à página `/obrigado/` | Nenhuma página `/obrigado/`: o formulário abre o WhatsApp direto | 🔴 Nunca dispara |
| **02 - GADS - Clicou botão Wpp após Enviar Forms** | Clique num link de WhatsApp com a mensagem exata "Olá! Vim **do** site e tenho interesse nos carros da Motors." | Nenhum link com essa mensagem | 🔴 Nunca dispara |
| **02 - Meta - Clicou botão WPP após enviar forms** | O mesmo acionador acima | O mesmo | 🔴 Nunca dispara |
| **01 - Tag Botão whatsapp - Meta** | Clique em algo com o texto "Whatsapp" (com **a** minúsculo) | Os botões escrevem "Whats**A**pp", com A maiúsculo, e o GTM diferencia maiúscula de minúscula. O botão flutuante não tem texto nenhum. | 🔴 Não dispara |
| **01 - Tag Falar no Wpp - Meta** | Clique em algo com o texto "Falar no whatsapp" | Nenhum botão com esse texto | 🔴 Não dispara |
| **01 - Tag Entrar em contato - Meta** | Clique em "Entrar em contato" | Só existe dentro de um formulário antigo do Elementor ("Contato Motors"), numa janela pop-up que não tem nenhum jeito de abrir | 🔴 Na prática, não dispara |

### Botões de WhatsApp que existem hoje e não são rastreados por nenhuma tag

Os botões abaixo são da página inicial.

| Botão | Mensagem que abre no WhatsApp |
|---|---|
| Chamar no WhatsApp | "Olá! Vim pelo site da Muricy Motors." |
| Botão flutuante (só o ícone) | "Olá! Vim pelo site da Muricy Motors." |
| (11) 91289-9610, que aparece duas vezes | "Olá! Vim pelo site da Muricy Motors." |
| Simular financiamento | "Olá! Quero simular um financiamento." |
| Fale com a nossa equipe | "Olá! Estou procurando um carro específico." |
| Venda seu Carro | "Olá! Gostaria de vender meu carro." |
| Trabalhe Conosco | "Olá! Gostaria de enviar meu currículo." |

Nas páginas de veículo, tem também o formulário **"Enviar pelo WhatsApp"**.

---

## 3. Por que quebrou

O GTM foi publicado pela última vez há cerca de 7 meses, **antes de o site ser refeito**. Os acionadores procuram textos e endereços do site antigo, como "Falar no whatsapp", "Entrar em contato" e a página `/obrigado/`. O site novo usa outros textos e não tem a página `/obrigado/`, então nenhum acionador encontra o que procura.

Ninguém errou no GTM. Ele só não foi atualizado junto com o site.

---

## 4. Outras coisas que encontrei

| | O quê | Por que importa |
|---|---|---|
| 🟡 | As 4 tags de clique do Meta mandam o **mesmo nome de evento**, `1087123063496680` | Parece um código colado no lugar do nome do evento. Mesmo quando disparam, o Meta não sabe distinguir um botão do outro. O nome padrão para esse caso é **"Contact"** (contato). Antes de trocar, preciso ver no Meta se alguma campanha depende desse nome. |
| 🟡 | O snippet da tag do Google Ads repete a tag que o GTM já carrega | A tag carrega duas vezes. Pode desligar o snippet (passo 1 do plano). |
| 🟢 | A tag do GA4 usa o acionador "Consent Initialization" | Funciona, mas o Google recomenda "Initialization - All Pages". É ajuste fino, sem urgência. |
| 🟢 | O GA4 só recebe visitas, nenhum evento de clique no WhatsApp | Melhoria opcional: contar os leads também no Analytics. |
| ℹ️ | O nome **"TG - Muricy Motors"** que apareceu no Tag Assistant | É o nome da tag do Google dentro do Google Ads. No GTM, a tag se chama "00 - Google Ads \| Tag do Google". As duas são a mesma coisa. |

---

## 5. Plano para deixar 100% funcional

| Passo | O quê | Quem faz | Depende de |
|---|---|---|---|
| **1** | Desligar o snippet da tag do Google Ads e conferir no Tag Assistant se a `AW-16834399743` continua aparecendo | Você, no WordPress (2 min) | Nada. Pode fazer já. |
| **2** | No GTM-TXTXKBT6, criar um acionador novo para **qualquer clique em link do WhatsApp**, que pega todos os botões de uma vez, inclusive o flutuante. Ligar nele as tags do Meta e uma conversão do Google Ads. **Fica de fora o "Trabalhe Conosco"**: currículo não é cliente. Falta decidir se "Venda seu Carro" conta como lead. | Quem tiver o login **videos**. Eu mando o passo a passo. | Prints do Google Ads (B1) e do Meta (D1) |
| **3** | Criar a página `/obrigado/` e fazer o formulário do veículo passar por ela | Você, no WordPress. Eu mando os códigos. | Zip do tema filho, snippet de preview (com o token trocado por `XXXX`) e as 4 respostas pendentes |
| **4** | No GTM, atualizar o acionador das tags "02" para o botão novo da `/obrigado/` (`id="btn-obrigado-whatsapp"`) e para o evento `obrigado_redirect_whatsapp` | Quem tiver o login **videos** | Passo 3 pronto |
| **5** | Testar tudo com o Tag Assistant, o Google Ads e o Meta | Você, comigo | Passos 1 a 4 |

---

## 6. O que preciso agora

1. **Print das conversões do Google Ads** (seção B1 do arquivo `auditoria-tags-o-que-enviar.md`). Preciso saber quais conversões existem para escolher em qual delas ligar os cliques no WhatsApp.
2. **Prints do Meta** (seção D1): visão geral e diagnóstico. Preciso saber se alguma campanha usa o evento `1087123063496680`.
3. **Confirmar:** em qual GTM você viu o aviso "Urgente"?
4. **Para a `/obrigado/`:**
   - o zip do tema filho e o snippet de preview, com o token trocado por `XXXX`;
   - as respostas: qual é o plugin de snippets? Tem plugin de SEO? Aparece "Editor de arquivos de tema"? O zip é a versão que está no ar?
