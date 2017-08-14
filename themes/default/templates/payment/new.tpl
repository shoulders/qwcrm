<!-- new.tpl -->
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
                    <td class="menuhead2" width="80%">&nbsp;{t}Billing For Work Order{/t} {$invoice_details.workorder_id}</td>
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
                            <tr>
                                <td>                                                
                                    {include file='payment/blocks/new_transactions_log_block.tpl'}
                                </td>
                            </tr>                            
                                
                            {if $invoice_details.IS_PAID == 0 && $invoice_details.balance > 0 }
                                
                                <!-- Cash -->
                                {if $active_payment_methods.cash_active == '1'}
                                    <tr>
                                        <td>                                    
                                            {include file='payment/blocks/new_payment_cash_block.tpl'}                                    
                                        </td>
                                    </tr>
                                {/if}                            

                                <!-- Cheques -->
                                {if $active_payment_methods.cheque_active == '1'}  
                                    <tr>
                                        <td>                                                                              
                                            {include file='payment/blocks/new_payment_cheque_block.tpl'}                                    
                                        </td>
                                    </tr>
                                {/if}                            

                                <!-- Credit Cards -->
                                {if $active_payment_methods.credit_card_active == '1' && $active_credit_cards != ''}
                                    <tr>
                                        <td>
                                            {include file='payment/blocks/new_payment_credit_card_block.tpl'}
                                        </td>
                                    </tr>
                                {/if}                            

                                <!-- Direct Deposit -->
                                {if $active_payment_methods.direct_deposit_active == '1'}
                                    <tr>
                                        <td>                                    
                                            {include file='payment/blocks/new_payment_direct_deposit_block.tpl'}                                    
                                        </td>
                                    </tr>
                                {/if}                            

                                <!-- Gift Certificates -->
                                {if $active_payment_methods.gift_certificate_active == '1'}
                                    <tr>
                                        <td>
                                            {include file='payment/blocks/new_payment_gift_certificate_block.tpl'}
                                        </td>
                                    </tr>
                                {/if}

                                <!-- Paypal -->
                                {if $active_payment_methods.paypal_active == '1'}
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