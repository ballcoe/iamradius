# iamradius

Implement up to you \
change config mysql in config.php \
default sql in radius/sql \
default username is admin \
default password is radius

# Installation
## On Ubuntu 20.04
## 1. Install Nginx and PHP
```
sudo apt install nginx -y
```
```
sudo apt install php-fpm php-gd php-common php-mail \
php-mail-mime php-mysql php-pear php-db php-mbstring php-xml php-curl -y
```
### Check the version of php installed
```
php -v
```
### Config php.ini for upload file
``` 
sudo nano /etc/php/7.4/fpm/php.ini
```
```
;;;;;;;;;;;;;;;;
; File Uploads ;
;;;;;;;;;;;;;;;;
file_uploads = On
upload_tmp_dir = /tmp/
max_file_uploads = 20
allow_url_fopen = On
allow_url_include = Off

cgi.fix_pathinfo=0
upload_max_filesize = 2G
post_max_size = 2G

;;;;;;;;;;;;;;;;;;;
; Resource Limits ;
;;;;;;;;;;;;;;;;;;;
max_execution_time = 3600
max_input_time = 3600
max_input_vars = 1000
memory_limit = 512M

```
### restart php-fpm
```
systemctl restart php7.4-fpm
```
### Config nginx.conf and site-enabled add client_max_body_size
```
sudo nano /etc/nginx/nginx.conf
```
```
client_max_body_size 2G;
fastcgi_read_timeout 1d;
proxy_read_timeout 1d;
```
### nginx configfile for site.conf Ubuntu
```
cat /dev/null > /etc/nginx/sites-available/default
```
```
server {
	listen 80 default_server;
	listen [::]:80 default_server;

	# SSL configuration
	#
	# listen 443 ssl default_server;
	# listen [::]:443 ssl default_server;
	#
	# Note: You should disable gzip for SSL traffic.
	# See: https://bugs.debian.org/773332
	#
	# Read up on ssl_ciphers to ensure a secure configuration.
	# See: https://bugs.debian.org/765782
	#
	# Self signed certs generated by the ssl-cert package
	# Don't use them in a production server!
	#
	# include snippets/snakeoil.conf;

	root /var/www/iamradius;

	# Add index.php to the list if you are using PHP
	index index.php index.html index.htm;

	server_name iamradius.domain.com;
	
	add_header X-Frame-Options "SAMEORIGIN";
        add_header X-XSS-Protection "1; mode=block";
        add_header X-Content-Type-Options "nosniff";

        location @rewrite {
                rewrite ^/(.*)$ /index.php?_url=/$1;
        }

	error_page 404 /404.html;
	error_page 403 /403.html;
	
	location / {
		# First attempt to serve request as file, then
		# as directory, then fall back to displaying a 404.
		try_files $uri $uri.html $uri/ @extensionless-php;
                index index.html index.htm index.php;
	}
	location @extensionless-php {
                rewrite ^(.*)$ $1.php last;
        }

	location = /favicon.ico { access_log off; log_not_found off; }
        location = /robots.txt  { access_log off; log_not_found off; }

	# pass PHP scripts to FastCGI server
	#
	location ~ \.php$ {
	#	include snippets/fastcgi-php.conf;
	#
	#	# With php-fpm (or other unix sockets):
		try_files $uri =404;
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
		fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
		fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
		include fastcgi_params;
	#	# With php-cgi (or other tcp sockets):
	#	fastcgi_pass 127.0.0.1:9000;
	}

	# deny access to .htaccess files, if Apache's document root
	# concurs with nginx's one
	#
	location ~ /\.ht {
		deny all;
	}
	location ^~ /iamradius/layout/ {
        deny all;
		return 403;
    }
	location ^~ /users/layout/ {
        deny all;
        return 403;
    }
}
```
### Reload nginx
```
sudo service nginx restart
```

## 2. Install MariaDB and Create a database

