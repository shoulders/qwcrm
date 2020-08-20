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
            {*<td class="menuhead2" width="20%" align="right" valign="middle">  <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}ADMINISTRATOR_CONFIG_HELP_TITLE{/t}</strong></div><hr><div>{t escape=js}ADMINISTRATOR_CONFIG_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();"></td>*}
        </tr>
        <tr>
            <td class="menutd2">
                <table width="600" class="olotable" cellpadding="5" cellspacing="0" border="0">

                    <!-- Pre-Installation Check -->

                    <tr>
                        <td align="center" colspan="2">
                            <h2><strong>{t}Pre-Installation Check{/t}</strong></h2>
                            <p>{t escape=no}If any of these items are not supported (marked as <span class="cbadge cfailed">No</span>) then please take actions to correct them. You can't install QWcrm until your setup meets the requirements below.{/t}</p>
                        </td>                        
                    </tr>
                    <tr>
                        <td>
                            <table>
                                {foreach from=$compatibility_results.php_options item=php_option}
                                    <tr>
                                        <td align="right" width="50%">
                                            <span class="clabel">{$php_option.label}</span>                                
                                        </td>
                                        <td>             
                                            {if $php_option.state === true}<span class="cbadge cpassed">{t}Yes{/t}</span>{/if}                                
                                            {if $php_option.state === false}
                                                <span class="cbadge cfailed">{t}No{/t}</span>
                                                {if $php_option.notice}
                                                    <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div>{$php_option.notice|htmlentities|regex_replace:"/[\t\r\n']/":" "}</div>');" onMouseOut="hideddrivetip();">
                                                {/if}
                                            {/if}                                
                                        </td>
                                    </tr>                        
                                {/foreach}
                            </table>
                        <td>
                    </tr>                    
                                        
                    <!-- Recommended Settings -->
                    <tr class="row2">
                        <td class="menuhead" colspan="2" width="100%">&nbsp;</td>
                    </tr> 
                    <tr>
                        <td align="center" colspan="3">
                            <h2><strong>{t}Recommended Settings{/t}</strong></h2>
                            <p>{t}These settings are recommended for PHP to ensure full compatibility with QWcrm. However, QWcrm will still operate if your settings do not quite match the recommended configuration.{/t}</p>
                        </td>                        
                    </tr>
                    <tr>
                        <td>
                            <table>
                                <tr>
                                    <td align="center"><strong>{t}Directive{/t}</strong></td>
                                    <td align="center"><strong>{t}Recommended{/t}</strong></td>
                                    <td align="center"><strong>{t}Actual{/t}</strong></td>
                                </tr>
                                {foreach from=$compatibility_results.php_settings item=php_setting}                        
                                    <tr>
                                        <td>
                                            <span class="clabel">{$php_setting.label}<br></span>                                
                                        </td>
                                        <td>             
                                            {if $php_setting.recommended === true}<span class="cbadge cpassed">{t}On{/t}</span>{/if}
                                            {if $php_setting.recommended === false}<span class="cbadge cpassed">{t}Off{/t}</span>{/if}                                                             
                                        </td>
                                        <td>
                                            <span class="cbadge {if $php_setting.state === $php_setting.recommended}cpassed{else}cwarning{/if}">
                                                {if $php_setting.state}{t}On{/t}{else}{t}Off{/t}{/if}                                                
                                            </span>
                                            {if $php_setting.state !== $php_setting.recommended && $php_setting.notice}
                                                <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div>{$php_setting.notice|htmlentities|regex_replace:"/[\t\r\n']/":" "}</div>');" onMouseOut="hideddrivetip();">
                                            {/if}
                                        </td>
                                    </tr>                        
                                {/foreach}
                            </table>
                        </td>
                    </tr>
                                                        
                    <!-- Next Button -->

                    <tr class="row2">
                        <td class="menuhead" colspan="2" width="100%">&nbsp;</td>
                    </tr>                    
                    <tr>
                        <td colspan="2" style="text-align: center;">
                            {if !$compatibility_results.compatibility_status}<p>{t}You need to fix your Server Enviroment before you can install QWcrm.{/t}</p>{/if}
                            <button id="setup_compatibility_test_next_button" href="javascript:void(0)"{if !$compatibility_results.compatibility_status} disabled{/if}>{t}Next{/t}</button>
                        </td>
                    </tr> 

                </table>
            </td>
        </tr>
    </table>                        
</form>    