<!-- install_stage5_block.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}

<form method="post" action="index.php?page=setup:install">                   
     <table width="600" cellpadding="5" cellspacing="0" border="0">
         <tr>
             <td class="menuhead2" width="80%">&nbsp;{t}QWcrm Config Settings{/t}</td>
             <td class="menuhead2" width="20%" align="right" valign="middle">  <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}ADMINISTRATOR_CONFIG_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}ADMINISTRATOR_CONFIG_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();"></td>
         </tr>
         <tr>
             <td class="menutd2">
                 <table width="600" class="olotable" cellpadding="5" cellspacing="0" border="0">

                     <!-- Database --> 

                     <tr>
                         <td align="right"><b>{t}Database Tables Prefix{/t}</b> <span style="color: #ff0000">*</span></td>
                         <td>
                             <input name="db_prefix" class="olotd5" size="6" value="{$qwcrm_config.db_prefix}" type="text" maxlength="6" required onkeydown="return onlyAlphaNumeric(event);"/>
                             <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}Database Tables Prefix{/t}</strong></div><hr><div>{t escape=tooltip}The prefix used for your database tables, created during the installation process. Do not edit this field unless absolutely necessary (eg the transfer of the database to a new hosting provider).{/t}</div>');" onMouseOut="hideddrivetip();">
                         </td>
                     </tr>


                     <!-- Other -->

                     <tr class="row2">
                         <td class="menuhead" colspan="5" width="100%">&nbsp;{t}Other{/t}</td>
                     </tr>

                     <tr>
                         <td align="right"><b>{t}Gzip Page Compression{/t}</b></td>
                         <td>
                             <select class="olotd5" id="gzip" name="gzip">                                                       
                                 <option value="0"{if $qwcrm_config.gzip == '0'} selected{/if}>{t}No{/t}</option>
                                 <option value="1"{if $qwcrm_config.gzip == '1'} selected{/if}>{t}Yes{/t}</option>
                             </select>
                             <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}Gzip Page Compression{/t}</strong></div><hr><div>{t escape=tooltip}Compress buffered output if supported.{/t}</div>');" onMouseOut="hideddrivetip();">
                         </td>                        
                     </tr>

                     <!-- Submit -->

                     <tr class="row2">
                         <td class="menuhead" colspan="5" width="100%">&nbsp;</td>
                     </tr> 

                     <tr>
                         <td colspan="2" style="text-align: center;">
                             <input type="hidden" name="stage" value="5">
                             <button class="olotd5" type="submit" name="submit" value="stage5">{t}Submit{/t}</button>
                         </td>
                     </tr> 

                 </table>
             </td>
         </tr>
     </table>                        
 </form>  