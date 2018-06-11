<!-- acl.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<table width="100%" border="0" cellpadding="20" cellspacing="5">
    <tr>
        <td>
            <form method="post" action="index.php?component=administrator&page_tpl=acl">
                <table width="400" cellpadding="4" cellspacing="0" border="0">
                    
                    <!-- Header -->
                    <tr>
                        <td class="menuhead2" width="80%">&nbsp;{t}Update Permissions for Users{/t}</td>
                        <td class="menuhead2" width="20%" align="right" valign="middle">
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}ADMINISTRATOR_ACL_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}ADMINISTRATOR_ACL_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>

                    <!-- Matrix -->
                    <tr>
                        <td class="menutd2" colspan="2">
                            <table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
                                <tr>
                                    <td class="menutd">                                    
                                        <table class="olotable" cellpadding="5" cellspacing="0" border="0">
                                            <tr>
                                                <td class="olohead">{t}Module:Page{/t}</td>
                                                <td class="olohead">{t}Administrator{/t}</td>
                                                <td class="olohead">{t}Manager{/t}</td>
                                                <td class="olohead">{t}Supervisor{/t}</td>
                                                <td class="olohead">{t}Technician{/t}</td>
                                                <td class="olohead">{t}Clerical{/t}</td>
                                                <td class="olohead">{t}Counter{/t}</td>
                                                <td class="olohead">{t}Customer{/t}</td>
                                                <td class="olohead">{t}Guest{/t}</td>
                                                <td class="olohead">{t}Public{/t}</td>
                                            </tr>
                                            {section name=i loop=$acl}
                                                <tr onmouseover="this.className='row2';" onmouseout="this.className='row1';" class="row1">

                                                    <!-- Module:Page -->
                                                        <td class="olotd4"><b>{$acl[i].page}</b></td>
                                                    
                                                    {if $acl[i].page == 'core:403' || $acl[i].page == 'core:404' || $acl[i].page == 'core:error' || $acl[i].page == 'core:home' || $acl[i].page == 'core:maintenance' || $acl[i].page == 'user:login'}
                                                        
                                                        <td class="olotd4" style="background-color: #E0E0E0;"><input name="qwpermissions[{$acl[i].page}][Administrator]" type="hidden" value="1">{t}Yes{/t}</td>
                                                        <td class="olotd4" style="background-color: #E0E0E0;"><input name="qwpermissions[{$acl[i].page}][Manager]" type="hidden" value="1">{t}Yes{/t}</td>
                                                        <td class="olotd4" style="background-color: #E0E0E0;"><input name="qwpermissions[{$acl[i].page}][Supervisor]" type="hidden" value="1">{t}Yes{/t}</td>
                                                        <td class="olotd4" style="background-color: #E0E0E0;"><input name="qwpermissions[{$acl[i].page}][Technician]" type="hidden" value="1">{t}Yes{/t}</td>
                                                        <td class="olotd4" style="background-color: #E0E0E0;"><input name="qwpermissions[{$acl[i].page}][Clerical]" type="hidden" value="1">{t}Yes{/t}</td>
                                                        <td class="olotd4" style="background-color: #E0E0E0;"><input name="qwpermissions[{$acl[i].page}][Counter]" type="hidden" value="1">{t}Yes{/t}</td>
                                                        <td class="olotd4" style="background-color: #E0E0E0;"><input name="qwpermissions[{$acl[i].page}][Customer]" type="hidden" value="1">{t}Yes{/t}</td>
                                                        <td class="olotd4" style="background-color: #E0E0E0;"><input name="qwpermissions[{$acl[i].page}][Guest]" type="hidden" value="1">{t}Yes{/t}</td>
                                                        <td class="olotd4" style="background-color: #E0E0E0;"><input name="qwpermissions[{$acl[i].page}][Public]" type="hidden" value="1">{t}Yes{/t}</td>
                                                        
                                                    {else}
                                                        
                                                        <!-- Administrator -->
                                                        <td class="olotd4" style="background-color: #E0E0E0;">
                                                            <input name="qwpermissions[{$acl[i].page}][Administrator]" type="hidden" value="1">{t}Yes{/t}
                                                        </td>

                                                        <!-- Manager -->
                                                        <td class="olotd4">
                                                           <input name="qwpermissions[{$acl[i].page}][Manager]" {if $acl[i].Manager == '1'}checked {/if}type="checkbox" value="1">
                                                        </td>

                                                        <!-- Supervisor -->
                                                        <td class="olotd4">
                                                            <input name="qwpermissions[{$acl[i].page}][Supervisor]" {if $acl[i].Supervisor == '1'}checked {/if}type="checkbox" value="1">                                                        
                                                        </td>

                                                        <!-- Technician -->
                                                        <td class="olotd4">
                                                            <input name="qwpermissions[{$acl[i].page}][Technician]" {if $acl[i].Technician == '1'}checked {/if}type="checkbox" value="1">
                                                        </td>

                                                        <!-- Clerical -->
                                                        <td class="olotd4">
                                                            <input name="qwpermissions[{$acl[i].page}][Clerical]" {if $acl[i].Clerical == '1'}checked {/if}type="checkbox" value="1">
                                                        </td>

                                                        <!-- Counter-->
                                                        <td class="olotd4">
                                                            <input name="qwpermissions[{$acl[i].page}][Counter]" {if $acl[i].Counter == '1'}checked {/if}type="checkbox" value="1">
                                                        </td>                                                

                                                        <!-- Customer -->
                                                        <td class="olotd4">
                                                            <input name="qwpermissions[{$acl[i].page}][Customer]" {if $acl[i].Customer == '1'}checked {/if}type="checkbox" value="1">
                                                        </td>

                                                        <!-- Guest -->
                                                        <td class="olotd4">
                                                            <input name="qwpermissions[{$acl[i].page}][Guest]" {if $acl[i].Guest == '1'}checked {/if}type="checkbox" value="1">
                                                        </td>

                                                        <!-- Public -->
                                                        <td class="olotd4">
                                                            <input name="qwpermissions[{$acl[i].page}][Public]" {if $acl[i].Public == '1'}checked {/if}type="checkbox" value="1">
                                                        </td>
                                                        
                                                    {/if}
                                                    
                                                </tr>
                                            {/section}
                                        </table>                                                                      
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Buttons -->                
                    <tr>
                        <td colspan="2">
                            <table>
                                <tr>
                                    <td width="75%">
                                        <button type="submit" name="submit" value="update">{t}Submit{/t}</button>&nbsp;
                                        <button type="reset" name="reset" value="reset">{t}Reset{/t}</button>&nbsp;
                                        <button type="button" class="olotd4" onclick="window.location.href='index.php';">{t}Cancel{/t}</button>
                                    </td>
                                    <td width="25%"><button type="submit" name="submit" value="reset_default" onclick="return confirmChoice('{t}Are you sure you want to reset the permissions to their defaults?{/t}');">{t}Reset to default Permissions{/t}</button></td>    
                                </tr>
                            </table>
                        </td>
                    </tr>
                    
                    <!-- Message -->                
                    <tr>
                        <td colspan="2">
                            <table>
                                <tr>
                                    <td class="olotd4" style="width: 40px; background-color: #E0E0E0;"></td>
                                    <td> = {t}Mandatory Settings, you cannot change these.{/t}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>                    

                </table>
            </form>  
        </td>
    </tr>
</table>