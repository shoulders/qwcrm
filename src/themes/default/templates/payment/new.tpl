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
                    <td class="menuhead2" width="80%">&nbsp;{t}Payments for{/t} {t}Invoice{/t} {$invoice_details.invoice_id} - {$customer_details.display_name}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}PAYMENT_NEW_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}PAYMENT_NEW_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
                    </td>
                </tr>
                <tr>
                    <td class="olotd5" colspan="2">                        
                        <table width="100%" border="0" cellpadding="10" cellspacing="0">
                                        
                            <!-- Invoice Details -->
                            <tr>
                                <td>                                                
                                    {include file='payment/blocks/new_invoice_details_block.tpl'}
                                </td>
                            </tr>

                            <!-- Transactions -->
                            {if $display_transactions}
                                <tr>
                                    <td>                                                
                                        {include file='payment/blocks/display_transactions_block.tpl'}
                                    </td>
                                </tr>
                            {/if}
                                
                            {if $invoice_details.is_closed == 0 && $invoice_details.balance > 0}
                                
                                <!-- Cash -->
                                {if $active_payment_system_methods.cash}
                                    <tr>
                                        <td>                           
                                            {include file='payment/blocks/new_payment_cash_block.tpl'}                                    
                                        </td>
                                    </tr>
                                {/if}                            

                                <!-- Cheques -->
                                {if $active_payment_system_methods.cheque}  
                                    <tr>
                                        <td>                                                                              
                                            {include file='payment/blocks/new_payment_cheque_block.tpl'}                                    
                                        </td>
                                    </tr>
                                {/if}                            

                                <!-- Credit Cards -->
                                {if $active_payment_system_methods.credit_card && $active_credit_cards}
                                    <tr>
                                        <td>
                                            {include file='payment/blocks/new_payment_credit_card_block.tpl'}
                                        </td>
                                    </tr>
                                {/if}                            

                                <!-- Direct Deposit -->
                                {if $active_payment_system_methods.direct_deposit}
                                    <tr>
                                        <td>                                    
                                            {include file='payment/blocks/new_payment_direct_deposit_block.tpl'}                                    
                                        </td>
                                    </tr>
                                {/if}                            

                                <!-- Gift Certificates -->
                                {if $active_payment_system_methods.gift_certificate}
                                    <tr>
                                        <td>
                                            {include file='payment/blocks/new_payment_gift_certificate_block.tpl'}
                                        </td>
                                    </tr>
                                {/if}
                                
                                <!-- Paypal -->
                                {if $active_payment_system_methods.paypal}
                                    <tr>
                                        <td>                                    
                                            {include file='payment/blocks/new_payment_paypal_block.tpl'}                                    
                                        </td>
                                    </tr>
                                {/if}
                                
                            {/if}
                            
                        </table>           
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>