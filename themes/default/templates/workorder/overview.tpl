<!-- overview.tpl -->
<table width="100%" border="0" cellpadding="20" cellspacing="5">
    <tr>
        <td>            
            <table width="700" cellpadding="3" cellspacing="0" border="0">
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{t}Work Order Overview{/t}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}WORKORDER_OVERVIEW_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}WORKORDER_OVERVIEW_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
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
                                            <td>
                                                <a name="unassigned"></a>                                                
                                                {include file='workorder/blocks/overview_workorders_stats_block.tpl'}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <a name="unassigned"></a>                                                
                                                {include file='workorder/blocks/overview_workorders_unassigned_block.tpl'}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <a name="assigned"></a>
                                                {include file='workorder/blocks/overview_workorders_assigned_block.tpl'}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <a name="waiting_for_parts"></a>
                                                {include file='workorder/blocks/overview_workorders_waiting_for_parts_block.tpl'}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <a name="on_hold"></a>
                                                {include file='workorder/blocks/overview_workorders_on_hold_block.tpl'}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <a name="management"></a>
                                                {include file='workorder/blocks/overview_workorders_management_block.tpl'}
                                            </td>
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