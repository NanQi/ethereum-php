<?php
/**
 * author: NanQi
 * datetime: 2019/7/2 13:49
 */
namespace Ethereum;

use GuzzleHttp\Client;
use http\Exception\InvalidArgumentException;

class Eth {

    protected $apiKey;
    protected $type;

    function __construct(string $apiKey, string $type = 'etherscan') {
        $this->apiKey = $apiKey;
        $this->type = $type;
    }

    protected function _send(string $method, string $url, array $options = []) {
        $client = new Client([ 'timeout'  => 30 ]);
        $res = $client->request($method, $url, $options)->getBody();
        $res = json_decode((string)$res, true);
        return $res;
    }

    protected function getParams($params)
    {
        if ($this->type == 'infura') {
            return [$params, 'latest'];
        } else {
            return $params;
        }
    }

    public function send($method, $params = [])
    {
        if ($this->type == 'etherscan') {
            $url = "https://api.etherscan.io/api?module=proxy&action={$method}&apikey={$this->apiKey}";
            if ($params && count($params) > 0) {
                $strParams = http_build_query($params);
                $url .= "&{$strParams}";
            }

            $res = $this->_send('GET', $url);

        } else if ($this->type == 'infura') {
            $url = "https://mainnet.infura.io/v3/{$this->apiKey}";

            $arr = array_map(function ($item) {
                if (is_array($item)) {
                    return json_encode($item);
                } else {
                    return '"' . $item . '"';
                }
            }, $params);
            $strParams = implode(",", $arr);
            $data_string = <<<data
{"jsonrpc":"2.0","method":"{$method}","params": [$strParams],"id":1}
data;
            $res = $this->_send('POST', $url, [
                'body' => $data_string
            ]);
        } else {
            throw new \InvalidArgumentException('type invalid');
        }

        if (isset($res['result'])) {
            return $res['result'];
        } else {
            return false;
        }
    }

    public function gasPrice()
    {
        return $this->send('eth_gasPrice');
    }

    public static function gasPriceOracle($type = null)
    {
        $client = new Client([ 'timeout'  => 10 ]);
        $url = 'https://www.etherchain.org/api/gasPriceOracle';
        $res = $client->request('GET', $url)->getBody();
        $res = json_decode((string)$res, true);
        if ($type && isset($res[$type])) {
            return Utils::toWei($res[$type], 'gwei');
        } else {
            return $res;
        }
    }
}