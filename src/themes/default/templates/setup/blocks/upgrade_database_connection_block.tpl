<!-- upgrade_database_connection_block.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<form method="post" action="index.php?component=setup&page_tpl=upgrade">                   
    <table width="600" cellpadding="5" cellspacing="0" border="0">
        <tr>
            <td class="menuhead2" width="80%">&nbsp;{t}Database Connection{/t}</td>
            {*<td class="menuhead2" width="20%" align="right" valign="middle">  <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}ADMINISTRATOR_CONFIG_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}ADMINISTRATOR_CONFIG_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();"></td>*}
        </tr>
        <tr>
            <td class="menutd2">
                <table width="600" class="olotable" cellpadding="5" cellspacing="0" border="0">                    
                    <tr class="row2">
                        <td class="menuhead" colspan="2" width="100%">&nbsp;{t}Database Connection Details{/t}</td>                        
                    </tr>
                    <tr>
                        <td><b>{t}Host{/t}</b></td>
                        <td>{$qwcrm_config.db_host}</td>
                    </tr>
                    <tr>
                        <td><b>{t}Database Name{/t}</b></td>
                        <td>{$qwcrm_config.db_name}</td>
                    </tr>                    
                    <tr>
                        <td><b>{t}Database Username{/t}</b></td>
                        <td>{$qwcrm_config.db_user}</td>
                    </tr>                                       
                    <tr class="row2">
                        <td class="menuhead" colspan="5" width="100%">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: center;">
                            <button class="olotd5" type="submit" name="submit" value="database_connection"{if !$enable_next}disabled{/if}>{t}Next{/t}</button>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>                        
</form>    