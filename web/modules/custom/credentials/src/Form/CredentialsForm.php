<?php

namespace Drupal\credentials\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Form handler for the Credentials add and edit forms.
 */
class CredentialsForm extends EntityForm {

  /**
   * Constructs a CredentialsForm object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   The entityTypeManager.
   */
  public function __construct(EntityTypeManagerInterface $entityTypeManager) {
    $this->entityTypeManager = $entityTypeManager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $credentials = $this->entity;

    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Name'),
      '#maxlength' => 255,
      '#default_value' => $credentials->label(),
      '#description' => $this->t("Name of the Credentials."),
      '#required' => TRUE,
    ];
    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $credentials->id(),
      '#machine_name' => [
        'exists' => [$this, 'exist'],
      ],
      '#disabled' => !$credentials->isNew(),
    ];
    $form['url'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Endpoint URL'),
      '#maxlength' => 255,
      '#default_value' => $credentials->getUrl(),
      '#required' => TRUE,
    ];
    $form['key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('API Key'),
      '#maxlength' => 255,
      '#default_value' => $credentials->getKey(),
      '#required' => TRUE,
    ];
    $form['secret'] = [
      '#type' => 'textfield',
      '#title' => $this->t('API Secret'),
      '#maxlength' => 255,
      '#default_value' => $credentials->getSecret(),
      '#required' => TRUE,
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
  public function save(array $form, FormStateInterface $form_state) {
    $credentials = $this->entity;
    $status = $credentials->save();

    if ($status === SAVED_NEW) {
      $this->messenger()->addMessage($this->t('The %label Credentials was created.', [
        '%label' => $credentials->label(),
      ]));
    }
    else {
      $this->messenger()->addMessage($this->t('The %label Credentials has been updated.', [
        '%label' => $credentials->label(),
      ]));
    }

    $form_state->setRedirect('entity.credentials.collection');
  }

  /**
   * Helper function to check whether a Credentials configuration entity exists.
   */
  public function exist($id) {
    $entity = $this->entityTypeManager->getStorage('credentials')->getQuery()
      ->condition('id', $id)
      ->execute();
    return (bool) $entity;
  }

}