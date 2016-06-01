#!/bin/bash
DATE=`date +%Y-%m-%d`
while : ; do
    read line
    [ -z "$line" ] && exit
    IP=`echo $line | cut -d' ' -f10 | cut -d: -f1`
    echo $line >> /var/www/html/logs/error_${IP}.log
    chown www-data:www-data /var/www/html/logs/error_${IP}.log
done

