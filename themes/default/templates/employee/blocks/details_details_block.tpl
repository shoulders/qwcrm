<!-- details_details_block.tpl -->
<table class="olotable" border="0" cellpadding="2" cellspacing="0" width="100%" summary="Customer Contact">
    <tr>
        <td class="olohead" colspan="4">{t}Contact Information{/t}</td>
    </tr>
    <tr>                        
        <td class="menutd"><b>{t}Display Name{/t}</b></td>
        <td class="menutd"> {$employee_details.EMPLOYEE_DISPLAY_NAME}</td>
        <td class="menutd"><b>{t}Email{/t}</b></td>
        <td class="menutd"> {$employee_details.EMPLOYEE_EMAIL}</td>
    </tr>
    <tr>
        <td class="menutd"><b>{t}First Name{/t}</b></td>
        <td class="menutd">{$employee_details.EMPLOYEE_FIRST_NAME}</td>
        <td class="menutd"><b>{t}Last Name{/t}</b>
        <td class="menutd">{$employee_details.EMPLOYEE_LAST_NAME}</td>
    </tr>
    <tr class="row2">
        <td class="menutd" colspan="4">&nbsp;</td>
    </tr>
    <tr>
        <td class="menutd"><b>{t}Address{/t}</b></td>
        <td class="menutd">
            {$employee_details.EMPLOYEE_ADDRESS|nl2br}<br>
            {$employee_details.EMPLOYEE_CITY}<br>
            {$employee_details.EMPLOYEE_STATE}<br>
            {$employee_details.EMPLOYEE_ZIP}
        </td>
        <td class="menutd"><b>{t}Home{/t}</b></td>
        <td class="menutd">{$employee_details.EMPLOYEE_HOME_PHONE}</td>
    </tr>
    <tr>                                
        <td class="menutd"></td>
        <td class="menutd"></td>           
        <td class="menutd"><b>{t}Phone{/t}</b></td>
        <td class="menutd"> {$employee_details.EMPLOYEE_WORK_PHONE}</td>
    </tr>
    <tr>
        <td class="menutd"></td>
        <td class="menutd"></td>
        <td class="menutd"><b>{t}Mobile{/t}</b></td>
        <td class="menutd"> {$employee_details.EMPLOYEE_MOBILE_PHONE}</td>
    </tr>
    <tr class="row2">
        <td class="menutd" colspan="4">&nbsp;</td>
    </tr>
    <tr>
        <td class="menutd"><b>{t}Type{/t}</b></td>
        <td class="menutd">{$employee_details.EMPLOYEE_TYPE}</td>
        <td class="menutd"><b>{t}Username{/t}</b></td>
        <td class="menutd">{$employee_details.EMPLOYEE_LOGIN}</td>
    </tr>
    <tr class="row2">
        <td class="menutd" colspan="4">&nbsp;</td>
    </tr>
</table>