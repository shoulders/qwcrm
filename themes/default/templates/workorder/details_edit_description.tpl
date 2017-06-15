<!-- details_edit_description.tpl -->
<script src="{$theme_js_dir}tinymce/tinymce.min.js"></script>
<script src="{$theme_js_dir}editor-config.js"></script>
<script>{include file="`$theme_js_dir_finc`modules/workorder.js"}</script>

<table width="100%" border="0" cellpadding="20" cellspacing="0">
    <tr>
        <td>
            <table width="700" cellpadding="5" cellspacing="0" border="0" >
                <tr>                    
                    <td class="menuhead2" width="80%">{t}Edit Work Order Description{/t}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}WORKORDER_DETAILS_EDIT_DESCRIPTION_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}WORKORDER_DETAILS_EDIT_DESCRIPTION_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
                        </a>
                    </td>
                </tr>                
                <tr>
                    <td class="menutd2" colspan="2">                        
                        <table width="100%" class="olotable" cellpadding="5" cellspacing="0" border="0">
                            <tr>
                                <td width="100%" valign="top">                                                                      
                                    <form method="POST" action="index.php?page=workorder:details_edit_description" name="new_refund" id="new_refund">                                    
                                        <b>{t}Scope{/t}</b></br>
                                        <input name="workorder_scope" class="olotd4" size="20" value="{$workorder_scope}" type="text" maxlength="80" required onkeydown="return onlyAlphaNumeric(event);">
                                        <br>
                                        <br>
                                        <br>
                                        <b>{t}Description{/t}</b><br>
                                        <textarea name="workorder_description" class="olotd4 mceCheckForContent" rows="15" cols="70">{$workorder_description}</textarea>
                                        <br>
                                        <input type="hidden" name="workorder_id" value="{$workorder_id}">
                                        <button type="submit" name="submit" value="submit">{t}Submit{/t}</button>
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