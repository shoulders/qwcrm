<!-- status.tpl -->
<table width="100%" border="0" cellpadding="20" cellspacing="0">
    <tr>
        <td><!-- Begin Page -->
            <table width="700" cellpadding="5" cellspacing="0" border="0" >
                <tr>
                    <td class="menuhead2" width="80%">{$translate_workorder_status_title} - {$translate_workorder_status_update_work_order_status_for_work_order_id} #{$wo_id}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a><img src="{$theme_images_dir}icons/16x16/help.gif" border="0" 
                            onMouseOver="ddrivetip('<b>{$translate_workorder_status_help_title|nl2br|regex_replace:"/[\r\t\n]/":" "}</b><hr><p>{$translate_workorder_status_help_content|nl2br|regex_replace:"/[\r\t\n]/":" "}</p>');" 
                            onMouseOut="hideddrivetip();">
                        </a>
                    </td>
                </tr>                
                <tr>
                    <td class="menutd2" colspan="2">
                        {if $error_msg != ""}
                            {include file="core/error.tpl"}
                        {/if}
                        <table width="100%" class="olotable" cellpadding="5" cellspacing="0" border="0" >
                            <tr>
                                <td width="100%" valign="top" >
                                    <!-- Content Here -->
                                    <form action="index.php?page=workorder:status" method="POST" name="new_workorder_status" id="new_workorder_status">
                                        <input type="hidden" name="page" value="workorder:status">
                                        <input type="hidden" name="create_by" value="{$display_login}">
                                        <input type="hidden" name="wo_id" value="{$wo_id}">
                                        <p>&nbsp;</p>
                                        <b>{$translate_workorder_status_new_status}: </b>
                                        <select class="olotd4" name="status">                                            
                                            <option value="2">{$translate_workorder_assigned}</option>
                                            <option value="3">{$translate_workorder_waiting_for_parts}</option>
                                        </select>
                                        <p>&nbsp;</p>
                                        <p>&nbsp;</p>
                                        <input class="olotd4" name="submit" value="{$translate_workorder_submit}" type="submit" />
                                        <p>&nbsp;</p>                               
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