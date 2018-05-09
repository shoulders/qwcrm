<!-- details_workorder_block.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
{include file='workorder/blocks/display_workorders_block.tpl' display_workorders=$workorders_open block_title=_gettext("Open")}
<br>
{include file='workorder/blocks/display_workorders_block.tpl' display_workorders=$workorders_closed block_title=_gettext("Closed")}