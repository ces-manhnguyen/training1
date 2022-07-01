<?php

namespace Drupal\transcode_profile\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class TranscodeProfileForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'transcode_profile_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Form constructor.
    $form = parent::buildForm($form, $form_state);
    // Default settings.
    $config = $this->config('transcode_profile.settings');

    $form['profile_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Profile Name'),
      '#default_value' => $config->get('transcode_profile.profile_name'),
      '#description' => $this->t('Video transcode profile name'),
    ];

    $form['enable_transcoding'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enable transcoding'),
      '#default_value' => $config->get('transcode_profile.enable_transcoding'),
      '#description' => $this->t('Enables video transcoding'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

  }
  
  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('transcode_profile.settings');
    $config->set('transcode_profile.profile_name', $form_state->getValue('profile_name'));
    $config->set('transcode_profile.enable_transcoding', $form_state->getValue('enable_transcoding'));
    $config->save();
    return parent::submitForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'transcode_profile.settings',
    ];
  }
}