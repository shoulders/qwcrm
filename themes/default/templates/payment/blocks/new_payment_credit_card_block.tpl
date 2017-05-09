<!-- new_payment_credit_card_block.tpl -->
<form method="post" action="index.php?page=payment:new&invoice_id={$invoice_id}">
    <table width="100%" cellpadding="4" cellspacing="0" border="0" >
        <tr>
            <td class="menuhead2">&nbsp;{$translate_payment_credit_card}</td>
        </tr>
        <tr>
            <td class="menutd2">
                <table width="100%" cellpadding="4" cellspacing="0" border="0" width="100%" class="olotable">
                    <tr class="olotd4">
                        <td class="row2"></td>
                        <td class="row2"><b>{$translate_payment_type}:</b></td>
                        <td class="row2"><b>{$translate_payment_name_on_card}:</b></td>                        
                        <td class="row2"><b>{$translate_payment_amount}:</b></td>
                    </tr>
                    <tr class="olotd4">
                        <td></td>
                        <td>
                            <select name="card_type" class="olotd4">                     
                                {section name=c loop=$active_credit_cards}
                                    <option value="{$active_credit_cards[c].CARD_TYPE}">{$active_credit_cards[c].CARD_NAME}</option>
                                {/section}
                            </select>
                        </td>
                        <td><input type="text" name="name_on_card" size="20" class="olotd4"></td>                        
                        <td>{$currency_sym}<input type="text" name="amount" size="8" value="{$balance|string_format:"%.2f"}" class="olotd4"></td>
                    </tr>
                    <tr>
                        <td valign="top"><b>{$translate_payment_note}</b></td>
                        <td colspan="3"><textarea name="note" cols="60" rows="4" class="olotd4"></textarea></td>
                    </tr>
                </table>
                <p>
                    <input type="hidden" name="type" value="1">                    
                    <button type="submit" name="submit" value="submit">Submit Credit Card Payment</button>
                </p>
            </td>
        </tr>
    </table>
</form>