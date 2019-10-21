<!-- dashboard_employee.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
{if $page_version == 'employee'}
    {include file='core/dashboard_employee.tpl'}
{elseif $page_version == 'client'}
    {include file='core/dashboard_client.tpl'}
{/if}