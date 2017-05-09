<!-- search_gift.tpl -->
<table width="700" border="0" cellpadding="20" cellspacing="5">
    <tr>
        <td>
            <table width="100%" cellpadding="4" cellspacing="0" border="0">
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{$translate_payment_gift}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle"></td>
                </tr>
                <tr>
                    <td class="olotd5" colspan="2">              
                        <table width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
                            <tr>
                                <td class="olotd4">
                                    <table width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
                                        <tr>
                                            <td class="olotd4" valign="top">
                                                <table cellpadding="3" cellspacing="0" border="0" width="100%">
                                                    <tr>
                                                        <td><h2>{$translate_payment_gift}</h2></td>
                                                        <td>{$company_name}</td>
                                                        <td>{$company_phone}</td>
                                                    </tr>
                                                </table>
                                                <hr>
                                                <table cellpadding="3" cellspacing="0" border="0" width="100%">
                                                    <tr>
                                                        <td valign="top" width="50%">
                                                            <b>To:</b>                                                            
                                                            {$customer_details.CUSTOMER_DISPLAY_NAME}<br>
                                                            {$customer_details.CUSTOMER_ADDRESS}<br>
                                                            {$customer_details.CUSTOMER_CITY} {$customer_details.CUSTOMER_STATE}, {$customer_details.CUSTOMER_ZIP}<br>
                                                            <b>Customer ID: </b>{$customer_details.CUSTOMER_ID}
                                                            {assign var="customer_id" value=$customer_details.CUSTOMER_ID}                                                                                                                       
                                                        </td>
                                                        <td valign="top" width="50%">
                                                            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                                                <tr>
                                                                    <td><b>{$translate_payment_amount}</b></td>
                                                                    <td>${$amount|string_format:"%.2f"}</td>
                                                                </tr><tr>
                                                                    <td><b>{$translate_payment_gift_code_3}</b></td>
                                                                    <td>{$giftcert_code}</td>
                                                                </tr><tr>
                                                                    <td><b>{$translate_payment_created}</b></td>
                                                                    <td>{$create|date_format:$date_format}</td>
                                                                </tr><tr>
                                                                    <td><b>{$translate_payment_expires}</b></td>
                                                                    <td>{$expire|date_format:$date_format}</td>
                                                                </tr>
                                                            </table>
                                                            <table cellpadding="3" cellspacing="0" border="0" width="100%">
                                                                <tr>
                                                                    <td>{$note}</td>
                                                                </tr>
                                                            </table>
                                                        <td>
                                                    </tr>
                                                </table>
                                                {$translate_payment_gift_note_1} {$currency_sym}{$amount} {$translate_payment_gift_note_2}
                                            </td>
                                        </tr>
                                    </table>                            
                                </td>
                            </tr>
                        </table>
                        <a href="index.php?page=payment:details&giftcert_id={$giftcert_id}&action=print&submit=1&theme=off" target="new"><img src="{$theme_images_dir}icons/16x16/fileprint.gif" border="0" onMouseOver="ddrivetip('Print');" onMouseOut="hideddrivetip();"></a>&nbsp;<a href="index.php?page=customer:details&customer_id={$customer_id}">{$translate_payment_back}</a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>