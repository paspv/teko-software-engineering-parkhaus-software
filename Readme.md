# Parkhaus-Software 

## Requirements
 - Docker is installed (e.g. https://docs.docker.com/engine/install/)
 - DDEV is installed (https://ddev.com/get-started/)

## Setup

1. Pull project
1. Enter directory with `cd teko-software-engineering-parkhaus-software`
1. Run `ddev start`
1. Run `ddev composer install`
1. Run `ddev import-db` -> choose `db.sql` file

## Access prototype

Run `ddev launch` to open the page in your browser.

## Stop the project

Run `ddev stop`