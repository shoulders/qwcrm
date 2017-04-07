<!-- details_details_block.tpl -->
<table class="olotable" border="0" cellpadding="2" cellspacing="0" width="100%" summary="Customer Contact">
    <tr>
        <td class="olohead" colspan="4">{$translate_employee_contact_information}</td>
    </tr>
    <tr>                        
        <td class="menutd"><b>{$translate_employee_display_name}</b></td>
        <td class="menutd"> {$employee_details[i].EMPLOYEE_DISPLAY_NAME}</td>
        <td class="menutd"><b>{$translate_employee_email}</b></td>
        <td class="menutd"> {$employee_details[i].EMPLOYEE_EMAIL}</td>
    </tr>
    <tr>
        <td class="menutd"><b>{$translate_employee_first_name}</b></td>
        <td class="menutd">{$employee_details[i].EMPLOYEE_FIRST_NAME}</td>
        <td class="menutd"><b>{$translate_employee_last_name}</b>
        <td class="menutd">{$employee_details[i].EMPLOYEE_LAST_NAME}</td>
    </tr>
    <tr class="row2">
        <td class="menutd" colspan="4">&nbsp;</td>
    </tr>
    <tr>
        <td class="menutd"><b>{$translate_employee_address}</b></td>
        <td class="menutd">
            {$employee_details[i].EMPLOYEE_ADDRESS|nl2br}<br>
            {$employee_details[i].EMPLOYEE_CITY}<br>
            {$employee_details[i].EMPLOYEE_STATE}<br>
            {$employee_details[i].EMPLOYEE_ZIP}
        </td>
        <td class="menutd"><b>{$translate_employee_home}</b></td>
        <td class="menutd">{$employee_details[i].EMPLOYEE_HOME_PHONE}</td>
    </tr>
    <tr>                                
        <td class="menutd"></td>
        <td class="menutd"></td>           
        <td class="menutd"><b>{$translate_employee_work_phone}</b></td>
        <td class="menutd"> {$employee_details[i].EMPLOYEE_WORK_PHONE}</td>
    </tr>
    <tr>
        <td class="menutd"></td>
        <td class="menutd"></td>
        <td class="menutd"><b>{$translate_employee_mobile}</b></td>
        <td class="menutd"> {$employee_details[i].EMPLOYEE_MOBILE_PHONE}</td>
    </tr>
    <tr class="row2">
        <td class="menutd" colspan="4">&nbsp;</td>
    </tr>
    <tr>
        <td class="menutd"><b>{$translate_employee_type}</b></td>
        <td class="menutd"> {$employee_details[i].TYPE_NAME    }</td>
        <td class="menutd"><b>{$translate_employee_login}</b></td>
        <td class="menutd">{$employee_details[i].EMPLOYEE_LOGIN}</td>
    </tr>
    <tr class="row2">
        <td class="menutd" colspan="4">&nbsp;</td>
    </tr>
</table>