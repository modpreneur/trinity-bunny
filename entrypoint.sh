#!/bin/bash sh

composer update

vendor/codeception/codeception/codecept run

#For run test uncomment
#while true; do sleep 1000; done