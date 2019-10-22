<!-- print.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
{if $print_content == 'technician_workorder_slip'}
    {include file='workorder/printing/print_technician_workorder_slip.tpl'}
{elseif $print_content == 'client_workorder_slip'}
    {include file='workorder/printing/print_client_workorder_slip.tpl'}
{elseif $print_content == 'technician_job_sheet'}
    {include file='workorder/printing/print_technician_job_sheet.tpl'}
{/if}