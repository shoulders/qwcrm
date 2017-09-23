<!-- new_payment_direct_deposit_block.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<form method="post" action="index.php?page=payment:new&invoice_id={$invoice_id}">
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
                            <input id="direct_deposit_date" name="date" class="olotd4" size="10" value="{$invoice_details.date|date_format:$date_format}" type="text" maxlength="10" pattern="{literal}^[0-9]{1,2}(\/|-)[0-9]{1,2}(\/|-)[0-9]{2,2}([0-9]{2,2})?${/literal}" required onkeydown="return onlyDate(event);">
                            <input id="direct_deposit_date_button" value="+" type="button">                                                    
                            <script>                                                        
                                Calendar.setup( {
                                    trigger     : "direct_deposit_date_button",
                                    inputField  : "direct_deposit_date",
                                    dateFormat  : "{$date_format}"                                                                                            
                                } );                                                        
                            </script>                                                    
                        </td>
                        <td><input name="deposit_reference" class="olotd5" type="text" maxlength="15" required onkeydown="return onlyAlphaNumeric(event);"></td>
                        <td>{$currency_sym}<input name="amount" class="olotd5" size="10" value="{$invoice_details.balance|string_format:"%.2f"}" type="text" required maxlength="10" pattern="{literal}[0-9]{1,7}(.[0-9]{0,2})?{/literal}" required onkeydown="return onlyNumberPeriod(event);"/></td>
                    </tr>
                    <tr>
                        <td valign="top"><b>{t}Note{/t}</b></td>
                        <td colspan="3" ><textarea name="note" cols="60" rows="4" class="olotd4"></textarea></td>
                    </tr>
                </table>
                <p>
                    <input type="hidden" name="method_name" value="{t}Direct Deposit{/t}">
                    <input type="hidden" name="method_type" value="direct_deposit">                   
                    <button type="submit" name="submit" value="submit">{t}Submit Direct Deposit Payment{/t}</button>
                </p>
            </td>
        </tr>
    </table>
</form>