All-about
=========

Hello and welcome to All-about. All-about is a template project to a question-site, a simple site similar to Stack Overflow. Users can create questions, answer questions and gather points. If the creator of a question finds the correct answer among the comments, he can mark the answer as such, giving extra points to the submitter. 

A fun way of learning new things! 

Installation
------------

To get your installation of All-about, simply follow the steps below. 

Get a clone of the project using git clone. 

Select a theme and a topic for your installation in the file app/config/theme.php. In this file, you set the title of the site, tagline and include your custom css files. 

Clear the database file and setup a fresh one. For simplicity, this project is using SQLite as database. You can also choose to use MySQL as database by using the config_mysql.php instead of the current config_sqlite.php in index.php. Those files are located in app/config.

This project heavily depends on rerouting the urls to get the beautiful urls. This requires you to reroute requests to the index.php file by pointing all requests to this file in the .htaccess file located in /webroot/.

Now you are ready to ask and answer questions! 

Good luck! :)

Anax-MVC
--------

This template project uses Anax-MVC as framework. Anax-MVC is developed by Mikael Roos. See the Anax-MVC project at Github for more information on the framework and included 3pp softwares. 

[Anax-MVC on Github](https://github.com/mosbth/Anax-MVC)

Acknowledgements
----------------

In addition to the 3pp softwares used by Anax-MVC, this installation also includes: 

* [Font-Awesome](http://fortawesome.github.io/Font-Awesome/) 