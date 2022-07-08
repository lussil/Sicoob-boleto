<?php

require_once "sicoob/Classes/Sicoob.php";

//post dados convencao
$idconvencao            = $_GET['idConvencao'];
$nmConvencao            = $_GET['nmConvencao'];
$ccRegistro             = $_GET['ccRegistro'];

$ambiente               = $_GET['ambiente'];

//post produto
$numeroPedido           = $_GET['boletoNossoNumero'];
$valorPedido            = $_GET['boletoValorTitulo'];


//post endereco comprador
$pontos                 = array("-", ".");
$compradorCep           = $_GET['compradorCep'];

$compradorLogradouro    = $_GET['compradorLogradouro'];
$compradorNumero        = $_GET['compradorNumero'];
$compradorComplemento   =  $_GET['compradorComplemento'];
$compradorBairro        =  $_GET['compradorBairro'];
$compradorCidade        =  $_GET['compradorCidade'];
$compradorUf            = $_GET['compradorUf'];

//post comprador
$compradorNome          =  $_GET['compradorNome'];
$compradorDocumento     = $_GET['compradorDocumento'];
//post boleto instrução
$boletoInstrucaoLinha1  = $_GET['boletoInstrucaoLinha1'];

// Regra para instrução de todas as convenções não espeficiadas
$boletoInstrucaoLinha2  = $_GET['boletoInstrucaoLinha2'];
$boletoInstrucaoLinha3  = $_GET['boletoInstrucaoLinha3'];
$boletoInstrucaoLinha4  = $_GET['boletoInstrucaoLinha4'];
$boletoInstrucaoLinha5  = $_GET['boletoInstrucaoLinha5'];
$boletoInstrucaoLinha6  = $_GET['boletoInstrucaoLinha6'];
$boletoInstrucaoLinha7  = $_GET['boletoInstrucaoLinha7'];
$boletoInstrucaoLinha8  = "";       // $_GET['boletoInstrucaoLinha8'];
$boletoInstrucaoLinha9  = "Sr. Caixa, somente receber o valor TOTAL deste BOLETO";       // $_GET['boletoInstrucaoLinha9'];

//post boleto
$boletoNossoNumero      = $_GET['boletoNossoNumero'];
$boletoDataEmissao      = date('Y-m-d');

$boletoInstrucaoLinha10 = "";                          // $_GET['boletoInstrucaoLinha10'];
$boletoInstrucaoLinha11 = "";                          // $_GET['boletoInstrucaoLinha11'];
$boletoInstrucaoLinha12 = "Cliente Nr. " . $ccRegistro;   // $_GET['boletoInstrucaoLinha12'];
$emailCliente           = $_GET['emailCliente'];

//////////////////////////////////////////////////////////////////////////////////////////////////////////
//                         V e n c i m e n t o   d o   B o l e t o                                      // 
//////////////////////////////////////////////////////////////////////////////////////////////////////////
//
$boletoDataVencimento   = date('d/m/Y', strtotime($_GET['dataVencimento']));    // date('Y-m-d', strtotime( $_GET['dataVencimento'] ) );
$boletoInstrucaoLinha11 = "Sr. CAIXA, Receber até 10 dias após o vencimento.";
$qtde_parcelas          = $_GET['QtdParcelas'];  // '1';


echo "<pre>";

$sicoob = new Sicoob();
// $bb = $sicoob->consultarBoleto('25546454', '1');
// echo $bb;

$sicoob->numeroContrato             = '25546454';                           // Número que identifica o contrato do beneficiário no Sisbr.
$sicoob->modalidade                 = 1;
$sicoob->numeroContaCorrente        = 0;
$sicoob->especieDocumento           = "DM";
$sicoob->dataEmissao                = date('Y-m-d');
$sicoob->nossoNumero                = "2588658";
$sicoob->seuNumero                  = "1235512";                            // Número identificador do boleto no sistema do beneficiário. `Tamanho máximo 18`
$sicoob->valor = $valorPedido;
$sicoob->dataVencimento             = $boletoDataVencimento;
// $sicoob->dataLimitePagamento     = "2018-09-20T00:00:00-03:00";          // (opcional)
$sicoob->valorAbatimento            = 1;
$sicoob->tipoDesconto               = 1;
// $sicoob->dataPrimeiroDesconto    = "2018-09-20T00:00:00-03:00";          // (opcional)
// $sicoob->dataSegundoDesconto     = "2018-09-20T00:00:00-03:00";          // (opcional)
// $sicoob->valorSegundoDesconto    = 0;                                    // (opcional)
// $sicoob->dataTerceiroDesconto    = "2018-09-20T00:00:00-03:00";          // (opcional)
// $sicoob->valorTerceiroDesconto   = 0;                                    // (opcional)
$sicoob->tipoMulta                  = 0;
// $sicoob->dataMulta               = "2018-09-20T00:00:00-03:00";          // (opcional)
// $sicoob->valorMulta              = 5;                                    // (opcional) Valor da multa. Deve ser preenchido caso o campo dataMulta seja preenchido.
$sicoob->tipoJurosMora              = 2;
// $sicoob->dataJurosMora           = "2018-09-20T00:00:00-03:00";          // (opcional) Deve ser maior que a data de vencimento do boleto e menor ou igual que data limite de pagamento.
// $sicoob->valorJurosMora          = 4;                                    // (opcional)
$sicoob->numeroParcela              = $qtde_parcelas;
// $sicoob->codigoNegativacao       = 2;                                    // (opcional)
// $sicoob->numeroDiasNegativacao   = 60;                                   // (opcional)
// $sicoob->codigoProtesto          = 1;                                    // (opcional)
// $sicoob->numeroDiasProtesto      = 30;                                   // (opcional)
// =============  comprador
$sicoob->numeroCpfCnpj              = $compradorDocumento;
$sicoob->nome                       = $compradorNome;
$sicoob->endereco                   = "{$compradorLogradouro} {$compradorNumero}";
$sicoob->bairro                     = $compradorBairro;
$sicoob->cidade                     = $compradorCidade;
$sicoob->cep                        = $compradorCep;
$sicoob->uf                         = $compradorUf;
$sicoob->email                      = $emailCliente;

$sicoob->mensagensInstrucao_1       = $boletoInstrucaoLinha1;
$sicoob->mensagensInstrucao_2       = $boletoInstrucaoLinha2;
$sicoob->mensagensInstrucao_3       = $boletoInstrucaoLinha3;
$sicoob->mensagensInstrucao_4       = $boletoInstrucaoLinha4;
$sicoob->mensagensInstrucao_5       = $boletoInstrucaoLinha5;
$sicoob->gerarPdf                   = true;

$bb = $sicoob->gerarBoleto();

var_dump($bb);
