# Details

OStest is a lightweight multiple choice testing application. I needed a small web application to test people's knowledge on the cheap.

# How to Install.

## Requirements

* PHP 7.2 +
* MySQL 5.7
* Web Server

## Install Steps

1. Copy Files
2. Setup DB
3. Using as Docker
4. Manage Application 

## Note:

This web app may contain security issues please deploy behind a WAF that if you are going to use this on the internet.
A good idea would be to deploy this in an environment that has no sensitive data.
NB: I will take no any responsibility for this application as per the MIT license, if you need help or what me to modify the application send me an email reiniernel89@gmail.com

## 1 Copy Files

You have a couple of options here
Clone the repo to the web directory, below is an example if you are using apache2 on debian or ubuntu

`git clone https://github.com/ReinierNel/OSTest.git /var/www/html/`

Download as zip and then copy to web server and extract
	
`scp OSTest-master.zip [youruser]@[your.webserver.com]:/tmp/`
`unzip OSTest-master.zip`
`mv OStest-master/* /var/www/html/`

Download as ZIP, unzip locally and copy files to web server

`unzip OSTest-master.zip`
`scp Ostest-master/* [youruser]@[your.webserver.com]:/var/www/html/`

## 2 Setup DB

Create a database on mysql web server.

`CREATE DATABASE ostest;`

You have 2 options on adding the database details to the application

Set environment variables

`export ostest_use_env_var=true`
`export ostest_sqlserver=[your db server address]`
`export ostest_sqluser=[your db user]`
`export ostest_sqlpwd=[your db user password]`
`expose ostest_sqldb=[the db name]`

Edit the sql.php file with your database servers details on line 11, 12, 13 and 14
	
`//edit this for manual`
`$sqlServer = "[your db server address]";`
`$sqlUser = "[your db user]";`
`$sqlPassword = "[your db user password]";`
`$sqlDatabase = "[the db name]";`

Once the settings has been set up you can go to `http://[your.webserver.com]/` and wait for the page to load, note the first load will take some time as the app is setting up the db once done you will be presented with a page that contains a demo test.

3. Using as Docker

Use the following command to run the image

`docker run --name ostest -itd -p 0.0.0.0:8080:80 --env ostest_use_env_var=true --env ostest_sqlserver=[your db server address] --env ostest_sqluser=[your db user] --env ostest_sqlpwd=[your db user password] --env ostest_sqldb=[the db name] reiniernel89/ostest:tagname`

Optional you can create a also run an instance of mysql just not you will need to setup president storage and also have a working internal docker network with dns name resolution.

`docker run --name dbsrv -itd -p 0.0.0.0:3306:3306 --env MYSQL_ROOT_PASSWORD=[root password] --env MYSQL_RANDOM_ROOT_PASSWORD=yes --env MYSQL_DATABASE=[db name for use with ostest] --env MYSQL_USER=[mysql user for use with ostest] --env MYSQL_PASSWORD=[mysql user password for use with ostest] mysql:5.7`

##NOTE
Make sure to remove the square brakes and update the information to your values.

## 4 Manage Application

Default username: `admin`
Default password: `admin`

Go to go to `http://[your.webserver.com]/admin.php` and login there. Once logged in go to the users tab and add a user for yourself and delete the admin user.

For instructions on how the app works go to `http://[your.webserver.com]/help/ostest.pdf`.