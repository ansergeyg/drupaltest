<?php

use Robo\Symfony\ConsoleIO;

class RoboFile extends \Robo\Tasks
{

  function drhelp($word) {
    $this->say("To install clean drupal website run: vendor/bin/robo drup");
  }


  function drup(ConsoleIO $io, $opts = ['account-name' => '','account-pass' => '', 'site-name' => '', 'locale' => '', 'site-mail' => '', 'db-url' => '']) {

    // $install_params = array_map(function ($key, $value) use ($opts) { 
    //   return array_key_exists($key, $opts) ? sprintf('--%s=%s', $key, $value) : '';
    // }, array_keys($opts), array_values($opts));
    
    // $io->say("DUMP:");
    // var_dump($install_params);

    $this->taskExec('drush si standard')->options(array_slice($opts,0, 6), '=')->run();
  }
}
