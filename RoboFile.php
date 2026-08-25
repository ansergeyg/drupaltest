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

  function drmodules() {
    $this->taskExec('drush en language locale content_translation config_translation workflows content_moderation -y')->run();
    $this->taskExec('drush config:import --partial --source=web/core/recipes/editorial_workflow/config -y')->run();
    $this->taskExec("drush config:set --input-format=yaml workflows.workflow.editorial type_settings.entity_types.node '[article]' -y")->run();
    $this->taskExec('drush language:add fr,nl')->run();
    $this->taskExec('drush config:set language.negotiation url.prefixes.en en -y')->run();
    $this->taskExec('drush config:set language.negotiation url.prefixes.fr fr -y')->run();
    $this->taskExec('drush config:set language.negotiation url.prefixes.nl nl -y')->run();
  }
}
