<?php
/**
 * This file is part of the PHPEthereumTools package
 *
 * PHP Version 7.1
 * 
 * @category PHPEthereumTools
 * @package  PHPEthereumTools
 * @author   Micky Socaci <micky@nowlive.ro>
 * @license  https://github.com/mickys/php-eth-tools/blob/master/LICENSE.md MIT
 * @link     https://github.com/mickys/php-eth-tools/
 */
namespace Tests;


use Ethereum\Eth;
use PHPUnit\Framework\TestCase as BaseTestCase;

class EthTest extends BaseTestCase
{
    function testSend() {
        $eth = new Eth('KJU6S4DP2AFPA91T6XEKCEDJUA6V5R9MD5');
//        $eth = new Eth('8275f7b717754213a1c07e22939b324d', 'infura');
        $res = $eth->send('eth_gasPrice');
        var_dump($res);

        $this->assertTrue(true);
    }

    public function testGasPriceOracle()
    {
        $res = Eth::gasPriceOracle('fast');
        var_dump($res);

        $this->assertTrue(true);
    }
}
