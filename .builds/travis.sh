#!/bin/bash

STEP=$1
TEST=$2

case "$STEP" in
    install)

        echo "Installing..."
        if [ -d vendor ]; then
            chmod 777 -R vendor
            rm -r vendor
        fi
        COMPOSER=dev.json composer install
    ;;
    script)

        echo "Run tests...";
        if [ ! -d vendor ]; then
            echo "Application not installed. Tests stopped. Exit with code 1"
            exit 1
        fi

        case "$TEST" in
            unit)
                echo "Run  phpunit --verbose --testsuite=unit...";
                php vendor/bin/phpunit --verbose --testsuite=unit
            ;;
            phpcs)
                echo "Run phpcs --encoding=utf-8 --extensions=php --standard=psr2 Okvpn/ -p...";
                php vendor/bin/phpcs --encoding=utf-8 --standard=psr2 -p src
            ;;
        esac
    ;;
esac
