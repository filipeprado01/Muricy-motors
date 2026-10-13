# Instalar a tag do Google Ads (AW-16834399743)

> **Resposta curta:** não cole no `header.php`. Cole num **snippet novo** do plugin de snippets, marcado para entrar no **cabeçalho (head) do site inteiro**. É assim que o Google Tag Manager já está instalado hoje.
>
> Tempo estimado: 5 minutos. Você não vai alterar nenhum código que já existe, então não precisa de backup.

---

## 1. Por que não no `header.php`

| Problema | O que acontece |
|---|---|
| O arquivo que você colou é do **tema pai, o Hello Elementor** | Na próxima atualização do tema, o WordPress substitui o arquivo e **a tag some sem aviso**. |
| As páginas principais **não usam esse arquivo** | A página inicial, o estoque e as páginas de veículo usam o cabeçalho do tema filho "Muricy Motors Child", carregado pelo snippet. Conferi no site no ar em 13/10/2026. Colando só nesse arquivo, a tag ficaria fora justamente das páginas com o formulário. |
| O snippet resolve os dois casos | O plugin coloca o código no `<head>` de **todas** as páginas, venham do cabeçalho do tema pai ou do filho. |

---

## 2. Antes de começar: conferir o GTM (1 minuto)

O site já tem o Google Tag Manager (GTM-TXTXKBT6). Se o GTM já tiver essa mesma tag, colar o código de novo faria o Google contar as visitas em dobro.

1. Entre em **tagmanager.google.com** e abra o contêiner **GTM-TXTXKBT6**.
2. Clique em **Tags**.
3. Procure uma tag do tipo **"Tag do Google"** com o ID **AW-16834399743**.
   - **Não existe:** siga para o passo 3.
   - **Existe:** pare aqui e me avise. A tag já está no site pelo GTM.

> As tags de conversão "01 - GADS..." e "02 - GADS..." **não** contam. Elas são de outro tipo ("Acompanhamento de conversões do Google Ads").

---

## 3. Descobrir qual é o seu plugin de snippets

No painel do WordPress, olhe o **menu da esquerda**:

| Se aparece no menu... | O plugin é... | Siga o |
|---|---|---|
| **Code Snippets** (com o logo do WPCode) | WPCode | Caminho A |
| **Snippets** | Code Snippets | Caminho B |

---

## 4A. Caminho A: WPCode

1. Menu da esquerda → **Code Snippets** → **+ Add Snippet** (Adicionar snippet).
2. Passe o mouse em **Add Your Custom Code (New Snippet)** e clique em **+ Add Custom Snippet**.
3. Escolha o tipo **HTML Snippet**.
4. No título, escreva: `Google Ads - tag do Google (AW-16834399743)`
5. Na caixa **Code Preview**, cole **exatamente** este código:

```html
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=AW-16834399743"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'AW-16834399743');
</script>
```

6. Desça até **Insertion** (Inserção):
   - **Insert Method:** `Auto Insert`
   - **Location:** `Site Wide Header`
7. No topo da tela, mude a chave de **Inactive** para **Active**.
8. Clique em **Save Snippet** (Salvar snippet).

---

## 4B. Caminho B: Code Snippets

1. Menu da esquerda → **Snippets** → **Add New** (Adicionar novo).
2. No título, escreva: `Google Ads - tag do Google (AW-16834399743)`
3. Deixe o tipo em **PHP**, que é o padrão.
4. Na caixa de código, cole **exatamente** este código. Ele é diferente do Caminho A porque este plugin pede PHP:

```php
add_action( 'wp_head', function () {
	?>
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=AW-16834399743"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'AW-16834399743');
</script>
	<?php
}, 1 );
```

5. Abaixo da caixa, marque **Only run on site front-end** (Executar apenas no front-end do site).
6. Clique em **Save Changes and Activate** (Salvar alterações e ativar).

> Se o código tiver algum erro, o plugin **não salva** e mostra um aviso. Nada vai para o ar.

---

## 5. Testar

1. Se o site tiver plugin de cache (LiteSpeed Cache, WP Rocket, W3 Total Cache...), clique em **Limpar cache**.
2. Abra **muricymotors.com.br** numa **janela anônima**.
3. Aperte **Ctrl + U** para ver o código-fonte.
4. Aperte **Ctrl + F** e procure `AW-16834399743`.
   - **2 resultados:** certo (um no endereço do script, outro na linha `config`).
   - **4 resultados:** a tag entrou duas vezes. Desative o snippet e me avise.
   - **0 resultados:** limpe o cache e tente de novo.
5. Na mesma tela, procure `GTM-TXTXKBT6`. Ele deve continuar aparecendo.
6. Repita os passos 3 a 5 numa **página de veículo**.
7. Opcional: abra **tagassistant.google.com**, digite o endereço do site e confira se a tag **AW-16834399743** aparece.

---

## 6. Se algo der errado

Desative o snippet: menu do plugin → lista de snippets → desligue a chave do snippet "Google Ads - tag do Google". O site volta a ser como era antes.

---

## 7. Se mesmo assim quiser colar no `header.php` (não recomendado)

O lugar exato é **entre** a linha do `<link rel="profile"...>` e a linha do `<?php wp_head(); ?>`:

```php
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<!-- Google tag (gtag.js) -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=AW-16834399743"></script>
	<script>
	  window.dataLayer = window.dataLayer || [];
	  function gtag(){dataLayer.push(arguments);}
	  gtag('js', new Date());

	  gtag('config', 'AW-16834399743');
	</script>
	<?php wp_head(); ?>
```

Só que, por esse caminho, você precisaria:
- colar nos **dois** arquivos `header.php`, o do tema pai e o do tema filho;
- colar **de novo** a cada atualização do Hello Elementor.

---

## 8. O aviso "A tag parou de enviar dados" no Google

O aviso aparece porque o site ficou sem essa tag: até 13/10/2026, nenhuma página tinha o `AW-16834399743`. O Google confere as **últimas 48 horas**, então o aviso costuma sumir sozinho em **1 a 2 dias**, conforme as visitas chegam. Não precisa fazer nada nele.

**Para confirmar na hora, sem esperar o aviso:**
1. Abra **tagassistant.google.com**.
2. Clique em **Adicionar domínio**, digite `https://muricymotors.com.br/` e clique em **Conectar**. O site abre numa aba nova.
3. Volte para a aba do Tag Assistant. No topo, deve aparecer **AW-16834399743**, além do **GTM-TXTXKBT6**.
4. Se não aparecer, desligue o bloqueador de anúncios do navegador e tente de novo.
5. Se aparecer **"A depuração não está ativada para o GTM-TXTXKBT6 / Você não tem acesso a este contêiner"**: não é erro no site. Só quer dizer que a sua conta do Google não tem acesso ao GTM. Clique em **AW-16834399743** na barra do topo para ver a tag do Google Ads, que não depende de acesso.

**Se o aviso continuar depois de 3 dias:** faça o teste da janela anônima (seção 5). Se o `AW-16834399743` não aparecer para quem não está logado, limpe o cache do site.

**O outro item do aviso, "Adicionar outro administrador":** não tem a ver com a tag. É o Google sugerindo uma segunda pessoa da equipe como administradora, para vocês não perderem o acesso se a conta de alguém der problema. Vale fazer quando der.
