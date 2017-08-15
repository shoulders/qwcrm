<!-- details.tpl -->
<table width="700" cellpadding="4" cellspacing="0" border="0" class="olotable">
    <tr>
        <td class="olotd4">
            <table width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{t}Gift Certificate{/t}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a href="index.php?page=giftcert:edit&giftcert_id={$giftcert_details.giftcert_id}" ><img src="{$theme_images_dir}icons/edit.gif"  alt="" height="16" border="0">{t}Edit{/t}</a>
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
                                    <p><b>{t}Customer{/t} </b><a href="index.php?page=customer:details&customer_id={$customer_details.id}">{$customer_details.display_name}</a></p>
                                    <p><strong>{t}Address{/t}</strong></p>
                                    <p>
                                        {$customer_details.address|nl2br|regex_replace:"/[\r\t\n]/":" "}<br>
                                        {$customer_details.city}<br>
                                        {$customer_details.state}<br>
                                        {$customer_details.zip}<br>
                                        {$customer_details.country}
                                    </p>
                                </td>
                                
                                <!-- Gift Certificate Details -->
                                <td valign="top" width="50%">                                    
                                    <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                        <tr>
                                            <td><b>{t}Giftcert ID{/t}</b></td>
                                            <td>{$giftcert_details.giftcert_id}</td>
                                        </tr>
                                        <tr>
                                            <td><b>{t}Code{/t}</b></td>
                                            <td>{$giftcert_details.giftcert_code}</td>
                                        </tr>
                                        <tr>
                                            <td><b>{t}Is Active{/t}</b></td>
                                            <td>
                                                {if $giftcert_details.status == '0'}Blocked{/if}
                                                {if $giftcert_details.status == '1'}Active{/if}
                                            </td>
                                        </tr>                                        
                                        <tr>
                                            <td><b>{t}Amount{/t}</b></td>
                                            <td>{$currency_sym}{$giftcert_details.amount|string_format:"%.2f"}</td>
                                        </tr>
                                        <tr>
                                            <td><b>{t}Created on{/t}</b></td>
                                            <td>{$giftcert_details.date_created|date_format:$date_format}</td>
                                        </tr>
                                        <tr>
                                            <td><b>{t}Created by{/t}</b></td>
                                            <td>
                                                <a href="index.php?page=user:details&user_id={$giftcert_details.employee_id}">{$employee_display_name}</a>                                                
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b>{t}Expires{/t}</b></td>
                                            <td>{$giftcert_details.date_expires|date_format:$date_format}</td>
                                        </tr>
                                        <tr>
                                            <td><b>{t}Redeemed on{/t}</b></td>
                                            <td>{$giftcert_details.date_redeemed|date_format:$date_format}</td>
                                        </tr>
                                        <tr>
                                            <td><b>{t}Is Redeemed{/t}</b></td>
                                            <td>
                                                {if $giftcert_details.is_redeemed == '0'}Not Redeemed{/if}
                                                {if $giftcert_details.is_redeemed == '1'}Redeemed{/if}
                                            </td>
                                        </tr>
                                    </table>                                   
                                </td>                                
                            </tr>
                        </table>                           
                        <table cellpadding="3" cellspacing="0" border="0" width="100%">
                            <tr>
                                <td><b>{t}Notes{/t}:</b></td>
                            </tr>
                            <tr>
                                <td>{$giftcert_details.notes}</td>
                            </tr>
                        </table>                        
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>