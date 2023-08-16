<?php

use Robo\Symfony\ConsoleIO;

class RoboFile extends \Robo\Tasks
{
  /*
    Help command to list all available commands.
  */
  function drhelp($word) {
    $this->say("To install clean drupal website run: vendor/bin/robo drup");
  }

  /*
    Command to install clean drupal website.

    For some reason options injected into a method 
    have unnecessary options that are not needed fo this method. 
    
    We have to remove them by using array_splice method. 
  */
  function drup(ConsoleIO $io, $opts = [
    'account-name' => '',
    'account-pass' => '', 
    'site-name' => '', 
    'locale' => '', 
    'site-mail' => '', 
    'db-url' => '']) {
    
    $ACTUAL_OPTIONS_SIZE = 6;

    $this->taskExec('drush si standard -y')->options(
      array_slice($opts,0, $ACTUAL_OPTIONS_SIZE), '=')->run();
  }
}
