<!-- details_resolution_block.tpl -->
<table class="olotable" border="0" width="100%" cellpadding="0" cellspacing="0" >
    <tr>
        <td class="olohead">
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{t}Resolution{/t}</td>
                    <td class="menuhead2" width="20%" align="right">
                        <table cellpadding="2" cellspacing="2" border="0">
                            <tr>
                                <td width="33%" align="right">
                                    {if $workorder_details.status != 6}
                                        <a href="index.php?page=workorder:details_edit_resolution&workorder_id={$workorder_details.workorder_id}">
                                            <img src="{$theme_images_dir}icons/16x16/small_edit.gif" border="0" onMouseOver="ddrivetip('{t}Click to edit resolution{/t}');" onMouseOut="hideddrivetip();">
                                        </a>
                                    {/if}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td class="menutd">
            <table width="100%" cellpadding="4" cellspacing="0" border="0">
                <tr>
                    <td>                        
                        {if $workorder_details.closed_by != ''}
                            <p>
                                <b>{t}Closed by{/t}: </b>{$employee_details.display_name}<br>
                                <b>{t}Date{/t}: </b>{$workorder_details.close_date|date_format:$date_format}<br>
                            </p>
                        {/if}
                        <div>{$workorder_details.resolution}</div>                        
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>