<?php

namespace App\Repository;

use App\DB\Database;
use App\Util\ConstantesGenericsUtil;
use InvalidArgumentException;

class TokensAutorizeds
{
    private string $token;
    private object $Connection;
    private const TABLE = 'token_autorizados';
    
    /**
     * MÉTODO CONSTRUCT - RESPONSÁVEL POR SETAR A CONEXÃO COM O BANCO DE DADOS
     *
     * @return void
     */
    public function __construct()
    {
        $this->Connection = new Database(self::TABLE);
    }
    
    /**
     * MÉTODO RESPONSÁVEL POR VERIFICAR O TOKEN RECEBIDO
     *
     * @return void
     */
    public function verifyToken()
    {
        // RECEBENDO TOKEN ENVIADO NO HEADER DA REQUISIÇÃO
        $this->token = $this->receiveToken();

        $response = $this->Connection->getDatas('token = "' . $this->token . '" AND status = "' . ConstantesGenericsUtil::ACTIVE . '"', null, null, 'id');

        // VERIFICANDO SE O TOKEN NÃO EXISTE
        if ($response->rowCount() !== 1) {
            header('HTTP/1.1 401 Unauthorized');
            throw new InvalidArgumentException(ConstantesGenericsUtil::MSG_ERROR_TOKEN_UNAUTHORIZED);
        }

        return $response;
    }
    
    /**
     * MÉTODO RESPONSÁVEL POR RECEBER O TOKEN E TRATAR
     *
     * @return string
     */
    private function receiveToken()
    {
        $token = str_replace(['Bearer', ' '], '', getallheaders()['Authorization']);

        // VERIFICANDO SE O TOKEN FOI ENVIADO
        if ($token) {
            return $token;
        } else {
            header('HTTP/1.1 401 Unauthorized');
            throw new InvalidArgumentException(ConstantesGenericsUtil::MSG_ERROR_TOKEN_EMPTY);
        }
    }
}
