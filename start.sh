#!/bin/bash

ip="$(ifconfig | grep inet | grep -v 127.0.0.1 | grep -v inet6 | awk '{print $2}' | tr -d "addr:")"
# echo http://$ip:8000 | pbcopy
cd $(dirname $0)
open http://$ip:8000
php -c ./ -S $ip:8000
