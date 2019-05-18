<!-- upgrade_database_upgrade_block.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<form method="post" action="index.php?component=setup&page_tpl=upgrade">                   
    <table width="600" cellpadding="5" cellspacing="0" border="0">
        <tr>
            <td class="menuhead2" width="80%">&nbsp;{t}Upgrade the QWcrm Database{/t}</td>            
         </tr>
         <tr>
            <td class="menutd2">
                <table width="600" class="olotable" cellpadding="5" cellspacing="0" border="0">
                    <tr>
                        <td colspan="2" style="text-align: center;">{t}Click next to start the database upgrade.{/t}</td>
                    </tr>
                    <tr>
                        <td style="text-align: center;"><h2>{t}From{/t}</h2></td>
                        <td style="text-align: left;"><h2>{t}To{/t}</h2></td>
                    </tr>
                    <tr>
                        <td style="text-align: center;"><h2>{$qwcrm_config.from}</h2></td>
                        <td style="text-align: left;"><h2>{$qwcrm_config.to}</h2></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: center;">
                            <button class="olotd5" type="submit" name="submit" value="database_upgrade">{t}Next{/t}</button>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</form>