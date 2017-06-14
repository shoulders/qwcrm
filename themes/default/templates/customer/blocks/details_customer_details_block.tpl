<!-- details_customer_details_block.tpl -->
<table width="100%" border="0" cellpadding="5" cellspacing="5">
    <tr>
        <td>            
            <table width="100%" cellpadding="4" cellspacing="0" border="0" >
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{t}Customer Details{/t} {t}for{/t} - {$customer_details.CUSTOMER_FIRST_NAME}&nbsp;{$customer_details.CUSTOMER_LAST_NAME}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle"><a href="index.php?page=customer:edit&customer_id={$customer_details.CUSTOMER_ID}" ><img src="{$theme_images_dir}icons/edit.gif"  alt="" height="16" border="0">{t}Edit{/t}</a></td>
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
                                            <td class="menutd">{$customer_details.CUSTOMER_FIRST_NAME} {$customer_details.CUSTOMER_LAST_NAME}</td>
                                            <td class="menutd"><b>{t}Website{/t}</b></td>
                                            <td class="menutd"><a href="{$customer_details.CUSTOMER_WWW}"</a>{$customer_details.CUSTOMER_WWW}</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td class="menutd"><b>{t}Email{/t}</b></td>
                                            <td class="menutd">{$customer_details.CUSTOMER_EMAIL}</td>                                                                    
                                        </tr>
                                        <tr>
                                            <td class="menutd"><b>{t}Credit Terms{/t}</b></td>
                                            <td class="menutd">{$customer_details.CREDIT_TERMS}</td>
                                        </tr>
                                        <tr class="row2">
                                            <td class="menutd" colspan="4"></td>
                                        </tr>
                                        <tr>
                                            <td class="menutd"> <b>{t}Address{/t}</b> <a style="color:red" href="{$GoogleMapString}" target="_blank" ><img src="{$theme_images_dir}icons/map.png" alt="" border="0" height="14" width="14" />[{t}Get Directions{/t}]</a></td>
                                            <td class="menutd"></td>
                                            <td class="menutd"><b>{t}Home Phone{/t}</b></td>
                                            <td class="menutd">{$customer_details.CUSTOMER_PHONE}</td>
                                        </tr>
                                        <tr>
                                            <td class="menutd"></td>
                                            <td class="menutd">{$customer_details.CUSTOMER_ADDRESS|nl2br}<br>{$customer_details.CUSTOMER_CITY}<br>{$customer_details.CUSTOMER_STATE}<br>{$customer_details.CUSTOMER_ZIP}</td>
                                            <td class="menutd"><b>{t}Work Phone{/t}</b></td>
                                            <td class="menutd">{$customer_details.CUSTOMER_WORK_PHONE}</td>
                                        </tr>
                                        <tr>
                                            <td class="menutd"></td>
                                            <td class="menutd"></td>
                                            <td class="menutd"><b>{t}Mobile Phone{/t}</b></td>
                                            <td class="menutd">{$customer_details.CUSTOMER_MOBILE_PHONE}</td>
                                        </tr>
                                        <tr class="row2">
                                            <td class="menutd" colspan="4"></td>
                                        </tr>
                                        <tr>
                                            <td class="menutd"><b>{t}Customer Type{/t}</b></td>
                                            <td class="menutd">
                                                {if $customer_details.CUSTOMER_TYPE ==1} {t}CUSTOMER_TYPE_1{/t} {/if}
                                                {if $customer_details.CUSTOMER_TYPE ==2} {t}CUSTOMER_TYPE_2{/t} {/if}
                                                {if $customer_details.CUSTOMER_TYPE ==3} {t}CUSTOMER_TYPE_3{/t} {/if}
                                                {if $customer_details.CUSTOMER_TYPE ==4} {t}CUSTOMER_TYPE_4{/t} {/if}
                                                {if $customer_details.CUSTOMER_TYPE ==5} {t}CUSTOMER_TYPE_5{/t} {/if}
                                                {if $customer_details.CUSTOMER_TYPE ==6} {t}CUSTOMER_TYPE_6{/t} {/if}
                                                {if $customer_details.CUSTOMER_TYPE ==7} {t}CUSTOMER_TYPE_7{/t} {/if}
                                                {if $customer_details.CUSTOMER_TYPE ==8} {t}CUSTOMER_TYPE_8{/t} {/if}
                                                {if $customer_details.CUSTOMER_TYPE ==9} {t}CUSTOMER_TYPE_9{/t} {/if}
                                                {if $customer_details.CUSTOMER_TYPE ==10} {t}CUSTOMER_TYPE_10{/t} {/if}                                               
                                            </td>
                                            <td class="menutd"><b>{t}Discount{/t}</b></td>
                                            <td class="menutd">{$customer_details.DISCOUNT_RATE}%</td>
                                        </tr>
                                        <tr class="row2">
                                            <td class="menutd" colspan="4"></td>
                                        </tr>
                                        <tr>
                                            <td class="menutd"><b>{t}Account Created{/t}</b></td>
                                            <td class="menutd">{$customer_details.CREATE_DATE|date_format:$date_format}</td>
                                            <td class="menutd"><b>{t}Last Active{/t}</b></td>
                                            <td class="menutd">{$customer_details.LAST_ACTIVE|date_format:$date_format}</td>
                                        </tr>
                                        <tr class="row2">
                                            <td class="menutd" colspan="4"></td>
                                        </tr>
                                        <tr>
                                            <td class="menutd"><b>{t}Notes{/t}</b></td>
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