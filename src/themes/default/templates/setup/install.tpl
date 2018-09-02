<!-- install.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<table width="100%" border="0" cellpadding="20" cellspacing="5">
    <tr>
        <td>            
            <table width="700" cellpadding="3" cellspacing="0" border="0">
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{t}QWcrm Installation{/t}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}SETUP_INSTALL_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}SETUP_INSTALL_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
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
                                        {if $stage == 'database_connection' || !$stage}                                        
                                            <tr>
                                                <td>                                                                                                  
                                                    {include file='setup/blocks/install_database_connection_block.tpl'}
                                                </td>
                                            </tr>
                                        {/if}
                                        
                                        <!-- Config Settings -->
                                        {if $stage == 'config_settings'}                                        
                                            <tr>
                                                <td>                                                                                                 
                                                    {include file='setup/blocks/install_config_settings_block.tpl'}
                                                </td>
                                            </tr>
                                        {/if}
                                        
                                        <!-- Install the Database -->
                                        {if $stage == 'database_install'}                                        
                                            <tr>
                                                <td>                                                                                                 
                                                    {include file='setup/blocks/install_database_install_block.tpl'}
                                                </td>
                                            </tr>
                                        {/if}
                                        
                                        <!-- Database Installation Results -->
                                        {if $stage == 'database_install_results'}                                        
                                            <tr>
                                                <td>                                                                                                 
                                                    {include file='setup/blocks/install_database_install_results_block.tpl'}
                                                </td>
                                            </tr>
                                        {/if}
                                        
                                        <!-- Company Options -->
                                        {if $stage == 'company_details'}                                        
                                            <tr>
                                                <td>                                                                                                   
                                                    {include file='setup/blocks/install_company_details_block.tpl'}
                                                </td>
                                            </tr>
                                        {/if}
                                        
                                        <!-- Start Numbers (Workorder/Invoice ) -->
                                        {if $stage == 'start_numbers'}                                        
                                            <tr>
                                                <td>
                                                    {include file='setup/blocks/install_start_numbers_block.tpl'}
                                                </td>
                                            </tr>
                                        {/if}
                                        
                                        <!-- Create an Administrator Account -->
                                        {if $stage == 'administrator_account'}                                        
                                            <tr>
                                                <td>
                                                    {include file='setup/blocks/install_administrator_account_block.tpl'}
                                                </td>
                                            </tr>
                                        {/if}
                                        
                                        <!-- Delete Setup Folder -->
                                        {if $stage == 'delete_setup_folder'}                                        
                                            <tr>
                                                <td>
                                                    {include file='setup/blocks/install_delete_setup_folder_block.tpl'}
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