<!-- details_invoice_block.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
{include file='invoice/blocks/display_invoices_block.tpl' display_invoices=$invoices_pending block_title=_gettext("Pending")}
<br>
<br>
{include file='invoice/blocks/display_invoices_block.tpl' display_invoices=$invoices_unpaid block_title=_gettext("Unpaid")}
<br>
<br>
{include file='invoice/blocks/display_invoices_block.tpl' display_invoices=$invoices_partially_paid block_title=_gettext("Partially Paid")}
<br>
<br>
{include file='invoice/blocks/display_invoices_block.tpl' display_invoices=$invoices_paid block_title=_gettext("Paid")}