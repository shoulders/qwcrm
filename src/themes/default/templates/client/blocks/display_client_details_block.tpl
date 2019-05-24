<!-- details_details_block.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<table width="100%" border="0" cellpadding="5" cellspacing="5">
    <tr>
        <td>            
            <table width="100%" cellpadding="4" cellspacing="0" border="0" >
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{t}Client Details{/t} {t}for{/t} {$client_details.display_name}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle"><a href="index.php?component=client&page_tpl=edit&client_id={$client_details.client_id}" ><img src="{$theme_images_dir}icons/edit.gif"  alt="" height="16" border="0">{t}Edit{/t}</a></td>
                </tr>
                <tr>
                    <td class="menutd2" colspan="2">
                        <table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
                            <tr>
                                <td class="menutd">                                                           
                                    <table class="olotable" border="0" cellpadding="5" cellspacing="5" width="100%" summary="Client Contact">
                                        <tr>
                                            <td class="olohead" colspan="4">
                                                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                    <tr>
                                                        <td class="menuhead2">&nbsp;{t}Client Details{/t}</td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="menutd"><b>{t}Name{/t}</b></td>
                                            <td class="menutd">{$client_details.display_name}</td>
                                            <td class="menutd"><b>{t}Website{/t}</b></td>
                                            <td class="menutd"><a href="{$client_details.website}"</a>{$client_details.website|regex_replace:"/^https?:\/\//":""}</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td class="menutd"><b>{t}Email{/t}</b></td>
                                            <td class="menutd">{$client_details.email}</td>                                                                    
                                        </tr>
                                        <tr>
                                            <td class="menutd"><b>{t}Credit Terms{/t}</b></td>
                                            <td class="menutd">{$client_details.credit_terms}</td>
                                            <td class="menutd"><b>{t}Discount{/t}</b></td>
                                            <td class="menutd">{$client_details.unit_discount_rate}%</td>
                                        </tr>
                                        </tr>
                                        <tr class="row2">
                                            <td class="menutd" colspan="4"></td>
                                        </tr>
                                        <tr>
                                            <td class="menutd"><b>{t}Contact{/t}</b></td>
                                            <td class="menutd">{$client_details.full_name}</td>
                                            <td class="menutd" colspan="2"></td>
                                        </tr>
                                        <tr>
                                            <td class="menutd"><b>{t}Address{/t}</b> <a style="color:red" href="{$GoogleMapString}" target="_blank" ><img src="{$theme_images_dir}icons/map.png" alt="" border="0" height="14" width="14" />{t}Get Directions{/t}</a></td>
                                            <td class="menutd"></td>
                                            <td class="menutd"><b>{t}Phone{/t}</b></td>
                                            <td class="menutd">{$client_details.primary_phone}</td>
                                        </tr>
                                        <tr>
                                            <td class="menutd"></td>
                                            <td class="menutd">{$client_details.address|nl2br}<br>{$client_details.city}<br>{$client_details.state}<br>{$client_details.zip}<br>{$client_details.country}</td>
                                            <td class="menutd"><b>{t}Mobile Phone{/t}</b></td>
                                            <td class="menutd">{$client_details.mobile_phone}</td>
                                        </tr>
                                        <tr>
                                            <td class="menutd"></td>
                                            <td class="menutd"></td>
                                            <td class="menutd"><b>{t}Fax{/t}</b></td>
                                            <td class="menutd">{$client_details.fax}</td>
                                        </tr>
                                        <tr class="row2">
                                            <td class="menutd" colspan="4"></td>
                                        </tr>
                                        <tr>
                                            <td class="menutd"><b>{t}Client Type{/t}</b></td>
                                            <td class="menutd">              
                                                {section name=s loop=$client_types}    
                                                    {if $client_details.type == $client_types[s].type_key}{t}{$client_types[s].display_name}{/t}{/if}        
                                                {/section}   
                                            </td>
                                            <td class="menutd"><b>{t}Active{/t}</b></td>
                                            <td class="menutd">{if $client_details.active == 1}{t}Active{/t}{else}{t}Blocked{/t}{/if}</td>
                                        </tr>
                                        <tr>
                                            <td class="menutd"><b>{t}Opened On{/t}</b></td>
                                            <td class="menutd">{$client_details.opened_on|date_format:$date_format}</td>
                                            <td class="menutd"><b>{t}Last Active{/t}</b></td>
                                            <td class="menutd">{$client_details.last_active|date_format:$date_format}</td>
                                        </tr>
                                        <tr class="row2">
                                            <td class="menutd" colspan="4"></td>
                                        </tr>
                                        <tr>
                                            <td class="menutd"><b>{t}Note{/t}</b></td>
                                            <td class="menutd" colspan="3">{$client_details.note}</td>
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