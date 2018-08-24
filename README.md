# Installation
***NOTE: If your server hosting provider already installed Apache Web server, you can skip to step 5***<br />
***NOTE: If your server hosting provider already installed Apache Web server and does not support SSH access. you can just simply upload the master branch to root directory*** <br />
**1. Install Ubuntu Server 18.04 (https://www.ubuntu.com/download/server)** <br />
**2. Login to Ubuntu with credentials you set.** <br />
**3. Become root**
```bash
sudo passwd root
```
Write you current password, then set new root password
```bash
exit
```
Login as root <br />
**4. Install LAMP pack**<br />
Note: apt will tell you which packages it plans to install and how much extra disk space they'll take up. Press Y and hit Enter to continue, and the installation will proceed.
```bash
apt update
apt install apache2
apt install mysql-server
mysql_secure_installation
```
You will be asked if you want to configure the VALIDATE PASSWORD PLUGIN. Answer **y**<br />
Then you'll be asked to select a level of password validation. Answer **0** and set your mysql password.<br />
You'll be asked for a few more things, answer **y** to all.
```bash
apt install php libapache2-mod-php php-mysql
nano /etc/apache2/mods-enabled/dir.conf
```
We want to move the PHP index file to the first position after the DirectoryIndex specification: <br />
**DirectoryIndex index.html index.cgi index.pl index.php index.xhtml index.htm** -> **DirectoryIndex index.php index.html index.cgi index.pl index.xhtml index.htm**<br />
Configuration will look like this:
```html
<IfModule mod_dir.c>
    DirectoryIndex index.php index.html index.cgi index.pl index.xhtml index.htm
</IfModule>
```
When you are finished, save and close the file by pressing Ctrl-X. You'll have to confirm the save by typing Y and then hit Enter to confirm the file save location.
```bash
service apache2 restart
nano /var/www/html/index.php
```
This will open a blank file. We want to put the following text, which is valid PHP code, inside the file:
```php
<?php
phpinfo();
```
When you are finished, save and close the file.<br />
Now we can test whether our web server can correctly display content generated by a PHP script. To try this out, we just have to visit this page in our web browser. You'll need your server's public IP address.
```bash
curl http://icanhazip.com
```
Open in your web browser: http://your_server_IP_address <br />
This page basically gives you information about your server from the perspective of PHP. It is useful for debugging and to ensure that your settings are being applied correctly.<br />
<br />
If this was successful, then your PHP is working as expected.<br />
<br />
You probably want to remove this file after this test because it could actually give information about your server to unauthorized users. To do this, you can type this:
```bash
rm /var/www/html/index.php
```
**5.Clone TechnicSolder repository** 
```bash
cd /var/www/
git clone https://github.com/TheGameSpider/TechnicSolder.git
nano /etc/apache2/sites-enabled/000-default.conf
```
Change DocumentRoot to /var/www/TechnicSolder<br />
Add this before <xmp></VirtualHost></xmp> close tag:
```html
<Directory /var/www/TechnicSolder>
        Options Indexes FollowSymLinks MultiViews
        AllowOverride All
        Require all granted
    </Directory>
```
Enable RewriteEngine
```bash
a2enmod rewrite
service apache2 restart
```
Installation is complete. Now you need to confige TechnicSolder before using it.
# TechnicSolder Configuration
**1. Configure MySQL**
```bash
mysql -p -u root
```
Login with your password you set earlier. <br />
Create new user
```MYSQL
CREATE USER 'solder'@'localhost' IDENTIFIED BY 'secret';
```
**NOTE: By writing *IDENTIFIED BY 'secret'* you set your password. Dont use *secret***<br />
Create database solder and grant user *solder* access to it.
```MYSQL
CREATE DATABASE solder;
GRANT ALL ON solder.* TO 'solder'@'localhost';
exit
```
Open your TechnicSolder configuration.
```bash
nano /var/www/TechnicSolder/config.php
```
Change **db-user** to **solder**<br />
Change **db-pass** to password you set for user *solder*<br />
**2. Configure login credentials**<br />
There is only one thing to say: *Do not use the password that you are using to login to your email.*<br />
**3. Link TechnicSolder with your https://technicpack.net account**<br />
First, go to your profile and click *Edit Profile*<br />
Then click *Solder Configuration* and copy your API Key<br />
In your config.php file set api_key to key you copied.<br />
Now, you can save the config by pressing Ctrl-X. You'll have to confirm the save by typing Y and then hit Enter to confirm.<br />
The final step is to set your Solder URL in Solder Configuration (In your https://technicpack.net profile)
```http
http://your_server_IP_address/api/
```
Click **Link solder**<br />
That's it. You have successfully installed and configured TechnicSolder. It's ready to use!