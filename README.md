> UPD: Drupal 8 version of this module was added to [Drupal.org](https://www.drupal.org/project/private_files_download_permission).

# Private files download permission

Drupal 8 port of [Private files download permission](https://www.drupal.org/project/private_files_download_permission) module.

## Installation / Configuration

Browse to Configuration > Media > Private files download permission (url:
/admin/config/media/private-files-download-permission). Then add or edit each
directory path you want to put under control, associating users and roles which
are allowed to download from it.
All directory paths are relative to your private file system path, but must
have a leading slash ('/'), as the private file system root itself could be put
under control.

E.g.:
Suppose your private file system path is /opt/private.
You could configure /opt/private (and all of its subdirectories) by adding a
'/' entry, while a '/test' entry would specifically refer to /opt/private/test
(and all of its subdirectories).

Please note that per-user checks may slow your site if there are plenty of
users. You can then bypass this feature by browsing to Configuration > Media >
Private files download permission > Preferences (url:
/admin/config/media/private-files-download-permission/preferences) and change
the setting accordingly.

Also configure which users and roles have access to the module configuration
under People > Permissions (url: /admin/people/permissions).
