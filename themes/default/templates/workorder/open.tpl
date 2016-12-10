<!-- open.tpl -->
<table width="100%" border="0" cellpadding="20" cellspacing="5">
    <tr>
        <td>            
            <table width="700" cellpadding="3" cellspacing="0" border="0" >
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{$translate_workorder_open_title}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" 
                            onMouseOver="ddrivetip('<b>{$translate_workorder_open_help_title|nl2br|regex_replace:"/[\r\t\n]/":" "}</b><hr><p>{$translate_workorder_open_help_content|nl2br|regex_replace:"/[\r\t\n]/":" "}</p>');" 
                            onMouseOut="hideddrivetip();">
                        </a>
                    </td>
                </tr>
                <tr>
                    <td class="menutd2" colspan="2">
                        <table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
                            <tr>
                                <td class="menutd">
                                    <table width="100%" border="0" cellpadding="10" cellspacing="0">
                                        <tr>
                                            <td><a name="new"></a>{include file="workorder/blocks/open_new_workorders_block.tpl"}</td>
                                        </tr>
                                        <tr>
                                            <td><a name="assigned"></a>{include file="workorder/blocks/open_assigned_workorders_block.tpl"}</td>
                                        </tr>
                                        <tr>
                                            <td><a name="awaiting"></a>{include file="workorder/blocks/open_awaiting_parts_workorders_block.tpl"}</td>
                                        </tr>
                                        <tr>
                                            <td><a name="payment"></a>{include file="workorder/blocks/open_unpaid_workorders_block.tpl"}</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>