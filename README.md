# ethereum-php
ethereum eth web3 php keysotre bip44 infura etherscan proxy api

# 可能遇到的问题

## 1. error:02001003:system library:fopen:No such process

生成私钥时`generateNewPrivateKey`，需要openssl扩展，此时可能会如上错误，
查看phpinfo，Openssl default config，位置对应`openssl.cnf`可能不存在，
一般`extras`文件夹内有此文件，拷贝到指定位置即可。

## 2. cURL error 60: SSL certificate problem: unable to get local issuer certificate 

在发送https请求时，可能会报如上错误，具体解决办法如下：

1. 下载https://curl.haxx.se/ca/cacert.pem
2. 修改php.ini：curl.cainfo=/usr/local/curl/cacert.pem