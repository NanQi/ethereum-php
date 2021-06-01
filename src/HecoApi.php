<?php
/**
 * author: NanQi
 * datetime: 2019/7/3 17:53
 */
namespace Ethereum;

class HecoApi extends EtherscanApi {

    protected function getUrl($method)
    {
        $preApi = 'api';
        if ($this->network != 'heco-main') {
            $preApi .= '-testnet';
        }

        return "https://$preApi.hecoinfo.com/api?action={$method}&apikey={$this->apiKey}";
    }
}
