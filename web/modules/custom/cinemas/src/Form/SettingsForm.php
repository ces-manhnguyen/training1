<?php

namespace Drupal\cinemas\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\cinemas\Service\SettingsService;
use Symfony\Component\DependencyInjection\ContainerInterface;

class SettingsForm extends ConfigFormBase {

  /**
   * The settings service object.
   *
   * @var \Drupal\cinemas\Service\SettingsService
   */
  protected $settingsService;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('settings_service')
    );
  }

  /**
   * Constructs a SettingsForm object.
   *
   * @param \Drupal\cinemas\Service\SettingsService $settings_service
   *   A settings service instance.
   */
  public function __construct(SettingsService $settings_service) {
    $this->settingsService = $settings_service;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'cinemas_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Form constructor.
    $form = parent::buildForm($form, $form_state);
    // Default settings.
    $config = $this->config('cinemas.settings');


    $form['url'] = [
      '#type' => 'textfield',
      '#title' => $this->t('URL'),
      '#default_value' => $config->get('cinemas.url'),
      '#required' => TRUE,
    ];

    $form['api_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('API Key'),
      '#default_value' => $config->get('cinemas.api_key'),
      '#required' => TRUE,
    ];

    $form['api_secret'] = [
      '#type' => 'textfield',
      '#title' => $this->t('API Secret'),
      '#default_value' => $config->get('cinemas.api_secret'),
      '#required' => TRUE,
    ];

    $form['key_select'] = [
      '#type' => 'select',
      '#title' => $this->t('Select Key'),
      '#options' => $this->settingsService->getKeys(),
      '#default_value' => $config->get('cinemas.key_select'),
      '#required' => TRUE,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    $message = $this->settingsService
      ->authenticate($values['url'], $values['api_key'], $values['api_secret'], $values['key_select']);
    if ($message) {
      $form_state->setErrorByName('url', $this->t($message));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('cinemas.settings');
    $values = $form_state->getValues();
    $config->set('cinemas.url', $values['url']);
    $config->set('cinemas.api_key', $values['api_key']);
    $config->set('cinemas.api_secret', $values['api_secret']);
    $config->set('cinemas.key_select', $values['key_select']);
    $config->save();

    return parent::submitForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'cinemas.settings',
    ];
  }
}
