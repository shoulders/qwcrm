<!-- migrate_myitcrm_database_connection_myitcrm_block.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<form method="post" action="index.php?component=setup&page_tpl=migrate">                   
    <table width="600" cellpadding="5" cellspacing="0" border="0">
        <tr>
            <td class="menuhead2" width="80%">&nbsp;{t}MyITCRM Database Connection{/t}</td>
            {*<td class="menuhead2" width="20%" align="right" valign="middle">  <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}ADMINISTRATOR_CONFIG_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}ADMINISTRATOR_CONFIG_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();"></td>*}
        </tr>
        <tr>
            <td class="menutd2">
                <table width="600" class="olotable" cellpadding="5" cellspacing="0" border="0">

                    <!-- Database --> 

                    <tr>
                        <td align="right"><b>{t}MyITCRM Database Tables Prefix{/t}</b> <span style="color: #ff0000">*</span></td>
                        <td>
                            <input name="myitcrm_prefix" class="olotd5" size="6" value="" type="text" maxlength="6" placeholder="myit_" required onkeydown="return onlyMysqlDatabaseName(event);"/>                            
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: center;">{t}Enter The prefix used for your MyITCRM database tables.{/t}</td>
                    </tr>

                    <!-- Submit -->

                    <tr class="row2">
                        <td class="menuhead" colspan="2" width="100%">&nbsp;</td>
                    </tr>
                    
                    <tr>
                        <td colspan="2" style="text-align: center;">
                            {t}The MyITCRM tables should be in the same database you are installing QWcrm into.{/t}                            
                        </td>
                    </tr>

                    <tr>
                        <td colspan="2" style="text-align: center;">
                            <button class="olotd5" type="submit" name="submit" value="database_connection_myitcrm">{t}Next{/t}</button>
                        </td>
                    </tr> 

                </table>
            </td>
        </tr>
    </table>                        
</form>    