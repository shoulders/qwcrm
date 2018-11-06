<!-- display_payment_methods_block.tpl -->
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
<script>{include file="../`$theme_js_dir_finc`jscal2/language.js"}</script>
<script>
    
    // Show selected Payment Method
    function selectPaymentMethod() { 

        // Get the selected payment method name
        var paymentMethod = document.getElementById("method").value;
        
        // Hide the other payment methods
        $(".paymentMethod").hide();
        
        // Disable all input fields
        $(".paymentInput").prop("disabled", true);
        
        // Unhide the selected payment method               
        $("#" + paymentMethod).show();
        
        // Enable selected input fields
        $("#" + paymentMethod + " .paymentInput").prop("disabled", false);
        
    }

</script>

<table width="700" border="0" cellpadding="20" cellspacing="5">
    <tr>
        <td>
            <table width="100%" cellpadding="4" cellspacing="0" border="0">
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{t}Payments for{/t} {t}Invoice{/t} {$invoice_details.invoice_id} - {$client_details.display_name}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}PAYMENT_NEW_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}PAYMENT_NEW_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
                    </td>
                </tr>
                <tr>
                    <td class="olotd5" colspan="2">
                        <form method="post" action="index.php?component=payment&page_tpl=new&invoice_id={$invoice_id}">
                            <table width="100%" border="0" cellpadding="10" cellspacing="0" class="olotable">

                                <!-- Select Payment Method -->
                                <tr>
                                    <td>
                                        <p>{t}Select Payment Method{/t}</p>
                                        <select id="method" name="qpayment[method]" class="olotd4" onChange="selectPaymentMethod();" required>                                            
                                            <option selected hidden disabled></option>
                                            {section name=s loop=$active_payment_accepted_methods}                                            
                                                <option value="{$active_payment_accepted_methods[s].accepted_method_id}"{if $active_payment_accepted_methods[s].accepted_method_id === $payment_method} selected{/if}>{t}{$active_payment_accepted_methods[s].display_name}{/t}</option>
                                            {/section} 
                                        </select>
                                    </td> 
                                </tr>

                                <!-- Payment Methods -->
                                <tr>
                                    <td>

                                        <!-- Cash -->
                                        <div id="cash" class="paymentMethod"{if $payment_method !== 'cash'} hidden{/if}>
                                            <table width="100%" cellpadding="4" cellspacing="0" border="0">
                                                <tr>
                                                    <td class="menuhead2">&nbsp;{t}Cash{/t}</td>
                                                </tr>
                                                <tr>
                                                    <td class="menutd2">
                                                        <table width="100%" cellpadding="4" cellspacing="0" border="0" width="100%" class="olotable">
                                                            <tr class="olotd4">
                                                                <td class="row2"></td>
                                                                <td class="row2"><b>{t}Date{/t}</b></td>
                                                                <td class="row2"><b>{t}Amount{/t}</b></td>
                                                            </tr>
                                                            <tr class="olotd4">
                                                                <td></td>
                                                                <td>
                                                                    <input id="cash_date" name="qpayment[date]" class="paymentInput olotd4" size="10" value="{$smarty.now|date_format:$date_format}" type="text" maxlength="10" pattern="{literal}^[0-9]{2,4}(?:\/|-)[0-9]{2}(?:\/|-)[0-9]{2,4}${/literal}" required onkeydown="return onlyDate(event);" disabled>
                                                                    <button type="button" id="cash_date_button">+</button>
                                                                    <script>                                                        
                                                                        Calendar.setup( {
                                                                            trigger     : "cash_date_button",
                                                                            inputField  : "cash_date",
                                                                            dateFormat  : "{$date_format}"                                                                                            
                                                                        } );                                                        
                                                                    </script>                                                    
                                                                </td>
                                                                <td>{$currency_sym}<input name="qpayment[amount]" class="paymentInput olotd5" size="10" value="{$invoice_details.balance|string_format:"%.2f"}" type="text" maxlength="10" required pattern="{literal}[0-9]{1,7}(.[0-9]{0,2})?{/literal}" required onkeydown="return onlyNumberPeriod(event);" disabled></td>
                                                            </tr>
                                                            <tr>
                                                                <td valign="top"><b>{t}Note{/t}</b></td>
                                                                <td colspan="3"><textarea name="qpayment[note]" cols="60" rows="4" class="paymentInput olotd4" disabled></textarea></td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>

                                        <!-- Cheque -->
                                        <div id="cheque" class="paymentMethod"{if $payment_method !== 'cheque'} hidden{/if}>
                                            <table width="100%" cellpadding="4" cellspacing="0" border="0" >
                                                <tr>
                                                    <td class="menuhead2">&nbsp;{t}Cheque{/t}</td>
                                                </tr>
                                                <tr>
                                                    <td class="menutd2">
                                                        <table width="100%" cellpadding="4" cellspacing="0" border="0" width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
                                                            <tr class="olotd4">
                                                                <td class="row2"></td>
                                                                <td class="row2"><b>{t}Date{/t}</b></td>
                                                                <td class="row2"><b>{t}Cheque Number{/t}:</b></td>
                                                                <td class="row2"><b>{t}Amount{/t}</b></td>
                                                            </tr>
                                                            <tr class="olotd4">
                                                                <td></td>
                                                                <td>
                                                                    <input id="cheque_date" name="qpayment[date]" class="paymentInput olotd4" size="10" value="{$smarty.now|date_format:$date_format}" type="text" maxlength="10" pattern="{literal}^[0-9]{2,4}(?:\/|-)[0-9]{2}(?:\/|-)[0-9]{2,4}${/literal}" required onkeydown="return onlyDate(event);" disabled>
                                                                    <button type="button" id="cheque_date_button">+</button>
                                                                    <script>                                                        
                                                                        Calendar.setup( {
                                                                            trigger     : "cheque_date_button",
                                                                            inputField  : "cheque_date",
                                                                            dateFormat  : "{$date_format}"                                                                                            
                                                                        } );                                                        
                                                                    </script>                                                    
                                                                </td>
                                                                <td><input name="qpayment[cheque_number]" class="paymentInput olotd5" type="text" maxlength="15" required onkeydown="return onlyNumber(event);"></td>                        
                                                                <td>{$currency_sym}<input name="qpayment[amount]" class="paymentInput olotd5" size="10" value="{$invoice_details.balance|string_format:"%.2f"}" type="text" maxlength="10" required pattern="{literal}[0-9]{1,7}(.[0-9]{0,2})?{/literal}" required onkeydown="return onlyNumberPeriod(event);" disabled></td>
                                                            </tr>
                                                            <tr>
                                                                <td valign="top"><b>{t}Note{/t}</b></td>
                                                                <td colspan="3" ><textarea name="qpayment[note]" cols="60" rows="4" class="paymentInput olotd4" disabled></textarea></td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div> 

                                        <!-- Credit Card -->
                                        <div id="credit_card" class="paymentMethod"{if $payment_method !== 'credit_card'} hidden{/if}>
                                            <table width="100%" cellpadding="4" cellspacing="0" border="0" >
                                                <tr>
                                                    <td class="menuhead2">&nbsp;{t}Credit Card{/t}</td>
                                                </tr>
                                                <tr>
                                                    <td class="menutd2">
                                                        <table width="100%" cellpadding="4" cellspacing="0" border="0" width="100%" class="olotable">
                                                            <tr class="olotd4">
                                                                <td class="row2"></td>
                                                                <td class="row2"><b>{t}Date{/t}</b></td>
                                                                <td class="row2"><b>{t}type{/t}:</b></td>
                                                                <td class="row2"><b>{t}Name on Card{/t}:</b></td>                        
                                                                <td class="row2"><b>{t}Amount{/t}:</b></td>
                                                            </tr>
                                                            <tr class="olotd4">
                                                                <td></td>
                                                                <td>
                                                                    <input id="credit_card_date" name="qpayment[date]" class="paymentInput olotd4" size="10" value="{$smarty.now|date_format:$date_format}" type="text" maxlength="10" pattern="{literal}^[0-9]{2,4}(?:\/|-)[0-9]{2}(?:\/|-)[0-9]{2,4}${/literal}" required onkeydown="return onlyDate(event);" disabled>
                                                                    <button type="button" id="credit_card_date_button">+</button>
                                                                    <script>                                                        
                                                                        Calendar.setup( {
                                                                            trigger     : "credit_card_date_button",
                                                                            inputField  : "credit_card_date",
                                                                            dateFormat  : "{$date_format}"                                                                                            
                                                                        } );                                                        
                                                                    </script>                                                    
                                                                </td>
                                                                <td>
                                                                    <select name="qpayment[card_type]" class="paymentInput olotd4" required disabled>
                                                                        <option selected hidden disabled></option>
                                                                        {section name=c loop=$active_credit_cards}
                                                                            <option value="{$active_credit_cards[c].card_key}">{$active_credit_cards[c].display_name}</option>
                                                                        {/section}
                                                                    </select>
                                                                </td>                        
                                                                <td><input name="qpayment[name_on_card]" class="paymentInput olotd5" type="text" maxlength="20" required onkeydown="return onlyAlpha(event);" disabled></td>
                                                                <td>{$currency_sym}<input name="qpayment[amount]" class="paymentInput olotd5" size="10" value="{$invoice_details.balance|string_format:"%.2f"}" type="text" maxlength="10" required pattern="{literal}[0-9]{1,7}(.[0-9]{0,2})?{/literal}" required onkeydown="return onlyNumberPeriod(event);" disabled></td>
                                                            </tr>
                                                            <tr>
                                                                <td valign="top"><b>{t}Note{/t}</b></td>
                                                                <td colspan="4"><textarea name="qpayment[note]" cols="60" rows="4" class="paymentInput olotd4" disabled></textarea></td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>

                                        <!-- Direct Deposit -->
                                        <div id="direct_deposit" class="paymentMethod"{if $payment_method !== 'direct_deposit'} hidden{/if}>
                                            <table width="100%" cellpadding="4" cellspacing="0" border="0" >
                                                <tr>
                                                    <td class="menuhead2">&nbsp;{t}Direct Deposit{/t}</td>
                                                </tr>
                                                <tr>
                                                    <td class="menutd2">
                                                        <table width="100%" cellpadding="4" cellspacing="0" border="0" width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
                                                            <tr class="olotd4">
                                                                <td class="row2"></td>
                                                                <td class="row2"><b>{t}Date{/t}</b></td>
                                                                <td class="row2"><b>{t}Direct Deposit ID{/t}:</b></td>
                                                                <td class="row2"><b>{t}Amount{/t}:</b></td>
                                                            </tr>
                                                            <tr class="olotd4">
                                                                <td></td>
                                                                <td>
                                                                    <input id="direct_deposit_date" name="qpayment[date]" class="paymentInput olotd4" size="10" value="{$smarty.now|date_format:$date_format}" type="text" maxlength="10" pattern="{literal}^[0-9]{2,4}(?:\/|-)[0-9]{2}(?:\/|-)[0-9]{2,4}${/literal}" required onkeydown="return onlyDate(event);" disabled>
                                                                    <button type="button" id="direct_deposit_date_button">+</button>
                                                                    <script>                                                        
                                                                        Calendar.setup( {
                                                                            trigger     : "direct_deposit_date_button",
                                                                            inputField  : "direct_deposit_date",
                                                                            dateFormat  : "{$date_format}"                                                                                            
                                                                        } );                                                        
                                                                    </script>                                                    
                                                                </td>
                                                                <td><input name="qpayment[deposit_reference]" class="paymentInput olotd5" type="text" maxlength="35" required onkeydown="return onlyAlphaNumericPunctuation(event);" disabled></td>
                                                                <td>{$currency_sym}<input name="qpayment[amount]" class="paymentInput olotd5" size="10" value="{$invoice_details.balance|string_format:"%.2f"}" type="text" required maxlength="10" pattern="{literal}[0-9]{1,7}(.[0-9]{0,2})?{/literal}" required onkeydown="return onlyNumberPeriod(event);" disabled></td>
                                                            </tr>
                                                            <tr>
                                                                <td valign="top"><b>{t}Note{/t}</b></td>
                                                                <td colspan="3" ><textarea name="qpayment[note]" cols="60" rows="4" class="paymentInput olotd4" disabled></textarea></td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>                                    

                                        <!-- Gift Certificate -->
                                        <div id="gift_certificate" class="paymentMethod"{if $payment_method !== 'gift_certificate'} hidden{/if}>
                                            <table width="100%" cellpadding="4" cellspacing="0" border="0" >
                                                <tr>
                                                    <td class="menuhead2">&nbsp;{t}Gift Certificate{/t}</td>
                                                </tr>
                                                <tr>
                                                    <td class="menutd2">
                                                        <table width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
                                                            <tr class="olotd4">
                                                                <td class="row2"></td>
                                                                <td class="row2"><b>{t}Date{/t}</b></td>
                                                                <td class="row2"><b>{t}Gift Code{/t}</b></td>
                                                            </tr>
                                                            <tr class="olotd4">
                                                                <td></td>
                                                                <td>
                                                                    <input id="giftcert_date" name="qpayment[date]" class="paymentInput olotd4" size="10" value="{$smarty.now|date_format:$date_format}" type="text" maxlength="10" pattern="{literal}^[0-9]{2,4}(?:\/|-)[0-9]{2}(?:\/|-)[0-9]{2,4}${/literal}" required onkeydown="return onlyDate(event);" disabled>
                                                                    <button type="button" id="giftcert_date_button">+</button>
                                                                    <script>                                                        
                                                                        Calendar.setup( {
                                                                            trigger     : "giftcert_date_button",
                                                                            inputField  : "giftcert_date",
                                                                            dateFormat  : "{$date_format}"                                                                                            
                                                                        } );                                                        
                                                                    </script>                                                    
                                                                </td>
                                                                <td><input name="qpayment[giftcert_code]" class="paymentInput olotd5" type="text" maxlength="16" required onkeydown="return onlyGiftCertCode(event);" disabled></td> 
                                                            </tr>
                                                            <tr>
                                                                <td valign="top"><b>{t}Note{/t}</b></td>
                                                                <td colspan="2"><textarea name="qpayment[note]" cols="60" rows="4" class="paymentInput olotd4" disabled></textarea></td>
                                                            </tr>                    
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>

                                        <!-- PayPal -->
                                        <div id="paypal" class="paymentMethod"{if $payment_method !== 'paypal'} hidden{/if}>
                                            <table width="100%" cellpadding="4" cellspacing="0" border="0" >
                                                <tr>
                                                    <td class="menuhead2">&nbsp;{t}Paypal{/t}</td>
                                                </tr>
                                                <tr>
                                                    <td class="menutd2">
                                                        <table width="100%" cellpadding="4" cellspacing="0" border="0" width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
                                                            <tr class="olotd4">
                                                                <td class="row2"></td>
                                                                <td class="row2"><b>{t}Date{/t}</b></td> 
                                                                <td class="row2"><b>{t}Transaction ID{/t}:</b></td>
                                                                <td class="row2"><b>{t}Amount{/t}:</b></td>
                                                            </tr>
                                                            <tr class="olotd4">
                                                                <td></td>
                                                                <td>
                                                                    <input id="paypal_date" name="qpayment[date]" class="paymentInput olotd4" size="10" value="{$smarty.now|date_format:$date_format}" type="text" maxlength="10" pattern="{literal}^[0-9]{2,4}(?:\/|-)[0-9]{2}(?:\/|-)[0-9]{2,4}${/literal}" required onkeydown="return onlyDate(event);" disabled>
                                                                    <button type="button" id="paypal_date_button">+</button>
                                                                    <script>                                                        
                                                                        Calendar.setup( {
                                                                            trigger     : "paypal_date_button",
                                                                            inputField  : "paypal_date",
                                                                            dateFormat  : "{$date_format}"                                                                                            
                                                                        } );                                                        
                                                                    </script>                                                    
                                                                </td>
                                                                <td><input name="qpayment[paypal_payment_id]" class="paymentInput olotd5" type="text" maxlength="20" required onkeydown="return onlyAlphaNumeric(event);" disabled></td>
                                                                <td>{$currency_sym}<input name="qpayment[amount]" class="paymentInput olotd5" size="10" value="{$invoice_details.balance|string_format:"%.2f"}" type="text" maxlength="10" required pattern="{literal}[0-9]{1,7}(.[0-9]{0,2})?{/literal}" required onkeydown="return onlyNumberPeriod(event);" disabled></td>
                                                            </tr>
                                                            <tr>
                                                                <td valign="top"><b>{t}Note{/t}</b></td>
                                                                <td colspan="4"><textarea name="qpayment[note]" cols="60" rows="4" class="paymentInput olotd4" disabled></textarea></td>
                                                            </tr>                    
                                                        </table>                                                        
                                                    </td>
                                                </tr>                                                
                                            </table>                                                  
                                        </div>
                                                                
                                        <!-- Hidden Variables -->                                                
                                        <div hidden> 
                                            <input type="hidden" name="qpayment[invoice_id]" value="{$invoice_id}">
                                            <input type="hidden" name="qpayment[type]" class="paymentInput" value="{$payment_type}">                                                    
                                        </div>

                                    </td>
                                </tr>
                                
                            </table>
                        </form>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>