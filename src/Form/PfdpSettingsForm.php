<?php

namespace Drupal\private_files_download_permission\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * General settings form for private files download permission module.
 */
class PfdpSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'private_files_download_permission_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['private_files_download_permission.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['private_files_download_permission_by_user_checks'] = array(
      '#type' => 'checkbox',
      '#title' => t('Enable by-user checks'),
      '#default_value' => $this->config('private_files_download_permission.settings')->get('by_user_checks'),
      '#description' => t('You may wish to disable this feature if there are plenty of users, as it may slow down the entire site.'),
    );

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('private_files_download_permission.settings')
      ->set('by_user_checks', $form_state->getValue('private_files_download_permission_by_user_checks'))
      ->save();
    // Purge directory list from cache.
    //drupal_static_reset('private_files_download_permission_get_directory_list');

    parent::submitForm($form, $form_state);
  }

}
