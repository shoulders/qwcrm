<!-- details_notes_block.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<table class="olotable" border="0" width="100%" cellpadding="0" cellspacing="0" >
    <tr>
        <td class="olohead">
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{t}Notes{/t}</td>
                    <td class="menuhead2" width="20%" align="right">
                        <table cellpadding="2" cellspacing="2" border="0">
                            <tr> 
                                <td width="33%" align="right">   
                                    <a href="index.php?component=workorder&page_tpl=note_new&workorder_id={$workorder_id}">
                                        <img src="{$theme_images_dir}icons/16x16/small_new_work_order.gif" border="0" onMouseOver="ddrivetip('{t}Click to create a new note{/t}');" onMouseOut="hideddrivetip();">
                                    </a>                    
                                </td>  
                            </tr>
                        </table>
                        </a>
                    </td>
                </tr>
            </table>       
        </td>
    </tr>   
    <tr>
        <td class="menutd">
            <table width="100%" cellpadding="4" cellspacing="0" border="0">
                {section name=n loop=$workorder_notes}
                    <tr>
                        <td class="menutd">
                            <table width="100%" cellpadding="4" cellspacing="0" style="border-collapse: collapse;">                
                                <tr style="border: 1px black solid; background-color: #ededed;">
                                    <td><b>{t}Workorder Note ID{/t}: {$workorder_notes[n].workorder_note_id}</b></td>
                                    <td width="33%" align="right">
                                        {if $login_usergroup_id == 1}
                                            <a href="index.php?component=workorder&page_tpl=note_edit&workorder_note_id={$workorder_notes[n].workorder_note_id}">
                                                <img src="{$theme_images_dir}icons/16x16/small_edit.gif" onMouseOver="ddrivetip('{t}Edit the Note{/t}');" onMouseOut="hideddrivetip();">                                                 
                                            </a>
                                            <a href="index.php?component=workorder&page_tpl=note_delete&workorder_note_id={$workorder_notes[n].workorder_note_id}" onclick="return confirmChoice('{t}Are you sure you want to delete this note?{/t}');">
                                                <img src="{$theme_images_dir}icons/16x16/small_edit.gif" onMouseOver="ddrivetip('{t}Delete the Note{/t}');" onMouseOut="hideddrivetip();">                                                 
                                            </a>
                                        {/if}
                                    </td>
                                </tr>                                
                            </table>    
                        </td>    
                    </tr> 
                    <tr>                    
                        <td>
                            <b>{t}Employee{/t}: </b>{$workorder_notes[n].employee_display_name}<br>                           
                            <b>{t}Date{/t}: </b>{$workorder_notes[n].date|date_format:$date_format}<br>
                            <b>{t}Time{/t}: </b>{$workorder_notes[n].date|date_format:"%H:%M"}<br>
                            <b>{t}Notes{/t}:</b>
                            <div>{$workorder_notes[n].description}<br></div>
                        </td>
                    </tr>
                {sectionelse}
                    <tr>
                        <td colspan="6" class="error">{t}There are no workorder notes.{/t}</td>
                    </tr>        
                {/section}
            </table>                            
        </td>          
    </tr>
</table>
