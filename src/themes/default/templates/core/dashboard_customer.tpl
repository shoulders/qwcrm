<!-- dashboard_client.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<table width="700" border="0" cellpadding="2" cellspacing="5">
    <tr>
        <td>
            
            <!-- Surrounding Table (for styling) -->
            <table width="700" cellpadding="4" cellspacing="0" border="0">
                
                <!-- Header -->
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{t}QWcrm - Welcome to your Online Portal{/t}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}CORE_DASHBOARD_HELP_TITLE{/t}</strong></div><hr><div>{t escape=js}CORE_DASHBOARD_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
                        </a>
                    </td>
                </tr>

                <!-- Content -->
                <tr>
                    <td class="menutd2" colspan="2">
                        <table class="olotable" width="700" border="0" cellpadding="5" cellspacing="0">
                            <tr>
                                <td>                                    
                                    <table>                                        
                                        <tr>
                                            <td align="center">
                                                <img src="{$company_logo}" alt="" height="114">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="center">
                                                <button id="logout_button" type="submit" name="logout" onclick="window.location.href='index.php?component=user&page_tpl=login&action=logout';">
                                                    <img src="{$theme_images_dir}tick.png" alt=""> {t}Logout{/t}
                                                </button>
                                            </td>                    
                                        </tr>
                                        <tr>
                                            <td>
                                                {t}There is nothing here yet for clients, please logout.{/t}
                                            </td>
                                        </tr>                                        
                                    </table>                                        
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>