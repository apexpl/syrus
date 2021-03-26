
# Designers - Getting Started

If you're a designer who has been tasked with a Syrus theme, this documentation will quickly step you through everything you need to know.  It's assumed you are familiar with docker-compose, and if not, please install docker-compose by following the steps at [Install docker-compose Guide](https://docs.docker.com/compose/install/).

## Setup Local Server

You do not need PHP installed on your system to create Syrus themes.  Instead, it's recommended to spin up a temporary local server via docker-compose with the commands:

~~~
git clone https://github.com/apexpl/syrus.git mysite
cd mysite
sudo docker-compose up -d
~~~

That's it, and a new site running Syrus may now be found at http://127.0.0.1:8180/.  Alternatively, if you have PHP running on your system, you may utilize PHP's built-in server instead by running the commands:

~~~
composer update
cd public
php -S 127.0.0.1:8180
~~~

Please note, you may wish to contact the back-end developers and ask them to provide you a vars.php file, which should be replaced as the /public/vars.php file within the Syrus installation.  This will provide dummy data for all necessary template variables and blocks the back-end will support.


## Create Theme

If this is a brand new site / project, you may create a new blank theme with the commands:

~~~
cp -R ./config/skel ./views/themes/mysite
mv ./views/mysite/public ./public/themes/mysite
~~~

Replace `mysite` to anything you wish, and this will provide you a bare skeleton theme to start with.  Additionally, you will want to open the ~/config/site.yml file, and at the top in the `themes` section change the default theme to the newly created theme, for example:

~~~
themes:
  default: mysite
~~~

This will ensure all pages are displayed using the newly created `mysite` theme.


Once setup, please continue by visiting the [File / Directory Structure](theme_structure.md) page of the documentation.

