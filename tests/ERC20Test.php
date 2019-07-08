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
use Ethereum\EtherscanApi;
use PHPUnit\Framework\TestCase as BaseTestCase;

class ERC20Test extends BaseTestCase
{
    const CONTRACT_ADDRESS = '0xF00dA0bDBeFE30659f2a40aBa168E9317A6dbB72';
    const ACCOUNT_ADDRESS = '0xcDFC7406BeacF91ED425eade994CD0839d3FA9fD';

    const INFURA_KEY = '8275f7b717754213a1c07e22939b324d';
    const ETHERSCAN_KEY = 'KJU6S4DP2AFPA91T6XEKCEDJUA6V5R9MD5';

    private function getERC20($contractAddress = self::CONTRACT_ADDRESS)
    {
        $erc20 = new ERC20($contractAddress, new EtherscanApi(self::ETHERSCAN_KEY));
        return $erc20;
    }

    public function testBalanceApi()
    {
        $res = $this->getERC20()->balanceByApi(self::ACCOUNT_ADDRESS, 8);
        var_dump($res);

        $this->assertTrue(true);
    }

    public function testBalance()
    {
        $res = $this->getERC20()->balance(self::ACCOUNT_ADDRESS, 8);
        var_dump($res);

        $this->assertTrue(true);
    }
}
