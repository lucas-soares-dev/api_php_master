<?php

namespace App\DB;

use App\Util\ConstantesGenericsUtil;
use InvalidArgumentException;
use PDO;
use PDOException;

class Database
{
    private object $db;
    private $table;
    
    /**
     * __construct
     *
     * @param  mixed $table
     * @return void
     */
    public function __construct($table = null)
    {
        $this->table = $table;
        $this->db = $this->setConnection();
    }
    
    /**
     * MÉTODO RESPONSÁVEL POR SETAR A CONEXÃO COM O BANCO DE DADOS
     *
     * @return object
     */
    public function setConnection()
    {
        try {
            return new PDO("mysql:host=" . HOST . "; dbname=" . DBNAME . "; ", USER, PASSWORD);
        } catch (PDOException $e) {
            throw new PDOException('ERRO: '.$e->getMessage());
        }
    }
   
    /**
     * MÉTODO RESPONSÁVEL POR BUSCAR OS DADOS NO BD
     *
     * @param  mixed $where - RECEBE A CONDIÇÃO PARA REALIZAR A BUSCA DOS DADOS
     * @param  mixed $order - RECEBE A ORDEM QUE SERÁ RETORNADO
     * @param  mixed $limit - RECEBE O LIMIT DE DADOS QUE SERÃO RETORNADOS
     * @param  mixed $fields - RECEBE OS CAMPOS QUE SERÁ RETORNADO, QUANDO NÃO PASSA OS CAMPOS ELE SELECIONA TODOS
     * @return object
     */
    public function getDatas($where = null, $order = null, $limit = null, $fields = '*')
    {
        $where = strlen($where) ? "WHERE " . $where : '';
        $order = strlen($order) ? "ORDER " . $order : '';
        $limit = strlen($limit) ? "LIMIT " . $limit : '';

        $query = 'SELECT ' . $fields . ' FROM ' . $this->table . ' ' . $where . ' ' . $order . ' ' . $limit;
        $response = $this->executeQuery($query);

        return $response;
    }
    
    /**
     * MÉTODO RESPONSÁVEL POR DELETAR UM DADO NO BD
     *
     * @param  mixed $where - RECEBE A CONDIÇÃO DE DELEÇÃO (OBRIGATÓRIO PASSAR ESSE PARÂMETRO)
     * @return string
     */
    public function delete($where)
    {
        if($where) {
            $query = 'DELETE FROM ' . $this->table . ' WHERE id = ' . $where;

            // VERIFICANDO SE FOI EXECUTADO COM SUCESSO
            if($this->executeQuery($query)->rowCount() === 1) {
                return ConstantesGenericsUtil::MSG_DELETED_SUCCESS;
            }
            throw new InvalidArgumentException(ConstantesGenericsUtil::MSG_ERROR_NOT_REGISTER);
        }

        throw new InvalidArgumentException(ConstantesGenericsUtil::MSG_ERROR_ID_REQUIRE);
    }
    
    /**
     * MÉTODO RESPONSÁVEL POR INSERIR OS DADOS NO BD
     *
     * @param  mixed $datas - RECEBE OS DADOS QUE SERÃO CADASTRADOS
     * @return object
     */
    public function insert($datas)
    {
        // RECEBER O NOME DAS COLUNAS DO BD  
        $colunmsDb = array_keys($datas);
        // ARRAY COM A QUANTIDADE DE ? QUE TEM A ARRAYKEYS
        $binds = array_pad([], count($colunmsDb), '?');
        
        // QUERY DE INSERSÃO
        $query = 'INSERT INTO '.$this->table.' ('.implode(',',$colunmsDb).') VALUES ('.implode(',',$binds).')';
        
        // VERIFICANDO SE FOI INSERIDO OS DADOS NO BD
        if($this->executeQuery($query, array_values($datas))->rowCount() === 1) {
            return ['id_insert' => $this->db->lastInsertId()];
        }

        $this->db->rollBack();
        
        return ConstantesGenericsUtil::MSG_ERROR_GENERIC;
    }

    /**
     * MÉTODO RESPONSÁVEL POR ATUALIZAR UM DADO NO BD
     *
     * @param  mixed $where - RECEBE A CONDIÇÃO DE DELEÇÃO (OBRIGATÓRIO PASSAR ESSE PARÂMETRO)
     * @return int
     */
    public function update($where, $datas)
    {
        // RECEBER O NOME DAS COLUNAS DO BD
        $colunmsDb = array_keys($datas);        

        if($where) {
            $query = 'UPDATE ' . $this->table . ' SET ' . implode('=?,',$colunmsDb) . '=? WHERE ' . $where;

            // VERIFICANDO SE FOI ENCONTRADO ALGUM RESULTADO NO BD
            return $this->executeQuery($query, array_values($datas))->rowCount();
        }

        throw new InvalidArgumentException(ConstantesGenericsUtil::MSG_ERROR_ID_REQUIRE);
    }
    
    /**
     * MÉTODO RESPONSÁVEL POR EXECUTAR AS QUERIES
     *
     * @param  mixed $query - RECEBE A QUERY PARA REALIZAR A EXECUÇÃO
     * @param  mixed $params - RECEBE OS PARÂMTROS, CASO SEJA UMA INSERÇÃO OU ATUALIZAÇÃO DE DADOS
     * @return object
     */
    public function executeQuery($query, $params=[])
    {
        try {
            // PREPARANDO A QUERY
            $stmt = $this->db->prepare($query);
            $stmt->execute($params);

            // RETORNANDO O RESULTADO APÓS EXECUTAR A QUERY
            return $stmt;
        } catch (PDOException $e) {
            throw new InvalidArgumentException(ConstantesGenericsUtil::MSG_ERROR_GENERIC);
        }
    }
}