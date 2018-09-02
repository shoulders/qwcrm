<!-- migrate_myitcrm_database_connection_qwcrm_block.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<form method="post" action="index.php?component=setup&page_tpl=migrate">                   
    <table width="600" cellpadding="5" cellspacing="0" border="0">
        <tr>
            <td class="menuhead2" width="80%">&nbsp;{t}QWcrm Database Connection{/t}</td>
            {*<td class="menuhead2" width="20%" align="right" valign="middle">  <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}ADMINISTRATOR_CONFIG_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}ADMINISTRATOR_CONFIG_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();"></td>*}
        </tr>
        <tr>
            <td class="menutd2">
                <table width="600" class="olotable" cellpadding="5" cellspacing="0" border="0">

                    <!-- Database --> 

                    <tr class="row2">
                        <td class="menuhead" colspan="5" width="100%">&nbsp;{t}Enter your database connection details MMM{/t}</td>
                    </tr>

                    <tr>
                        <td align="right"><b>{t}Host{/t}</b> <span style="color: #ff0000">*</span></td>
                        <td>
                            <input name="qwcrm_config[db_host]" class="olotd5" size="25" value="{$qwcrm_config.db_host}" type="text" maxlength="20" placeholder="localhost" required onkeydown="return onlyAlphaNumeric(event);"/>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}Host{/t}</strong></div><hr><div>{t escape=tooltip}The hostname for your database entered during the installation process. Do not edit this field unless absolutely necessary (eg the transfer of the database to a new hosting provider).{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><b>{t}Database Name{/t}</b> <span style="color: #ff0000">*</span></td>
                        <td>
                            <input name="qwcrm_config[db_name]" class="olotd5" size="25" value="{$qwcrm_config.db_name}" type="text" maxlength="20" required onkeydown="return onlyMysqlDatabaseName(event);"/>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}Database Name{/t}</strong></div><hr><div>{t escape=tooltip}The name for your database entered during the installation process. Do not edit this field unless absolutely necessary (eg the transfer of the database to a new hosting provider).{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>                    
                    <tr>
                        <td align="right"><b>{t}Database Username{/t}</b> <span style="color: #ff0000">*</span></td>
                        <td>
                            <input name="qwcrm_config[db_user]" class="olotd5" size="25" value="{$qwcrm_config.db_user}" type="text" maxlength="20" required onkeydown="return onlyAlphaNumeric(event);"/>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}Database Username{/t}</strong></div><hr><div>{t escape=tooltip}The username for access to your database entered during the installation process. Do not edit this field unless absolutely necessary (eg the transfer of the database to a new hosting provider).{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>                    
                    <tr>
                        <td align="right"><b>{t}Database Password{/t}</b></td>
                        <td>
                            <input name="qwcrm_config[db_pass]" class="olotd5" size="25" value="{$qwcrm_config.db_pass}" type="password" maxlength="20" onkeydown="return onlyAlphaNumeric(event);"/>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}Database Password{/t}</strong></div><hr><div>{t escape=tooltip}The password for access to your database entered during the installation process. Do not edit this field unless absolutely necessary (eg the transfer of the database to a new hosting provider).{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>                    

                    <!-- Submit -->

                    <tr class="row2">
                        <td class="menuhead" colspan="5" width="100%">&nbsp;</td>
                    </tr>

                    <tr>
                        <td colspan="2" style="text-align: center;">
                            {t}You should set your default database collation to{/t}: <b>utf8_unicode_ci</b><br>
                            {t}This can usually be done via phpMyAdmin.{/t}
                        </td>
                    </tr>

                    <tr>
                        <td colspan="2" style="text-align: center;">
                            <button class="olotd5" type="submit" name="submit" value="database_connection_qwcrm">{t}Next{/t}</button>
                        </td>
                    </tr> 

                </table>
            </td>
        </tr>
    </table>                        
</form>    