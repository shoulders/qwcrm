/*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

--
-- Remove invoice delete labour and parts page permissions
--

DELETE FROM `#__user_acl_page` WHERE `#__user_acl_page`.`page` = 'invoice:delete_labour';
DELETE FROM `#__user_acl_page` WHERE `#__user_acl_page`.`page` = 'invoice:delete_parts';