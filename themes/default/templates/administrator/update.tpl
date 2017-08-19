<!-- update.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<table width="100%" border="0" cellpadding="20" cellspacing="0">
    <tr>
        <td>
            <table width="700" cellpadding="5" cellspacing="0" border="0"> 
                
                
                <!-- Header -->
                <tr>
                    <td class="menuhead2" width="80%">{t}Update Status{/t}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}ADMINISTRATOR_UPDATE_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}ADMINISTRATOR_UPDATE_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
                        </a>
                    </td>
                </tr>
                
                <!-- Main Content -->
                <tr>
                    <td class="menutd2" colspan="2">
                        <table width="100%" class="olotable" cellpadding="5" cellspacing="0" border="0">
                                                        
                            <!-- Default Page content -->
                            {if !$update_response}                                
                                <tr>
                                    <td colspan="2">
                                        <p>{t}This page will show you updates for QWcrm{/t}</p>
                                        <p><b>{t}Current Version{/t}:</b> {$current_version}</p>
                                    </td>                                    
                                </tr>                                
                            {/if}
                            
                            <!-- Page with a response -->                            
                            {if $update_response}
                                <tr>
                                    <td colspan="2">
                                        <div>{$update_response.message}</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="%50" align="left">
                                        <b>{t}Current Version{/t}:</b> {$current_version}<br>
                                    </td>
                                    <td width="%50" align="left">
                                        <b>{t}Latest Version{/t}:</b> {$update_response.version}<br>
                                        <b>{t}Release Date{/t}:</b> {$update_response.release_date}<br>
                                        {if $version_compare == 1}
                                            <b>{t}Download Link{/t}:</b> <a href="{$update_response.downloadurl}">{$update_response.downloadurl}</a>
                                        {/if}
                                    </td>
                                </tr>
                                <tr>
                                    <td valign="top" colspan="2">                                        
                                        {if $version_compare == 1}
                                            {t}An update is available.{/t}<br>                                           
                                            {t}Please download and once you unpack the file read the README for further instructions.{/t}                                            
                                        {else}
                                            {t}No Updates Available, you have the latest version.{/t}
                                        {/if}                                    
                                        <br>
                                    </td>
                                </tr>
                            {/if}
                            
                            <!-- Submit Button -->                                                            
                            <tr>
                                <td>
                                    <form method="post" action="index.php?page=administrator:update"> 
                                        <button class="olotd5" type="submit" name="submit" value="check_for_update">{t}Check for Update{/t}</button>
                                    </form>
                                </td>
                            </tr>                            
                            
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>