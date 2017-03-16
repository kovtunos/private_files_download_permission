<?php
/**
 * @file
 * Contains \Drupal\private_files_download_permission\Form\PfdpDirectoryDeleteForm.
 */

namespace Drupal\private_files_download_permission\Form;

use Drupal\Core\Entity\EntityConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Provides a deletion confirmation form for Ball entity.
 */
class PfdpDirectoryDeleteForm extends EntityConfirmFormBase {

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return $this->t('Are you sure you want to delete the directory %name?', array('%name' => $this->entity->label()));
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return new Url('entity.pfdp_directory.collection');
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return $this->t('Delete');
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->entity->delete();
    $this->logger('pfdp_directory')->notice('Directory %name has been deleted.', array('%name' => $this->entity->label()));
    drupal_set_message($this->t('Directory %name has been deleted.', array('%name' => $this->entity->label())));
    $form_state->setRedirectUrl($this->getCancelUrl());
  }

}
