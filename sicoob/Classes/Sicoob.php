<?php

require_once "SicoobToken.php";

class Sicoob
{
    // Boleto 

    public $numeroContrato; //  Número que identifica o contrato do beneficiário no Sisbr.
    public $modalidade     = 1;   //  1 SIMPLES COM REGISTRO - 5 CARNÊ DE PAGAMENTOS - 6 INDEXADA - 14 CARTÃO DE CRÉDITO
    public $numeroContaCorrente; // Número da Conta Corrente onde será realizado o crédito da liquidação do boleto.
    public $especieDocumento = "FAT";  /*Espécie do documento - CH - Cheque - DM - Duplicata Mercantil - DMI - Duplicata Mercantil Indicação - DS - Duplicata de Serviço - DSI - Duplicata Serviço Indicação - DR - Duplicata Rural - LC - Letra de Câmbio - NCC - Nota de Crédito Comercial - NCE - Nota de Crédito Exportação - NCI - Nota de Crédito Industrial - NCR - Nota de Crédito Rural - NP - Nota Promissória - NPR - Nota Promissória Rural - TM - Triplicata Mercantil - TS - Triplicata de Serviço - NS - Nota de Seguro - RC - Recibo - FAT - Fatura - ND - Nota de Débito - AP - Apólice de Seguro - ME - Mensalidade Escolar - PC - Pagamento de Consórcio - NF - Nota Fiscal - DD - Documento de Dívida - CC - Cartão de Crédito - BDP - Boleto Proposta - OU - Outros */

    public $dataEmissao; //  
    public $nossoNumero = NULL; // OPCIONAL BANCO GERA

    public $seuNumero; // Nº da sua fatura melhor para indentificar `Tamanho máximo 18` 

    public $valorTitulo;     // Valor nominal do boleto. Decimal
    public $dataVencimento; //  Formato ANO-MES-DIA 

    public $dataLimitePagamento = NULL;   // OPCIONAL
    public $valorAbatimento     = NULL;  // OPCIONAL
    public $numeroParcela       = 1;

    // desconto 
    public $tipoDesconto          = 0;      //  - 0 Sem Desconto - 1 Valor Fixo Até a Data Informada - 2 Percentual até a data informada - 3 Valor por antecipação dia corrido - 4 Valor por antecipação dia útil - 5 Percentual por antecipação dia corrido - 6 Percentual por antecipação dia útil
    public $dataPrimeiroDesconto  = NULL;  // informar se for ter desconto ANO-MES-DIA
    public $valorPrimeiroDesconto = NULL; // informar se for ter desconto
    public $dataSegundoDesconto   = NULL;
    public $valorSegundoDesconto  = NULL;
    public $dataTerceiroDesconto  = NULL;
    public $valorTerceiroDesconto = NULL;

    public $gerarPdf = true; // true gera PDF ou false nao gera

    // Multa 
    public $tipoMulta  = 0;      // 0 Isento - 2 Percentual
    public $dataMulta  = NULL;  // informar se for cobrar multa ANO-MES-DIA
    public $valorMulta = NULL; // informar se for cobrar multa Decimal

    // juros
    public $tipoJurosMora  = 3;     //  2 Taxa Mensal - 3 Isento
    public $valorJurosMora = NULL; // informar se for cobrar juros
    public $dataJurosMora = NULL;
    // Negativacao
    public $codigoNegativacao     = 3;     // 2 Negativar Dias Úteis - 3 Não Negativar
    public $numeroDiasNegativacao = NULL; // informar nº dias de for negativar

    // protesto
    public $codigoProtesto     = 3;     // 1 Protestar Dias Corridos - 3 Não Protestar
    public $numeroDiasProtesto = NULL; // informar nº dias de for protestar

    // dados pagador
    public $numeroCpfCnpj; //  CPF ou CNPJ do pagador do boleto de cobrança. `Tamanho máximo 14`
    public $nome;         // `Tamanho máximo 50`
    public $endereco;    // `Tamanho máximo 40`
    public $bairro;     // `Tamanho máximo 30`
    public $cidade;    // `Tamanho máximo 40`
    public $cep;      //`Tamanho máximo 8`
    public $uf;      // `Tamanho máximo 2`
    public $email = NULL;

    // Mensagem instrucao 
    public $mensagensInstrucao_1 = NULL;
    public $mensagensInstrucao_2 = NULL;
    public $mensagensInstrucao_3 = NULL;
    public $mensagensInstrucao_4 = NULL;
    public $mensagensInstrucao_5 = NULL;

    // Avalista 
    public $numeroCpfCnpjSacadorAvalista = NULL;  // OPCIONAL `Tamanho máximo 14` 
    public $nomeSacadorAvalista          = NULL; // OPCIONAL `Tamanho máximo 50`

