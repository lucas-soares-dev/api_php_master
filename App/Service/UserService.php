<?php

namespace App\Service;

use App\DB\Database;
use App\Util\ConstantesGenericsUtil;
use InvalidArgumentException;
use PDO;

class UserService
{   
    private array $request;
    private object $objDatabase;
    private array $datasRequest;

    private const TABLE = 'usuarios';
    private const RECURSES_GET = ['listar'];
    private const RECURSES_POST = ['cadastrar'];
    private const RECURSES_PUT = ['atualizar'];
    private const RECURSES_DELETE = ['deletar'];

    /**
     * MÉTODO CONSTRUCT - RESPONSÁVEL POR RECEBER A REQUISICAO E SETAR A CONEXÃO COM O BANCO DE DADOS
     *
     * @return void
     */
    public function __construct($request = [])
    {        
        $this->request = $request;
        $this->objDatabase = new Database(self::TABLE);
    }
    
    /**
     * MÉTODO RESPONSÁVEL POR VALIDAR O RECURSO DE LISTAGEM E CHAMAR O MÉTODO REPONSÁVEL POR LISTAR
     *
     * @return object
     */
    public function validateGet()
    {
        $recurse = $this->request['recurse'];

        if(in_array($recurse, self::RECURSES_GET, true)) {
            $response = $this->request['id'] > 0 ? $this->getUser() : $this->$recurse();
            
            if($response === false) {
                throw new InvalidArgumentException(ConstantesGenericsUtil::MSG_ERROR_NOT_REGISTER);
            }
        } else {
            throw new InvalidArgumentException(ConstantesGenericsUtil::MSG_ERROR_RECURSE_NOT_EXIST);
        }

        // VALIDAR SE NÃO EXISTE ALGUM RETORNO
        if($response === null) {
            throw new InvalidArgumentException(ConstantesGenericsUtil::MSG_ERROR_GENERIC);
        }

        return $response;
    }
    
    /**
     * MÉTODO RESPONSÁVEL POR VALIDAR O RECURSO DE DELEÇÃO E CHAMAR O MÉTODO RESPONSÁVEL
     *
     * @return string
     */
    public function validateDelete()
    {
        $recurse = $this->request['recurse'];

        // VALIDANDO SE O RECURSO EXISTE
        if(in_array($recurse, self::RECURSES_DELETE, true)) {            
            if($this->request['id'] > 0) {
                $response = $this->$recurse();
            } else {
                throw new InvalidArgumentException(ConstantesGenericsUtil::MSG_ERROR_ID_REQUIRE);
            }
        } else {
            throw new InvalidArgumentException(ConstantesGenericsUtil::MSG_ERROR_RECURSE_NOT_EXIST);
        }

        return $response;
    }
    
    /**
     * MÉTODO RESPONSÁVEL POR VALIDAR O RECURSO DE ENVIO DE DADOS E CHAMAR O MÉTODO RESPONSÁVEL
     *
     * @return array
     */
    public function validatePost()
    {
        $response = null;
        $recurse = $this->request['recurse'];

        // VALIDANDO SE O RECURSO EXISTE
        if(in_array($recurse, self::RECURSES_POST, true)) {            
            $response = $this->$recurse();
        } else {
            throw new InvalidArgumentException(ConstantesGenericsUtil::MSG_ERROR_RECURSE_NOT_EXIST);
        }

        if($response == null) {
            throw new InvalidArgumentException(ConstantesGenericsUtil::MSG_ERROR_GENERIC);
        }

        return $response;
    }

    /**
     * MÉTODO RESPONSÁVEL POR VALIDAR O RECURSO DE ATUALIZAR E CHAMAR O MÉTODO RESPONSÁVEL
     *
     * @return string
     */
    public function validatePut()
    {
        $recurse = $this->request['recurse'];

        // VALIDANDO SE O RECURSO EXISTE
        if(in_array($recurse, self::RECURSES_PUT, true)) {            
            if($this->request['id'] > 0) {
                $response = $this->$recurse();
            } else {
                throw new InvalidArgumentException(ConstantesGenericsUtil::MSG_ERROR_ID_REQUIRE);
            }
        } else {
            throw new InvalidArgumentException(ConstantesGenericsUtil::MSG_ERROR_RECURSE_NOT_EXIST);
        }

        return $response;
    }
    
    /**
     * MÉTODO RESPONSÁVEL POR BUSCAR UM DADO ESPECÍFICO NO BD
     *
     * @return object
     */
    private function getUser()
    {
        return $this->objDatabase->getDatas('id='.$this->request['id'])->fetch(PDO::FETCH_OBJ);
    }
    
    /**
     * MÉTODO RESPONSÁVEL POR RETORNAR OS DADOS SOLICITADOS
     *
     * @return object
     */
    private function listar()
    {
        return $this->objDatabase->getDatas()->fetchAll(PDO::FETCH_OBJ);
    }
    
    /**
     * MÉTODO RESPONSÁVEL POR DELETAR UM DADO
     *
     * @return string
     */
    private function deletar()
    {
        return $this->objDatabase->delete($this->request['id']);
    }
    
    /**
     * MÉTODO RESPONSÁVEL POR CADASTRAR
     *
     * @return array
     */
    private function cadastrar()
    {
        [$login, $cep] = [$this->datasRequest['login'], $this->datasRequest['cep']];

        if($login && is_numeric($cep)) {
            $response = $this->objDatabase->insert($this->datasRequest);

            return $response;
        }

        throw new InvalidArgumentException(ConstantesGenericsUtil::MSG_ERROR_LOGIN_CEP_REQUIRE);
    }

    /**
     * MÉTODO RESPONSÁVEL POR ATUALIZAR DADOS DO BD
     *
     * @return string
     */
    private function atualizar()
    {
        if($this->objDatabase->update('id='.$this->request['id'], $this->datasRequest) === 1) {
            return ConstantesGenericsUtil::MSG_UPDATED_SUCCESS;
        }
                
        throw new InvalidArgumentException(ConstantesGenericsUtil::MSG_ERROR_NOT_REGISTER);
    }
    
    /**
     * MÉTODO RESPONSÁVEL POR SETAR OS DADOS DO CORPO DA REQUISIÇÃO (CASO FOR INSERÇÃO OU ATUALIZAÇÃO DE DADOS)
     *
     * @param  array $datasRequest
     * @return void
     */
    public function setDatasBodyRequest($datasRequest = [])
    {
        $this->datasRequest = $datasRequest;
    }
}