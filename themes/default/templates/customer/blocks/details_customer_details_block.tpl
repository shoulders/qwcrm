<!-- details_customer_details_block.tpl -->
<table width="100%" border="0" cellpadding="5" cellspacing="5">
    <tr>
        <td>            
            <table width="100%" cellpadding="4" cellspacing="0" border="0" >
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{t}Customer Details{/t} {t}for{/t} {$customer_details.display_name}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle"><a href="index.php?page=customer:edit&customer_id={$customer_details.customer_id}" ><img src="{$theme_images_dir}icons/edit.gif"  alt="" height="16" border="0">{t}Edit{/t}</a></td>
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
                                                        <td class="menuhead2">&nbsp;{t}Customer Details{/t}</td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="menutd"><b>{t}Contact{/t}</b></td>
                                            <td class="menutd">{$customer_details.first_name} {$customer_details.last_name}</td>
                                            <td class="menutd"><b>{t}Website{/t}</b></td>
                                            <td class="menutd"><a href="{$customer_details.website}"</a>{$customer_details.website}</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td class="menutd"><b>{t}Email{/t}</b></td>
                                            <td class="menutd">{$customer_details.email}</td>                                                                    
                                        </tr>
                                        <tr>
                                            <td class="menutd"><b>{t}Credit Terms{/t}</b></td>
                                            <td class="menutd">{$customer_details.credit_terms}</td>
                                            <td class="menutd"><b>{t}Discount{/t}</b></td>
                                            <td class="menutd">{$customer_details.discount_rate}%</td>
                                        </tr>
                                        </tr>
                                        <tr class="row2">
                                            <td class="menutd" colspan="4"></td>
                                        </tr>
                                        <tr>
                                            <td class="menutd"> <b>{t}Address{/t}</b> <a style="color:red" href="{$GoogleMapString}" target="_blank" ><img src="{$theme_images_dir}icons/map.png" alt="" border="0" height="14" width="14" />{t}Get Directions{/t}</a></td>
                                            <td class="menutd"></td>
                                            <td class="menutd"><b>{t}Phone{/t}</b></td>
                                            <td class="menutd">{$customer_details.primary_phone}</td>
                                        </tr>
                                        <tr>
                                            <td class="menutd"></td>
                                            <td class="menutd">{$customer_details.address|nl2br}<br>{$customer_details.city}<br>{$customer_details.state}<br>{$customer_details.zip}<br>{$customer_details.country}</td>
                                            <td class="menutd"><b>{t}Mobile Phone{/t}</b></td>
                                            <td class="menutd">{$customer_details.mobile_phone}</td>
                                        </tr>
                                        <tr>
                                            <td class="menutd"></td>
                                            <td class="menutd"></td>
                                            <td class="menutd"><b>{t}Fax{/t}</b></td>
                                            <td class="menutd">{$customer_details.fax}</td>
                                        </tr>
                                        <tr class="row2">
                                            <td class="menutd" colspan="4"></td>
                                        </tr>
                                        <tr>
                                            <td class="menutd"><b>{t}Customer Type{/t}</b></td>
                                            <td class="menutd">
                                                {if $customer_details.type == 1}{t}CUSTOMER_TYPE_1{/t} {/if}
                                                {if $customer_details.type == 2}{t}CUSTOMER_TYPE_2{/t} {/if}
                                                {if $customer_details.type == 3}{t}CUSTOMER_TYPE_3{/t} {/if}
                                                {if $customer_details.type == 4}{t}CUSTOMER_TYPE_4{/t} {/if}
                                                {if $customer_details.type == 5}{t}CUSTOMER_TYPE_5{/t} {/if}
                                                {if $customer_details.type == 6}{t}CUSTOMER_TYPE_6{/t} {/if}
                                                {if $customer_details.type == 7}{t}CUSTOMER_TYPE_7{/t} {/if}
                                                {if $customer_details.type == 8}{t}CUSTOMER_TYPE_8{/t} {/if}
                                                {if $customer_details.type == 9}{t}CUSTOMER_TYPE_9{/t} {/if}
                                                {if $customer_details.type == 10}{t}CUSTOMER_TYPE_10{/t} {/if}                                               
                                            </td>
                                            <td class="menutd"><b>{t}Active{/t}</b></td>
                                            <td class="menutd">
                                                {if $customer_details.active == 0}{t}Blocked{/t}{/if}
                                                {if $customer_details.active == 1}{t}Active{/t}{/if}                                                                                               
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="menutd"><b>{t}Account Created{/t}</b></td>
                                            <td class="menutd">{$customer_details.create_date|date_format:$date_format}</td>
                                            <td class="menutd"><b>{t}Last Active{/t}</b></td>
                                            <td class="menutd">{$customer_details.last_active|date_format:$date_format}</td>
                                        </tr>
                                        <tr class="row2">
                                            <td class="menutd" colspan="4"></td>
                                        </tr>
                                        <tr>
                                            <td class="menutd"><b>{t}Notes{/t}</b></td>
                                            <td class="menutd" colspan="3">{$customer_details.notes}</td>
                                        </tr>
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