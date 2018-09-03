<!-- migrate_myitcrm_upgrade_confirmation_block.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}

<table width="100%" border="0" cellpadding="20" cellspacing="0">
    <tr>
        <td>
            <table width="900" cellpadding="5" cellspacing="0" border="0">
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{t}Start the Upgrade procedure{/t}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle"></td>
                </tr>
                <tr>
                    <td class="menutd2" colspan="2">
                        <table width="100%" class="olotable" cellpadding="5" cellspacing="0" border="0">
                            <tr>
                                <td width="100%" valign="top">                                    
                                    <form action="index.php?component=setup&page_tpl=migrate" method="post"> 
                                        <table class="menutable" width="100%" border="0" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td class="menutd">
                                                    <table width="100%" cellpadding="2" cellspacing="2" border="0" class="menutd2">
                                                        <tr>
                                                            <td>                                                                
                                                                <table class="olotable" width="100%" cellpadding="5" cellspacing="0" border="0">
                                                                    
                                                                    <!-- Common -->
                                                                    
                                                                    <tr>
                                                                        <td>
                                                                            <div id="upgrade_confirmation">
                                                                                
                                                                                <!-- Upgrade Confirmation Message -->
                                                                                <p style="text-align: center;">
                                                                                    <strong>{t}Migration from MyITCRM is Complete, but an upgrade is still required.{/t}</strong>
                                                                                    <br />
                                                                                    {t}When you click next the upgrade procedure will start and is straight forward.{/t}
                                                                                </p>
                                                                                
                                                                                <!-- Upgrade Confirmation Button -->                                                                    
                                                                                <button id="upgrade_confirmation_button" type="button" style="display: block; margin: auto auto;" onclick="window.location.href='index.php';">{t}Start Upgrade{/t}</button>
                                                                            
                                                                            </div>
                                                                            
                                                                        </td>
                                                                    </tr>      
                                                                                                                                        
                                                                </table>                                                                
                                                            </td>
                                                    </table>
                                                </td>
                                        </table>
                                    </form>                                                                        
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>