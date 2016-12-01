<?php

// Misc

 /* 
  * this inly kept for legacy purposes, i will slowly remove this pointless thing from the code
  * const SEP = '/'; //this type of constant definition is supposed to be quicker and only php 5.3+  * 
  */
//define('SEP',                   DIRECTORY_SEPARATOR         );          // on windows = '\'
define('SEP',                   '/'                         );

// Information and Configuration
define('INSTALL_DATE',          'Jul 11 2016 08:46:45 PM'   );          // is this really needed?
define('QWCRM_VERSION' ,        '2.9.4'                     );

define('debug',                 'no'                        );          // in billing/include.php and core/footer.php - needs fixing - controls some footer informtion
define('PRFX',                  $db_prefix                  );          // Database Prefix

// System Folders
define('CACHE_DIR',             'cache/'                    );
define('INCLUDES_DIR',          'includes/'                 ); 
//define('MEDIA_DIR',             'media/'                    );          // not currently used
define('LANGUAGE_DIR',          'language/'                 );
define('LOGS_DIR',              'logs/'                     );

// Theme
define('THEME_NAME',            $theme_name                 );
define('THEME_LANGUAGE',        $theme_language.'.xml'      );
define('THEME_DIR',             'themes/'.THEME_NAME.'/'    );          // set the current theme's file locations
define('THEME_IMAGES_DIR',      THEME_DIR.'images/'         );
define('THEME_CSS_DIR',         THEME_DIR.'css/'            );
define('THEME_JS_DIR',          THEME_DIR.'js/'             );
define('THEME_TEMPLATE_DIR',    THEME_DIR.'templates/'      );

// Smarty
define('SMARTY_DIR',            INCLUDES_DIR.'smarty/'      );          // Only used in the installer
define('SMARTY_CACHE_DIR',      CACHE_DIR.'smarty/cache/'   );
define('SMARTY_COMPILE_DIR',    CACHE_DIR.'smarty/compile/' );

// Log files
define('ACTIVITY_LOG',          LOGS_DIR.'activity.log'     );
define('ACCESS_LOG',            LOGS_DIR.'access.log'       );
define('ERROR_LOG',             LOGS_DIR.'error.log'        );





