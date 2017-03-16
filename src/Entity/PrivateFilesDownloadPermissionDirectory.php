<?php

namespace Drupal\private_files_download_permission\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;

/**
 * Defines the private file directory class.
 *
 * @ConfigEntityType(
 *   id = "pfdp_directory",
 *   label = @Translation("Private files download permission - Directory"),
 *   handlers = {
 *     "list_builder" = "Drupal\private_files_download_permission\Controller\PfdpDirectoryListBuilder",
 *     "form" = {
 *       "add" = "Drupal\private_files_download_permission\Form\PfdpDirectoryForm",
 *       "edit" = "Drupal\private_files_download_permission\Form\PfdpDirectoryForm",
 *       "delete" = "Drupal\private_files_download_permission\Form\PfdpDirectoryDeleteForm",
 *     }
 *   },
 *   admin_permission = "administer private files download permission",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "path",
 *   },
 *   links = {
 *     "edit-form" = "/admin/config/media/private-files-download-permission/{pfdp_directory}",
 *     "delete-form" = "/admin/config/media/private-files-download-permission/{pfdp_directory}/delete",
 *   }
 * )
 */
class PrivateFilesDownloadPermissionDirectory extends ConfigEntityBase {
  public $id;
  public $path;
  public $bypass = FALSE;
  public $users = [];
  public $roles = [];
}
