<?php

namespace App\Util;

class RotasUtil
{
    
    /**
     * MÉTODO RESPONSÁVEL POR TRATAR E RETORNAR AS ROTAS E O MÉTODO DA REQUISIÇÃO
     *
     * @return array
     */
    public static function getRotas()
    {
        $url = self::getUrls();

        // VERIFICAR ROTAS DA REQUEST(URL)
        $request['route'] = filter_var($url[0], FILTER_SANITIZE_STRING);
        $request['recurse'] = $url[1] ? filter_var($url[1], FILTER_SANITIZE_STRING) : null;
        $request['id'] = $url[2] ? filter_var($url[2], FILTER_SANITIZE_NUMBER_INT) : null;
        $request['method'] = $_SERVER['REQUEST_METHOD'];

        return $request;
    }
    
    /**
     * MÉTODO RESPONSÁVEL POR EXPLODIR A URL QUANDO ESTIVER UMA BARRA(/)
     *
     * @return array
     */
    private static function getUrls()
    {
        // RETIRANDO O DIRETÓRIO RAIZ DA ROTA
        $url = str_replace(DIR_SEPARATOR . DIR_PROJ . DIR_SEPARATOR, '', $_SERVER['REQUEST_URI']);

        // MONTAR ARRAY COM AS ROTAS, QUEBRANDO PELA / (BARRA)
        return explode('/', $url);
    }
}