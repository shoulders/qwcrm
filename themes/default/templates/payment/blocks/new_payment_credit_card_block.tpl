<!-- new_payment_credit_card_block.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<form method="post" action="index.php?page=payment:new&invoice_id={$invoice_id}">
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
                            <input id="credit_card_date" name="date" class="olotd4" size="10" value="{$invoice_details.date|date_format:$date_format}" type="text" maxlength="10" pattern="{literal}^[0-9]{1,2}(\/|-)[0-9]{1,2}(\/|-)[0-9]{2,2}([0-9]{2,2})?${/literal}" required onkeydown="return onlyDate(event);">
                            <input id="credit_card_date_button" value="+" type="button">                                                    
                            <script>                                                        
                                Calendar.setup( {
                                    trigger     : "credit_card_date_button",
                                    inputField  : "credit_card_date",
                                    dateFormat  : "{$date_format}"                                                                                            
                                } );                                                        
                            </script>                                                    
                        </td>
                        <td>
                            <select name="card_type" class="olotd4">                     
                                {section name=c loop=$active_credit_cards}
                                    <option value="{$active_credit_cards[c].card_key}">{$active_credit_cards[c].display_name}</option>
                                {/section}
                            </select>
                        </td>                        
                        <td><input name="name_on_card" class="olotd5" type="text" maxlength="20" required onkeydown="return onlyAlpha(event);"></td>
                        <td>{$currency_sym}<input name="amount" class="olotd5" size="10" value="{$invoice_details.balance|string_format:"%.2f"}" type="text" maxlength="10" required pattern="{literal}[0-9]{1,7}(.[0-9]{0,2})?{/literal}" required onkeydown="return onlyNumberPeriod(event);"/></td>
                    </tr>
                    <tr>
                        <td valign="top"><b>{t}Note{/t}</b></td>
                        <td colspan="4"><textarea name="note" cols="60" rows="4" class="olotd4"></textarea></td>
                    </tr>
                </table>
                <p>
                    <input type="hidden" name="method_name" value="{t}Credit Card{/t}">
                    <input type="hidden" name="method_type" value="credit_card">               
                    <button type="submit" name="submit" value="submit">{t}Submit Credit Card Payment{/t}</button>
                </p>
            </td>
        </tr>
    </table>
</form>