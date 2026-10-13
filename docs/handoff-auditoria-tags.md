# Passagem de bastão: auditoria das tags da Muricy Motors

> **Para:** o Claude que tem acesso ao WordPress, ao MCP do Elementor, ao Google Tag Manager, ao Google Ads, ao GA4 e ao Meta.
> **De:** a sessão do Claude que fez a primeira auditoria em 13/10/2026, **sem** esses acessos. Ela trabalhou com prints e exportações enviados pelo usuário e com o código da página inicial, lido uma vez antes de o firewall bloquear.
> **Objetivo do usuário:** deixar o rastreamento do site **100% funcional**.
>
> **Como ler:** cada informação vem marcada com o grau de certeza:
> - ✅ **confirmado**: visto diretamente;
> - 🟡 **provável**: indício forte, falta confirmar;
> - ❓ **hipótese**: precisa ser verificada.

---

## 0. Antes de tudo: como trabalhar com este usuário

- Ele **não é programador**. Responda **em português**, com passos **clique por clique**.
- Ele se distrai fácil e gosta de tudo organizado. Entregue **em arquivo** (documento, PDF ou página), diga **que tipo de arquivo** gerou e **pergunte se ele prefere PDF, artigo ou chat**.
- **Antes de mudar qualquer coisa**, mostre o plano e espere o "ok". Antes de alterar algo que já existe, ensine a fazer **backup**.
- **Não existe agência.** Quem cuida do GTM, do Ads e do Meta é a **própria equipe** dele.
- Ele já ficou inseguro com mudanças que pareciam "voltar a quebrar". Explique **por que** cada passo é seguro e **como desfazer**.

---

## 1. Mapa do que existe

