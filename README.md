# Details

OStest is a light weigh multiple choice testing application I built. I needed a small web application to test peoples knowledge on the cheap.

# How to Install.

## Requirements

* PHP 7.2 +
* MySQL 5.7 +
* Web Server

## Install Steps

1. copy files to root of virtual directory on web server
2. Edit the sql.php file and update database settings
3. Open the index.php file from your web browser e.g. http://mydomain.com/ or http://mydomain.com/index.php
4. After a little time you will be redirected back to the index page an see the demo data
5. go the http://mydomain.com/admin.php to manage the tests, settings and view results 

> If the details supplied on the sql.php page is correct it will create all needed tables, constraints and insert demo test.

> For more help go to http://mydomain.com/help and view the pdf file there.

> Note that I have not yet built in any authentication on the admin.php page so please use the web server to handle authentication to that page for time being
