<!-- payment_options.tpl -->
<script src="{$theme_js_dir}tinymce/tinymce.min.js"></script>
<script src="{$theme_js_dir}editor-config.js"></script>

<table width="100%" border="0" cellpadding="20" cellspacing="0">
    <tr>
        <td>
            <table width="700" cellpadding="5" cellspacing="0" border="0" >
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;Payment Options    </td>
                </tr>
                <tr>
                    <td class="menutd2">                    
                        <table width="100%" class="olotable" cellpadding="5" cellspacing="0" border="0" >
                            <tr>
                                <td width="100%" valign="top" class="menutd">                                    
                                    <form method="POST" action="index.php?page=payment:options">
                                        <table>
                                            <caption><b><font color="red">Available Payment types</font></b></caption>
                                            {section name=q loop=$payment_methods_status}
                                                <tr>
                                                    <td colspan="2"><b>{$payment_methods_status[q].METHOD}</b></td>
                                                    <td>Active: <input type="checkbox" name="{$payment_methods_status[q].SMARTY_TPL_KEY}" {if $payment_methods_status[q].ACTIVE == 1} checked {/if} value=1 class="olotd5"></td>
                                                </tr>
                                            {/section}
                                        </table>                                        
                                        {section name=w loop=$payment_settings}                                            
                                            <table>
                                                
                                                <!-- Bank Details -->  
                                                <tr>
                                                    <td colspan="2"><font color="red"><b>Bank Details</b></font></td>
                                                </tr>
                                                <tr>
                                                    <td><b>Bank Account Name:</b></td>
                                                    <td><input class="olotd5" type="text" name="bank_account_name" value="{$payment_settings[w].BANK_ACCOUNT_NAME}"></td>
                                                </tr>
                                                <tr>
                                                    <td><b>Bank Name:</b></td>
                                                    <td><input class="olotd5" type="text" name="bank_name" value="{$payment_settings[w].BANK_NAME}"></td>
                                                </tr>
                                                <tr>
                                                    <td><b>Bank Account Number:</b></td>
                                                    <td><input class="olotd5" type="text" name="bank_account_number" value="{$payment_settings[w].BANK_ACCOUNT_NUMBER}"></td>
                                                </tr>
                                                <tr>
                                                    <td><b>Bank Sort Code</b></td>
                                                    <td><input class="olotd5" type="text" name="bank_sort_code" value="{$payment_settings[w].BANK_SORT_CODE}"></td>
                                                </tr>
                                                <tr>
                                                    <td><b>Bank IBAN</b></td>
                                                    <td><input class="olotd5" type="text" name="bank_iban" value="{$payment_settings[w].BANK_IBAN}"></td>
                                                </tr>
                                                
                                                <!-- PayPal -->
                                                <tr>
                                                    <td colspan="2"><b><font color="red">Paypal Information</font></b></td>                                                    
                                                </tr>
                                                <tr>
                                                    <td colspan="2">You must have a Paypal Merchant account set and working. Please see https://www.paypal.com/ for more information.</td>
                                                </tr>
                                                <tr>
                                                    <td><b>Paypal Email</b></td>
                                                    <td><input type="text" name="paypal_email" value="{$payment_settings[w].PAYPAL_EMAIL}" size="50" class="olotd5"></td>
                                                </tr> 
                                                
                                                <!-- Invoice Messages -->
                                                <tr>
                                                    <td colspan="2"><font color="red"><b>Invoice Messages</b></font></td>
                                                </tr>
                                                <tr>
                                                    <td><b>Bank Transaction Message</b></td>
                                                    <td><textarea class="olotd5" name="bank_transaction_message" cols="50" rows="2" >{$payment_settings[w].BANK_TRANSACTION_MSG}</textarea><br></td>
                                                </tr>
                                                <tr>
                                                    <td><b>Checks payable to:</b></td>
                                                    <td><textarea class="olotd5" name="cheque_payable_to_msg" cols="50" rows="2" >{$payment_settings[w].CHEQUE_PAYABLE_TO_MSG}</textarea><br></td>
                                                </tr>
                                                <tr>
                                                    <td><b>Invoice Footer Message:</b></td>
                                                    <td><textarea class="olotd5" name="invoice_footer_msg" cols="50" rows="2" >{$payment_settings[w].INVOICE_FOOTER_MSG}</textarea><br></td>
                                                </tr>
                                                
                                            </table>                                            
                                            <input type="submit" name="submit" value="Submit">
                                        {/section}
                                    </form>   
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>