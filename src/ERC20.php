<?php
/**
 * author: NanQi
 * datetime: 2019/7/2 16:07
 */

namespace Ethereum;

use Hamcrest\Util;

class ERC20 extends Eth{

    protected $contractAddress;

    function __construct(string $contractAddress, string $apiKey, string $type = 'etherscan') {
        parent::__construct($apiKey, $type);

        $this->contractAddress = $contractAddress;
    }

    public function balanceByApi(string $address, int $decimals)
    {
        if ($this->type == 'etherscan') {
            $url = "https://api.etherscan.io/api?module=account&action=tokenbalance&contractaddress={$this->contractAddress}&address={$address}&tag=latest&apikey={$this->apiKey}";
            $res = $this->_send('GET', $url);
        } else {
            throw new \InvalidArgumentException('type invalid');
        }

        if (isset($res['result'])) {
            return Utils::toDisplayAmount($res['result'], $decimals);
        } else {
            return false;
        }
    }

    public function balance(string $address, int $decimals)
    {
        $params = [];
        $params['to'] = $this->contractAddress;

        $method = 'balanceOf(address)';
        $signMethod = Sign::toMethodSign($method);
        $signAddress = Sign::toAddressSign($address);

        $params['data'] = "0x{$signMethod}{$signAddress}";

        $balance = $this->send('eth_call', $this->getParams($params));
        return Utils::toDisplayAmount($balance, $decimals);
    }
}