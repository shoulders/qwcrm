<!-- upgrade_database_results_block.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<form method="post" action="index.php?component=setup&page_tpl=upgrade">                   
    <table width="600" cellpadding="5" cellspacing="0" border="0">
        <tr>
            <td class="menuhead2" width="80%">&nbsp;{t}Database Upgrade Results{/t}</td>
        </tr>
        <tr>
            <td class="menutd2">
                <table width="600" class="olotable" cellpadding="5" cellspacing="0" border="0">
                    <tr>
                        <td style="text-align: center;">
                            {$executed_sql_results}
                        </td>
                    </tr>                    
                    {if !$setup_error_flag}
                        <tr>
                            <td colspan="2" style="text-align: center;">
                                <button class="olotd5" type="submit" name="submit" value="database_upgrade_results">{t}Next{/t}</button>
                            </td>
                        </tr>
                    {else}
                        <tr>
                            <td colspan="2" style="text-align: center;">
                                <strong><span style="color: red">{t}You cannot continue because there was a fault with the database upgrade.{/t}</span></strong>
                            </td>
                        </tr>
                    {/if}                    
                </table>
            </td>
        </tr>
    </table>
</form>