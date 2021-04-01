<?php

namespace App\Util;

class ConstantesGenericsUtil
{
    /* REQUESTS */
    public const TYPE_REQUEST = ['GET', 'POST', 'DELETE', 'PUT'];
    public const TYPE_GET = ['usuarios'];
    public const TYPE_POST = ['usuarios'];
    public const TYPE_DELETE = ['usuarios'];
    public const TYPE_PUT = ['usuarios'];

    /* MESSAGES ERRORS */
    public const MSG_ERROR_REQUEST_NOT_EXISTS = 'O método de requisição não é suportado';
    public const MSG_ERROR_TYPE_ROUTE = 'Rota não permitida!';
    public const MSG_ERROR_RECURSE_NOT_EXIST = 'Recurso inexistente!';
    public const MSG_ERROR_GENERIC = 'Ocorreu algum erro na requisição, verifique se o login já existe!';
    public const MSG_ERROR_NOT_REGISTER = 'Nenhum registro encontrado!';
    public const MSG_ERROR_NOT_AFFECTED = 'Nenhum registro afetado!';
    public const MSG_ERROR_TOKEN_EMPTY = 'É necessário informar um Token!';
    public const MSG_ERROR_TOKEN_UNAUTHORIZED = 'Token não autorizado!';
    public const MSG_ERR0R_JSON_EMPTY = 'O Corpo da requisição não pode ser vazio!';

    /* MESSAGES SUCCESS */
    public const MSG_DELETED_SUCCESS = 'Registro deletado com Sucesso!';
    public const MSG_UPDATED_SUCCESS = 'Registro atualizado com Sucesso!';

    /* MESSAGES RECURSES USERS */
    public const MSG_ERROR_ID_REQUIRE = 'Identificador obrigatório!';
    public const MSG_ERROR_LOGIN_CEP_REQUIRE = 'Login e CEP são obrigatórios!';

    /* TYPE RETURN JSON */
    const TYPE_SUCESS = 'success';
    const TYPE_ERROR = 'error';

    /* OTHERS */
    public const ACTIVE = 'S';
    public const STATUS = 'status';
    public const RESPONSE = 'datas';
}