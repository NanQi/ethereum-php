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


use Ethereum\ERC20;
use PHPUnit\Framework\TestCase as BaseTestCase;

class ERC20Test extends BaseTestCase
{
    public function testBalanceApi()
    {
        $erc20 = new ERC20('0xF00dA0bDBeFE30659f2a40aBa168E9317A6dbB72', 'KJU6S4DP2AFPA91T6XEKCEDJUA6V5R9MD5');
        $res = $erc20->balanceByApi('0xcDFC7406BeacF91ED425eade994CD0839d3FA9fD', 8);
        var_dump($res);

        $this->assertTrue(true);
    }

    public function testBalance()
    {
        $erc20 = new ERC20('0xF00dA0bDBeFE30659f2a40aBa168E9317A6dbB72', 'KJU6S4DP2AFPA91T6XEKCEDJUA6V5R9MD5');
//        $erc20 = new ERC20('0xF00dA0bDBeFE30659f2a40aBa168E9317A6dbB72', '8275f7b717754213a1c07e22939b324d', 'infura');
        $res = $erc20->balance('0xcDFC7406BeacF91ED425eade994CD0839d3FA9fD', 8);
        var_dump($res);

        $this->assertTrue(true);
    }
}
