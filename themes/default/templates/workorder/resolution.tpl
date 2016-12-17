<!-- resolution.tpl -->
<script src="{$theme_js_dir}tinymce/tinymce.min.js"></script>
<script src="{$theme_js_dir}editor-config.js"></script>

<table width="100%" border="0" cellpadding="20" cellspacing="0">
    <tr>
        <td>
            <table width="700" cellpadding="5" cellspacing="0" border="0" >
                <tr>
                    <td class="menuhead2" width="80%">{$translate_workorder_details_edit_resolution_title}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<b>{$translate_workorder_details_edit_resolution_help_title|nl2br|regex_replace:"/[\r\t\n]/":" "}</b><hr><p>{$translate_workorder_details_edit_resolution_help_content|nl2br|regex_replace:"/[\r\t\n]/":" "}</p>');" onMouseOut="hideddrivetip();">                            
                        </a>
                    </td>
                </tr>
                <tr>
                    <td class="menutd2" colspan="2">                        
                        <table width="100%" class="olotable" cellpadding="5" cellspacing="0" border="0" >
                            <tr>
                                <td width="100%" valign="top">                                    
                                    <form  action="index.php?page=workorder:resolution" method="POST" name="close_workorder" id="close_workorder">
                                        <b>{$translate_workorder_details_resolution_title}</b><br>
                                        <textarea class="olotd4" rows="15" cols="70" name="workorder_resolution">{$workorder_resolution}</textarea>
                                        <br>
                                        <input type="hidden" name="page" value="workorder:resolution">
                                        <input type="hidden" name="created_by" value="{$login_display_name}">
                                        <input type="hidden" name="wo_id" value="{$wo_id}">
                                        <input type="submit" name="submitchangesonly" value="{$translate_workorder_details_edit_resolution_submit_changes_only}">
                                        <input type="submit" name="closewithoutinvoice" value="{$translate_workorder_details_edit_resolution_close_without_invoice}">
                                        <input type="submit" name="closewithinvoice" value="{$translate_workorder_details_edit_resolution_close_with_invoice}">
                                    </form>
                                    <br>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>