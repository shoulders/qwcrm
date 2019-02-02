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
                                            
                                            <!-- Available Payment Methods -->
                                            <tr>
                                                <td colspan="2">
                                                    <table>
                                                        <caption><b><font color="red">{t}Available Payment Methods{/t}</font></b></caption>
                                                        <tr>
                                                            <td>{t}Name{/t}</td>
                                                            <td>{t}Send{/t}</td>
                                                            <td>{t}Receive{/t}</td>
                                                            <td>{t}Enabled{/t}</td> 
                                                        </tr>
                                                        {section name=q loop=$payment_methods}
                                                            <tr>
                                                                <td>
                                                                    <b>{t}{$payment_methods[q].display_name}{/t}</b>
                                                                    <input type="hidden" name="payment_methods[{$payment_methods[q].method_key}][method_key]" value="{$payment_methods[q].method_key}">
                                                                </td>
                                                                <td>
                                                                    <input type="checkbox" name="payment_methods[{$payment_methods[q].method_key}][send]" value="1" class="olotd5" {if $payment_methods[q].send} checked{/if}{if $payment_methods[q].send_protected} disabled{/if}>
                                                                    {if $payment_methods[q].send_protected}
                                                                        <input type="hidden" name="payment_methods[{$payment_methods[q].method_key}][send]" value="{$payment_methods[q].send}">
                                                                    {/if}
                                                                </td>
                                                                <td>
                                                                    <input type="checkbox" name="payment_methods[{$payment_methods[q].method_key}][receive]" value="1" class="olotd5" {if $payment_methods[q].receive} checked{/if}{if $payment_methods[q].receive_protected} disabled{/if}>
                                                                    {if $payment_methods[q].receive_protected}
                                                                        <input type="hidden" name="payment_methods[{$payment_methods[q].method_key}][receive]" value="{$payment_methods[q].receive}">
                                                                    {/if}
                                                                </td>
                                                                <td>
                                                                    <input type="checkbox" name="payment_methods[{$payment_methods[q].method_key}][enabled]" value="1" class="olotd5" {if $payment_methods[q].enabled} checked{/if}>                                                                    
                                                                </td>
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
                                                <td><b>{t}Paypal Email{/t}</b></td>
                                                <td><input name="paypal_email" class="olotd5" value="{$payment_options.paypal_email}" size="50" type="email" maxlength="50" placeholder="no-reply@quantumwarp.com" onkeydown="return onlyEmail(event);"/></td>
                                            </tr>
                                            <!-- Invoice Messages -->
                                            <tr>
                                                <td colspan="2"><font color="red"><b>{t}Invoice Messages{/t}</b></font></td>
                                            </tr>
                                            <tr>
                                                <td><b>{t}Bank Transfer{/t}</b></td>
                                                <td><textarea class="olotd5" name="invoice_bank_transfer_msg" cols="50" rows="2" >{$payment_options.invoice_bank_transfer_msg}</textarea><br></td>
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