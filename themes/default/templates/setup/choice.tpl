<!-- choice.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<table width="100%" border="0" cellpadding="20" cellspacing="5">
    <tr>
        <td>            
            <table width="700" cellpadding="3" cellspacing="0" border="0">
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{t}QWcrm Installation / MyITCRM Migration{/t}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}SETUP_CHOICE_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}SETUP_CHOICE_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
                        </a>
                    </td>
                </tr>
                <tr>
                    <td class="menutd2" colspan="2">
                        <table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
                            <tr>
                                <td class="menutd">
                                    <table width="100%" border="0" cellpadding="10" cellspacing="0">
                                        
                                        <!-- Installation Message-->   
                                        <tr>
                                            <td>                                                                                                  
                                                {t}Choose choose whether you want to install a fresh copy of QWcrm or migrate from MyITCRM{/t}
                                            </td>
                                        </tr> 
                                        
                                        <!-- Install QWcrm -->   
                                        <tr>
                                            <td>                                                                                                  
                                                <a href="index.php?page=setup:install"><button type="submit" name="submit" value="update">{t}Install QWcrm{/t}</button></a>
                                            </td>
                                        </tr>                                        
                                        
                                        <!-- Migrate from MyITCRM -->  
                                        <tr>
                                            <td>                                                                                                 
                                                <a href="index.php?page=setup:migrate"><button type="submit" name="submit" value="update">{t}Migrate from MyITCRM{/t}</button></a>
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