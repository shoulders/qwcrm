<!-- new_payment_paypal_block.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<form method="post" action="index.php?component=payment&page_tpl=new&invoice_id={$invoice_id}">
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
                            <input id="paypal_date" name="date" class="olotd4" size="10" value="{$smarty.now|date_format:$date_format}" type="text" maxlength="10" pattern="{literal}^[0-9]{1,2}(\/|-)[0-9]{1,2}(\/|-)[0-9]{2,2}([0-9]{2,2})?${/literal}" required onkeydown="return onlyDate(event);">
                            <input id="paypal_date_button" value="+" type="button">                                                    
                            <script>                                                        
                                Calendar.setup( {
                                    trigger     : "paypal_date_button",
                                    inputField  : "paypal_date",
                                    dateFormat  : "{$date_format}"                                                                                            
                                } );                                                        
                            </script>                                                    
                        </td>
                        <td><input name="paypal_transaction_id" class="olotd5" type="text" maxlength="20" required onkeydown="return onlyAlphaNumeric(event);"></td>
                        <td>{$currency_sym}<input name="amount" class="olotd5" size="10" value="{$invoice_details.balance|string_format:"%.2f"}" type="text" maxlength="10" required pattern="{literal}[0-9]{1,7}(.[0-9]{0,2})?{/literal}" required onkeydown="return onlyNumberPeriod(event);"/></td>
                    </tr>
                    <tr>
                        <td valign="top"><b>{t}Note{/t}</b></td>
                        <td colspan="4"><textarea name="note" cols="60" rows="4" class="olotd4"></textarea></td>
                    </tr>                    
                </table>
                <p>
                    <input type="hidden" name="method_name" value="{t}PayPal{/t}">
                    <input type="hidden" name="method_type" value="paypal">                   
                    <button type="submit" name="submit" value="submit">{t}Submit Paypal Payment{/t}</button>
                </p>
            </td>
        </tr>
    </table>
</form>