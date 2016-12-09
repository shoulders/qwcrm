<!-- details_edit_comments.tpl - Edit Work Order Comments Page -->
<script src="{$theme_js_dir}tinymce/tinymce.min.js"></script>
<script src="{$theme_js_dir}editor-config.js"></script>

<table width="700" border="0" cellpadding="20" cellspacing="0">
    <tr>
        <td>
            <table width="700" cellpadding="5" cellspacing="0" border="0" >
                <tr>                    
                    <td class="menuhead2" width="80%">{$translate_workorder_details_edit_comments_title}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a><img src="{$theme_images_dir}icons/16x16/help.gif" border="0" 
                            onMouseOver="ddrivetip('<b>{$translate_workorder_details_edit_comments_help_title|nl2br|regex_replace:"/[\r\t\n]/":" "}</b><hr><p>{$translate_workorder_details_edit_comments_help_content|nl2br|regex_replace:"/[\r\t\n]/":" "}</p>');" 
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
                                    <form  action="?page=workorder:details_edit_comments" method="POST">
                                    <b>{$translate_workorder_details_comments_title}</b><br>
                                    <textarea class="olotd4" rows="15" cols="70" mce_editable="true" name="workorder_comments">{$workorder_comments}</textarea>
                                    <br>
                                    <input type="hidden" name="wo_id" value="{$wo_id}">
                                    <input class="olotd4" name="submit" value="{$translate_workorder_submit}" type="submit" />
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