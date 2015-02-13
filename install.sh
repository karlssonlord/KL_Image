#!/bin/sh

php -S localhost:8081 -t src/ &

wget https://raw.githubusercontent.com/netz98/n98-magerun/master/n98-magerun.phar

php n98-magerun.phar install --noDownload --dbHost="127.0.0.1" --dbUser="root" --dbPass="topsecret" --dbName="kl_image_test" --useDefaultConfigParams=yes --installationFolder="src" --baseUrl="http://localhost:8081/"