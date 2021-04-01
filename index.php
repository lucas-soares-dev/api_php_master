<?php

use App\Repository\TokensAutorizeds;
use App\Util\ConstantesGenericsUtil;
use App\Util\JsonUtil;
use App\Util\RotasUtil;
use App\Validator\RequestValidator;

include 'bootstrap.php';

try {
    // VERIFICAR SE O USUÁRIO É AUTORIZADO
    $objTokensAutorizeds = new TokensAutorizeds();
    $objTokensAutorizeds->verifyToken();

    // VALIDAR A REQUISIÇÃO
    $objValidRequest = new RequestValidator(RotasUtil::getRotas());
    $response = $objValidRequest->proccessRequest();

    // TRATAR RESPOSTAerwes
    $objJsonUtil = new JsonUtil();
    $objJsonUtil->treatArrayResponse($response);
} catch(Exception $e) {
    echo json_encode([
        ConstantesGenericsUtil::STATUS => ConstantesGenericsUtil::TYPE_ERROR,
        ConstantesGenericsUtil::RESPONSE => ConstantesGenericsUtil::MSG_ERROR_GENERIC
    ]);
}