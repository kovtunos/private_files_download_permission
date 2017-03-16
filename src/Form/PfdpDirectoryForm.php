<?php

/**
 * @file
 * Contains \Drupal\private_files_download_permission\Form\PfdpDirectoryForm.
 */

namespace Drupal\private_files_download_permission\Form;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityForm;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\Query\QueryFactory;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Site\Settings;
use Drupal\Component\Utility\Unicode;

class PfdpDirectoryForm extends EntityForm {

  /**
   * @param \Drupal\Core\Entity\Query\QueryFactory $entity_query
   *   The entity query.
   */
  public function __construct(QueryFactory $entity_query) {
    $this->entityQuery = $entity_query;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity.query')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $directory = $this->entity;

    $form['path'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Path'),
      '#field_prefix' => Settings::get('file_private_path'),
      '#size' => 60,
      '#maxlength' => 255,
      '#default_value' => $directory->path,
      '#required' => TRUE,
    );
    $form['id'] = array(
      '#type' => 'machine_name',
      '#default_value' => $directory->id(),
      '#machine_name' => array(
        'exists' => array($this, 'exist'),
        'source' => array('path'),
      ),
      '#disabled' => !$directory->isNew(),
    );
    $form['bypass'] = array(
      '#type' => 'checkbox',
      '#title' => t('Bypass'),
      '#default_value' => $directory->bypass,
      '#description' => t('Enable to make this module ignore the above path.'),
    );

    $enable_users = $this->config('private_files_download_permission.settings')->get('by_user_checks');
    if ($enable_users) {
      $form['users_wrapper'] = array(
        '#type' => 'details',
        '#title' => t('Enabled users'),
        '#collapsible' => TRUE,
        '#collapsed' => TRUE,
      );
      $form['users_wrapper']['users'] = array(
        '#type' => 'checkboxes',
        '#default_value' => $directory->users,
        '#options' => $this->getUsers(),
      );
    }

    $form['roles_wrapper'] = array(
      '#type' => 'details',
      '#title' => t('Enabled roles'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    );
    $form['roles_wrapper']['roles'] = array(
      '#type' => 'checkboxes',
      '#options' => user_role_names(),
      '#default_value' => $directory->roles,
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // Retrieve $path (which, being required, is surely not blank).
    $path = $form_state->getValue('path');
    // Perform slash validation:
    if ($path) {
      // ...there must be a leading slash.
      if (strpos($path, '/') !== 0) {
        $form_state->setErrorByName('path', t('You must add a leading slash.'));
      }
      if (Unicode::substr($path, -1) === '/') {
        $form_state->setErrorByName('path', t('You cannot use trailing slashes.'));
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $directory = $this->entity;
    $directory->roles = array_filter($directory->roles);
    $directory->users = array_filter($directory->users);
    $status = $directory->save();

    if ($status) {
      drupal_set_message($this->t('Saved the %label directory.', array(
        '%label' => $directory->label(),
      )));
    }
    else {
      drupal_set_message($this->t('The %label directory was not saved.', array(
        '%label' => $directory->label(),
      )));
    }

    $form_state->setRedirect('entity.pfdp_directory.collection');
  }

  public function exist($id) {
    $entity = $this->entityQuery->get('pfdp_directory')
      ->condition('id', $id)
      ->execute();
    return (bool) $entity;
  }

  /**
   * @todo Switch to UserStorage instead.
   */
  protected function getUsers() {
    return db_select('users_field_data', 't')
      ->fields('t', ['uid', 'name'])
      ->orderBy('t.name', 'ASC')
      ->condition('uid', \Drupal\user\RoleInterface::ANONYMOUS_ID, '<>')
      ->execute()
      ->fetchAllKeyed();
  }
}
