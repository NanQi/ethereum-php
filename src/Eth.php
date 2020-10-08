<?php
/**
 * author: NanQi
 * datetime: 2019/7/2 13:49
 */
namespace Ethereum;

use League\Event\EmitterTrait;
use Web3p\EthereumTx\Transaction;

/**
 * @method bool|null receiptStatus(string $txHash)
 * @method mixed gasPrice()
 * @method mixed ethBalance(string $address)
 * @method mixed getTransactionReceipt(string $txHash)
 */
class Eth {
    use EmitterTrait;

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
            $price = Utils::toWei((string)$res[$type], 'gwei');
//            $price = $price * 1e9;
            return $price;
        } else {
            return $res;
        }
    }

    public static function getChainId($network) : int {
        $chainId = 1;
        switch ($network) {
            case 'rinkeby':
                $chainId = 4;
                break;
            case 'ropsten':
                $chainId = 3;
                break;
            case 'kovan':
                $chainId = 42;
                break;
            default:
                break;
        }

        return $chainId;
    }

    public function transfer(string $privateKey, string $to, float $value, string $gasPrice = 'standard')
    {
        $from = PEMHelper::privateKeyToAddress($privateKey);
        $nonce = $this->proxyApi->getNonce($from);
        if (!Utils::isHex($gasPrice)) {
            $gasPrice = Utils::toHex(self::gasPriceOracle($gasPrice), true);
        }

        $eth = Utils::toWei("$value", 'ether');
//        $eth = $value * 1e16;
        $eth = Utils::toHex($eth, true);

        $transaction = new Transaction([
            'nonce' => "$nonce",
            'from' => $from,
            'to' => $to,
            'gas' => '0x76c0',
            'gasPrice' => "$gasPrice",
            'value' => "$eth",
            'chainId' => self::getChainId($this->proxyApi->getNetwork()),
        ]);

        $raw = $transaction->sign($privateKey);
        $res = $this->proxyApi->sendRawTransaction('0x'.$raw);
        if ($res !== false) {
            $this->emit(new TransactionEvent($transaction, $privateKey, $res));
        }

        return $res;
    }
}