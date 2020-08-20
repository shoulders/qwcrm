<!-- upgrade.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<script>
    // Disable Back Button
    history.pushState(null, null, location.href);
    window.onpopstate = function () { 
        alert('{t}The Back Button has been disabled.{/t}');
        history.go(1);
    };
</script>

<table width="100%" border="0" cellpadding="20" cellspacing="5">
    <tr>
        <td>            
            <table width="700" cellpadding="3" cellspacing="0" border="0">
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{t}QWcrm Installation{/t}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}SETUP_INSTALL_HELP_TITLE{/t}</strong></div><hr><div>{t escape=js}SETUP_INSTALL_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
                        </a>
                    </td>
                </tr>
                <tr>
                    <td class="menutd2" colspan="2">
                        <table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
                            <tr>
                                <td class="menutd">
                                    <table width="100%" border="0" cellpadding="10" cellspacing="0">                                        
                                        
                                        <!-- Database Connection and test -->
                                        {if !$stage || $stage == 'database_connection'}                                        
                                            <tr>
                                                <td>                                                                                                  
                                                    {include file='setup/blocks/upgrade_database_connection_block.tpl'}
                                                </td>
                                            </tr>
                                        {/if}
                                        
                                        <!-- Upgrade the Database -->
                                        {if $stage == 'database_upgrade'}                                        
                                            <tr>
                                                <td>                                                                                                 
                                                    {include file='setup/blocks/upgrade_database_upgrade_block.tpl'}
                                                </td>
                                            </tr>
                                        {/if}
                                        
                                        <!-- Database Upgrade Results -->
                                        {if $stage == 'database_upgrade_results'}                                        
                                            <tr>
                                                <td>                                                                                                 
                                                    {include file='setup/blocks/upgrade_database_upgrade_results_block.tpl'}
                                                </td>
                                            </tr>
                                        {/if}
                                        
                                        <!-- Delete Setup Folder -->
                                        {if $stage == 'delete_setup_folder'}                                        
                                            <tr>
                                                <td>
                                                    {include file='setup/blocks/upgrade_delete_setup_folder_block.tpl'}
                                                </td>
                                            </tr>
                                        {/if}
                                        
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