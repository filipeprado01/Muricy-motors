/**
 * Muricy Motors - recebe os leads do formulario "Interessado neste veiculo?"
 *
 * Google Apps Script ligado a planilha "Leads do site - Muricy Motors"
 * (Extensoes > Apps Script). Publicado como App da Web (Executar como: Eu;
 * Quem pode acessar: Qualquer pessoa). O snippet "Muricy Motors - pagina
 * obrigado" do WPCode manda os dados para o endereco /exec no momento em que
 * a pessoa envia o formulario, antes de ela ir para a /obrigado/. Assim o
 * lead fica gravado mesmo que ela desista de mandar a mensagem no WhatsApp.
 *
 * O TOKEN abaixo tem que ser igual ao do snippet. Ele aparece no codigo da
 * pagina, entao serve so para barrar robos que nao leem o site, nao e senha.
 */

var TOKEN = '40b0ead5df0313d439c15932';
var ABA = 'Leads';
var COLUNAS = ['Data/hora', 'Nome', 'E-mail', 'Telefone', 'Veículo', 'Mensagem', 'Página'];

function doPost(e) {
  var dados;
  try {
    dados = JSON.parse(e.postData.contents);
  } catch (err) {
    return resposta('formato invalido');
  }
  if (!dados || dados.token !== TOKEN) return resposta('token invalido');

  var linha = [
    new Date(),
    limpar(dados.nome, 200),
    limpar(dados.email, 200),
    limpar(dados.telefone, 50),
    limpar(dados.veiculo, 200),
    limpar(dados.mensagem, 2000),
    limpar(dados.pagina, 500)
  ];
  if (!linha[1] && !linha[2] && !linha[3]) return resposta('vazio');

  // Trava para dois leads chegando no mesmo segundo nao se sobrescreverem.
  var trava = LockService.getScriptLock();
  trava.waitLock(10000);
  try {
    var aba = pegarAba();
    aba.appendRow(linha);
    aba.getRange(aba.getLastRow(), 1).setNumberFormat('dd/MM/yyyy HH:mm:ss');
  } finally {
    trava.releaseLock();
  }
  return resposta('ok');
}

// Abrir o endereco /exec no navegador mostra esta mensagem: serve para
// conferir que a publicacao deu certo.
function doGet() {
  return resposta('Leads Muricy: ok');
}

function pegarAba() {
  var planilha = SpreadsheetApp.getActiveSpreadsheet();
  var aba = planilha.getSheetByName(ABA) || planilha.insertSheet(ABA, 0);
  if (aba.getLastRow() === 0) {
    aba.appendRow(COLUNAS);
    aba.getRange(1, 1, 1, COLUNAS.length).setFontWeight('bold');
    aba.setFrozenRows(1);
  }
  return aba;
}

// Texto simples e curto. Um valor comecando com = + - ou @ viraria formula
// na planilha; o apostrofo na frente faz o Sheets tratar como texto.
function limpar(valor, max) {
  var texto = String(valor == null ? '' : valor).trim().slice(0, max);
  return /^[=+\-@]/.test(texto) ? "'" + texto : texto;
}

function resposta(texto) {
  return ContentService.createTextOutput(texto).setMimeType(ContentService.MimeType.TEXT);
}
