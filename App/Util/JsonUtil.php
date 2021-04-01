<?php

namespace App\Util;

use InvalidArgumentException;
use JsonException;

class JsonUtil
{    
    /**
     * MÉTODO RESPONSÁVEL POR TRATAR A RESPOSTA
     *
     * @param  mixed $response
     * @return void
     */
    public function treatArrayResponse($response)
    {
        // CRIANDO ARRAY DE DADOS VAZIO
        $datasResponse = [];
        // SETANDO RESPOSTA DE ERRO, POR PADRÃO
        $datasResponse[ConstantesGenericsUtil::STATUS] = ConstantesGenericsUtil::TYPE_ERROR;

        // VERIFICANDO SE A RESPOSTA É UM OBJETO OU UM ARRAY E SE TEM DADOS NA RESPOSTA
        if(((is_object($response) || is_array($response)) && count($response) >= 0) || strlen($response) > 0) {
            $datasResponse[ConstantesGenericsUtil::STATUS] = ConstantesGenericsUtil::TYPE_SUCESS;
            $datasResponse[ConstantesGenericsUtil::RESPONSE] = $response;
        }

        $this->parseResponseJson($datasResponse);
    }
    
    /**
     * MÉTODO RESPONSÁVEL POR PASSAR A REPOSTA DE ARRAY PARA JSON
     *
     * @param  array $datasResponse
     * @return void
     */
    public function parseResponseJson($datasResponse = [])
    {
        header('Content-type: application/json');
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Access-Control-Allow-Method: GET, POST, PUT, DELETE');
        // IMPRIMINDO RETORNO JSON
        echo json_encode($datasResponse);
    }
    
    /**
     * MÉTODO RESPONSÁVEL POR TRATAR O CORPO DA REQUISIÇÃO - RECEBE OS DADOS JSON E PASSA PARA ARRAY
     *
     * @return array
     */
    public static function treatBodyRequest()
    {
        try {
            $jsonDecodeBody = json_decode(file_get_contents('php://input'), true);
        } catch(JsonException $e) {
            throw new InvalidArgumentException(ConstantesGenericsUtil::MSG_ERR0R_JSON_EMPTY);
        }

        if(is_array($jsonDecodeBody) && count($jsonDecodeBody) > 1) {
            return $jsonDecodeBody;
        } else {
            throw new InvalidArgumentException(ConstantesGenericsUtil::MSG_ERROR_GENERIC);
        }
    }
}