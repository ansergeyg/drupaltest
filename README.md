## If you need to install clean drupal website you can use this repository.


It has minial configuration.

0) Install ahoy binary command mamanger (source: )

1) Clone repository

2) If you have apache2.4 + mysql 5.7 + php >= 7.1 you can just run: composer install

3) If you don't want to install all that separately you can use docker-compose. Run ahoy up 

4) If you need to change drupal version then update composer.json (currently drupal/core is 9.5)


## If you need to add more configuration options during the installation of the clean drupal website

you need to edit two files:

1) robo.yml (add more options here)
2) RoboFile.php (increase $ACTUAL_OPTIONS_SIZE respectively)