```
sudo apt update
sudo apt install mariadb-server -y
```
### Change my.cnf
```
sudo mv /etc/mysql/my.cnf /etc/mysql/my.cnf.bak
sudo nano /etc/mysql/my.cnf
```
```
#
# The MySQL database server configuration file.
#
# You can copy this to one of:
# - "/etc/mysql/my.cnf" to set global options,
# - "~/.my.cnf" to set user-specific options.
#
# One can use all long options that the program supports.
# Run program with --help to get a list of available options and with
# --print-defaults to see which it would actually understand and use.
#
# For explanations see
# http://dev.mysql.com/doc/mysql/en/server-system-variables.html

# This will be passed to all mysql clients
# It has been reported that passwords should be enclosed with ticks/quotes
# escpecially if they contain "#" chars...
# Remember to edit /etc/mysql/debian.cnf when changing the socket location.

# Here is entries for some specific programs
# The following values assume you have at least 32M ram

[mysqld_safe]
socket          = /var/run/mysqld/mysqld.sock
nice            = 0
default-character-set=utf8

[mysqld]
#
# * Basic Settings
#
user            = mysql
pid-file        = /var/run/mysqld/mysqld.pid
socket          = /var/run/mysqld/mysqld.sock
port            = 3306
basedir         = /usr
datadir         = /var/lib/mysql
tmpdir          = /tmp
lc-messages-dir = /usr/share/mysql
skip-external-locking
#
# Instead of skip-networking the default is now to listen only on
# localhost which is more compatible and is not less secure.
bind-address            = 0.0.0.0
#
# * Fine Tuning
#
server-id=1
#key_buffer_size                = 512M
max_allowed_packet      = 512M
thread_stack            = 192K
thread_cache_size       = 8
# This replaces the startup script and checks MyISAM tables if needed
# the first time they are touched
myisam-recover-options  = BACKUP
max_connections        = 100
#table_cache            = 64
#thread_concurrency     = 8
default-storage-engine=INNODB
#sql-mode="STRICT_TRANS_TABLES,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION"
table_open_cache=2000
tmp_table_size=16M
thread_cache_size=10
collation-server = utf8_general_ci
init-connect=SET NAMES utf8
character-set-server = utf8
lower_case_table_names = 1
default-time-zone='+07:00'
#
# * Query Cache Configuration
#
query_cache_limit       = 512M
query_cache_size        = 512M
#
# * Logging and Replication
#
# Both location gets rotated by the cronjob.
# Be aware that this log type is a performance killer.
# As of 5.1 you can enable the log at runtime!
#general_log_file        = /var/log/mysql/mysql.log
#general_log             = 1
#
# Error log - should be very few entries.
#
log_error = /var/log/mysql/error.log
#
# Here you can see queries with especially long duration
#log_slow_queries       = /var/log/mysql/mysql-slow.log
#long_query_time = 2
#log-queries-not-using-indexes
#
# The following can be used as easy to replay backup logs or for replication.
# note: if you are setting up a replication slave, see README.Debian about
#       other settings you may need to change.
#server-id              = 1
#log_bin                        = /var/log/mysql/mysql-bin.log
expire_logs_days        = 10
max_binlog_size   = 100M
#binlog_do_db           = include_database_name
#binlog_ignore_db       = include_database_name
#
# * InnoDB
#
# InnoDB is enabled by default with a 10MB datafile in /var/lib/mysql/.
# Read the manual for more InnoDB related options. There are many!
#
# * Security Features
#
# Read the manual, too, if you want chroot!
# chroot = /var/lib/mysql/
#
# For generating SSL certificates I recommend the OpenSSL GUI "tinyca".
#
# ssl-ca=/etc/mysql/cacert.pem
# ssl-cert=/etc/mysql/server-cert.pem
# ssl-key=/etc/mysql/server-key.pem
myisam_max_sort_file_size=100G
myisam_sort_buffer_size=24M
key_buffer_size=256M
read_buffer_size=2M
read_rnd_buffer_size=2M
innodb_flush_log_at_trx_commit=1
innodb_log_buffer_size=64M
innodb_buffer_pool_size=512M
innodb_log_file_size=256M
innodb_autoextend_increment=64
innodb_buffer_pool_instances=8
innodb_concurrency_tickets=5000
innodb_old_blocks_time=1000
innodb_open_files=300
innodb_stats_on_metadata=0
innodb_file_per_table=1
innodb_checksum_algorithm=0
query_cache_type=0
sort_buffer_size=256K
table_definition_cache=1400
binlog_row_event_max_size=8K

[mysql]
no-auto-rehash
default-character-set=utf8

```
### Create database for radius
```
create database radius;
```
### Change root User for radius database
```
update mysql.user set plugin = "" where user = "root";
ALTER USER 'root'@'localhost' IDENTIFIED BY 'password';
```
### Create User for radius database
```
create user 'radius'@'localhost' identified by 'radiuspassword';
grant all privileges on radius.* to 'radius'@'localhost';
flush privileges;
```

## 3. Install and Configure FreeRADIUS
### Install FreeRADIUS v3.0
```
sudo apt-get install freeradius freeradius-mysql freeradius-utils -y
```
### Import the freeradius MySQL database scheme
```
mysql -u root -p radius < /etc/freeradius/3.0/mods-config/sql/main/mysql/schema.sql
```
### Check tables created
```
mysql -u root -p -e "use radius;show tables;"
```
### Create a soft link for sql module
```
ln -s /etc/freeradius/3.0/mods-available/sql /etc/freeradius/3.0/mods-enabled/
```
### Configure SQL module and change the database connection
```
sudo nano /etc/freeradius/3.0/mods-enabled/sql
```
### Change sql section
```
sql {
driver = "rlm_sql_mysql"
dialect = "mysql"

# Connection info:
server = "localhost"
port = 3306
login = "<user>"
password = "<password>"

# Database table configuration for everything except Oracle
radius_db = "radius"
}

# Set to ‘yes’ to read radius clients from the database (‘nas’ table)
# Clients will ONLY be read on server startup.
read_clients = yes

# Table to keep radius client info
client_table = "nas"
```
### Change group right of /etc/freeradius/3.0/mods-enabled/sql
```
chgrp -h freerad /etc/freeradius/3.0/mods-available/sql
chown -R freerad:freerad /etc/freeradius/3.0/mods-enabled/sql
```
### Change Secert key for radius
```
nano /etc/freeradius/3.0/clients.conf
_____________________________________
*
    secret = <secert key> ;                 #line 100
*
    client localhost_ipv6 {                 #line 220
        ipv6addr        = ::1               #line 221
        secret          = <secert key>      #line 222
    }                                       #line 223
*
    client private-network-1 {              #line 241
        ipaddr          = 192.168.0.0/16    #line 242
        secret          = <secert key>      #line 243
    }                                       #line 244
*
    
```
### Restart freeradius service
```
systemctl restart freeradius.service
```
### Enable freeradius service
```
update-rc.d freeradius enable
```
## 4. Install IAMRadius
### Dump Mysql IAM Radius
```
mysql -u root -p radius < iamradius.sql
```
### Clone IAMRadius
```
cd /var/www/
git clone https://github.com/ballcoe/iamradius
```
