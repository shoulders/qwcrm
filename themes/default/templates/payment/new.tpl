<!-- new.tpl -->
<table width="700" border="0" cellpadding="20" cellspacing="5">
    <tr>
        <td>
            <table width="100%" cellpadding="4" cellspacing="0" border="0">
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{$translate_payment_title}{$workorder_id}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <img src="{$theme_images_dir}icons/16x16/help.gif" alt="" border="0" onMouseOver="ddrivetip('<b>New Invoice</b><hr><p></p>');" onMouseOut="hideddrivetip();">
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

                            <!-- Cash -->
                            {if $payment_options.cash_active == '1'}
                                <tr>
                                    <td>                                    
                                        {include file='payment/blocks/new_payment_cash_block.tpl'}                                    
                                    </td>
                                </tr>
                            {/if}                            
                            
                            <!-- Cheques -->
                            {if $payment_options.cheque_active == '1'}  
                                <tr>
                                    <td>                                                                              
                                        {include file='payment/blocks/new_payment_cheque_block.tpl'}                                    
                                    </td>
                                </tr>
                            {/if}                            
                            
                            <!-- Credit Cards -->
                            {if $payment_options.credit_card_active == '1'}
                                <tr>
                                    <td>
                                        {include file='payment/blocks/new_payment_credit_card_block.tpl'}
                                    </td>
                                </tr>
                            {/if}                            

                            <!-- Direct Deposit -->
                            {if $payment_options.direct_deposit_active == '1'}
                                <tr>
                                    <td>                                    
                                        {include file='payment/blocks/new_payment_direct_deposit_block.tpl'}                                    
                                    </td>
                                </tr>
                            {/if}                            

                            <!-- Gift Certificates -->
                            {if $payment_options.gift_certificate_active == '1'}
                                <tr>
                                    <td>
                                        {include file='payment/blocks/new_payment_gift_certificate_block.tpl'}
                                    </td>
                                </tr>
                            {/if}

                            <!-- Paypal -->
                            {if $payment_options.paypal_active == '1'}
                                <tr>
                                    <td>                                    
                                        {include file='payment/blocks/new_payment_paypal_block.tpl'}                                    
                                    </td>
                                </tr>
                            {/if}
                            
                        </table>                        
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>