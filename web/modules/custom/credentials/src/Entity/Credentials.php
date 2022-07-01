<?php

namespace Drupal\credentials\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\credentials\CredentialsInterface;

/**
 * Defines the Credentials entity.
 *
 * @ConfigEntityType(
 *   id = "credentials",
 *   label = @Translation("Credentials"),
 *   handlers = {
 *     "list_builder" = "Drupal\credentials\Controller\CredentialsListBuilder",
 *     "form" = {
 *       "add" = "Drupal\credentials\Form\CredentialsForm",
 *       "edit" = "Drupal\credentials\Form\CredentialsForm",
 *       "delete" = "Drupal\credentials\Form\CredentialsDeleteForm",
 *     }
 *   },
 *   config_prefix = "credentials",
 *   admin_permission = "administer site configuration",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "url" = "url",
 *     "key" = "key",
 *     "secret" = "secret",
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "url",
 *     "key",
 *     "secret"
 *   },
 *   links = {
 *     "edit-form" = "/admin/config/system/credentials/{credentials}",
 *     "delete-form" = "/admin/config/system/credentials/{credentials}/delete",
 *   }
 * )
 */
class Credentials extends ConfigEntityBase implements CredentialsInterface {

  /**
   * The Credentials ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Credentials label.
   *
   * @var string
   */
  protected $label;

  /**
   * The Credentials endpoint URL.
   *
   * @var string
   */
  protected $url;

  /**
   * The Credentials API key.
   *
   * @var string
   */
  protected $key;

  /**
   * The Credentials API secret.
   *
   * @var string
   */
  protected $secret;

  // Your specific configuration property get/set methods go here,
  // implementing the interface.

  /**
   * Get the value of url
   */
  public function getUrl()
  {
    return $this->url;
  }

  /**
   * Set the value of url
   */
  public function setUrl($url)
  {
    $this->url = $url;

    return $this;
  }

  /**
   * Get the value of key
   */
  public function getKey()
  {
    return $this->key;
  }

  /**
   * Set the value of key
   */
  public function setKey($key)
  {
    $this->key = $key;

    return $this;
  }

  /**
   * Get the value of secret
   */
  public function getSecret()
  {
    return $this->secret;
  }

  /**
   * Set the value of secret
   */
  public function setSecret($secret)
  {
    $this->secret = $secret;

    return $this;
  }
}