### Site
| Item | Detalhe | Certeza |
|---|---|---|
| Domínio | `muricymotors.com.br`. Um snippet redireciona o `www` para o domínio sem `www`. | ✅ |
| Tema | Pai: **Hello Elementor 3.4.7**. Filho: "Muricy Motors Child", na pasta `hello-elementor-child-1`, **inativo**. Quem carrega os templates dele é o snippet **"Tema"** (#3801), com `template_redirect` + `require` + `exit` para os veículos, a página 114 (estoque) e a página 2766 (início). | ✅ |
| Plugins relevantes | Elementor 4.3.0-beta2, Elementor Pro, UAE (Header Footer Elementor 2.9.4), JetEngine e Smart Filters (Crocoblock), **Site Kit 1.187.0**, **GTM4WP** (contêiner vazio, de propósito), **Code Snippets**, **WPCode**, **LiteSpeed Cache**, WPvivid Backup | ✅ |
| Firewall da hospedagem | **cPGuard/OPSShield**. Para IPs "suspeitos", responde `406 Not Acceptable`, `405` em POST, ou uma tela "Security Check Required / Verify you are human" (ALTCHA). Bloqueou o IP desta sessão depois de poucos acessos. | ✅ |
| WhatsApp da loja | `5511912899610` (**não alterar**) | ✅ |

### Snippets (Code Snippets)
| # | Nome | Tipo / local | Status |
|---|---|---|---|
| 3877 | Google Ads - tag do Google (AW-16834399743) | HTML, "Cabeçalho em todo o site". Criado em 13/10 pelo usuário, com orientação desta sessão. | Ativo. **Duplica** a tag do GTM (ver 3.3). |
| 3875 | Muricy Motors - página obrigado | PHP, "Executar em qualquer lugar". Criado em 1º/10. | Ativo. **Esta sessão não viu o código.** |
| 3801 | Tema | PHP. Criado em 11/09. É o "mecanismo de preview do tema filho" e **contém um token secreto**: nunca copie para chat nem arquivo. | Ativo |
| 1639, 1638 | Amostras do plugin | — | Inativos |

### IDs de rastreamento
| Ferramenta | ID | Certeza |
|---|---|---|
| GTM instalado no site | **GTM-TXTXKBT6**: conta GTM "GTM - Familia Muricy", login "videos" | ✅ |
| GTM **não** instalado | **GTM-MVP62MSZ**: conta GTM "Muricy Motors", login "Marketing 2". Tem só a v1 "Empty Container", de **1º/10/2026**: mesmo dia e mesma hora do snippet #3875 da `/obrigado/`. Talvez alguém tenha pensado em usá-lo no fluxo novo; vale perguntar ao usuário. | ✅ |
| Google Ads | `AW-16834399743`. Tag do Google "TG - Muricy Motors" (`GT-NGK5QQW2`), conta "Muricy Motors Account". O mapa de dados mostra 5 conversões. | ✅ |
| Rótulos de conversão do Ads | `KbsECLXV2f0bEP-bots-` (tag "01 - Enviou Forms") e `Iwo3CK--0YAcEP-bots-` (tag "02 - Clicou Wpp após forms") | ✅ |
| GA4 | `G-2P8YNKJCQK`. Fluxo `muricymotors.com.br`, ID `15117557291`, "Recebendo tráfego nas últimas 48 horas". | ✅ |
| Meta Pixel | `576829964909195`. Há também uma meta `facebook-domain-verification` no `<head>`. | ✅ |
| Clarity | `vn4770jq8i` | ✅ |

---

## 2. Conteúdo do GTM-TXTXKBT6 (versão 10, a publicada)

A versão 10 foi publicada em **28/02/2026**, de acordo com a data registrada na exportação, **antes de o site ser refeito**. O CSS e o JS do tema novo são de 15/09/2026. O workspace também não muda há 7 meses.

### Tags
| Tag | Tipo | Acionador | Situação no site novo |
|---|---|---|---|
| 00 - Google Ads \| Tag do Google | googtag `AW-16834399743` | All Pages | ✅ Dispara: o Tag Assistant mostrou page view e remarketing, com origem neste contêiner |
| 00 - Vinculador de Conversões | gclidw | All Pages | ✅ Deve funcionar |
| 00 - [GA4] Tag | googtag `{{GA4 I TAG}}` = `G-2P8YNKJCQK`, `send_page_view=true` | **Consent Initialization - All Pages** (o recomendado é Initialization) | ✅ Carrega |
| 00 - META Ads \| PageView | Modelo da comunidade "Facebook Pixel", PageView, pixel `{{META Ads \| ID do Pixel}}` | All Pages | 🟡 Não verificado |
| 00 - Clarity | HTML personalizado `vn4770jq8i` | All Pages | 🟡 Não verificado |
| 01 - GADS - Enviou Forms no Site | awct, rótulo `KbsECLXV2f0bEP-bots-` | PAGEVIEW, Page Path contém `/obrigado/` | 🟡 Deve funcionar **se** a `/obrigado/` estiver no ar (ver 3.2) |
| 02 - GADS - Clicou botão Wpp após Enviar Forms no Site | awct, rótulo `Iwo3CK--0YAcEP-bots-` | Acionador 20 | 🔴 Quase certamente não dispara (ver abaixo) |
| 02 - Meta - Clicou botão WPP após enviar forms | Pixel, evento custom `1087123063496680` | Acionador 20 | 🔴 Idem |
| 01 - Tag Falar no Wpp - Meta | Pixel, custom `1087123063496680`, uma vez por carregamento | CLICK, Click Text contém "Falar no whatsapp" | 🔴 Nenhum botão tem esse texto |
| 01 - Tag Botão whatsapp - Meta | Pixel, custom `1087123063496680` | CLICK, Click Text contém "Whatsapp" | 🔴 O site escreve "Whats**A**pp", e a comparação diferencia maiúsculas. O `.btn` tem `text-transform: uppercase`. O botão flutuante não tem texto. |
| 01 - Tag Entrar em contato - Meta | Pixel, custom `1087123063496680` | CLICK, Click Text contém "Entrar em contato" | 🔴 Só existe no pop-up Elementor **1269**, formulário "Contato Motors", com `"triggers":[]`: o pop-up nunca abre |

### Acionador 20: "Clicou Botão - Pedir Orçamento (Após envio do forms)"
- Tipo: LINK_CLICK.
- Condição: Click URL contém exatamente `https://wa.me/5511912899610?text=Ol%C3%A1!%20Vim%20do%20site%20e%20tenho%20interesse%20nos%20carros%20da%20Motors.`
- Nenhum link do site novo usa essa mensagem.
- A especificação da equipe para a `/obrigado/` (seção 5) prevê outro sinal: `id="btn-obrigado-whatsapp"` e o evento `obrigado_redirect_whatsapp`. **O acionador nunca foi atualizado.**

### Observações sobre o GTM
- As **4 tags de clique do Meta mandam o mesmo nome de evento custom**, `1087123063496680`. Parece um ID colado no lugar do nome. Antes de trocar para o padrão **`Contact`**, verifique no Meta se alguma **conversão personalizada** ou campanha depende desse nome.
- **Não existe nenhuma tag de evento do GA4.** O GA4 só recebe page_view e a medição otimizada.
- **Não existe conversão do Google Ads para os cliques gerais no WhatsApp.**

---

## 3. O que foi descoberto

### 3.1 🔴 O mais grave: as tags não contam a maioria dos visitantes
Fonte: print do painel do **Site Kit**, últimos 28 dias.
- O GA4 contou **13 visitantes**, uma **queda de 99,1%** em relação aos 28 dias anteriores.
  - Canais: Direct 46,2%, Paid Search 30,8%, Display 15,4%, Organic 7,7%.
  - **Eventos principais: 0** (−100%).
- O Search Console, que não depende de tag, registrou **26 cliques** e 330 impressões. Só os orgânicos já superam o total do GA4.
- Páginas mais vistas no GA4:
  1. **"Página não encontrada"** (404), com 11 visualizações;
  2. Política de privacidade (4);
  3. "Muricy Motors – Os Melhores Carros da Região" (2);
  4. "Estoque (novo)" `/estoque-novo/` (2);
  5. "Preta" (1).
- **A página inicial atual nem aparece** entre as mais vistas. O título dela é "Muricy Motors – Carros Premium com Procedência e Confiança".

**Hipóteses a verificar, em ordem:**
1. ❓ **O firewall cPGuard está desafiando ou bloqueando visitantes reais**, por exemplo: 4G com IP compartilhado, tráfego de anúncio com `gclid`/`fbclid`, robôs do Google. Se for isso, é **perda de clientes**, não só de dados.
2. ❓ **O LiteSpeed Cache atrapalha o GTM para quem não está logado**: JS adiado ou atrasado, Guest Mode, combinação de arquivos. Os testes logados parecem normais justamente porque o problema só afeta deslogados.
3. ❓ **O GTM não está em todos os tipos de página para deslogados**: páginas Elementor, templates do tema filho, `/obrigado/`, 404.
4. ❓ **O Site Kit está ligado a outra propriedade do GA4.** O Google Ads oferece "4 propriedades" para vincular, então os 13 visitantes podem ser de uma propriedade errada.
5. ❓ **Anúncios ou links antigos apontam para URLs que não existem mais (404).**

### 3.2 🟡 A `/obrigado/` provavelmente já existe
- Indícios:
  - existe o snippet **#3875 "Muricy Motors - página obrigado"**, ativo desde 1º/10;
  - o `veiculo.js` está com `ver=1790877972`, ou seja, foi alterado em 1º/10;
  - o Google Ads mostra **2 "Solicitar cotação" e 2 "Contato"** nos últimos 30 dias.
- **Ninguém nesta sessão viu o código.** Confirme se o fluxo segue a especificação da seção 5.

### 3.3 🟡 A tag do Google Ads está duplicada
O GTM já carrega a `AW-16834399743` em todas as páginas, e o snippet #3877 carrega de novo. O plano é **desligar o #3877**, mas só depois de confirmar (3.1) que o GTM carrega para visitantes **deslogados**.

### 3.4 Google Ads: metas
- Todas são "Padrão da conta", ligadas a 4 de 4 campanhas.
- Últimos 30 dias: Contatos 2, Solicitar cotações 2.

| Meta | Ações principais | Status |
|---|---|---|
| Contato | 1 | 🔴 **Configuração incorreta** |
| Solicitar cotação | 1 | 🔴 **Configuração incorreta** |
| Download | **0** | 🔴 **Configuração incorreta** |
| Engajamento | 1 | 🟡 Requer atenção |
| Visualizações resultantes no YouTube | 1 | 🟡 Requer atenção |

**O GA4 não está vinculado ao Google Ads.** O YouTube está vinculado.

### 3.5 Aviso no GTM: "Qualidade de Contêiner: Urgente"
- O aviso diz: "A tag parou de enviar dados (não detectada nas últimas 48 h)". Junto aparece "Adicionar outro administrador".
- ❓ **Não se sabe de qual contêiner é.** O mais provável é o **GTM-MVP62MSZ**, que está vazio e não instalado.

### 3.6 Como o GTM entra no site
- ✅ No `<head>` da página inicial (visitante deslogado, lido em 13/10 de manhã), o bloco `<!-- Google Tag Manager -->` do GTM-TXTXKBT6 vem logo depois da meta `facebook-domain-verification`. O `<noscript>` vem logo depois do `<body>`.
- 🟡 **De onde vem:** provavelmente do **WPCode → Header & Footer**. Pode ser também do "Código personalizado" do Elementor Pro. Confirme.
- ✅ O GTM4WP deixa só um esboço do `dataLayer` (`'set','developer_id...'`) e "placement footer". O contêiner está **vazio de propósito**: não adicione nada lá, ou o GTM carrega duas vezes.

### 3.7 Links de WhatsApp na página inicial (lidos em 13/10)
| Texto | Classe | Mensagem (`?text=`) |
|---|---|---|
| Chamar no WhatsApp | `btn btn--outline btn--lg` | "Olá! Vim pelo site da Muricy Motors." |
| (sem texto, botão flutuante) | `whatsapp-float` | idem |
| (11) 91289-9610 (×2) | — | idem |
| Simular financiamento | `btn btn--gold btn--lg` | "Olá! Quero simular um financiamento." |
| Fale com a nossa equipe | — | "Olá! Estou procurando um carro específico." |
| Venda seu Carro | — | "Olá! Gostaria de vender meu carro." |
| Trabalhe Conosco | — | "Olá! Gostaria de enviar meu currículo." |

Todos usam `target="_blank"`. Nas páginas de veículo existe ainda o formulário "Interessado neste veículo?", com o botão "Enviar pelo WhatsApp" (`#lead-form`, `assets/js/veiculo.js`).

---

## 4. O que verificar com os seus acessos (em ordem)

### Prioridade 1: visitantes e carregamento das tags (3.1)
- [ ] **Site Kit → Configurações → Analytics:** qual propriedade e qual ID de métrica estão conectados? Precisa ser `G-2P8YNKJCQK`.
- [ ] **GA4:** Relatórios → Engajamento → Páginas e telas (28 dias), e **Tempo real** enquanto o usuário abre o site num celular em 4G, deslogado.
- [ ] **Comparar com fontes independentes:**
  - total de cliques das campanhas no Google Ads (28 dias);
  - Search Console;
  - sessões no Clarity;
  - acessos no servidor (cPanel → Métricas → Visitantes ou Awstats).
- [ ] **cPGuard:** logs e desafios aplicados. Ele está desafiando IPs brasileiros de celular, o Googlebot ou o AdsBot? Ver a lista de liberados e o nível de proteção. Se precisar, combinar a mudança com a hospedagem.
- [ ] **LiteSpeed Cache → Otimização de página → JS:** "Carregar JS adiado" ou "atrasado", combinar e minificar, Guest Mode. Ver o código-fonte **deslogado** e procurar `type="litespeed/javascript"` no GTM. Se for o caso, excluir `googletagmanager`, `gtm.js`, `gtag` e `clarity` da otimização, e depois limpar o cache.
- [ ] **Confirmar que o GTM-TXTXKBT6 aparece, para deslogados,** em: início, estoque (114), uma página de veículo, `/obrigado/`, uma página Elementor qualquer e uma 404.
- [ ] **404:** em GA4 → Páginas, filtrar o título "Página não encontrada" e ver o caminho; em Search Console → Páginas → "Não encontrado (404)"; no Google Ads → Anúncios, conferir as **URLs finais**. Criar redirecionamentos 301 para as URLs antigas que ainda recebem tráfego.

### Prioridade 2: conversões
- [ ] Ler o **snippet #3875** e o `veiculo.js` e conferir a `/obrigado/` contra a especificação (seção 5). Fazer o checklist de teste da seção 5.
- [ ] **Google Ads → Metas → Conversões → Ver todas as ações de conversão:**
  - qual ação usa cada rótulo (`KbsECL...` e `Iwo3CK...`);
  - quais são **principais** e quais **secundárias**;
  - o **motivo** de cada "Configuração incorreta".
- [ ] **Meta → Gerenciador de Eventos → Pixel `576829964909195`:**
  - eventos recebidos;
  - aba Diagnóstico;
  - **conversões personalizadas** que usem o evento `1087123063496680`;
  - verificação do domínio.
- [ ] **Clarity:** sessões nos últimos 30 dias.
- [ ] **GTM:** de qual contêiner é o aviso "Urgente" (3.5)?

---

## 5. Especificação da `/obrigado/`, escrita pela equipe (resumo fiel)

**Fluxo:** formulário válido → mensagem salva no `sessionStorage` com a chave `muricy_wpp_msg` → redirecionamento **na mesma aba** para `https://muricymotors.com.br/obrigado/`.
- Pode passar só `?veiculo=<slug>` na URL.
- **Nenhum dado pessoal na URL.**
- A mensagem do WhatsApp continua igual à de antes:

```
Olá! Vim pelo site e tenho interesse no {veículo}.

Nome: {nome}
E-mail: {email}
Telefone: {telefone}

Mensagem: {mensagem}
```

A linha "Mensagem" só aparece se a pessoa preencher o campo.

**Página `/obrigado/`:**
- Título "Recebemos seu interesse!".
- Texto "Estamos te levando para o nosso WhatsApp para finalizar o atendimento.".
- Contagem regressiva de 5 segundos, visível.
- Visual escuro com dourado, igual ao do site.

**Botão:**
- É um `<a>` de verdade, com `id="btn-obrigado-whatsapp"`, `target="_self"` e o texto **literal** "Pedir Orçamento no WhatsApp".
- **Sem** `text-transform`.
- Ícone com `pointer-events: none`.
- **Sem** `preventDefault`.
- `href` = `https://wa.me/5511912899610?text=` + `encodeURIComponent(mensagem)`.
- Mensagem padrão, se o `sessionStorage` estiver vazio: "Olá! Vim pelo site da Muricy Motors.".

**Redirecionamento automático:**
- Aos 5 segundos, rodar `window.dataLayer.push({ event: 'obrigado_redirect_whatsapp' })`, esperar **300 ms** e redirecionar para o mesmo link do botão.
- Se a pessoa clicar antes, **cancelar o timer**.
- Limpar o `muricy_wpp_msg` depois de usar.

**`noindex`** na página. Nenhum código de GTM, gtag ou pixel dentro dela.

**Teste:**
1. Enviar o formulário → ir para `/obrigado/`.
2. Esperar os 5 segundos → abrir o WhatsApp com os dados.
3. Clicar antes dos 5 segundos → abrir uma vez só.
4. Enviar o formulário vazio → **não** redirecionar.
5. Abrir a `/obrigado/` direto → o botão usa a mensagem padrão.

---

## 6. Plano de correção proposto (apresente ao usuário antes)

1. **Resolver a contagem de visitantes (3.1).** Vem primeiro: pode haver clientes sendo bloqueados.
2. **GTM-TXTXKBT6:** criar as mudanças no workspace, testar no **Visualizar** e publicar uma versão com nome. **Anote o número da versão anterior** para poder voltar.
   1. Criar um acionador novo "Clique - WhatsApp (leads)":
      - tipo **Clique - Apenas links**;
      - condição `Click URL` contém `wa.me/5511912899610`;
      - **excluir o "Trabalhe Conosco"**: `Click URL` contém `curr%C3%ADculo`;
      - **"Venda seu Carro": o usuário ainda não decidiu** se conta como lead. Perguntar.
      - Usar `Click URL` ou `Click ID`, **não** `Click Text`, porque o texto diferencia maiúsculas e depende do CSS.
   2. Tags de clique do Meta: apontar para o acionador novo e trocar o evento para `Contact`, **depois** de checar as conversões personalizadas.
   3. Google Ads: conversão para os cliques gerais no WhatsApp. Usar uma ação existente ou criar uma nova, decidindo com o usuário se ela é **principal** ou **secundária**, porque isso mexe nos lances.
   4. Tags "02": trocar o acionador 20 por `Click ID` igual a `btn-obrigado-whatsapp`, **ou** evento personalizado `obrigado_redirect_whatsapp`.
   5. (Opcional) GA4: mudar o acionador para "Initialization - All Pages"; criar eventos `generate_lead` e `whatsapp_click` e marcá-los como eventos principais.
3. **Desligar o snippet #3877** depois de confirmar, deslogado, que o GTM carrega a `AW-16834399743`. Limpar o cache do LiteSpeed.
4. **Google Ads:**
   - corrigir as metas com "Configuração incorreta" (a "Download" tem 0 ações e está no padrão da conta);
   - **vincular o GA4**;
   - **adicionar um segundo administrador**.
5. **Redirecionamentos 301** para as URLs 404 que recebem tráfego.
6. **Testar tudo:** Tag Assistant e o "Testar eventos" do Meta, num navegador **deslogado** e no celular.
7. **GTM-MVP62MSZ:** deixar sem usar, ou apagar se o usuário quiser. **Nunca instalar** no site enquanto o TXTXKBT6 estiver lá.

---

## 7. Cuidados

- **Firewall:** evite rajadas de acesso ao site, inclusive pelo MCP do Elementor. O cPGuard bloqueia o IP por um tempo. Se bloquear, avise o usuário e não fique repetindo.
- **Não alterar** o número `5511912899610` nem o comportamento dos outros botões de WhatsApp no código do site. As correções de rastreamento acontecem **no GTM**.
- **GTM4WP:** continua vazio.
- **Snippet "Tema" (#3801):** tem um token secreto. Não copie.
- **Tema filho:** editar pelo **Editor de arquivos de tema**, não reenviando o zip. O zip não tem pasta raiz, e o WordPress cria `-1`, `-2`... Com isso, o snippet pode acabar carregando a pasta errada.
- **Cache:** limpar o LiteSpeed depois de cada mudança no site.
- **Metas do Google Ads:** qualquer mudança afeta os lances. Confirme com o usuário antes.

---

## 8. Referências

Arquivos no repositório `filipeprado01/Muricy-motors`, branch `claude/muricy-motors-gtag-header-87u725`:
- `docs/auditoria-tags-relatorio.md`: relatório completo, com a atualização da tarde.
- `docs/instrucoes-pagina-obrigado.md`: especificação original da equipe para a `/obrigado/`, cópia exata.
- `PROXIMA-SESSAO.md`: notas de passagem. A seção 3 está **desatualizada** sobre a `/obrigado/`.

O usuário também tem os arquivos exportados `GTM-TXTXKBT6_v10.json` e `GTM-MVP62MSZ_v1.json`. Você pode pedir a ele ou exportar de novo.

**O que esta sessão NÃO conseguiu ver:**
- páginas de veículo;
- a `/obrigado/` no ar;
- o código dos snippets;
- o Meta e o Clarity;
- as tags vistas por um visitante deslogado depois de 13/10 de manhã;
- a lista detalhada de ações de conversão do Google Ads.
