<!-- 403.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<table width="100%" cellpadding="5" cellspacing="5">
    <tr>
        <td>
            <img src="{$company_logo}" alt="" style="max-width: 450px;">            
            <hr>
            <font size="+1">{t}403 - You do not have permission to access this resource or your session has expired.{/t}</font>            
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p style="margin-left: 200px;"><button type="button" onClick="window.location.href='index.php';">{t}Home{/t}</button></p>
            {*<p style="margin-left: 200px;">
                <button id="login_button" type="submit" name="login" onclick="window.location.href='index.php?component=user&page_tpl=login';">
                    <img src="{$theme_images_dir}tick.png" alt=""> {t}Login{/t}
                </button>
            </p>*}
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
        </td>
    </tr>
</table>