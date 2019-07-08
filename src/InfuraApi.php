<?php
/**
 * author: NanQi
 * datetime: 2019/7/3 17:53
 */
namespace Ethereum;

class InfuraApi implements ProxyApi {
    protected $apiKey;

    function __construct(string $apiKey) {
        $this->apiKey = $apiKey;
    }

    public function send($method, $params = [])
    {
        $url = "https://mainnet.infura.io/v3/{$this->apiKey}";

        $params[] = 'latest';

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
        $res = Utils::httpRequest('POST', $url, [
            'body' => $data_string
        ]);

        if (isset($res['result'])) {
            return $res['result'];
        } else {
            return false;
        }
    }

    function gasPrice()
    {
        return $this->send('eth_gasPrice');
    }

    function ethBalance(string $address)
    {
        // TODO: Implement balance() method.
    }

    function receiptStatus(string $txHash): bool
    {
        // TODO: Implement receiptStatus() method.
    }

    function sendRawTransaction($raw)
    {
        // TODO: Implement sendRawTransaction() method.
    }

    function getNonce(string $address)
    {
        // TODO: Implement getNonce() method.
    }
}
