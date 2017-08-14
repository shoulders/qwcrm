<!-- new_payment_gift_certificate_block.tpl -->
<form method="post" action="index.php?page=payment:new&invoice_id={$invoice_id}">
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
                            <input id="giftcert_date" name="date" class="olotd4" size="10" value="{$invoice_details.date|date_format:$date_format}" type="text" maxlength="10" pattern="{literal}^[0-9]{1,2}(\/|-)[0-9]{1,2}(\/|-)[0-9]{2,2}([0-9]{2,2})?${/literal}" required onkeydown="return onlyDate(event);">
                            <input id="giftcert_date_button" value="+" type="button">                                                    
                            <script>                                                        
                                Calendar.setup( {
                                    trigger     : "giftcert_date_button",
                                    inputField  : "giftcert_date",
                                    dateFormat  : "{$date_format}"                                                                                            
                                } );                                                        
                            </script>                                                    
                        </td>
                        <td><input name="giftcert_code" class="olotd5" type="text" maxlength="16" required onkeydown="return onlyAlphaNumeric(event);"></td> 
                    </tr>
                    <tr>
                        <td valign="top"><b>{t}Note{/t}</b></td>
                        <td colspan="2"><textarea name="note" cols="60" rows="4" class="olotd4"></textarea></td>
                    </tr>                    
                </table>
                <p>
                    <input type="hidden" name="method_name" value="{t}Gift Certificate{/t}">
                    <input type="hidden" name="type" value="4">                    
                    <button type="submit" name="submit" value="submit">{t}Submit Gift Certificate{/t}</button>
                </p>
            </td>
        </tr>
    </table>
</form>