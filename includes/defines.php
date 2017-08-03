<?php

defined('_QWEXEC') or die;

// Misc
//define('SEP',                       DIRECTORY_SEPARATOR         );          // on windows = '\'
define('SEP',                       '/'                         );

// Information and Configuration
define('QWCRM_VERSION' ,            '3.0.0'                     );
define('PRFX',                      $QConfig->db_prefix         );

// System Folders
define('CACHE_DIR',                 'cache/'                    );
define('INCLUDES_DIR',              'includes/'                 );
define('MEDIA_DIR',                 'media/'                    );
define('MODULES_DIR',               'modules/'                  );
define('LANGUAGE_DIR',              'language/'                 );
define('LIBRARIES_DIR',             'libraries/'                );
define('LOGS_DIR',                  'logs/'                     );
define('FRAMEWORK_DIR',             INCLUDES_DIR.'framework/'   );

// Smarty
define('SMARTY_CACHE_DIR',          CACHE_DIR.'smarty/cache/'   );
define('SMARTY_COMPILE_DIR',        CACHE_DIR.'smarty/compile/' );

// Theme
define('THEME_NAME',                $QConfig->theme_name        );
define('THEME_DIR',                 'themes/'.THEME_NAME.'/'    );
define('THEME_IMAGES_DIR',          THEME_DIR.'images/'         );
define('THEME_CSS_DIR',             THEME_DIR.'css/'            );
define('THEME_JS_DIR',              THEME_DIR.'js/'             );
define('THEME_TEMPLATE_DIR',        THEME_DIR.'templates/'      );

// Theme Smarty File Include Paths (for use within the TPL files)
define('THEME_JS_DIR_FINC',         '../../js/'                 );

// Log files
define('ACTIVITY_LOG',              LOGS_DIR.'activity.log'     );
define('ACCESS_LOG',                LOGS_DIR.'access.log'       );
define('ERROR_LOG',                 LOGS_DIR.'error.log'        );