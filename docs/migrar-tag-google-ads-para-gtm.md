# Passar a tag do Google Ads (AW-16834399743) para o GTM

> **Decisão em 13/10/2026: a tag fica no snippet.** Este guia é opcional. Use só se a equipe que cuida do GTM quiser centralizar a tag lá.

> **Resumo:** a tag funciona hoje pelo snippet. Mover para o Google Tag Manager deixa tudo num lugar só, junto das conversões "01 - GADS" e "02 - GADS".
>
> **Regra de ouro:** a tag fica no GTM **ou** no snippet, **nunca nos dois**. Com os dois, o Google conta as visitas em dobro.
>
> Tempo estimado: 10 minutos.

---

## 1. Vale a pena?

| | Snippet (como está hoje) | GTM |
|---|---|---|
| Funciona? | Sim | Sim |
| Onde se gerencia | No WordPress | No mesmo lugar das outras tags (Ads, Meta) |
| Quem consegue mexer | Quem tem acesso ao WordPress | Quem da equipe tem acesso ao GTM |
| Recomendação do Google | Para sites **sem** GTM | Para sites **com** GTM, que é o seu caso |

**Antes de começar:** se outra pessoa da equipe cuida do GTM, peça para ela fazer as seções 2 e 3. Você faz só a seção 4.

---

## 2. Criar a tag no GTM

1. Entre em **tagmanager.google.com** e abra o contêiner **GTM-TXTXKBT6**.
2. Menu da esquerda → **Tags**.
   - Se já existir uma **"Tag do Google"** com o ID **AW-16834399743**, **não crie outra**. Pule direto para a seção 4.
3. Clique em **Nova**.
4. No nome da tag, escreva: `00 - GADS - Tag do Google (AW-16834399743)`
5. Clique em **Configuração da tag** → escolha **Tag do Google**.
6. Em **ID da tag**, cole: `AW-16834399743`
7. Clique em **Acionamento** → escolha **Initialization - All Pages** (Inicialização - Todas as páginas).
8. Clique em **Salvar**.

---

## 3. Testar e publicar no GTM

1. No topo, clique em **Visualizar**.
2. Digite `https://muricymotors.com.br/` e clique em **Connect** (Conectar). O site abre numa janela nova.
3. Volte para a aba do **Tag Assistant**. Em **Tags Fired** (Tags disparadas), confira se aparece `00 - GADS - Tag do Google`.
4. Feche o modo de visualização e volte para o GTM.
5. Clique em **Enviar**.
6. Em **Nome da versão**, escreva: `Tag do Google Ads AW-16834399743`
7. Clique em **Publicar**.

---

## 4. Desligar o snippet no WordPress (logo depois de publicar)

1. Painel do WordPress → menu do plugin de snippets (**Code Snippets** no WPCode, ou **Snippets**).
2. Na lista, ache **"Google Ads - tag do Google (AW-16834399743)"**.
3. Desligue a chave para **Inativo**. Não precisa apagar: desligado, ele serve de reserva.
4. Se o site tiver plugin de cache, clique em **Limpar cache**.

---

## 5. Conferir

> **Atenção:** depois da mudança, o **Ctrl+U não mostra mais** o `AW-16834399743`. Isso é normal: o GTM carrega a tag depois que a página abre. Por isso, a conferência agora é pelo Tag Assistant.

1. Abra **tagassistant.google.com**.
2. Clique em **Add domain**, digite `https://muricymotors.com.br/` e clique em **Connect**.
3. No resumo, confira se aparece a tag **AW-16834399743** uma vez só.
4. Repita numa página de veículo.

---

## 6. Se algo der errado

Religue o snippet no WordPress (seção 4, ao contrário) e despublique no GTM:
- **Versões** → versão anterior → **Publicar**.

Assim o site volta a ser como está hoje.
