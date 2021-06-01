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
use Ethereum\HecoApi;
use Ethereum\TransactionEvent;
use PHPUnit\Framework\TestCase as BaseTestCase;

class HecoTest extends BaseTestCase
{
    const HECO_KEY = '745NBY6ZY9KRM21HRWIVJVINA547NSF8KW';

    const SUCCESS_TX_HASH = '0xbda8dac4ea667678e675d8dba089ef0ed6edc74bb9ad8ca78865a0c88eeb8900';
    const FAIL_TX_HASH = '0x5f200172cacb4c0ec02e18ea15c989408a3980b1c049dfd6dda68ec831449180';

    const WALLET_PRIVATE_KEY = 'b1d2c195349a7928f69ac146064cc06ddf2e0cca85fa9f0a3bdf7e3c986abd23';
    const WALLET_ADDRESS = '0x731C83B3888B7c717371B6bBCc8e1aBB1BfB5832';

    private function getEth()
    {
        $eth = new Eth(new HecoApi(self::HECO_KEY, 'heco-test'));
        return $eth;
    }
    
    function testGasPrice() {
        $res = $this->getEth()->gasPrice();
        var_dump($res);

        $this->assertTrue(true);
    }

    function testBalance() {
        $res = $this->getEth()->ethBalance(self::WALLET_ADDRESS);
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
        $isSuccess = $this->getEth()->receiptStatus(self::SUCCESS_TX_HASH);
//        $isFail = $this->getEth()->receiptStatus(self::FAIL_TX_HASH);
//        $pending = $this->getEth()->receiptStatus('0x1f29451d8e68ceb8bcf9c7a568ee2449c87bceaf0b1ab583cf0cbd17d1a1639b ');
//        var_dump($pending);

//        $this->assertTrue(!$isFail);
        $this->assertTrue($isSuccess);
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
        $eth = $this->getEth();
        $eth->addListener(TransactionEvent::class, function($event) {

        });

        $res = $eth->transfer(
            self::WALLET_PRIVATE_KEY,
            '0x7283Fd194ee26e69eDeF7CC1fDa577048ce39132',
            0.0005, 'fast');
        var_dump($res);

        $this->assertTrue(true);
    }
}
