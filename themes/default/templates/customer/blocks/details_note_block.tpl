<!-- details_notes_block.tpl -->
<table class="olotable" border="0" width="100%" cellpadding="0" cellspacing="0" >
    <tr>
        <td class="olohead">
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{$translate_workorder_details_notes_title}</td>
                    <td class="menuhead2" width="20%" align="right">
                        <table cellpadding="2" cellspacing="2" border="0">
                            <tr> 
                                <td width="33%" align="right">                    
                                    <a href="index.php?page=customer:note_new&customer_id={$customer_id}">
                                        <img src="{$theme_images_dir}icons/16x16/small_edit.gif" onMouseOver="ddrivetip('Add New Customer Note');" onMouseOut="hideddrivetip();">                                                 
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
                {section name=n loop=$customer_notes}
                    <tr>
                        <td class="menutd">
                            <table width="100%" cellpadding="4" cellspacing="0" style="border-collapse: collapse;">                
                                <tr style="border: 1px black solid; background-color: #ededed;">
                                    <td><b>Customer Note ID: {$customer_notes[n].CUSTOMER_NOTE_ID}</b></td>
                                    <td width="33%" align="right">
                                        {if $login_account_type_id == 1}
                                            <a href="index.php?page=customer:note_edit&customer_note_id={$customer_notes[n].CUSTOMER_NOTE_ID}">
                                                <img src="{$theme_images_dir}icons/16x16/small_edit.gif" onMouseOver="ddrivetip('Edit the Note');" onMouseOut="hideddrivetip();">                                                 
                                            </a>
                                            <a href="index.php?page=customer:note_delete&customer_note_id={$customer_notes[n].CUSTOMER_NOTE_ID}" oNclick="return confirmDelete('Are you sure you want to delete this customer note?');">
                                                <img src="{$theme_images_dir}icons/16x16/small_edit.gif" onMouseOver="ddrivetip('Delete the Note');" onMouseOut="hideddrivetip();">                                                 
                                            </a>
                                        {/if}
                                    </td>
                                </tr>                                
                            </table>    
                        </td>    
                    </tr> 
                    <tr>                    
                        <td>
                            <b>Employee: </b>{$customer_notes[n].EMPLOYEE_DISPLAY_NAME}<br>                           
                            <b>Date: </b>{$customer_notes[n].DATE|date_format:$date_format}<br>
                            <b>Time: </b>{$customer_notes[n].DATE|date_format:"%H:%M"}<br>
                            <b>Notes:</b>
                            <div>{$customer_notes[n].NOTE}<br></div>
                        </td>
                    </tr>
                {/section}
            </table>                            
        </td>          
    </tr>
</table>