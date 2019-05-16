<!-- migrate.tpl -->
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
                                        
                                        <!-- Database connection and test (QWcrm) -->
                                        {if $stage == 'database_connection_qwcrm' || !$stage}                                        
                                            <tr>
                                                <td>                                                                                                  
                                                    {include file='setup/blocks/migrate_myitcrm_database_connection_qwcrm_block.tpl'}
                                                </td>
                                            </tr>
                                        {/if}
                                        
                                        <!-- Database connection and test (MyITCRM) -->
                                        {if $stage == 'database_connection_myitcrm'}                                        
                                            <tr>
                                                <td>                                                                                                  
                                                    {include file='setup/blocks/migrate_myitcrm_database_connection_myitcrm_block.tpl'}
                                                </td>
                                            </tr>
                                        {/if}
                                        
                                        <!-- Config Settings -->
                                        {if $stage == 'config_settings'}                                        
                                            <tr>
                                                <td>                                                                                                 
                                                    {include file='setup/blocks/migrate_myitcrm_config_settings_block.tpl'}
                                                </td>
                                            </tr>
                                        {/if}
                                        
                                        <!-- Install the Database (QWcrm) -->
                                        {if $stage == 'database_install_qwcrm'}                                        
                                            <tr>
                                                <td>                                                                                                 
                                                    {include file='setup/blocks/migrate_myitcrm_database_install_qwcrm_block.tpl'}
                                                </td>
                                            </tr>
                                        {/if}
                                        
                                        <!-- Database Installation Results (QWcrm) -->
                                        {if $stage == 'database_install_results_qwcrm'}                                        
                                            <tr>
                                                <td>                                                                                                 
                                                    {include file='setup/blocks/migrate_myitcrm_database_install_results_qwcrm_block.tpl'}
                                                </td>
                                            </tr>
                                        {/if}
                                        
                                        <!-- Company Details -->
                                        {if $stage == 'company_details'}                                        
                                            <tr>
                                                <td>                                                                                                   
                                                    {include file='setup/blocks/migrate_myitcrm_company_details_block.tpl'}
                                                </td>
                                            </tr>
                                        {/if}
                                        
                                        <!-- MyITCRM Database Migration -->
                                        {if $stage == 'database_migrate'}                                        
                                            <tr>
                                                <td>
                                                    {include file='setup/blocks/migrate_myitcrm_database_migrate_block.tpl'}
                                                </td>
                                            </tr>
                                        {/if}
                                        
                                        <!-- MyITCRM Database Migration Results -->
                                        {if $stage == 'database_migrate_results'}                                        
                                            <tr>
                                                <td>                                                                                                 
                                                    {include file='setup/blocks/migrate_myitcrm_database_migrate_results_block.tpl'}
                                                </td>
                                            </tr>
                                        {/if}
                                        
                                        <!-- Create an Administrator Account -->
                                        {if $stage == 'administrator_account'}                                        
                                            <tr>
                                                <td>
                                                    {include file='setup/blocks/migrate_myitcrm_administrator_account_block.tpl'}
                                                </td>
                                            </tr>
                                        {/if}
                                        
                                        <!-- Start the upgrade procedure -->
                                        {if $stage == 'upgrade_confirmation'}                                        
                                            <tr>
                                                <td>
                                                    {include file='setup/blocks/migrate_myitcrm_upgrade_confirmation_block.tpl'}
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