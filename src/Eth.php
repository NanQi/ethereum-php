<?php
/**
 * author: NanQi
 * datetime: 2019/7/2 13:49
 */
namespace Ethereum;

use GuzzleHttp\Client;
use Web3p\EthereumTx\Transaction;

class Eth {
    protected $proxyApi;

    function __construct(ProxyApi $proxyApi) {
        $this->proxyApi = $proxyApi;
    }

    public function gasPrice()
    {
        return $this->proxyApi->gasPrice();
    }

    public function ethBalance(string $address)
    {
        return $this->proxyApi->ethBalance($address);
    }

    public function receiptStatus(string $txHash)
    {
        return $this->proxyApi->receiptStatus($txHash);
    }

    public function getTransactionReceipt(string $txHash)
    {
        return $this->proxyApi->getTransactionReceipt($txHash);
    }

    public static function gasPriceOracle($type = 'standard')
    {
        $url = 'https://www.etherchain.org/api/gasPriceOracle';
        $res = Utils::httpRequest('GET', $url);
        if ($type && isset($res[$type])) {
            return Utils::toHex(Utils::toWei($res[$type], 'gwei'));
        } else {
            return $res;
        }
    }

    public function transfer(string $privateKey, string $to, float $value)
    {
        $from = PEMHelper::privateKeyToAddress($privateKey);
        $nonce = $this->proxyApi->getNonce($from);
        $gasPrice = self::gasPriceOracle();
        $eth = Utils::toWei("$value", 'ether');

        $transaction = new Transaction([
            'nonce' => "0x$nonce",
            'from' => $from,
            'to' => $to,
            'gas' => '0x76c0',
            'gasPrice' => "0x$gasPrice",
            'value' => $eth,
            'chainId' => 1,
        ]);

        $raw = $transaction->sign($privateKey);
        return $this->proxyApi->sendRawTransaction('0x'.$raw);
    }
}