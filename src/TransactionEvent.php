<?php
namespace Ethereum;

use League\Event\AbstractEvent;
use Web3p\EthereumTx\Transaction;

/**
 * author: NanQi
 * datetime: 2019/8/31 16:03
 */

class TransactionEvent extends AbstractEvent {
    public function __construct(Transaction $transaction, string $privateKey, string $txHash)
    {
        $this->transaction = $transaction;
        $this->privateKey = $privateKey;
        $this->txHash = $txHash;
    }
}