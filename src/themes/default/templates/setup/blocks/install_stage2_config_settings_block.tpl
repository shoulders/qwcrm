<!-- install_stage2_config_settings_block.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}

<script src="{$theme_js_dir}tinymce/tinymce.min.js"></script>

<form method="post" action="index.php?component=setup&page_tpl=install">                  
    <table width="600" cellpadding="5" cellspacing="0" border="0">
        <tr>
            <td class="menuhead2" width="80%">&nbsp;{t}Stage 2 - Config Settings{/t}</td>
            <td class="menuhead2" width="20%" align="right" valign="middle">  <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}ADMINISTRATOR_CONFIG_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}ADMINISTRATOR_CONFIG_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();"></td>
        </tr>
        <tr>
            <td class="menutd2" colspan="2">
                <table width="600" class="olotable" cellpadding="5" cellspacing="0" border="0">
                    
                    <!-- Database -->
                    
                    <tr class="row2">
                        <td class="menuhead" colspan="2" width="100%">&nbsp;{t}Database{/t}</td>
                    </tr>
                    
                    <tr>
                        <td align="right"><b>{t}Database Tables Prefix{/t}</b> <span style="color: blue">*</span></td>
                        <td>
                            <input name="qwcrm_config[db_prefix]" class="olotd5" size="6" value="{$qwcrm_config.db_prefix}" type="text" maxlength="6" required onkeydown="return onlyMysqlDatabaseName(event);" readonly/>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}Database Tables Prefix{/t}</strong></div><hr><div>{t escape=tooltip}The prefix used for your database tables, created during the installation process. Do not edit this field unless absolutely necessary (eg the transfer of the database to a new hosting provider).{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>                    
                    
                    <!-- Mail Settings -->
                    
                    <tr class="row2">
                        <td class="menuhead" colspan="2" width="100%">&nbsp;{t}Mail Settings{/t}</td>
                    </tr>                                                        

                        <!-- Common -->
                        
                    <tr>
                        <td align="right"><b>{t}From Email{/t}:</b> <span style="color: #ff0000">*</span></td> 
                        <td>
                            <input name="qwcrm_config[email_mailfrom]" class="olotd5" size="55" value="{$qwcrm_config.email_mailfrom}" type="email" maxlength="50" placeholder="no-reply@quantumwarp.com" required onkeydown="return onlyEmail(event);">
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}From Email{/t}</strong></div><hr><div>{t escape=tooltip}The email address that will be used to send site email.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>                    
                    
                    <!-- Next Button -->
                    
                    <tr class="row2">
                        <td class="menuhead" colspan="2" width="100%">&nbsp;</td>
                    </tr> 
                    
                    <tr>
                        <td colspan="2">
                            <p><span style="color: #ff0000">*</span> {t}Mandatory{/t}</p>
                            <p><span style="color: blue">*</span> {t}Only change if needed{/t}</p>
                        </td>
                    </tr>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: center;">
                            <input type="hidden" name="stage" value="2">
                            <button class="olotd5" type="submit" name="submit" value="stage2">{t}Next{/t}</button>
                        </td>
                    </tr> 
                    
                </table>
            </td>
        </tr>
    </table>                        
</form>