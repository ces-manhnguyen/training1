<?php

namespace Drupal\credentials;

use Drupal\Core\Config\Entity\ConfigEntityInterface;

/**
 * Provides an interface defining a Credentials entity.
 */
interface CredentialsInterface extends ConfigEntityInterface {
  // Add get/set methods for your configuration properties here.

  /**
   * Get the value of url
   */
  public function getUrl();

  /**
   * Set the value of url
   */
  public function setUrl($url);

  /**
   * Get the value of key
   */
  public function getKey();

  /**
   * Set the value of key
   */
  public function setKey($key);

  /**
   * Get the value of secret
   */
  public function getSecret();

  /**
   * Set the value of secret
   */
  public function setSecret($secret);
}
