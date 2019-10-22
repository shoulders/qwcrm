<!-- print.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
{if $print_content == 'invoice'}
    {include file='invoice/printing/print_invoice.tpl'}
{elseif $print_content == 'client_envelope'}
    {include file='invoice/printing/print_client_envelope.tpl'}
{/if}