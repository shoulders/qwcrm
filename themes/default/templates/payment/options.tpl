<!-- payment_options.tpl -->
<script src="{$theme_js_dir}tinymce/tinymce.min.js"></script>
<script>{include file="`$theme_js_dir_finc`editor-config.js"}</script>

<table width="100%" border="0" cellpadding="20" cellspacing="0">
    <tr>
        <td class="menuhead2" width="80%">&nbsp;{t}Employee Search{/t}</td>
        <td class="menuhead2" width="20%" align="right" valign="middle">                        
            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}PAYMENT_OPTIONS_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}PAYMENT_OPTIONS_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
        </td>
    </tr>    
    <tr>
        <td>
            <table width="700" cellpadding="5" cellspacing="0" border="0" >
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{t}Payment Options{/t}</td>
                </tr>
                <tr>
                    <td class="menutd2">                    
                        <table width="100%" class="olotable" cellpadding="5" cellspacing="0" border="0" >
                            <tr>
                                <td width="100%" valign="top" class="menutd">                                    
                                    <form method="post" action="index.php?page=payment:options">
                                        
                                        <table>
                                            
                                            <!-- Enable Tax on invoices -->
                                            <tr>
                                                <td><b>{t}Enable Tax/VAT{/t}:</b></td>
                                                <td>
                                                    <select class="olotd5" id="tax_enabled" name="tax_enabled">                                                       
                                                        <option value="0"{if $payment_settings.tax_enabled == '0'} selected{/if}>{t}No{/t}</option>
                                                        <option value="1"{if $payment_settings.tax_enabled == '1'} selected{/if}>{t}Yes{/t}</option>
                                                    </select>                                                    
                                                </td> 
                                            </tr>
                                            
                                            <!-- Available Payment Types -->
                                            <tr>
                                                <td colspan="2">
                                                    <table>
                                                        <caption><b><font color="red">{t}Available Payment Types{/t}</font></b></caption>
                                                        {section name=q loop=$payment_methods_status}
                                                            <tr>
                                                                <td colspan="2"><b>{$payment_methods_status[q].method}</b></td>
                                                                <td>{t}Active{/t}: <input type="checkbox" name="{$payment_methods_status[q].smarty_tpl_key}" {if $payment_methods_status[q].active == 1} checked {/if} value=1 class="olotd5"></td>
                                                            </tr>
                                                        {/section}
                                                    </table>
                                                </td>
                                            </tr>

                                            <!-- Bank Details -->  
                                            <tr>
                                                <td colspan="2"><font color="red"><b>{t}Bank Details{/t}</b></font></td>
                                            </tr>
                                            <tr>
                                                <td><b>{t}Bank Account Name{/t}:</b></td>
                                                <td><input name="bank_account_name" class="olotd5" value="{$payment_settings.bank_account_name}" type="text" maxlength="30" onkeydown="return onlyAlphaNumeric(event);"></td>
                                            </tr>
                                            <tr>
                                                <td><b>{t}Bank Name{/t}:</b></td>
                                                <td><input name="bank_name" class="olotd5" value="{$payment_settings.bank_name}" type="text" maxlength="30" onkeydown="return onlyAlphaNumeric(event);"></td>
                                            </tr>
                                            <tr>
                                                <td><b>{t}Bank Account Number{/t}:</b></td>
                                                <td><input name="bank_account_number" class="olotd5" value="{$payment_settings.bank_account_number}" type="text" maxlength="15" onkeydown="return onlyNumbers(event);"></td>
                                            </tr>
                                            <tr>
                                                <td><b>{t}Bank Sort Code{/t}</b></td>
                                                <td><input name="bank_sort_code" class="olotd5" value="{$payment_settings.bank_sort_code}" type="text" maxlength="10" onkeydown="return onlyNumbers(event);"></td>
                                            </tr>
                                            <tr>
                                                <td><b>{t}Bank IBAN{/t}</b></td>
                                                <td><input name="bank_iban" class="olotd5" value="{$payment_settings.bank_iban}" type="text" maxlength="34" placeholder="GB15MIDL40051512345678" onkeydown="return onlyAlphaNumeric(event);"></td>
                                            </tr>

                                            <!-- PayPal -->
                                            <tr>
                                                <td colspan="2"><b><font color="red">{t}Paypal Information{/t}</font></b></td>                                                    
                                            </tr>
                                            <tr>
                                                <td colspan="2">{t}You must have a PayPal Merchant account set and working. Please see https://www.paypal.com/ for more information.{/t}</td>
                                            </tr>
                                            <tr>
                                                <td><b>{t}Paypal Email{/t}</b></td>
                                                <td><input name="paypal_email" class="olotd5" value="{$payment_settings.paypal_email}" size="50" type="email" maxlength="50" placeholder="no-reply@quantumwarp.com" onkeydown="return onlyEmail(event);"/></td>

                                            <!-- Invoice Messages -->
                                            <tr>
                                                <td colspan="2"><font color="red"><b>{t}Invoice Messages{/t}</b></font></td>
                                            </tr>
                                            <tr>
                                                <td><b>{t}Bank Transaction Message{/t}</b></td>
                                                <td><textarea class="olotd5" name="bank_transaction_message" cols="50" rows="2" >{$payment_settings.bank_transaction_msg}</textarea><br></td>
                                            </tr>
                                            <tr>
                                                <td><b>{t}Cheques payable to{/t}:</b></td>
                                                <td><textarea class="olotd5" name="cheque_payable_to_msg" cols="50" rows="2" >{$payment_settings.cheque_payable_to_msg}</textarea><br></td>
                                            </tr>
                                            <tr>
                                                <td><b>{t}Invoice Footer Message{/t}:</b></td>
                                                <td><textarea class="olotd5" name="invoice_footer_msg" cols="50" rows="2" >{$payment_settings.invoice_footer_msg}</textarea><br></td>
                                            </tr>

                                        </table>                                            
                                        <input type="submit" name="submit" value="Submit">                                        
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