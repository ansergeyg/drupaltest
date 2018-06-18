<?php

namespace D8P\Robo;

use Robo\Config\Config;

/**
 * Class SettingsProcessor.
 *
 * @package EC\OpenEuropa\Robo
 */
class SettingsProcessor {
  /**
   * Root key in YAML configuration file.
   */
  const CONFIG_KEY = 'settings';
  /**
   * Comment starting settings processor block.
   */
  const BLOCK_START = "// Start settings processor block.";
  /**
   * Comment ending settings processor block.
   */
  const BLOCK_END = '// End settings processor block.';
  /**
   * Robo configuration object.
   *
   * @var \Robo\Config\Config
   */
  private $config;

  /**
   * SettingsProcessor constructor.
   */
  public function __construct(Config $config) {
    $this->config = $config;
  }

  /**
   * Process settings file.
   *
   * @param string $settings_file
   *   Full path to settings file to be processed, including its name.
   *
   * @return string
   *   Processed setting file.
   */
  public function process($settings_file) {
    $output[] = $this->getContent($settings_file);
    $output[] = '';
    $output[] = self::BLOCK_START;
    $output[] = '';
    foreach ($this->config->get(self::CONFIG_KEY) as $variable => $settings) {
      foreach ($settings as $name => $value) {
        $output[] = $this->getStatement($variable, $name, $value);
      }
      $output[] = '';
    }
    $output[] = self::BLOCK_END;
    return implode($output, "\n");
  }

  /**
   * Get settings file content.
   *
   * @param string $settings_file
   *   Full path to settings file to be processed, including its name.
   *
   * @return string
   *   File content without setting block.
   */
  private function getContent($settings_file) {
    $content = file_get_contents($settings_file);
    $regex = "/^" . preg_quote(self::BLOCK_START, '/') . ".*?" . preg_quote(self::BLOCK_END, '/') . "/sm";
    return preg_replace($regex, '', $content);
  }

  /**
   * Get variable assignment statement.
   *
   * @param string $variable
   *   Variable name.
   * @param string $name
   *   Setting name.
   * @param mixed $value
   *   Setting value.
   *
   * @return string
   *   Full statement.
   */
  private function getStatement($variable, $name, $value) {
    $output = var_export($value, TRUE);
    if (is_array($value)) {
      $output = str_replace(' ', '', $output);
      $output = str_replace("\n", "", $output);
      $output = str_replace("=>", " => ", $output);
      $output = str_replace(",)", ")", $output);
      $output = str_replace("),", "), ", $output);
    }
    return sprintf('$%s["%s"] = %s;', $variable, $name, $output);
  }

}
