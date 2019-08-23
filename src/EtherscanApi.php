<?php
/**
 * author: NanQi
 * datetime: 2019/7/3 17:53
 */
namespace Ethereum;

class EtherscanApi implements ProxyApi {
    protected $apiKey;
    protected $type;

    function __construct(string $apiKey, $type = 'mainnet') {
        $this->apiKey = $apiKey;
        $this->type = $type;
    }

    public function send($method, $params = [])
    {
        $defaultParams = [
            'module' => 'proxy',
            'tag' => 'latest',
        ];

        foreach ($defaultParams as $key => $val) {
            if (!isset($params[$key])) {
                $params[$key] = $val;
            }
        }

        $preApi = 'api';
        if ($this->type != 'mainnet') {
            $preApi .= '-' . $this->type;
        }

        $url = "https://$preApi.etherscan.io/api?action={$method}&apikey={$this->apiKey}";
        if ($params && count($params) > 0) {
            $strParams = http_build_query($params);
            $url .= "&{$strParams}";
        }

        $res = Utils::httpRequest('GET', $url);
        if (isset($res['result'])) {
            return $res['result'];
        } else {
            var_dump($res);
            return false;
        }
    }

    function gasPrice()
    {
        return $this->send('eth_gasPrice');
    }

    function ethBalance(string $address)
    {
        $params['module'] = 'account';
        $params['address'] = $address;

        return Utils::fromWei($this->send('balance', $params), 'ether');
    }

    function receiptStatus(string $txHash): ?bool
    {
        $res = $this->send('eth_getTransactionByHash', ['txhash' => $txHash]);
        if (!$res) {
            return false;
        }

        if (!$res['blockNumber']) {
            return null;
        }

        $params['module'] = 'transaction';
        $params['txhash'] = $txHash;

        $res =  $this->send('gettxreceiptstatus', $params);
        return $res['status'] == '1';
    }

    function getTransactionReceipt(string $txHash)
    {
        $res = $this->send('eth_getTransactionReceipt', ['txhash' => $txHash]);
        return $res;
    }

    function sendRawTransaction($raw)
    {
        return $this->send('eth_sendRawTransaction', ['hex' => $raw]);
    }

    function getNonce(string $address)
    {
        return $this->send('eth_getTransactionCount', ['address' => $address]);
    }
}
