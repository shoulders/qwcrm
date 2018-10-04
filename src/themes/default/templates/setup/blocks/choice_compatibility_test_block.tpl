<!-- choice_compatibility_test_block_block.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<form method="post" action="index.php?component=setup&page_tpl=migrate">                   
    <table width="600" cellpadding="5" cellspacing="0" border="0">
        <tr>
            <td class="menuhead2" width="80%">&nbsp;{t}QWcrm Compatibility Test{/t}</td>
            {*<td class="menuhead2" width="20%" align="right" valign="middle">  <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}ADMINISTRATOR_CONFIG_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}ADMINISTRATOR_CONFIG_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();"></td>*}
        </tr>
        <tr>
            <td class="menutd2">
                <table width="600" class="olotable" cellpadding="5" cellspacing="0" border="0">

                    <!-- Software Versions (PHP/MySQL/MariaDB) -->

                    <tr>
                        <td align="center" colspan="2"><h2><strong>{t}PHP Extensions{/t}</strong></h2></td>                        
                    </tr>                    
                    {foreach from=$compatibility_results.software_versions item=software_version}
                        <tr>
                            <td align="right" width="50%">
                                <span style="font-size: 16px;">{$software_version.name}</span>
                            </td>
                            <td>             
                                {if $software_version.status == 'passed'}<span class="cbadge cpassed">{t}Passed{/t}</span>{/if}
                                {if $software_version.status == 'warning'}<span class="cbadge cwarning">{t}Warning{/t}</span>{/if}
                                {if $software_version.status == 'failed'}<span class="cbadge cfailed">{t}Failed{/t}</span>{/if}                                
                            </td>
                        </tr>                        
                    {/foreach}
                    <tr>
                        <td colspan="2" style="text-align: center;">{t}These are the minimum version of software that QWcrm needs.{/t}</td>
                    </tr>
                                        
                    <!-- PHP Extensions -->

                    <tr>
                        <td align="center" colspan="2"><h2><strong>{t}PHP Extensions{/t}</strong></h2></td>                        
                    </tr>                    
                    {foreach from=$compatibility_results.php_extensions item=php_extension}
                        <tr>
                            <td align="right" width="50%">
                                <span style="font-size: 16px;">{$php_extension.name}</span>
                            </td>
                            <td>             
                                {if $php_extension.status == 'passed'}<span class="cbadge cpassed">{t}Passed{/t}</span>{/if}
                                {if $php_extension.status == 'warning'}<span class="cbadge cwarning">{t}Warning{/t}</span>{/if}
                                {if $php_extension.status == 'failed'}<span class="cbadge cfailed">{t}Failed{/t}</span>{/if}                                
                            </td>
                        </tr>                        
                    {/foreach}
                    <tr>
                        <td colspan="2" style="text-align: center;">{t}These are PHP Extensions that need to be enabled on a server level for QWcrm to work.{/t}</td>
                    </tr>
                    
                    <!-- PHP Settings -->
                    
                    <tr>
                        <td align="center" colspan="2"><h2><strong>{t}PHP Settings{/t}</strong></h2></td>                        
                    </tr>
                    {foreach from=$compatibility_results.php_settings item=php_setting}
                        <tr>
                            <td align="right" width="50%">
                                <span style="font-size: 16px;">{$php_setting.name}</span>
                            </td>
                            <td>             
                                {if $php_setting.status == 'passed'}<span class="cbadge cpassed">{t}Passed{/t}</span>{/if}
                                {if $php_setting.status == 'warning'}<span class="cbadge cwarning">{t}Warning{/t}</span>{/if}
                                {if $php_setting.status == 'failed'}<span class="cbadge cfailed">{t}Failed{/t}</span>{/if}                                
                            </td>
                        </tr>                        
                    {/foreach}
                    <tr>
                        <td colspan="2" style="text-align: center;">{t}You need to modified the servers php.ini file or create an override file to enable the required PHP settings.{/t}</td>
                    </tr>
                    
                    <!-- PHP Functions -->
                    
                    <tr>
                        <td align="center" colspan="2"><h2><strong>{t}PHP Functions{/t}</strong></h2></td>                        
                    </tr>
                    {foreach from=$compatibility_results.php_functions item=php_function}
                        <tr>
                            <td align="right" width="50%">
                                <span style="font-size: 16px;">{$php_function.name}</span>
                            </td>
                            <td>             
                                {if $php_function.status == 'passed'}<span class="cbadge cpassed">{t}Passed{/t}</span>{/if}
                                {if $php_function.status == 'warning'}<span class="cbadge cwarning">{t}Warning{/t}</span>{/if}
                                {if $php_function.status == 'failed'}<span class="cbadge cfailed">{t}Failed{/t}</span>{/if}                                
                            </td>
                        </tr>                        
                    {/foreach}
                    <tr>
                        <td colspan="2" style="text-align: center;">{t}PHP Functions are usually not present because the appropriate PHP Extension is not installed or your hosting provider has blocked it by disabling it in the servers php.ini file.{/t}</td>
                    </tr>
                                    
                    <!-- Next Button -->

                    <tr class="row2">
                        <td class="menuhead" colspan="2" width="100%">&nbsp;</td>
                    </tr>                    
                    <tr>
                        <td colspan="2" style="text-align: center;">
                            <button id="setup_compatibility_test_next_button" href="javascript:void(0)"{if !$compatibility_results.compatibility_status} disabled{/if}>{t}Next{/t}</button>
                        </td>
                    </tr> 

                </table>
            </td>
        </tr>
    </table>                        
</form>    