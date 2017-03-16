<?php

/**
 * @file
 * Contains \Drupal\private_files_download_permission\Controller\PfdpDirectoryListBuilder.
 */

namespace Drupal\private_files_download_permission\Controller;

use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Site\Settings;
use Drupal\user\Entity\User;
use Drupal\user\Entity\Role;

/**
 * Provides a listing of Example.
 */
class PfdpDirectoryListBuilder extends ConfigEntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function render() {
    if (file_default_scheme() !== 'private') {
      drupal_set_message($this->t('Your <a href="@url">default download method</a> is not set as private. Please keep in mind that these settings only affect private file system downloads.', array('@url' => \Drupal::url('system.file_system_settings'))), 'warning');
    }
    if (!Settings::get('file_private_path')) {
      drupal_set_message($this->t('Your private file system path is not set.'), 'warning');
    }

    return parent::render();
  }

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['path'] = $this->t('Directory path');
    $header['id'] = $this->t('Machine name');
    $header['bypass'] = $this->t('Bypass');
    $header['users'] = $this->t('Enabled users');
    $header['roles'] = $this->t('Enabled roles');

    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    $row['path'] = $entity->path;
    $row['id'] = $entity->id();
    $row['bypass'] = $entity->bypass ? $this->t('yes') : $this->t('no');

    $row['users'] = implode(', ', array_map(function ($uid) {
      $user = User::load($uid);
      return $user ? $user->label() : t('missing user');
    }, $entity->users));
    $row['roles'] = implode(', ', array_map(function ($rid) {
      $role = Role::load($rid);
      return $role ? $role->label() : t('missing role');
    }, $entity->roles));

    // You probably want a few more properties here...

    return $row + parent::buildRow($entity);
  }

  /**
   * {@inheritdoc}
   */
  public function getDefaultOperations(EntityInterface $entity) {
    $operations = parent::getDefaultOperations($entity);

    if ($entity->hasLinkTemplate('edit-form')) {
      $operations['edit'] = array(
        'title' => t('Edit directory'),
        'weight' => 20,
        'url' => $entity->urlInfo('edit-form'),
      );
    }
    return $operations;
  }

}
