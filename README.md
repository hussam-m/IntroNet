# Overview
InroNet is a web application for the user to organize conferences and events for a conference. There is a planner who is responsible for organizing the event and a participant who would be attending the event. The participant can be classified into VIP, non-VIP participant. Planner would register the participant for the event. There are two types of event in IntroNet such as One-One and One-Many. In One-One event, the software handles odd number adjustments. As participants move around, a different group of two will convene at a table each time, and extra person will form a group of three. Thus, the extra person gets to meet new people every round just like everyone else. In One-Many scenario participant is allowed to attend multiple posters, which he would like to visit during the event. You can further go through the other sections of this page to know some more information of the project.

# System Requirements
### Operating System (One of them):
- Windows (XP or later, 32-bits or 64-bits)
- Mac ( OS X 10.0 )
- Linux(ubuntu and fedora)

### Hardware requirements
- CPU: x86 processor and above
- RAM: 512MB
- Hard disk: 200MB

### Software requirement for the server side:
- PHP 5.3 and above
- MySQL
- Apache HTTP Server

# Installation
To install the project you have to download WAMP server to your PC from the following link:
http://www.wampserver.com/en/
Once you download the WAMP server configure it and make sure you have PHP interpreter in it. Then, copy the project folder in www folder. Start the WAMP server, then go to the browser and open local-host to run the specified project.

# Source code
IntroNet is written using PHP language. The source code of intronet is avilabe and can be download from https://github.com/hussam-m/IntroNet 

# Configuration
In order to run IntroNet, the configuration file must be created. The name of the configuration file should be “config.php” and it should be located in the same folder where the index.php is. The content of “config.php” should be as the example below. The only this that needs to be change is the database configuration. Replace “localhost” with the database host address of your database. Also, Replace “databaseName” with the name of your database. Finally, replace “username” and “password” with the database username and password. The database user should needs to be authorized to read and write from the database.




```php
;<?php die(); ?>

; Theme
theme="Light"

themes[] = "Light"
themes[] = "Dark"
themes[] = "Orange"
themes[] = "Dark Blue"
themes[] = "Simple"
themes[] = "Modern"

[database]
host= "localhost"
name = "databaseName"
username = "username"
password = "password"

[administer]
username = "admin"
password = "0000"
```
config.php

# Database
IntroNet is using MySQL for the database. The SQL query should be available with the source code. Also it is available in GitHub at https://github.com/hussam-m/IntroNet/tree/0.1/Database . Before running IntroNet, all database tables should be created.

# Testing and Running IntroNet
To run this project you have to copy this project in WAMP server. Make sure you start the server. Once you start the server. You can run the local host and run the project. There is also a user manual which would guide you throughout the project. We completed the White box testing by PHPunit and Black box testing by Selenium.

# Api References
- Selenium - http://www.seleniumhq.org/projects/ide/
- WAMP Server - http://www.wampserver.com/en/
- NetBeans - https://netbeans.org/downloads/

# Contributors
- Hussam Almoharb
- Chakshu Manrao
- Rania Alkhazaali
- Sandeep Apsingekar
