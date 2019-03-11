#!/usr/bin/env bash
apt-get update
apt-get upgrade
apt-get install -yq git autoconf libaio-dev

cd /root/
git clone https://github.com/swoole/swoole-src.git
cd swoole-src/
phpize
./configure
make && make install
rm -rf /var/cache/apk/*
rm -rf /root/swoole-src/
rm -rf /tmp/*

echo "extension=swoole" | tee -a /usr/local/etc/php/conf.d/swoole.ini