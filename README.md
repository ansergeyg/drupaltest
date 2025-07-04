## Clean drupal website installation for testing purposes.


It has minial configuration.

1) Install ahoy binary command manager if you don't have it (https://github.com/ahoy-cli/ahoy)

2) Clone repository

If you have docker just run:

1) ahoy up
2) ahoy drup


If you don't want docker then you should have apache2.4 + mysql 8.0 + php >= 8.3 installed. 

1) composer install
2) open localhost in the brawser and install the website


If you need to change drupal version then update composer.json (currently drupal/core is 10.5)


## If you need to add more configuration options during the installation of the clean drupal website

you need to edit two files:

1) robo.yml (add more options here)
2) RoboFile.php (increase $ACTUAL_OPTIONS_SIZE respectively)