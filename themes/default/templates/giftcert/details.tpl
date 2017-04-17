<!-- print_gift.tpl -->
<table width="700" cellpadding="4" cellspacing="0" border="0" class="olotable">
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
                                
                                <!-- Customer Details -->
                                <td valign="top" width="50%">
                                    <b>To:</b>
                                    {section name=q loop=$customer_details}
                                        {$customer_details[q].CUSTOMER_DISPLAY_NAME}<br>
                                        {$customer_details[q].CUSTOMER_ADDRESS}<br>
                                        {$customer_details[q].CUSTOMER_CITY} {$customer_details[q].CUSTOMER_STATE} .{$customer_details[q].CUSTOMER_ZIP}<br>
                                        <b>Customer ID: </b>{$customer_details[q].CUSTOMER_ID}                                        
                                    {/section}
                                </td>
                                
                                <!-- Gift Certificate Details -->
                                <td valign="top" width="50%">
                                    {section name=g loop=$giftcert_details}
                                        <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                            <tr>
                                                <td><b>Giftcert ID</b></td>
                                                <td>{$giftcert_details[g].GIFTCERT_ID}</td>
                                            </tr>                                            
                                            <tr>
                                                <td><b>{$translate_payment_gift_code_3}</b></td>
                                                <td>{$giftcert_details[g].GIFTCERT_CODE}</td>
                                            </tr>
                                            <tr>
                                                <td><b>{$translate_payment_amount}</b></td>
                                                <td>{$currency_sym}{$giftcert_details[g].AMOUNT|string_format:"%.2f"}</td>
                                            </tr>
                                            <tr>
                                                <td><b>{$translate_payment_created}</b></td>
                                                <td>{$giftcert_details[g].DATE_CREATED|date_format:$date_format}</td>
                                            </tr>
                                            <tr>
                                                <td><b>Created By</b></td>
                                                <td>{$giftcert_details[g].EMPLOYEE_ID}</td>
                                            </tr>
                                            <tr>
                                                <td><b>{$translate_payment_expires}</b></td>
                                                <td>{$giftcert_details[g].DATE_EXPIRES|date_format:$date_format}</td>
                                            </tr>
                                            <tr>
                                                <td><b>Redeemed on</b></td>
                                                <td>{$giftcert_details[g].DATE_REDEEMED|date_format:$date_format}</td>
                                            </tr>
                                            <tr>
                                                <td><b>Is Redeemed</b></td>
                                                <td>{$giftcert_details[g].IS_REDEEMED}</td>
                                            </tr>
                                        </table>
                                    {/section}
                                </td>
                                
                            </tr>
                        </table>
                        {section name=g loop=$giftcert_details}      
                            <table cellpadding="3" cellspacing="0" border="0" width="100%">
                                <tr>
                                    <td><b>Memo:</b></td>
                                </tr>
                                <tr>
                                    <td>{$giftcert_details[g].MEMO}</td>
                                </tr>
                                <tr>
                                    <td>
                                         <p><b>System Message</b><p>                        
                                         <p>{$translate_payment_gift_note_1} {$currency_sym}{$giftcert_details[g].AMOUNT|string_format:"%.2f"} {$translate_payment_gift_note_2}</p>                        
                                    </td>
                                </tr>
                            </table>                                            
                        {/section}   
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>