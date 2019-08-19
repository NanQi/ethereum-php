<?php
/**
 * author: NanQi
 * datetime: 2019/7/2 13:49
 */
namespace Ethereum;

use Web3p\EthereumTx\Transaction;

/**
 * @method bool|null receiptStatus(string $txHash)
 * @method mixed gasPrice()
 * @method mixed ethBalance(string $address)
 * @method mixed getTransactionReceipt(string $txHash)
 */
class Eth {
    protected $proxyApi;

    function __construct(ProxyApi $proxyApi) {
        $this->proxyApi = $proxyApi;
    }

    function __call($name, $arguments)
    {
        return call_user_func_array([$this->proxyApi, $name], $arguments);
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

    public function transfer(string $privateKey, string $to, float $value, string $gasPrice = 'standard')
    {
        $from = PEMHelper::privateKeyToAddress($privateKey);
        $nonce = $this->proxyApi->getNonce($from);
        if (!Utils::isHex($gasPrice)) {
            $gasPrice = Utils::toHex(self::gasPriceOracle($gasPrice), true);
        }

        $eth = Utils::toWei("$value", 'ether');
        $eth = Utils::toHex($eth, true);

        $transaction = new Transaction([
            'nonce' => "$nonce",
            'from' => $from,
            'to' => $to,
            'gas' => '0x76c0',
            'gasPrice' => "$gasPrice",
            'value' => "$eth",
            'chainId' => 1,
        ]);

        $raw = $transaction->sign($privateKey);
        return $this->proxyApi->sendRawTransaction('0x'.$raw);
    }
}