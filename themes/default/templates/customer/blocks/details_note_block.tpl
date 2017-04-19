<!-- details_note_block.tpl -->
{section name=i loop=$customer_notes}
    <table class="olotable" width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td class="olohead">
                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td class="menuhead2" width="80%">&nbsp;Customer Notes</td>                    
                        <td width="33%" align="right">                    
                            <a href="index.php?page=customer:note_new&customer_id={$customer_notes[i].CUSTOMER_ID}">
                                <img src="{$theme_images_dir}icons/16x16/small_edit.gif" onMouseOver="ddrivetip('Add New Customer Note');" onMouseOut="hideddrivetip();">                                                 
                            </a>                    
                        </td>                
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td class="menutd">
                <table width="100%" cellpadding="4" cellspacing="0" style="border-collapse: collapse;">                
                    <tr style="border: 1px black solid; background-color: #ededed;">
                        <td><b>{$customer_notes[i].DATE|date_format:$date_format}</b></td>
                        <td width="33%" align="right">
                            {if $login_id == 1}
                                <a href="index.php?page=customer:note_edit&customer_note_id={$customer_notes[i].CUSTOMER_NOTE_ID}">
                                    <img src="{$theme_images_dir}icons/16x16/small_edit.gif" onMouseOver="ddrivetip('Edit the Note');" onMouseOut="hideddrivetip();">                                                 
                                </a>
                                <a href="index.php?page=customer:note_delete&customer_note_id={$customer_notes[i].CUSTOMER_NOTE_ID}&action=delete">
                                    <img src="{$theme_images_dir}icons/16x16/small_edit.gif" onMouseOver="ddrivetip('Delete the Note');" onMouseOut="hideddrivetip();">                                                 
                                </a>
                            {/if}
                        </td>
                    </tr>
                    <tr>
                        <td>{$customer_notes[i].NOTE}<br></td>
                    </tr>
                </table>    
            </td>    
        </tr>
    </table>
{/section}