<?php
/**
 * author: NanQi
 * datetime: 2019/7/2 17:48
 */
namespace Ethereum;

class Sign {

    public static function toMethodSign($method)
    {
        return Utils::stripZero(substr(Utils::sha3($method), 0, 10));
    }

    public static function toAddressSign($address)
    {
        if (Utils::isAddress($address)) {
            $address = strtolower($address);

            if (Utils::isZeroPrefixed($address)) {
                $address = Utils::stripZero($address);
            }
        }
        return implode('', array_fill(0, 64 - strlen($address), 0)) . $address;
    }
}