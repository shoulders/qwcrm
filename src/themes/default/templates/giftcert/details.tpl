<!-- details.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<table width="700" cellpadding="4" cellspacing="0" border="0" class="olotable">
    <tr>
        <td class="olotd4">
            <table width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{t}Gift Certificate{/t}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a href="index.php?component=giftcert&page_tpl=edit&giftcert_id={$giftcert_details.giftcert_id}" ><img src="{$theme_images_dir}icons/edit.gif"  alt="" height="16" border="0">{t}Edit{/t}</a>
                        <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}GIFTCERT_DETAILS_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}GIFTCERT_DETAILS_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
                    </td>
                </tr>                
                <tr>
                    <td class="olotd4" valign="top" colspan="2">
                        <table cellpadding="3" cellspacing="0" border="0" width="100%">
                            <tr>
                                <td><h2>{t}Gift Certificate{/t}</h2></td>
                            </tr>
                        </table>
                        <hr>
                        <table cellpadding="3" cellspacing="0" border="0" width="100%">
                            <tr>
                                
                                <!-- Client Details -->
                                <td valign="top" width="50%">
                                    <p><b>{t}Client{/t} </b><a href="index.php?component=client&page_tpl=details&client_id={$client_details.client_id}">{$client_details.display_name}</a></p>
                                    <p><strong>{t}Address{/t}</strong></p>
                                    <p>
                                        {$client_details.address|nl2br|regex_replace:"/[\r\t\n]/":" "}<br>
                                        {$client_details.city}<br>
                                        {$client_details.state}<br>
                                        {$client_details.zip}<br>
                                        {$client_details.country}
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
                                            <td><b>{t}Last Employee{/t}</b></td>
                                            <td>
                                                <a href="index.php?component=user&page_tpl=details&user_id={$giftcert_details.employee_id}">{$employee_display_name}</a>                                                
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b>{t}Workorder ID{/t}</b></td>
                                            <td><a href="index.php?component=workorder&page_tpl=details&workorder_id={$giftcert_details.workorder_id}">{$giftcert_details.workorder_id}</a></td>
                                        </tr>
                                        <tr>
                                            <td><b>{t}Invoice ID{/t}</b></td>                                            
                                            <td><a href="index.php?component=invoice&page_tpl=details&invoice_id={$giftcert_details.invoice_id}">{$giftcert_details.invoice_id}</a></td>            
                                        </tr>
                                        <tr>
                                            <td><b>{t}Code{/t}</b></td>
                                            <td>{$giftcert_details.giftcert_code}</td>
                                        </tr>
                                        <tr>
                                            <td><b>{t}Blocked{/t}</b></td>
                                            <td>
                                                {if $giftcert_details.blocked == '0'}{t}No{/t}{/if}
                                                {if $giftcert_details.blocked == '1'}{t}Yes{/t}{/if}
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
                                            <td><b>{t}Expires{/t}</b></td>
                                            <td>{$giftcert_details.date_expires|date_format:$date_format}</td>
                                        </tr>
                                        <tr>
                                            <td><b>{t}Redeemed on{/t}</b></td>
                                            <td>{$giftcert_details.date_redeemed|date_format:$date_format}</td>
                                        </tr>
                                        <tr>
                                            <td><b>{t}Status{/t}</b></td>
                                            <td>
                                                {section name=s loop=$giftcert_statuses}    
                                                    {if $giftcert_details.status == $giftcert_statuses[s].status_key}{t}{$giftcert_statuses[s].display_name}{/t}{/if}        
                                                {/section}              
                                            </td>
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
                                <td>{$giftcert_details.note}</td>
                            </tr>
                        </table>                        
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>