<!-- new.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<link rel="stylesheet" href="{$theme_js_dir}jscal2/css/jscal2.css" />
<link rel="stylesheet" href="{$theme_js_dir}jscal2/css/steel/steel.css" />
<script src="{$theme_js_dir}jscal2/jscal2.js"></script>
<script src="{$theme_js_dir}jscal2/unicode-letter.js"></script>
<script>{include file="`$theme_js_dir_finc`jscal2/language.js"}</script>

<table width="700" border="0" cellpadding="20" cellspacing="5">
    <tr>
        <td>
            <table width="100%" cellpadding="4" cellspacing="0" border="0">
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{t}Add a New Payment{/t}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}PAYMENT_NEW_HELP_TITLE{/t}</strong></div><hr><div>{t escape=js}PAYMENT_NEW_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
                    </td>
                </tr>
                <tr>
                    <td class="olotd5" colspan="2">
                        
                        <table width="100%" border="0" cellpadding="10" cellspacing="0">

                            <!-- Record Details -->
                            <tr>
                                <td>                                                
                                    {if $payment_type == 'invoice'}{include file='invoice/blocks/display_invoice_balance_block.tpl'}{/if}
                                    {if $payment_type == 'refund'}{include file='refund/blocks/display_refund_balance_block.tpl'}{/if}
                                    {if $payment_type == 'expense'}{include file='expense/blocks/display_expense_balance_block.tpl'}{/if}
                                    {if $payment_type == 'otherincome'}{include file='otherincome/blocks/display_otherincome_balance_block.tpl'}{/if}
                                    {if $payment_type == 'creditnote'}{include file='creditnote/blocks/display_creditnote_balance_block.tpl'}{/if}
                                </td>
                            </tr>                                                       

                            <!-- Payments -->                           
                            <tr>
                                <td>                                                
                                    {include file='payment/blocks/display_payments_block.tpl' display_payments=$display_payments block_title=_gettext("Payments")}
                                </td>
                            </tr>
                            
                            <!-- Payment Methods -->
                            <tr>
                                <td>
                                    {include file='payment/blocks/display_payment_methods_block.tpl'}
                                </td>
                            </tr>

                        </table>                                                                    
                        
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>