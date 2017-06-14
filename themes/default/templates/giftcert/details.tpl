<!-- print_gift.tpl -->
<table width="700" cellpadding="4" cellspacing="0" border="0" class="olotable">
    <tr>
        <td class="olotd4">
            <table width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{$translate_payment_gift}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}GIFTCERT_DETAILS_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}GIFTCERT_DETAILS_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
                    </td>
                </tr>                
                <tr>
                    <td class="olotd4" valign="top">
                        <table cellpadding="3" cellspacing="0" border="0" width="100%">
                            <tr>
                                <td><h2>{t}Gift Certificate{/t}</h2></td>
                                <td>{$company_name}</td>
                                <td>{$company_phone}</td>
                            </tr>
                        </table>
                        <hr>
                        <table cellpadding="3" cellspacing="0" border="0" width="100%">
                            <tr>
                                
                                <!-- Customer Details -->
                                <td valign="top" width="50%">
                                    <b>{t}To{/t}:</b>                                    
                                    {$customer_details.CUSTOMER_DISPLAY_NAME}<br>
                                    {$customer_details.CUSTOMER_ADDRESS}<br>
                                    {$customer_details.CUSTOMER_CITY} {$customer_details.CUSTOMER_STATE} .{$customer_details.CUSTOMER_ZIP}<br>
                                    <b>{t}Customer ID{/t}: </b>{$customer_details.CUSTOMER_ID}                                    
                                </td>
                                
                                <!-- Gift Certificate Details -->
                                <td valign="top" width="50%">                                    
                                    <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                        <tr>
                                            <td><b>{t}Giftcert ID{/t}</b></td>
                                            <td>{$giftcert_details.GIFTCERT_ID}</td>
                                        </tr>
                                        <tr>
                                            <td><b>{t}Is Active/Deleted{/t}</b></td>
                                            <td>{$giftcert_details.ACTIVE}</td>
                                        </tr>  
                                        <tr>
                                            <td><b>{t}payment_gift_code_3{/t}</b></td>
                                            <td>{$giftcert_details.GIFTCERT_CODE}</td>
                                        </tr>
                                        <tr>
                                            <td><b>{t}payment_amount{/t}</b></td>
                                            <td>{$currency_sym}{$giftcert_details.AMOUNT|string_format:"%.2f"}</td>
                                        </tr>
                                        <tr>
                                            <td><b>{t}payment_created{/t}</b></td>
                                            <td>{$giftcert_details.DATE_CREATED|date_format:$date_format}</td>
                                        </tr>
                                        <tr>
                                            <td><b>{t}Created By{/t}</b></td>
                                            <td>{$giftcert_details.EMPLOYEE_ID}</td>
                                        </tr>
                                        <tr>
                                            <td><b>{t}payment_expires{/t}</b></td>
                                            <td>{$giftcert_details.DATE_EXPIRES|date_format:$date_format}</td>
                                        </tr>
                                        <tr>
                                            <td><b>{t}Redeemed on{/t}</b></td>
                                            <td>{$giftcert_details.DATE_REDEEMED|date_format:$date_format}</td>
                                        </tr>
                                        <tr>
                                            <td><b>{t}Is Redeemed{/t}</b></td>
                                            <td>{$giftcert_details.IS_REDEEMED}</td>
                                        </tr>
                                    </table>                                   
                                </td>                                
                            </tr>
                        </table>                           
                        <table cellpadding="3" cellspacing="0" border="0" width="100%">
                            <tr>
                                <td><b>{t}Note{/t}:</b></td>
                            </tr>
                            <tr>
                                <td>{$giftcert_details.NOTE}</td>
                            </tr>
                        </table>                        
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>