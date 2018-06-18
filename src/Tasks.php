<?php

namespace D8P\Robo;

use Robo\Robo;
use Robo\Tasks as RoboTasks;

/**
 * Class Tasks.
 *
 * @package D8P\Robo\Task\Build
 */
class Tasks extends RoboTasks {
  use \Boedah\Robo\Task\Drush\loadTasks;
  use \NuvoleWeb\Robo\Task\Config\loadTasks;

  /**
   * Output hostname.
   */
  public function ec2Hostname() {
    $result = $this
      ->taskExec('curl http://169.254.169.254/latest/meta-data/public-hostname')
      ->printOutput(false)
      ->run();
    if($result->wasSuccessful()) {
      $hostname = $result->getMessage();
      $this->say($hostname);
    }
  }

  /**
   * Output ip.
   */
  public function ec2Ip() {
    $result = $this
      ->taskExec('curl http://169.254.169.254/latest/meta-data/local-ipv4')
      ->printOutput(false)
      ->run();

    if($result->wasSuccessful()) {
      $ip = $result->getMessage();
      $this->say($ip);
    }
  }

  /**
   * Setup Behat.
   *
   * @command project:setup-behat
   * @aliases psb
   */
  public function projectSetupBehat() {
    $project_tokens = $this->config('project.tokens');
    $behat_tokens = $this->config('behat.tokens');

    $this->collectionBuilder()->addTaskList([
      $this->taskFilesystemStack()
        ->copy($this->config('behat.source'), $this->config('behat.destination'), TRUE),
      $this->taskReplaceInFile($this->config('behat.destination'))
        ->from(array_keys($behat_tokens))
        ->to($behat_tokens),
      $this->taskReplaceInFile($this->config('behat.destination'))
        ->from("{root}")
        ->to($this->config('project.root')),
      $this->taskReplaceInFile($this->config('behat.destination'))
        ->from("{url}")
        ->to($this->config('project.url')),
    ])->run();
  }

  /**
   * Install site.
   *
   * @command project:install
   * @aliases pi
   */
  public function projectInstall() {
    $this->getInstallTask()
      ->siteInstall($this->config('site.profile'))
      ->run();
    $this->projectSetupSettings();
  }

  /**
   * Install site from given configuration.
   *
   * @command project:install-config
   * @aliases pic
   */
  public function projectInstallConfig() {
    $this->getInstallTask()
      ->arg('config_installer_sync_configure_form.sync_directory=' . $this->config('settings.config_directories.sync'))
      ->siteInstall('config_installer')
      ->run();
    $this->projectSetupSettings();
  }

  /**
   * Setup Drupal settings.
   *
   * @command project:setup-settings
   * @aliases pss
   */
  public function projectSetupSettings() {
    $settings_file = $this->root() . '/web/sites/default/settings.php';
    $processor = new SettingsProcessor(Robo::config());
    $content = $processor->process($settings_file);
    $this->collectionBuilder()->addTaskList([
      $this->taskFilesystemStack()->chmod('web/sites', 0775, 0000, TRUE),
      $this->taskWriteToFile($settings_file)->text($content),
    ])->run();
  }

  /**
   * Get installation task.
   *
   * @return \Boedah\Robo\Task\Drush\DrushStack
   *   Drush installation task.
   */
  protected function getInstallTask() {
    return $this->taskDrushStack($this->config('bin.drush'))
      ->arg("--root={$this->root()}/web")
      ->siteName($this->config('site.name'))
      ->siteMail($this->config('site.mail'))
      ->locale($this->config('site.locale'))
      ->accountMail($this->config('account.mail'))
      ->accountName($this->config('account.name'))
      ->accountPass($this->config('account.password'))
      ->dbPrefix($this->config('database.prefix'))
      ->dbUrl(sprintf("mysql://%s:%s@%s:%s/%s",
        $this->config('database.user'),
        $this->config('database.password'),
        $this->config('database.host'),
        $this->config('database.port'),
        $this->config('database.name')));
  }

  /**
   * Get root directory.
   *
   * @return string
   *   Root directory.
   */
  protected function root() {
    return getcwd();
  }

}
