<?php
/**
 * author: NanQi
 * datetime: 2019/7/2 16:07
 */

namespace Ethereum;

use Hamcrest\Util;
use Web3p\EthereumTx\Transaction;

class ERC20 extends Eth {

    protected $contractAddress;

    function __construct(string $contractAddress, ProxyApi $proxyApi) {
        parent::__construct($proxyApi);

        $this->contractAddress = $contractAddress;
    }

    public function balanceByApi(string $address, int $decimals)
    {
        if ($this->proxyApi instanceof EtherscanApi) {
            $res = $this->proxyApi->send('tokenbalance', [
                'module' => 'account',
                'contractaddress' => $this->contractAddress,
                'address' => $address
            ]);

            if ($res !== false) {
                return Utils::toDisplayAmount($res, $decimals);
            } else {
                return false;
            }
        } else {
            throw new \InvalidArgumentException('type invalid');
        }

    }

    public function balance(string $address, int $decimals = 16)
    {
        $params = [];
        $params['to'] = $this->contractAddress;

        $method = 'balanceOf(address)';
        $formatMethod = Formatter::toMethodFormat($method);
        $formatAddress = Formatter::toAddressFormat($address);

        $params['data'] = "0x{$formatMethod}{$formatAddress}";

        $balance = $this->proxyApi->send('eth_call', $params);
        return Utils::toDisplayAmount($balance, $decimals);
    }

    public function transfer(string $privateKey, string $to, float $value)
    {
        $from = PEMHelper::privateKeyToAddress($privateKey);
        $nonce = $this->proxyApi->getNonce($from);
        $gasPrice = self::gasPriceOracle();

        $params = [
            'nonce' => "0x$nonce",
            'from' => $from,
            'to' => $this->contractAddress,
            'gas' => '0x15F90',
            'gasPrice' => "0x$gasPrice",
            'value' => '0x0',
            'chainId' => 1,
        ];
        $val = Utils::toMinUnitByDecimals("$value", 8);

        $method = 'transfer(address,uint256)';
        $formatMethod = Formatter::toMethodFormat($method);
        $formatAddress = Formatter::toAddressFormat($to);
        $formatInteger = Formatter::toIntegerFormat($val);

        $params['data'] = "0x{$formatMethod}{$formatAddress}{$formatInteger}";
        $transaction = new Transaction($params);

        $raw = $transaction->sign($privateKey);
        return $this->proxyApi->sendRawTransaction('0x'.$raw);
    }
}