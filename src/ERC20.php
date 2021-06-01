<?php
/**
 * author: NanQi
 * datetime: 2019/7/2 16:07
 */

namespace Ethereum;

use InvalidArgumentException;
use Web3p\EthereumTx\Transaction;

class ERC20 extends Eth
{

    protected $contractAddress;
    protected $decimals;

    function __construct(string $contractAddress, int $decimals, ProxyApi $proxyApi)
    {
        parent::__construct($proxyApi);

        $this->contractAddress = $contractAddress;
        $this->decimals = $decimals;
    }

    public function balanceByApi(string $address)
    {
        if ($this->proxyApi instanceof EtherscanApi) {
            $res = $this->proxyApi->send('tokenbalance', [
                'module' => 'account',
                'contractaddress' => $this->contractAddress,
                'address' => $address
            ]);

            if ($res !== false) {
                return Utils::toDisplayAmount($res, $this->decimals);
            } else {
                return false;
            }
        } else {
            throw new InvalidArgumentException('type invalid');
        }
        
    }

    public function balance(string $address)
    {
        $params = [];
        $params['to'] = $this->contractAddress;

        $method = 'balanceOf(address)';
        $formatMethod = Formatter::toMethodFormat($method);
        $formatAddress = Formatter::toAddressFormat($address);

        $params['data'] = "0x{$formatMethod}{$formatAddress}";

        $balance = $this->proxyApi->ethCall($params);
        return Utils::toDisplayAmount($balance, $this->decimals);
    }

    public function transfer(string $privateKey, string $to, float $value, string $gasPrice = 'standard')
    {
        $from = PEMHelper::privateKeyToAddress($privateKey);
        $nonce = $this->proxyApi->getNonce($from);
        if (!Utils::isHex($gasPrice)) {
            $gasPrice = Utils::toHex(self::gasPriceOracle($gasPrice), true);
        }
        $params = [
            'nonce' => "$nonce",
            'from' => $from,
            'to' => $this->contractAddress,
            'gas' => '0x15F90',
            'gasPrice' => "$gasPrice",
            'value' => Utils::NONE,
            'chainId' => self::getChainId($this->proxyApi->getNetwork()),
        ];
        $val = Utils::toMinUnitByDecimals("$value", $this->decimals);

        $method = 'transfer(address,uint256)';
        $formatMethod = Formatter::toMethodFormat($method);
        $formatAddress = Formatter::toAddressFormat($to);
        $formatInteger = Formatter::toIntegerFormat($val);

        $params['data'] = "0x{$formatMethod}{$formatAddress}{$formatInteger}";
        $transaction = new Transaction($params);

        $raw = $transaction->sign($privateKey);
        $res = $this->proxyApi->sendRawTransaction('0x'.$raw);
        if ($res !== false) {
            $this->emit(new TransactionEvent($transaction, $privateKey, $res));
        }

        return $res;
    }
}