<!-- status.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<table width="100%" border="0" cellpadding="20" cellspacing="0">
    <tr>
        <td>
            <table width="700" cellpadding="5" cellspacing="0" border="0" >
                <tr>
                    <td class="menuhead2" width="80%">{t}Status{/t} {t}for{/t} <a href="index.php?component=giftcert&page_tpl=details&giftcert_id={$giftcert_id}">{t}Gift Certificate{/t} {$giftcert_id}</a></td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}GIFTCERT_STATUS_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}GIFTCERT_STATUS_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
                        </a>
                    </td>
                </tr>  
                <tr>
                    <td class="menutd2" colspan="2">                        
                        <table class="olotable" width="100%" border="0" cellpadding="2" cellspacing="0" >
                            <tr>
                                <td class="olohead" align="center">{t}Status{/t}</td>
                                <td class="olohead" align="center">{t}Cancel{/t}</td>
                                <td class="olohead" align="center">{t}Refund{/t}</td>
                            </tr>
                            <tr>
                            
                                <!-- Update Status -->
                                <td class="olotd4" align="center" width="33%">
                                    {if $allowed_to_change_status}
                                        <p>&nbsp;</p>                                    
                                        <form action="index.php?component=giftcert&page_tpl=status&giftcert_id={$giftcert_id}" method="post">
                                            <b>{t}New Status{/t}: </b>
                                            <select class="olotd4" name="assign_status">
                                                {section name=s loop=$giftcert_edited_statuses}    
                                                    <option value="{$giftcert_edited_statuses[s].status_key}"{if $giftcert_status == $giftcert_edited_statuses[s].status_key} selected{/if}>{t}{$giftcert_edited_statuses[s].display_name}{/t}</option>
                                                {/section}                                            
                                            </select>                                    
                                            <p>&nbsp;</p>                                        
                                            <input class="olotd4" name="change_status" value="{t}Update{/t}" type="submit" />                                                                      
                                        </form>
                                    {else}
                                        <br />
                                        <b>{t}Current Status{/t} =
                                        {section name=s loop=$giftcert_statuses}    
                                            {if $giftcert_status == $giftcert_statuses[s].status_key}{$giftcert_statuses[s].display_name}{/if}
                                        {/section}
                                        </b>
                                        <br />
                                        <br />
                                        {t}This gift certificate cannot have it's status changed because it's current state does not allow it.{/t}
                                        <br />
                                        <br />
                                    {/if}
                                </td>

                                <!-- Cancel Gift Certificate --> 
                                <td class="olotd4" align="center" width="33%">
                                    {if $allowed_to_cancel}
                                        <form method="post" action="index.php?component=giftcert&page_tpl=status">
                                            <input name="cancel" value="{t}Cancel Gift Certificate{/t}" type="submit" onclick="return confirmChoice('{t}Are you sure you want to cancel this Gift Certificate?{/t}');">                                        
                                        </form>                                   
                                    {else}
                                        {t}This gift certificate cannot be cancelled because it's state does not allow it.{/t}
                                    {/if}
                                </td>

                                <!-- Refund Gift Certificate -->                        
                                <td class="olotd4" align="center" width="33%">                                                                       
                                    {if $allowed_to_refund}
                                        <form method="post" action="index.php?component=giftcert&page_tpl=status">
                                            <input name="refund" value="{t}Refund{/t}" type="submit" onclick="return confirmChoice('{t}Are you sure you want to refund this gift certificate?{/t}');">                                            
                                        </form>                                            
                                    {else}
                                        {t}This gift certificate cannot be refunded because it's state does not allow it.{/t}
                                    {/if}                                        
                                </td>                                
                                
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>