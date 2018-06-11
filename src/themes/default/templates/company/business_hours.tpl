<!-- business_hours.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<table width="100%" border="0" cellpadding="20" cellspacing="0">
    <tr>
        <td>
            <table width="700" cellpadding="5" cellspacing="0" border="0">
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{t}Business Hours{/t}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}COMPANY_BUSINESS_HOURS_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}COMPANY_BUSINESS_HOURS_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
                    </td>
                </tr>                
                <tr>
                    <td class="menutd2" colspan="2">
                        <table width="100%" class="olotable" cellpadding="5" cellspacing="0" border="0" >
                            <tr>
                                <td width="100%" valign="top" class="menutd">
                                    <form method="post" action="index.php?component=company&page_tpl=business_hours">                                
                                        <table>
                                            <tr>
                                                <td><b>{t}Opening Time{/t}</b></td>
                                                <td align="left">
                                                    {html_select_time use_24_hours=true minute_interval=15 display_seconds=false field_array=openingTime time=$opening_time}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><b>{t}Closing Time{/t}</b></td>
                                                <td align="left">
                                                    {html_select_time use_24_hours=true minute_interval=15 display_seconds=false field_array=closingTime time=$closing_time}                                                    
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <button type="submit" name="submit" value="submit">{t}Submit{/t}</button>&nbsp;                                                    
                                                    <button type="button" class="olotd4" onclick="window.location.href='index.php';">{t}Cancel{/t}</button>
                                                </td>
                                            </tr>    
                                        </table>
                                        {t}These settings are used to display the start and stop times of the schedule.{/t}
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