<!-- details_edit_description.tpl -->
<script src="{$theme_js_dir}tinymce/tinymce.min.js"></script>
<script src="{$theme_js_dir}editor-config.js"></script>
<script>{include file="`$theme_js_dir_finc`modules/workorder.js"}</script>

<table width="100%" border="0" cellpadding="20" cellspacing="0">
    <tr>
        <td>
            <table width="700" cellpadding="5" cellspacing="0" border="0" >
                <tr>                    
                    <td class="menuhead2" width="80%">{$translate_workorder_details_edit_description_title}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<b>{$translate_workorder_details_edit_description_help_title|nl2br|regex_replace:"/[\r\t\n]/":" "}</b><hr><p>{$translate_workorder_details_edit_description_help_content|nl2br|regex_replace:"/[\r\t\n]/":" "}</p>');" onMouseOut="hideddrivetip();">                            
                        </a>
                    </td>
                </tr>                
                <tr>
                    <td class="menutd2" colspan="2">                        
                        <table width="100%" class="olotable" cellpadding="5" cellspacing="0" border="0">
                            <tr>
                                <td width="100%" valign="top">                                    
                                    {literal}                                    
                                    <form method="POST" action="index.php?page=workorder:details_edit_description" name="new_refund" id="new_refund">
                                    {/literal}
                                        <b>{$translate_workorder_scope}</b></br>
                                        <input name="workorder_scope" class="olotd4" size="20" value="{$workorder_scope}" type="text" maxlength="80" required onkeydown="return onlyAlphaNumeric(event);">
                                        <br>
                                        <br>
                                        <br>
                                        <b>{$translate_workorder_details_description_title}</b><br>
                                        <textarea name="workorder_description" class="olotd4 wysiwyg-checkforcontent" rows="15" cols="70">{$workorder_description}</textarea>
                                        <br>
                                        <input type="hidden" name="wo_id" value="{$wo_id}">
                                        <input class="olotd4" name="submit" value="{$translate_workorder_submit}" type="submit">
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