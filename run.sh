#!/bin/bash

# Running the databse
docker-compose up -d

# Wait 5 seconds for the database to start running
sleep 5s

# Installing composer and PHPMailer
cd src
composer update

# Running PHP
php -S localhost:8080
