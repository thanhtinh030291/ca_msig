# README
Pacific Cross

# Php version
* 7.2

# System dependencies

* mysql 5.8
* nginx/1.15.8  or Apache2.4

# Prerequisites
* One Ubuntu 18.04 server, and a non-root user with sudo privileges.

# Step 1: Checkout source code from github repository and Installing Dependencies
```
cd ~
git clone  https://github.com/thanhtinh030291/thanhtinh.git  pacific_cross
```

* Move into the pacific_cross directory:
```
cd ~/pacific_cross
```

* Switch into branch develop
```
git checkout develop
```

* Set permissions on the project directory so that it is owned by your non-root user:
```
sudo chown -R $USER:$USER ~/pacific_cross
```

* Make a copy of the .env.example file that Laravel includes by default and name the copy .env, which is the file Laravel expects to define its environment:
```
cp .env.example .env
```

# Step 3: Database creation

* Log into the MySQL root administrative account:
```
mysql -u root -p
```

* Create the user account that will be allowed to access this database
```
GRANT ALL ON pacific_cross.* TO 'pacific_cross'@'%' IDENTIFIED BY 'p@ssword#';
FLUSH PRIVILEGES;
```

* Exit MySQL:
```
EXIT;
```


# Step 4 - Update local env and setting application key, clear configuration cache
```
cp .env.example .env
 vi .env
```
```
#DB_HOST will be your db database container.
#DB_DATABASE will be the dbName database.
#DB_USERNAME will be the username you will use for your database. In this case, we will use freeplus_tos.
#DB_PASSWORD will be the secure password you would like to use for this user account.
```

```
php artisan key:generate
php artisan storage:link
php artisan config:cache
php artisan cache:clear
composer dump-autoload
```

# Step 5 -  Migrating Data
```
php artisan migrate:fresh --seed
```
# Step 6 -  Setup JWT
php artisan jwt:secret

# Step 7 -  Setup google API
* add in file .env
```
client_id=979002650695-lgngcoinb4gb8uq4pbm94recnaslgu1d.apps.googleusercontent.com
client_secret =QylCuB6B0IequyszusNbFuKY
redirect =http://ec2-52-197-98-206.ap-northeast-1.compute.amazonaws.com/auth/google/callback

```
# Step 8  -  Start Node JS Server
* Go to project directory using Terminal / CMD Open 
```
sudo yum install nodejs
npm install

export NODE_ENV=dev
npm start

```
# Step 9 -  Setup Script crontab
crontab -e
 * * * * * php /var/www/html/tos_admin/artisan schedule:run >> /dev/null 2>&1
 sudo service cron reload

# Step 10 - Setup Police 
php artisan make:policy PostPolicy --model=Post
...

# step 11 - create permission 
php artisan auth:permission claim
php artisan auth:permission product
php artisan auth:permission term
php artisan auth:permission provider
php artisan auth:permission reason_reject
php artisan auth:permission letter_template 


# step 12 - pusher notifition
add to .env
PUSHER_APP_KEY=778c463afa5314725eda
PUSHER_APP_SECRET=2f138838a645af01ad3c
PUSHER_APP_ID=891772
PUSHER_APP_CLUSTER=ap1

# step 13 - notify
php artisan webpush:vapid 

appId: a640ac782552e93f
key: efaa761e84c08024d437f370eaa2a564

# php artisan larecipe:docs


# bao tri
php artisan down --message="Upgrading systems" --retry=60  --allow=192.168.0.16
php artisan up 
php artisan down --allow=::1 

gs -dNOPAUSE -sDEVICE=pdfwrite -sOUTPUTFILE=combine.pdf -dBATCH 1.pdf 2.pdf
gs -sDEVICE=pdfwrite -dNOPAUSE -dBATCH -dSAFER -dFirstPage=1 -dLastPage=2 -sOutputFile=OUTPUT.pdf ORIGINAL.pdf

sudo mount  -t cifs -o username=fubonprod,password=F2e1j3dNf2dX4sKn8W6Fe,vers=1.0,_netdev //192.168.0.233/bshprod_hbs_report /var/www/html/bshprod_hbs_report
