<!-- details_customer_details_block.tpl -->
<table class="olotable" border="0" cellpadding="0" cellspacing="0" width="100%" summary="Customer Contact">
    <tr>
        <td class="olohead">
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{t}Customer Details{/t}</td>
                    <td class="menuhead2" width="20%" align="right">
                        <table cellpadding="2" cellspacing="2" border="0">
                            <tr>
                                <td width="33%" align="right">
                                    <a href="index.php?page=customer:edit&customer_id={$single_workorder.customer_id}">
                                        <img src="{$theme_images_dir}icons/16x16/small_edit.gif" border="0" onMouseOver="ddrivetip('{t}Click to edit customer details{/t}');" onMouseOut="hideddrivetip();">                                            
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table class="olotable" border="0" cellpadding="5" cellspacing="5" width="100%" summary="Customer Contact">
                <tr>
                    <td class="menutd"><b>{t}Contact{/t}</b></td>
                    <td class="menutd"><a href="index.php?page=customer:customer_details&customer_id={$single_workorder.customer_id}">{$single_workorder.customer_display_name}</a></td>                
                    <td class="menutd"><b>{t}Website{/t}</b></td>
                    <td class="menutd">{$single_workorder.customer_www}</td>
                </tr>
                <tr>
                    <td class="menutd"></td>
                    <td class="menutd"></td>
                    <td class="menutd"><b>{t}Email{/t}</b></td>
                    <td class="menutd"><a href="index.php?page=customer:email&customer_id={$single_workorder.customer_id}"> {$single_workorder.customer_email}</a></td>
                </tr>
                <tr>
                    <td class="menutd"><b>{t}Credit Terms{/t}</b></td>
                    <td class="menutd">{$single_workorder.credit_terms}</td>
                </tr>
                <tr class="row2">
                    <td class="menutd" colspan="4"></td>
                </tr>
                <tr>
                    <td class="menutd"><b>{t}Address{/t}</b></td>
                    <td class="menutd">
                        {$single_workorder.customer_address|nl2br}<br>
                        {$single_workorder.customer_city}<br>
                        {$single_workorder.customer_state}<br>
                        {$single_workorder.customer_zip}
                    </td>
                    <td class="menutd"><b>{t}Phone{/t}</b></td>
                    <td class="menutd">{$single_workorder.customer_phone}</td>
                </tr>
                <tr>
                    <td class="menutd"></td>
                    <td class="menutd"></td>
                    <td class="menutd"><b>{t}Fax{/t}</b></td>
                    <td class="menutd">{$single_workorder.customer_work_phone}</td>
                </tr>
                <tr>
                    <td class="menutd"></td>
                    <td class="menutd"></td>
                    <td class="menutd"><b>{t}Mobile{/t}</b></td>
                    <td class="menutd">{$single_workorder.customer_mobile_phone}</td>
                </tr>
                <tr class="row2">
                    <td class="menutd" colspan="4"></td>
                </tr>
                <tr>
                    <td class="menutd"><b>{t}Type{/t}</b></td>
                    <td class="menutd">
                        {if $single_workorder.customer_type ==1}{t}CUSTOMER_TYPE_1{/t}{/if}
                        {if $single_workorder.customer_type ==2}{t}CUSTOMER_TYPE_2{/t}{/if}
                        {if $single_workorder.customer_type ==3}{t}CUSTOMER_TYPE_3{/t}{/if}
                        {if $single_workorder.customer_type ==4}{t}CUSTOMER_TYPE_4{/t}{/if}
                        {if $single_workorder.customer_type ==5}{t}CUSTOMER_TYPE_5{/t}{/if}
                        {if $single_workorder.customer_type ==6}{t}CUSTOMER_TYPE_6{/t}{/if}
                        {if $single_workorder.customer_type ==7}{t}CUSTOMER_TYPE_7{/t}{/if}
                        {if $single_workorder.customer_type ==8}{t}CUSTOMER_TYPE_8{/t}{/if}
                        {if $single_workorder.customer_type ==9}{t}CUSTOMER_TYPE_9{/t}{/if}
                        {if $single_workorder.customer_type ==10}{t}CUSTOMER_TYPE_10{/t}{/if}
                    </td>
                    <td class="menutd"><b>{t}Discount{/t}</b></td>
                    <td class="menutd">{$single_workorder.discount_rate|string_format:"%.2f"}%</td>
                <tr class="row2">
                    <td class="menutd" colspan="4"></td>
                </tr>
                <tr>
                    <td><b>{t}Created{/t}</b></td>
                    <td>{$single_workorder.create_date|date_format:$date_format}</td>
                    <td><b>{t}Last Activity{/t}</b></td>
                    <td>{$single_workorder.last_active|date_format:$date_format}</td>
                </tr>
                <tr class="row2">
                    <td class="menutd" colspan="4"></td>
                </tr>
                <tr>
                    <td><b>{t}Notes{/t}</b></td>
                    <td class="menutd" colspan="3">{$single_workorder.customer_notes}</td>
                </tr>
            </table>
        </td>
    </tr>
</table>