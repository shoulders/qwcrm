<!-- Display Customer Contact Infromation -->
<table class="olotable" border="0" cellpadding="0" cellspacing="0" width="100%" summary="Customer Contact">
    <tr>
        <td class="olohead">
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{$translate_workorder_cutomer_contact_title}</td>
                    <td class="menuhead2" width="20%" align="right">
                        <table cellpadding="2" cellspacing="2" border="0">
                            <tr>
                                <td width="33%" align="right"><a
                                        href="?page=customer:edit&customer_id={$single_workorder_array[i].CUSTOMER_ID}&page_title={$single_workorder_array[i].CUSTOMER_DISPLAY_NAME}"><img
                                        src="images/icons/16x16/small_edit.gif" border="0"
                                        onMouseOver="ddrivetip('Edit Customer')" onMouseOut="hideddrivetip()"></a></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
    {if $hide_customer_contact == 1}
        {else}
    <td>
        <table class="olotable" border="0" cellpadding="5" cellspacing="5" width="100%" summary="Customer Contact">
            <tr>
                <td class="menutd"><b>{$translate_workorder_contact}</b></td>
                <td class="menutd"><a
                        href="?page=customer:customer_details&customer_id={$single_workorder_array[i].CUSTOMER_ID}&page_title={$single_workorder_array[i].CUSTOMER_FIRST_NAME} {$single_workorder_array[i].CUSTOMER_LAST_NAME} ">{$single_workorder_array[i].CUSTOMER_DISPLAY_NAME}</a>
                </td>
                <td class="menutd"><b>{$translate_workorder_www}</b></td>
                <td class="menutd">{$single_workorder_array[i].CUSTOMER_WWW}</td>
            </tr>
            <tr>
                <td class="menutd"></td>
                <td class="menutd"></td>
                <td class="menutd"><b>{$translate_workorder_email}</b></td>
                <td class="menutd"><a
                        href="?page=customer:email&customer_id={$single_workorder_array[i].CUSTOMER_ID}&page_title=Email%20Customer"> {$single_workorder_array[i].CUSTOMER_EMAIL}</a>
                </td>
            </tr>
            <tr>
                <td class="menutd"><b>{$translate_workorder_credit_terms}</b></td>
                <td class="menutd">{$single_workorder_array[i].CREDIT_TERMS}</td>
            </tr>
            <tr class="row2">
                <td class="menutd" colspan="4"></td>
            </tr>
            <tr>
                <td class="menutd"><b>{$translate_workorder_address}</b></td>
                <td class="menutd"></td>
                <td class="menutd"><b>{$translate_workorder_phone_1}</b></td>
                <td class="menutd">{$single_workorder_array[i].CUSTOMER_PHONE}</td>
            </tr>
            <tr>
                <td class="menutd"></td>
                <td class="menutd">{$single_workorder_array[i].CUSTOMER_ADDRESS|nl2br}
                    <br>{$single_workorder_array[i].CUSTOMER_CITY}<br>{$single_workorder_array[i].CUSTOMER_STATE}
                    <br>{$single_workorder_array[i].CUSTOMER_ZIP}</td>
                <td class="menutd"><b>{$translate_workorder_phone_2}</b></td>
                <td class="menutd">{$single_workorder_array[i].CUSTOMER_WORK_PHONE}</td>
            </tr>
            <tr>
                <td class="menutd"></td>
                <td class="menutd"></td>
                <td class="menutd"><b>{$translate_workorder_phone_3}</b></td>
                <td class="menutd">{$single_workorder_array[i].CUSTOMER_MOBILE_PHONE}</td>
            </tr>
            <tr class="row2">
                <td class="menutd" colspan="4"></td>
            </tr>
            <tr>
                <td class="menutd"><b>{$translate_workorder_type}</b></td>
                <td class="menutd">
                    {if $single_workorder_array[i].CUSTOMER_TYPE ==1}
                        {$translate_workorder_type_1}
                    {/if}
												{if $single_workorder_array[i].CUSTOMER_TYPE ==2}
                    {$translate_workorder_type_2}
                {/if}
												{if $single_workorder_array[i].CUSTOMER_TYPE ==3}
                    {$translate_workorder_type_3}
                {/if}
												{if $single_workorder_array[i].CUSTOMER_TYPE ==4}
                    {$translate_workorder_type_4}
                {/if}
                </td>
                <td class="menutd"><b>{$translate_workorder_discount}</b></td>
                <td class="menutd">{$single_workorder_array[i].DISCOUNT}%</td>
            <tr class="row2">
                <td class="menutd" colspan="4"></td>
            </tr>
            <tr>
                <td><b>{$translate_workorder_created}</b></td>
                <td>{$single_workorder_array[i].CREATE_DATE|date_format:"$date_format"}</td>
                <td><b>{$translate_workorder_last_activity}</b></td>
                <td>{$single_workorder_array[i].LAST_ACTIVE|date_format:"$date_format"}</td>
            </tr>
            <tr class="row2">
                <td class="menutd" colspan="4"></td>
            </tr>
            <tr>
                <td><b>{$translate_workorder_notes}</b></td>
                <td class="menutd" colspan="3">{$single_workorder_array[i].CUSTOMER_NOTES}</td>
            </tr>
        </table>

    {/if}
    </tr>
</table>