<!-- login_dashboard_block.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<table class="olotable" width="900" border ="1" cellpadding="5" cellspacing="5">
    <tr>
        <td class="olohead" colspan="2">{t}Dashboard{/t}</td>
    </tr>
    <tr>
        <td class="olotd">
            <table class="olotable" width="100%" align="center" border ="0">
                <tr>
                    <td align="center">
                        <img src="{$company_logo}" alt="" height="114"></td>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p>&nbsp;</p>
                    </td>
                </tr>
                <tr>
                    <td align="center">
                        <button id="login_button" type="submit" name="login" onclick="window.location.href='index.php?page=user:login';">
                            <img src="{$theme_images_dir}tick.png" alt=""> {t}Login{/t}
                        </button>
                    </td>                    
                </tr>                
            </table>
        </td>
    </tr>
</table>