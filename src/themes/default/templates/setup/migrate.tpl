<!-- migrate.tpl -->
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
                                        
                                        <!-- Stage 1 - Database connection and test -->
                                        {if $stage == '1' || !$stage}                                        
                                            <tr>
                                                <td>                                                                                                  
                                                    {include file='setup/blocks/migrate_stage1_database_connection_block.tpl'}
                                                </td>
                                            </tr>
                                        {/if}
                                        
                                        <!-- Stage 2 - MyITCRM Database connection and test -->
                                        {if $stage == '2'}                                        
                                            <tr>
                                                <td>                                                                                                  
                                                    {include file='setup/blocks/migrate_stage2_myitcrm_database_connection_block.tpl'}
                                                </td>
                                            </tr>
                                        {/if}
                                        
                                        <!-- Stage 3 - Config Settings -->
                                        {if $stage == '3'}                                        
                                            <tr>
                                                <td>                                                                                                 
                                                    {include file='setup/blocks/migrate_stage3_config_settings_block.tpl'}
                                                </td>
                                            </tr>
                                        {/if}
                                        
                                        <!-- Stage 4 - Install the Database -->
                                        {if $stage == '4'}                                        
                                            <tr>
                                                <td>                                                                                                 
                                                    {include file='setup/blocks/migrate_stage4_database_install_block.tpl'}
                                                </td>
                                            </tr>
                                        {/if}
                                        
                                        <!-- Stage 5 - Database Installation Results -->
                                        {if $stage == '5'}                                        
                                            <tr>
                                                <td>                                                                                                 
                                                    {include file='setup/blocks/migrate_stage5_database_results_block.tpl'}
                                                </td>
                                            </tr>
                                        {/if}
                                        
                                        <!-- Stage 6 - Company Options -->
                                        {if $stage == '6'}                                        
                                            <tr>
                                                <td>                                                                                                   
                                                    {include file='setup/blocks/migrate_stage6_company_options_block.tpl'}
                                                </td>
                                            </tr>
                                        {/if}
                                        
                                        <!-- Stage 7 - MyITCRM Database Migration -->
                                        {if $stage == '7'}                                        
                                            <tr>
                                                <td>
                                                    {include file='setup/blocks/migrate_stage7_myitcrm_database_migrate_block.tpl'}
                                                </td>
                                            </tr>
                                        {/if}
                                        
                                        <!-- Stage 8 - MyITCRM Database Migration Results -->
                                        {if $stage == '8'}                                        
                                            <tr>
                                                <td>                                                                                                 
                                                    {include file='setup/blocks/migrate_stage8_myitcrm_database_results_block.tpl'}
                                                </td>
                                            </tr>
                                        {/if}
                                        
                                        <!-- Stage 9 - Create an Administrator Account -->
                                        {if $stage == '9'}                                        
                                            <tr>
                                                <td>
                                                    {include file='setup/blocks/migrate_stage9_administrator_account_block.tpl'}
                                                </td>
                                            </tr>
                                        {/if}
                                        
                                        <!-- Stage 10 - Delete Setup Files -->
                                        {if $stage == '8'}                                        
                                            <tr>
                                                <td>
                                                    {include file='setup/blocks/migrate_stage10_delete_setup_files_block.tpl'}
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