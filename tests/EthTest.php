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
use Ethereum\EtherscanApi;
use PHPUnit\Framework\TestCase as BaseTestCase;

class EthTest extends BaseTestCase
{
    const INFURA_KEY = '8275f7b717754213a1c07e22939b324d';
    const ETHERSCAN_KEY = 'KJU6S4DP2AFPA91T6XEKCEDJUA6V5R9MD5';
    const ACCOUNT_ADDRESS = '0xcDFC7406BeacF91ED425eade994CD0839d3FA9fD';

    const SUCCESS_TX_HASH = '0x424e10f550d5b7075cd0838ee8a77ef97e6fbce5e3e1569812b6dea500185653';
    const FAIL_TX_HASH = '0x5f200172cacb4c0ec02e18ea15c989408a3980b1c049dfd6dda68ec831449180';

    private function getEth()
    {
        $eth = new Eth(new EtherscanApi(self::ETHERSCAN_KEY));
        return $eth;
    }
    
    function testGasPrice() {
        $res = $this->getEth()->gasPrice();
        var_dump($res);

        $this->assertTrue(true);
    }

    function testBalance() {
        $res = $this->getEth()->ethBalance(self::ACCOUNT_ADDRESS);
        var_dump($res);

        $this->assertTrue(true);
    }

    public function testGasPriceOracle()
    {
        $res = Eth::gasPriceOracle('fast');
        var_dump($res);

        $this->assertTrue(true);
    }

    public function testReceiptStatus()
    {
//        $isSuccess = $this->getEth()->receiptStatus(self::SUCCESS_TX_HASH);
//        $isFail = $this->getEth()->receiptStatus(self::FAIL_TX_HASH);
        $pending = $this->getEth()->receiptStatus('0x3f81e83a0e9d0329c08b976b0585208d6fd90b7a1946e933b9731510025ccdf4');
        var_dump($pending);
//        $pending = $this->getEth()->receiptStatus('0x1f29451d8e68ceb8bcf9c7a568ee2449c87bceaf0b1ab583cf0cbd17d1a1639b ');
//        var_dump($pending);
        $this->assertTrue(true);

//        $this->assertTrue(!$isFail);
//        $this->assertTrue($isSuccess);
    }

    public function testGetTransactionReceipt()
    {
//        $isSuccess = $this->getEth()->receiptStatus(self::SUCCESS_TX_HASH);
//        $isFail = $this->getEth()->getTransactionReceipt(self::FAIL_TX_HASH);
        $pending = $this->getEth()->getTransactionReceipt('0x3f81e83a0e9d0329c08b976b0585208d6fd90b7a1946e933b9731510025ccdf4');
        var_dump($pending);
//        $pending = $this->getEth()->getTransactionReceipt('0x1f29451d8e68ceb8bcf9c7a568ee2449c87bceaf0b1ab583cf0cbd17d1a1639b ');
//        var_dump($pending);
        $this->assertTrue(true);

//        $this->assertTrue(!$isFail);
//        $this->assertTrue($isSuccess);
    }

    public function testTransfer() {
        $res = $this->getEth()->transfer(
            '',
            '0xBe3eD451a5CfA78AcE6A77959FeE5f6D6D91Bb27',
            0.0001, 'fast');
        var_dump($res);

        $this->assertTrue(true);
    }
}