    // Fim boleto // 

    public function consultarBoleto($numeroContrato, $modalidade)
    {

        $access_token  = SicoobToken::get_access_token();

        $location = "https://sandbox.sicoob.com.br/cobranca-bancaria/v1/boletos?numeroContrato={$numeroContrato}&modalidade={$modalidade}";
        //Configuracao do cabecalho da requisicao

        $headers = array();
        $headers[] = "Content-Type: application/json; charset=utf-8";
        $headers[] = "Authorization:Bearer " . $access_token;
        $headers[] = "Accept: */*";

        //curl
        $ch = curl_init($location);
        $options = array(
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_VERBOSE => true
        );
        curl_setopt_array($ch, $options);

        $response = curl_exec($ch);
        $err_status = curl_error($ch);
        curl_close($ch);

        if (!$response) {
            $response = 'Erro ao buscar boleto';
        }
        return (($response));
    }

    public function gerarBoleto()
    {

        $data = array(
            0 =>
            array(
                'numeroContrato' => $this->numeroContrato,
                'modalidade' => $this->modalidade,
                'numeroContaCorrente' => $this->numeroContaCorrente,
                'especieDocumento' => $this->especieDocumento,
                'dataEmissao' =>  $this->dataEmissao,
                'nossoNumero' => $this->nossoNumero,
                'seuNumero' =>  $this->seuNumero,
                'identificacaoBoletoEmpresa' => NULL,
                'identificacaoEmissaoBoleto' => 1,
                'identificacaoDistribuicaoBoleto' => 1,
                'valor' => $this->valorTitulo,
                'dataVencimento' =>  $this->dataVencimento . 'T00:00:00-04:00',
                'dataLimitePagamento' => $this->dataLimitePagamento,
                'valorAbatimento' =>  $this->valorAbatimento,
                'tipoDesconto' => $this->tipoDesconto,
                'dataPrimeiroDesconto' =>  $this->dataPrimeiroDesconto,
                'valorPrimeiroDesconto' => $this->valorPrimeiroDesconto,
                'dataSegundoDesconto' =>   $this->dataSegundoDesconto,
                'valorSegundoDesconto' =>  $this->valorSegundoDesconto,
                'dataTerceiroDesconto' =>  $this->dataTerceiroDesconto,
                'valorTerceiroDesconto' => $this->valorTerceiroDesconto,
                'tipoMulta' =>  $this->tipoMulta,
                'dataMulta' =>  $this->dataMulta,
                'valorMulta' =>  $this->valorMulta,
                'tipoJurosMora' => $this->tipoJurosMora,
                'dataJurosMora' => $this->dataJurosMora,
                'valorJurosMora' => $this->valorJurosMora,
                'numeroParcela' => $this->numeroParcela,
                'aceite' => TRUE,
                'codigoNegativacao' =>  $this->codigoNegativacao,
                'numeroDiasNegativacao' => $this->numeroDiasNegativacao,
                'codigoProtesto' => $this->codigoProtesto,
                'numeroDiasProtesto' => $this->numeroDiasProtesto,
                'pagador' =>
                array(
                    'numeroCpfCnpj' => $this->numeroCpfCnpj,
                    'nome' =>  $this->nome,
                    'endereco' => $this->endereco,
                    'bairro' =>  $this->bairro,
                    'cidade' => $this->cidade,
                    'cep' => $this->cep,
                    'uf' => $this->uf,
                    'email' =>
                    array(
                        0 => $this->email,
                    ),
                ),
                'sacadorAvalista' =>
                array(
                    'numeroCpfCnpjSacadorAvalista' =>  $this->numeroCpfCnpjSacadorAvalista,
                    'nomeSacadorAvalista' =>  $this->nomeSacadorAvalista,
                ),
                'mensagensInstrucao' =>
                array(
                    'mensagens' =>
                    array(
                        0 => $this->mensagensInstrucao_1,
                        1 => $this->mensagensInstrucao_2,
                        2 => $this->mensagensInstrucao_3,
                        3 => $this->mensagensInstrucao_4,
                        4 => $this->mensagensInstrucao_5,
                    ),
                ),
                'gerarPdf' => $this->gerarPdf,
            ),
        );
        $access_token  = SicoobToken::get_access_token();

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL =>  "https://sandbox.sicoob.com.br/cobranca-bancaria/v1/boletos",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_VERBOSE => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_HTTPHEADER  => array(
                "Content-type:application/json",
                "Authorization: Bearer " .  $access_token,
                "Client_id: " . 'Cliente id da sua aplicação'
            ),
            CURLOPT_POSTFIELDS => json_encode($data),
        ));
        $response = curl_exec($curl);
        $info = curl_getinfo($curl);
        $https_status =  $info['http_code'];
        curl_close($curl);

        return $response;
    }
}
