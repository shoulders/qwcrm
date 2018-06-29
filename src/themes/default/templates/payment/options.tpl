<!-- payment_options.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<script src="{$theme_js_dir}tinymce/tinymce.min.js"></script>
<script>{include file="`$theme_js_dir_finc`editor-config.js"}</script>

<table width="100%" border="0" cellpadding="0" cellspacing="0">    
    <tr>
        <td>
            <table width="700" cellpadding="5" cellspacing="0" border="0" >
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{t}Payment Options{/t}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">                        
                        <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}PAYMENT_OPTIONS_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}PAYMENT_OPTIONS_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
                    </td>
                </tr>
                <tr>
                    <td class="menutd2" colspan="2">                    
                        <table width="100%" class="olotable" cellpadding="5" cellspacing="0" border="0" >
                            <tr>
                                <td width="100%" valign="top" class="menutd">                                    
                                    <form method="post" action="index.php?component=payment&page_tpl=options">                                        
                                        <table>
                                            
                                            <!-- Available Payment Types -->
                                            <tr>
                                                <td colspan="2">
                                                    <table>
                                                        <caption><b><font color="red">{t}Available Payment Types{/t}</font></b></caption>
                                                        {section name=q loop=$payment_accepted_methods}
                                                            <tr>
                                                                <td colspan="2"><b>{t}{$payment_accepted_methods[q].display_name}{/t}</b></td>
                                                                <td><input type="checkbox" name="{$payment_accepted_methods[q].accepted_method_id}" {if $payment_accepted_methods[q].active == 1} checked{/if} value="1" class="olotd5"></td>
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
                                                <td><input name="bank_account_name" class="olotd5" value="{$payment_options.bank_account_name}" type="text" maxlength="30" onkeydown="return onlyAlphaNumeric(event);"></td>
                                            </tr>
                                            <tr>
                                                <td><b>{t}Bank Name{/t}:</b></td>
                                                <td><input name="bank_name" class="olotd5" value="{$payment_options.bank_name}" type="text" maxlength="30" onkeydown="return onlyAlphaNumeric(event);"></td>
                                            </tr>
                                            <tr>
                                                <td><b>{t}Bank Account Number{/t}:</b></td>
                                                <td><input name="bank_account_number" class="olotd5" value="{$payment_options.bank_account_number}" type="text" maxlength="15" onkeydown="return onlyNumber(event);"></td>
                                            </tr>
                                            <tr>
                                                <td><b>{t}Bank Sort Code{/t}</b></td>
                                                <td><input name="bank_sort_code" class="olotd5" value="{$payment_options.bank_sort_code}" type="text" maxlength="10" onkeydown="return onlySortcode(event);"></td>
                                            </tr>
                                            <tr>
                                                <td><b>{t}Bank IBAN{/t}</b></td>
                                                <td><input name="bank_iban" class="olotd5" value="{$payment_options.bank_iban}" type="text" maxlength="34" placeholder="GB15MIDL40051512345678" onkeydown="return onlyAlphaNumeric(event);"></td>
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
                                                <td><input name="paypal_email" class="olotd5" value="{$payment_options.paypal_email}" size="50" type="email" maxlength="50" placeholder="no-reply@quantumwarp.com" onkeydown="return onlyEmail(event);"/></td>

                                            <!-- Invoice Messages -->
                                            <tr>
                                                <td colspan="2"><font color="red"><b>{t}Invoice Messages{/t}</b></font></td>
                                            </tr>
                                            <tr>
                                                <td><b>{t}Direct Deposit{/t}</b></td>
                                                <td><textarea class="olotd5" name="invoice_direct_deposit_msg" cols="50" rows="2" >{$payment_options.invoice_direct_deposit_msg}</textarea><br></td>
                                            </tr>
                                            <tr>
                                                <td><b>{t}Cheque{/t}</b></td>
                                                <td><textarea class="olotd5" name="invoice_cheque_msg" cols="50" rows="2" >{$payment_options.invoice_cheque_msg}</textarea><br></td>
                                            </tr>
                                            <tr>
                                                <td><b>{t}Footer{/t}</b></td>
                                                <td><textarea class="olotd5" name="invoice_footer_msg" cols="50" rows="2" >{$payment_options.invoice_footer_msg}</textarea><br></td>
                                            </tr>

                                        </table>                                            
                                        <button type="submit" name="submit" value="submit">{t}Submit{/t}</button>
                                        <button type="button" class="olotd4" onclick="window.location.href='index.php';">{t}Cancel{/t}</button>
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