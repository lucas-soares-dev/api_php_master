<?php

namespace App\Validator;

use App\Service\UserService;
use App\Util\ConstantesGenericsUtil;
use App\Util\JsonUtil;
use InvalidArgumentException;

class RequestValidator
{
    private array $request;
    private array $datasRequest;

    private const GET = 'GET';
    private const DELETE = 'DELETE';
    private const USUARIOS = 'usuarios';

    
    /**
     * MÉTODO CONSTRUCT, RESPONSÁVEL POR SETAR AS ROTAS
     *
     * @param  mixed $request
     * @return void
     */
    public function __construct($request)
    {
        // ATRIBUINDO REQUEST RECEBIDA AO ATRIBUTO CORRESPONDENTE
        $this->request = $request;
    }
    
    /**
     * MÉTODO RESPONSÁVEL POR PROCESSAR O TIPO DA REQUISIÇÃO
     *
     * @return
     */
    public function proccessRequest()
    {
        $response = utf8_encode(ConstantesGenericsUtil::MSG_ERROR_RECURSE_NOT_EXIST);

        if(in_array($this->request['method'], ConstantesGenericsUtil::TYPE_REQUEST, true)) {
            $response = $this->directRequest();
        } else {
            throw new InvalidArgumentException(ConstantesGenericsUtil::MSG_ERROR_REQUEST_NOT_EXISTS);
        }

        return $response;
    }
    
    /**
     * MÉTODO RESPONSÁVEL POR DIRECIONAR A REQUISIÇÃO
     *
     * @return
     */
    private function directRequest()
    {
        $method = $this->request['method'];

        if($method !== self::GET && $method !== self::DELETE) {
            $this->datasRequest = JsonUtil::treatBodyRequest();
        }
        
        return $this->$method();
    }
    
    /**
     * MÉTODO RESPONSÁVEL POR VALIDAR A ROTA DO GET
     *
     * @return object
     */
    private function GET()
    {
        // SETAR UM RETORNO DE ERRO DE ROTA, COMO PADRÃO
        $response = utf8_encode(ConstantesGenericsUtil::MSG_ERROR_TYPE_ROUTE);

        // VERIFICAR SE A ROTA É VÁLIDA
        if(in_array($this->request['route'], ConstantesGenericsUtil::TYPE_GET, true)) {
            switch($this->request['route']) {
                case self::USUARIOS:
                    // INSTÂNCIANDO A CLASSE DE SERVIÇOS
                    $objUserService = new UserService($this->request);
                    $response = $objUserService->validateGet();
                    break;

                default:
                    throw new InvalidArgumentException(ConstantesGenericsUtil::MSG_ERROR_RECURSE_NOT_EXIST);
            }
        }

        return $response;
    }
    
    /**
     * MÉTODO RESPONSÁVEL POR VALIDAR A ROTA DE DELEÇÃO
     *
     * @return string
     */
    private function DELETE()
    {
        // SETAR UM RETORNO DE ERRO DE ROTA, COMO PADRÃO
        $response = utf8_encode(ConstantesGenericsUtil::MSG_ERROR_TYPE_ROUTE);

        // VERIFICAR SE A ROTA É VÁLIDA
        if(in_array($this->request['route'], ConstantesGenericsUtil::TYPE_DELETE, true)) {
            switch($this->request['route']) {
                case self::USUARIOS:
                    // INSTÂNCIANDO A CLASSE DE SERVIÇOS
                    $objUserService = new UserService($this->request);
                    $response = $objUserService->validateDelete();
                    break;

                default:
                    throw new InvalidArgumentException(ConstantesGenericsUtil::MSG_ERROR_RECURSE_NOT_EXIST);
            }
        }

        return $response;
    }
    
    /**
     * MÉTODO RESPONSÁVEL POR VALIDAR A ROTA DE ENVIO DE DADOS
     *
     * @return array
     */
    private function POST()
    {
        // SETAR UM RETORNO DE ERRO DE ROTA, COMO PADRÃO
        $response = utf8_encode(ConstantesGenericsUtil::MSG_ERROR_TYPE_ROUTE);

        // VERIFICAR SE A ROTA É VÁLIDA
        if(in_array($this->request['route'], ConstantesGenericsUtil::TYPE_POST, true)) {
            switch($this->request['route']) {
                case self::USUARIOS:
                    // INSTÂNCIANDO A CLASSE DE SERVIÇOS
                    $objUserService = new UserService($this->request);
                    $objUserService->setDatasBodyRequest($this->datasRequest);
                    $response = $objUserService->validatePost();
                    break;

                default:
                    throw new InvalidArgumentException(ConstantesGenericsUtil::MSG_ERROR_RECURSE_NOT_EXIST);
            }
        }

        return $response;
    }

    /**
     * MÉTODO RESPONSÁVEL POR VALIDAR A ROTA DE ATUALIZAÇÃO DE DADOS
     *
     * @return string
     */
    private function PUT()
    {
        // SETAR UM RETORNO DE ERRO DE ROTA, COMO PADRÃO
        $response = utf8_encode(ConstantesGenericsUtil::MSG_ERROR_TYPE_ROUTE);

        // VERIFICAR SE A ROTA É VÁLIDA
        if(in_array($this->request['route'], ConstantesGenericsUtil::TYPE_PUT, true)) {
            switch($this->request['route']) {
                case self::USUARIOS:
                    // INSTÂNCIANDO A CLASSE DE SERVIÇOS
                    $objUserService = new UserService($this->request);
                    $objUserService->setDatasBodyRequest($this->datasRequest);
                    $response = $objUserService->validatePut();
                    break;

                default:
                    throw new InvalidArgumentException(ConstantesGenericsUtil::MSG_ERROR_RECURSE_NOT_EXIST);
            }
        }

        return $response;
    }
}