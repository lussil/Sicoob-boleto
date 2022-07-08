<?php

class SicoobToken
{
    public static function get_refresh_token()
    {
        $json = json_decode(file_get_contents('token.json'));
        return $json->refresh_token;
    }

    private static function set_tokens(string $newRefreshToken, string $access_token)
    {
        $json = json_decode(file_get_contents('token.json'));
        $json->refresh_token = $newRefreshToken;
        $json->access_token = $access_token;
        $json->data_gerada = date('d-m-Y H:i');
        file_put_contents('token.json', json_encode($json));
    }

    public static function get_access_token()
    {
        $json = json_decode(file_get_contents('token.json'));
        $duracao = '00:55';
        $v = explode(':', $duracao);
        $dataQueExpira = date('d-m-Y H:i', strtotime("{$json->data_gerada} + {$v[0]} hours {$v[1]} minutes"));
        $now = date('d-m-Y H:i');

        if (strtotime($dataQueExpira) < strtotime($now)) {

            $status = SicoobToken::atualizarToken();
            $json = json_decode(file_get_contents('token.json'));

            if ($status['status'] == true) {
                return  $json->access_token;
            } else {
                // echo 'Erro ao atualizar tokens - gere um novo refresh token';
            }
        } else {
            return  $json->access_token;
        };

    }

    public static function atualizarToken()
    {
        $refresh_token = SicoobToken::get_refresh_token();

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://sandbox.sicoob.com.br/token',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',	
            CURLOPT_POSTFIELDS => 'grant_type=refresh_token&refresh_token=' . $refresh_token . '&redirect_uri=[[callback_url]]',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Basic [[token_basic]] token_basic da sua aplicação',
                'Content-Type: application/x-www-form-urlencoded',
            ),
        ));

        $response = curl_exec($curl);
        $info = curl_getinfo($curl);
        $https_status =  $info['http_code'];
        curl_close($curl);

        $responseDecode =  json_decode($response);

        if ($https_status == 200) {

            SicoobToken::set_tokens($responseDecode->refresh_token, $responseDecode->access_token);

            return array("status" => true, "mensagem" => "Atualizado com sucesso");
        } else {
            return array("status" => false,  "mensagem" => 'Erro ao atualizar');
        }
    }
}
