<!-- install_stage4_block.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<form method="post" action="index.php?page=setup:install">                   
     <table width="600" cellpadding="5" cellspacing="0" border="0">
         <tr>
             <td class="menuhead2" width="80%">&nbsp;{t}Database Install Results{/t}</td>
             <td class="menuhead2" width="20%" align="right" valign="middle">  <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}ADMINISTRATOR_CONFIG_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}ADMINISTRATOR_CONFIG_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();"></td>
         </tr>
         <tr>
        <tr>
            <td colspan="2" style="text-align: center;">
                <input type="hidden" name="stage" value="4">
                <button class="olotd5" type="submit" name="submit" value="stage4">{t}Submit{/t}</button>
            </td>
        </tr>        
    </table>
</form>