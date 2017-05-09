<!-- details_customer_details_block.tpl -->
<table width="100%" border="0" cellpadding="5" cellspacing="5">
    <tr>
        <td>            
            <table width="100%" cellpadding="4" cellspacing="0" border="0" >
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{$translate_customer_details} {$customer_details.CUSTOMER_FIRST_NAME}&nbsp;{$customer_details.CUSTOMER_LAST_NAME}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle"><a href="index.php?page=customer:edit&customer_id={$customer_details.CUSTOMER_ID}" ><img src="{$theme_images_dir}icons/edit.gif"  alt="" height="16" border="0"> Edit</a></td>
                </tr>
                <tr>
                    <td class="menutd2" colspan="2">
                        <table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
                            <tr>
                                <td class="menutd">                                                           
                                    <table class="olotable" border="0" cellpadding="5" cellspacing="5" width="100%" summary="Customer Contact">
                                        <tr>
                                            <td class="olohead" colspan="4">
                                                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                    <tr>
                                                        <td class="menuhead2">&nbsp;{$translate_customer_contact}</td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="menutd"><b>{$translate_customer_contact_2}</b></td>
                                            <td class="menutd">{$customer_details.CUSTOMER_FIRST_NAME} {$customer_details.CUSTOMER_LAST_NAME}</td>
                                            <td class="menutd"><b>{$translate_customer_www}</b></td>
                                            <td class="menutd"><a href="{$customer_details.CUSTOMER_WWW}"</a>{$customer_details.CUSTOMER_WWW}</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td class="menutd"><b>{$translate_email}</b></td>
                                            <td class="menutd">{$customer_details.CUSTOMER_EMAIL}</td>                                                                    
                                        </tr>
                                        <tr>
                                            <td class="menutd"><b>{$translate_credit_terms}</b></td>
                                            <td class="menutd">{$customer_details.CREDIT_TERMS}</td>
                                        </tr>
                                        <tr class="row2">
                                            <td class="menutd" colspan="4"></td>
                                        </tr>
                                        <tr>
                                            <td class="menutd"> <b>{$translate_customer_address}</b> <a style="color:red" href="{$GoogleMapString}" target="_blank" ><img src="{$theme_images_dir}icons/map.png" alt="" border="0" height="14" width="14" />[{$translate_customer_get_directions}]</a></td>
                                            <td class="menutd"></td>
                                            <td class="menutd"><b>{$translate_customer_home}</b></td>
                                            <td class="menutd">{$customer_details.CUSTOMER_PHONE}</td>
                                        </tr>
                                        <tr>
                                            <td class="menutd"></td>
                                            <td class="menutd">{$customer_details.CUSTOMER_ADDRESS|nl2br}<br>{$customer_details.CUSTOMER_CITY}<br>{$customer_details.CUSTOMER_STATE}<br>{$customer_details.CUSTOMER_ZIP}</td>
                                            <td class="menutd"><b>{$translate_customer_work}</b></td>
                                            <td class="menutd">{$customer_details.CUSTOMER_WORK_PHONE}</td>
                                        </tr>
                                        <tr>
                                            <td class="menutd"></td>
                                            <td class="menutd"></td>
                                            <td class="menutd"><b>{$translate_customer_mobile}</b></td>
                                            <td class="menutd">{$customer_details.CUSTOMER_MOBILE_PHONE}</td>
                                        </tr>
                                        <tr class="row2">
                                            <td class="menutd" colspan="4"></td>
                                        </tr>
                                        <tr>
                                            <td class="menutd"><b>{$translate_customer_type}</b></td>
                                            <td class="menutd"> {if $customer_details.CUSTOMER_TYPE ==1} {$translate_customer_type_1} {/if} {if $customer_details.CUSTOMER_TYPE ==2} {$translate_customer_type_2} {/if} {if $customer_details.CUSTOMER_TYPE ==3} {$translate_customer_type_3} {/if} {if $customer_details.CUSTOMER_TYPE ==4} {$translate_customer_type_4} {/if}</td>
                                            <td class="menutd"><b>{$translate_customer_discount}</b></td>
                                            <td class="menutd">{$customer_details.DISCOUNT_RATE}%</td>
                                        </tr>
                                        <tr class="row2">
                                            <td class="menutd" colspan="4"></td>
                                        </tr>
                                        <tr>
                                            <td class="menutd"><b>{$translate_customer_created}</b></td>
                                            <td class="menutd">{$customer_details.CREATE_DATE|date_format:$date_format}</td>
                                            <td class="menutd"><b>{$translate_customer_last}</b></td>
                                            <td class="menutd">{$customer_details.LAST_ACTIVE|date_format:$date_format}</td>
                                        </tr>
                                        <tr class="row2">
                                            <td class="menutd" colspan="4"></td>
                                        </tr>
                                        <tr>
                                            <td class="menutd"><b>{$translate_customer_notes}</b></td>
                                            <td class="menutd" colspan="3">{$customer_details.CUSTOMER_NOTES}</td>
                                        </tr>
                                        {assign var="customer_id" value=$customer_details.CUSTOMER_ID}
                                        {assign var="customer_name" value=$customer_details.CUSTOMER_DISPLAY_NAME}
                                    </table>

                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>                   
        </td>
    </tr>
</table>