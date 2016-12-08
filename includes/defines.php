<?php

// Misc

 /* 
  * this inly kept for legacy purposes, i will slowly remove this pointless thing from the code
  * const SEP = '/'; //this type of constant definition is supposed to be quicker and only php 5.3+  * 
  */
//define('SEP',                   DIRECTORY_SEPARATOR         );          // on windows = '\'
define('SEP',                   '/'                         );

// Information and Configuration
define('QWCRM_VERSION' ,        '2.9.4'                     );
define('PRFX',                  $db_prefix                  );          // Database Prefix

// System Folders - Web Based
define('CACHE_DIR',             'cache/'                    );
define('INCLUDES_DIR',          'includes/'                 ); 
define('MEDIA_DIR',             'media/'                    );
define('LANGUAGE_DIR',          'language/'                 );
define('LIBRARIES_DIR',         'libraries/'                );
define('LOGS_DIR',              'logs/'                     );

// System Folders - Physical - do i need pyshical paths defined?

// Smarty
define('SMARTY_CACHE_DIR',      CACHE_DIR.'smarty/cache/'   );
define('SMARTY_COMPILE_DIR',    CACHE_DIR.'smarty/compile/' );

// Theme
define('THEME_NAME',            $theme_name                 );
define('THEME_LANGUAGE',        $theme_language.'.xml'      );
define('THEME_DIR',             'themes/'.THEME_NAME.'/'    );
define('THEME_IMAGES_DIR',      THEME_DIR.'images/'         );
define('THEME_CSS_DIR',         THEME_DIR.'css/'            );
define('THEME_JS_DIR',          THEME_DIR.'js/'             );
define('THEME_TEMPLATE_DIR',    THEME_DIR.'templates/'      );

// Log files
define('ACTIVITY_LOG',          LOGS_DIR.'activity.log'     );
define('ACCESS_LOG',            LOGS_DIR.'access.log'       );
define('ERROR_LOG',             LOGS_DIR.'error.log'        );






