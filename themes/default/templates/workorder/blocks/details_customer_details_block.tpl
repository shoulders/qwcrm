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
                                    <a href="index.php?page=customer:edit&customer_id={$workorder_details.customer_id}">
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
                    <td class="menutd"><a href="index.php?page=customer:details&customer_id={$workorder_details.customer_id}">{$customer_details.display_name}</a></td>                
                    <td class="menutd"><b>{t}Website{/t}</b></td>
                    <td class="menutd">{$customer_details.website}</td>
                </tr>
                <tr>
                    <td class="menutd"></td>
                    <td class="menutd"></td>
                    <td class="menutd"><b>{t}Email{/t}</b></td>
                    <td class="menutd"><a href="index.php?page=customer:email&customer_id={$workorder_details.customer_id}"> {$customer_details.email}</a></td>
                </tr>
                <tr>
                    <td class="menutd"><b>{t}Credit Terms{/t}</b></td>
                    <td class="menutd">{$customer_details.credit_terms}</td>
                </tr>
                <tr class="row2">
                    <td class="menutd" colspan="4"></td>
                </tr>
                <tr>
                    <td class="menutd"><b>{t}Address{/t}</b></td>
                    <td class="menutd">
                        {$customer_details.address|nl2br|regex_replace:"/[\r\t\n]/":" "}<br>
                        {$customer_details.city}<br>
                        {$customer_details.state}<br>
                        {$customer_details.zip}<br>
                        {$customer_details.country}
                    </td>
                    <td class="menutd"><b>{t}Phone{/t}</b></td>
                    <td class="menutd">{$customer_details.primary_phone}</td>
                </tr>
                <tr>
                    <td class="menutd"></td>
                    <td class="menutd"></td>
                    <td class="menutd"><b>{t}Fax{/t}</b></td>
                    <td class="menutd">{$customer_details.fax}</td>
                </tr>
                <tr>
                    <td class="menutd"></td>
                    <td class="menutd"></td>
                    <td class="menutd"><b>{t}Mobile{/t}</b></td>
                    <td class="menutd">{$customer_details.mobile_phone}</td>
                </tr>
                <tr class="row2">
                    <td class="menutd" colspan="4"></td>
                </tr>
                <tr>
                    <td class="menutd"><b>{t}Type{/t}</b></td>
                    <td class="menutd">
                        {if $customer_details.type == 1}{t}CUSTOMER_TYPE_1{/t}{/if}
                        {if $customer_details.type == 2}{t}CUSTOMER_TYPE_2{/t}{/if}
                        {if $customer_details.type == 3}{t}CUSTOMER_TYPE_3{/t}{/if}
                        {if $customer_details.type == 4}{t}CUSTOMER_TYPE_4{/t}{/if}
                        {if $customer_details.type == 5}{t}CUSTOMER_TYPE_5{/t}{/if}
                        {if $customer_details.type == 6}{t}CUSTOMER_TYPE_6{/t}{/if}
                        {if $customer_details.type == 7}{t}CUSTOMER_TYPE_7{/t}{/if}
                        {if $customer_details.type == 8}{t}CUSTOMER_TYPE_8{/t}{/if}
                        {if $customer_details.type == 9}{t}CUSTOMER_TYPE_9{/t}{/if}
                        {if $customer_details.type == 10}{t}CUSTOMER_TYPE_10{/t}{/if}
                    </td>
                    <td class="menutd"><b>{t}Discount{/t}</b></td>
                    <td class="menutd">{$customer_details.discount_rate|string_format:"%.2f"}%</td>
                <tr class="row2">
                    <td class="menutd" colspan="4"></td>
                </tr>
                <tr>
                    <td><b>{t}Created{/t}</b></td>
                    <td>{$customer_details.create_date|date_format:$date_format}</td>
                    <td><b>{t}Last Activity{/t}</b></td>
                    <td>{$customer_details.last_active|date_format:$date_format}</td>
                </tr>
                <tr class="row2">
                    <td class="menutd" colspan="4"></td>
                </tr>
                <tr>
                    <td><b>{t}Notes{/t}</b></td>
                    <td class="menutd" colspan="3">{$customer_details.notes}</td>
                </tr>
            </table>
        </td>
    </tr>
</table>