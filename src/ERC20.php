<?php
/**
 * author: NanQi
 * datetime: 2019/7/2 16:07
 */

namespace Ethereum;

use Hamcrest\Util;

class ERC20 extends Eth{

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
        $signMethod = Sign::toMethodSign($method);
        $signAddress = Sign::toAddressSign($address);

        $params['data'] = "0x{$signMethod}{$signAddress}";

        $balance = $this->proxyApi->send('eth_call', $params);
        return Utils::toDisplayAmount($balance, $decimals);
    }
}