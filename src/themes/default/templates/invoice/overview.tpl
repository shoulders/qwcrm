<!-- overview.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<table width="100%" border="0" cellpadding="20" cellspacing="5">
    <tr>
        <td>            
            <table width="700" cellpadding="3" cellspacing="0" border="0">
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{t}Invoices Overview{/t}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}INVOICE_OVERVIEW_HELP_TITLE{/t}</strong></div><hr><div>{t escape=js}INVOICE_OVERVIEW_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
                        </a>
                    </td>
                </tr>
                <tr>
                    <td class="menutd2" colspan="2">
                        <table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
                            <tr>
                                <td class="menutd">
                                    <table width="100%" border="0" cellpadding="10" cellspacing="0">
                                        <tr>
                                            <td>
                                                <a name="invoice_stats"></a>                                                
                                                {include file='invoice/blocks/display_invoice_current_stats_block.tpl' invoice_stats=$overview_invoice_stats block_title=_gettext("Invoice Current Stats")|cat:" ("|cat:_gettext("Global")|cat:")"}                                           
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <a name="pending"></a>                                                
                                                {include file='invoice/blocks/display_invoices_block.tpl' display_invoices=$overview_invoices_pending block_title=_gettext("Pending")}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <a name="unpaid"></a>
                                                {include file='invoice/blocks/display_invoices_block.tpl' display_invoices=$overview_invoices_unpaid block_title=_gettext("Unpaid")}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <a name="partially_paid"></a>
                                                {include file='invoice/blocks/display_invoices_block.tpl' display_invoices=$overview_invoices_partially_paid block_title=_gettext("Partially Paid")}
                                            </td>
                                        </tr>
                                        {*<tr>
                                            <td>
                                                <a name="paid"></a>
                                                {include file='invoice/blocks/display_invoices_block.tpl' display_invoices=$overview_invoices_paid block_title=_gettext("Paid")}
                                            </td>
                                        </tr>*}
                                        <tr>
                                            <td>
                                                <a name="in_dispute"></a>
                                                {include file='invoice/blocks/display_invoices_block.tpl' display_invoices=$overview_invoices_in_dispute block_title=_gettext("In Dispute")}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <a name="overdue"></a>
                                                {include file='invoice/blocks/display_invoices_block.tpl' display_invoices=$overview_invoices_overdue block_title=_gettext("Overdue")}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <a name="collections"></a>
                                                {include file='invoice/blocks/display_invoices_block.tpl' display_invoices=$overview_invoices_collections block_title=_gettext("Collections")}
                                            </td>
                                        </tr> 
                                        {*<tr>
                                            <td>
                                                <a name="refunded"></a>
                                                {include file='invoice/blocks/display_invoices_block.tpl' display_invoices=$overview_invoices_refunded block_title=_gettext("Refunded")}
                                            </td>
                                        </tr>*}
                                        {*<tr>
                                            <td>
                                                <a name="cancelled"></a>
                                                {include file='invoice/blocks/display_invoices_block.tpl' display_invoices=$overview_invoices_cancelled block_title=_gettext("Cancelled")}
                                            </td>
                                        </tr> *}                                                                             
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>