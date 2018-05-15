<!-- install_stage6_block.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<form method="post" action="index.php?component=setup&page_tpl=install">                   
     <table width="600" cellpadding="5" cellspacing="0" border="0">
         <tr>
             <td class="menuhead2" width="80%">&nbsp;{t}Stage 6 - Work Order and Invoice Start Numbers{/t}</td>
             {*<td class="menuhead2" width="20%" align="right" valign="middle">  <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}ADMINISTRATOR_CONFIG_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}ADMINISTRATOR_CONFIG_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();"></td>*}
         </tr>
         <tr>
             <td class="menutd2">
                <table width="600" class="olotable" cellpadding="5" cellspacing="0" border="0">

                    <!-- Work Order Start Number --> 

                    <tr>
                        <td align="right"><b>{t}Work Order Start Number{/t}</b></td>
                        <td>
                            <input name="workorder_start_number" class="olotd5" size="6" type="text" maxlength="6" onkeydown="return onlyNumber(event);"/>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}Work Order Start Number{/t}</strong></div><hr><div>{t escape=tooltip}Only enter a number if you want to start your work orders from a specified number.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>

                    <!-- Invoice Start Number --> 

                    <tr>
                        <td align="right"><b>{t}Invoice Start Number{/t}</b></td>
                        <td>
                            <input name="invoice_start_number" class="olotd5" size="6" type="text" maxlength="6" onkeydown="return onlyNumber(event);"/>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}Invoice Start Number{/t}</strong></div><hr><div>{t escape=tooltip}Only enter a number if you want to start your invoices from a specified number.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>                       
                    </tr>

                    <!-- Submit -->

                    <tr class="row2">
                        <td class="menuhead" colspan="2" width="100%">&nbsp;</td>
                    </tr>
                    
                    <tr>
                        <td colspan="2" style="text-align: center;">
                            {t}These start numbers are optional and can be skipped by just clicking next.{/t}                            
                        </td>
                    </tr>

                    <tr>
                        <td colspan="2" style="text-align: center;">
                            <input type="hidden" name="stage" value="6">
                            <button class="olotd5" type="submit" name="submit" value="stage6">{t}Next{/t}</button>
                        </td>
                    </tr> 

                 </table>
             </td>
         </tr>
     </table>                        
 </form>  