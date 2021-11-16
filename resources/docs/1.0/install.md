# README
Pacific Cross

# Php version
 7.2

# System dependencies

 mysql 5.8
 nginx/1.15.8  or Apache2.4
 nodejs 

# Prerequisites
 One Ubuntu 18.04 server or Centos 7, and a non-root user with sudo privileges.
 composer install follow link : https://getcomposer.org/doc/00-intro.md#installation-linux-unix-macos
 

# Step 1: Checkout source code from SVN and Installing Dependencies
```
https://192.168.0.10:8443/svn/ClaimOCR

```

 Move into the ClaimOCR directory:
```
cd ClaimOCR
```

 Set permissions on the project directory so that it is owned by your non-root user:
```
sudo chown -R $USER:$USER ClaimOCR
```

 Make a copy of the .env.example file that Laravel includes by default and name the copy .env, which is the file Laravel expects to define its environment:
```
cp .env.example .env
```

# Step 2: Database creation

 Log into the MySQL root administrative account:
```
mysql -u root -p
```

 Create the user account that will be allowed to access this database
```
GRANT ALL ON pacific_cross.* TO 'pacific_cross'@'%' IDENTIFIED BY 'p@ssword#';
FLUSH PRIVILEGES;
```

 Exit MySQL:
```
EXIT;
```


# Step 3 - Update local env and setting application key, clear configuration cache
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

# Step 4 -  Migrating Data
```
php artisan migrate:fresh --seed
```

# Step 5  -  Start Node JS Server
 Go to project directory using Terminal / CMD Open 
```
sudo yum install nodejs
npm install
export NODE_ENV=dev
npm start
```

# Step 6 -  Setup Script crontab
```
crontab -e
  * * * * * php /var/www/html/tos_admin/artisan schedule:run >> /dev/null 2>&1 
```

# Step 7 - Setup Police 
```
php artisan make:policy PostPolicy --model=Post
...
```
# step 8 - create permission 
```
php artisan auth:permission claim
php artisan auth:permission product
php artisan auth:permission term
```

# step 9 - render Document
```
php artisan larecipe:docs
```

