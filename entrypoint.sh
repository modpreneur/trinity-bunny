#!/bin/bash sh

composer update

vendor/codeception/codeception/codecept run

while true; do sleep 1000; done