<!-- migrate_stage7_block.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<form method="post" action="index.php?page=setup:migrate">                   
    <table width="600" cellpadding="5" cellspacing="0" border="0">
        <tr>
            <td class="menuhead2" width="80%">&nbsp;{t}Stage 7 - MyITCRM Database Migration Results{/t}</td>
            {*<td class="menuhead2" width="20%" align="right" valign="middle">  <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}ADMINISTRATOR_CONFIG_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}ADMINISTRATOR_CONFIG_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();"></td>*}
        </tr>
        <tr>
            <td class="menutd2">
                <table width="600" class="olotable" cellpadding="5" cellspacing="0" border="0">
                    <tr>
                        <td style="text-align: center;">
                            {$executed_sql_results}
                        </td>
                    </tr>                    
                    {if $setup_error_flag}
                        <tr>
                            <td colspan="2" style="text-align: center;">
                                <strong><span style="color: red">{t}There was a fault with the database migration. The data might be compromised.{/t}</span></strong>
                            </td>
                        </tr>
                    {else}
                        <tr>
                            <td colspan="2" style="text-align: center;">
                                <strong>{t}The database migration was successful. You need to manually delete your old MyITCRM tables once you have completed this process.{/t}</strong>
                            </td>
                        </tr>                        
                    {/if}    
                    <tr>
                        <td colspan="2" style="text-align: center;">
                            <input type="hidden" name="stage" value="7">
                            <button class="olotd5" type="submit" name="submit" value="stage7">{t}Next{/t}</button>
                        </td>
                    </tr>                                       
                </table>
            </td>
        </tr>
    </table>
</form>