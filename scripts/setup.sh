sudo apt-get install lamp-server^
sudo apt install openssh-client
sudo apt install openssh-server
echo "ServerName localhost" | sudo tee /etc/apache2/conf-available/fqdn.conf && sudo a2enconf fqdn
sudo apt install mysql-server
sudo apt install pv
service apache2 restart
apt-get install mysql-server libapache2-mod-auth-mysql php5-mysql php-mcrypt
apt-get install mysql-workbench
apt-get install php-mcrypt
mysql -u root
SET PASSWORD FOR 'root'@'localhost' = PASSWORD('yourpassword');
GRANT ALL PRIVILEGES ON *.* TO 'yourusername'@'localhost' IDENTIFIED BY 'yourpassword' WITH GRANT OPTION;
GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, DROP, INDEX, ALTER, CREATE TEMPORARY TABLES, LOCK TABLES ON ccpoints.* TO 'dano'@'localhost' IDENTIFIED BY 'chesed';
mysqladmin -u root -p password chesed
ip addr show eth0 | grep inet | awk '{ print $2; }' | sed 's/\/.*$//'
sudo apt-get install curl
curl http://icanhazip.com
// to tunnel to mysql
 ssh -L 3470:127.0.0.1:3306 dano@192.168.45.205 &
 GRANT ALL PRIVILEGES ON *.* TO 'dano'@'localhost';
GRANT USAGE ON *.* TO 'dano'@'localhost' WITH GRANT OPTION;
 
// Slim
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('SHA384', 'composer-setup.php') === '669656bab3166a7aff8a7506b8cb2d1c292f042046c5a994c43155c0be6190fa0355160742ab2e1c88d40d5be660b410') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"
mv composer.phar /usr/local/bin/composer


sudo mkdir -p /var/www/ccpoints/frontend/html
sudo mkdir -p /var/www/ccpoints/backend
sudo chgrp -R dano /var/www/ccpoints
sudo chmod -R a+w /var/www/ccpoints
sudo cp /etc/apache2/sites-available/000-default.conf /etc/apache2/sites-available/ccpoints.conf
sudo vim /etc/apache2/sites-available/ccpoints.conf
sudo a2ensite ccpoints

sudo mkdir -p /etc/ostent
sudo chgrp -R dano /etc/ostent
sudo chmod -R a+w /etc/ostent

sudo mkdir -p /var/www/ccapi
sudo mkdir -p /var/www/ccapi/src
sudo chgrp -R dano /var/www/ccapi
sudo chmod -R a+w /var/www/ccapi
sudo cp /etc/apache2/sites-available/000-default.conf /etc/apache2/sites-available/ccapi.conf
sudo a2ensite ccapi
cd /var/www/ccapi/src
composer require slim/slim "^3.0"

cd /etc/apache2/sites-available/
sudo cat /etc/apache2/sites-available/ccpoints.conf | sed 's#/var/www#/var/www/ccpoints#;s#Directory /'

cp /etc/apache2/sites-
sudo htpasswd -c /usr/local/src/safe/.htpasswd dano chesed
.htaccess
echo 'RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^ index.php [QSA,L]

AddOutputFilterByType DEFLATE application/json

AuthUserFile /usr/local/src/safe/.htpasswd
AuthName "Pwd"
AuthType Basic
Require valid-user
'>/var/www/ccapi/html/.htaccess

.conf
<Directory /var/www/ccapi>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
</Directory>

 curl http://ccapi.com/meta/tablescurl > scripts/tablescurl.sh
 curl http://ccapi.com/meta/client_financials>scripts/c.sh
