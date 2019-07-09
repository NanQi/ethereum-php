<?php
/**
 * author: NanQi
 * datetime: 2019/7/3 17:53
 */
namespace Ethereum;

use phpDocumentor\Reflection\Types\Nullable;

interface ProxyApi {
    function send($method, $params = []);

    function gasPrice();

    function ethBalance(string $address);

    function receiptStatus(string $txHash) : ?bool;

    function getTransactionReceipt(string $txHash);

    function sendRawTransaction($raw);

    function getNonce(string $address);
}