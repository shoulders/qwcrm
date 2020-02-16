<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

    $QConfig = class_exists('QConfig') ? new QConfig : null;  // This is temporary, I have to include configuration.php earlier, however joomla does this as well

    // Misc
    //define('SEP',                       DIRECTORY_SEPARATOR             );            // on windows = '\'
    //define('SEP',                       '/'                             );

    // Information and Configuration
    define('QWCRM_VERSION' ,            '3.1.3'                                             );
    define('QWCRM_MINIMUM_MYSQL',       '5.0.0'                                             );
    
    // This allows the use of the database ASAP in the setup process - This is the where the config is first created from configuration.php
    if(isset($QConfig->db_prefix)) {
        define('PRFX',                  $QConfig->db_prefix                                 );
    }

    // System Folders
    define('CACHE_DIR',                 'cache/'                                            );
    define('COMPONENTS_DIR',            'components/'                                       );
    define('CINCLUDES_DIR',             COMPONENTS_DIR.'_includes/'                         );
    define('LANGUAGE_DIR',              'language/'                                         );
    define('LIBRARIES_DIR',             'libraries/'                                        );
    define('LOGS_DIR',                  'logs/'                                             );
    define('MEDIA_DIR',                 'media/'                                            );
    define('MODULES_DIR',               'modules/'                                          );    
    define('PLUGINS_DIR',               'plugins/'                                          );
    define('SETUP_DIR',                 'setup/'                                            );
    define('TMP_DIR',                   'tmp/'                                              );

    define('VENDOR_DIR',                LIBRARIES_DIR.'vendor/'                             );

    // Smarty
    define('SMARTY_CACHE_DIR',          CACHE_DIR.'smarty/cache/'                           );
    define('SMARTY_COMPILE_DIR',        CACHE_DIR.'smarty/compile/'                         );

    // Asset Folders
    define('QW_MEDIA_DIR',              QWCRM_BASE_PATH.MEDIA_DIR                           );

    // Theme Folders
    if(isset($QConfig->theme_name)) {
        define('THEME_NAME',            $QConfig->theme_name            );   
    } else {
        define('THEME_NAME',            'default'                                           );      
    }
    define('THEME_DIR',                 'themes/'.THEME_NAME.'/'                            );
    define('THEME_TEMPLATE_DIR',        THEME_DIR.'templates/'                              );
    define('THEME_IMAGES_DIR',          QWCRM_BASE_PATH.THEME_DIR.'images/'                 );
    define('THEME_CSS_DIR',             QWCRM_BASE_PATH.THEME_DIR.'css/'                    );
    define('THEME_JS_DIR',              QWCRM_BASE_PATH.THEME_DIR.'js/'                     );


    // Theme Smarty File Include Paths (for use within the TPL files)
    define('THEME_JS_DIR_FINC',         '../../js/'                                         );

    // Log files
    define('ACCESS_LOG',                LOGS_DIR.'access.log'                               );
    define('ACTIVITY_LOG',              LOGS_DIR.'activity.log'                             );
    define('EMAIL_ERROR_LOG',           LOGS_DIR.'email_error.log'                          );
    define('EMAIL_TRANSPORT_LOG',       LOGS_DIR.'email_transport.log'                      );
    define('ERROR_LOG',                 LOGS_DIR.'error.log'                                );
    define('SETUP_LOG',                 LOGS_DIR.'setup.log'                                );

unset($QConfig);