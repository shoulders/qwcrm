<!-- update.tpl -->
<table width="100%" border="0" cellpadding="20" cellspacing="0">
    <tr>
        <td>
            <table width="700" cellpadding="5" cellspacing="0" border="0">
                <tr>
                    <td class="menuhead2" width="100%">Update Status</td>
                </tr>
                <tr>
                    <td class="menutd2">
                        <table width="100%" class="olotable" cellpadding="5" cellspacing="0" border="0">
                            <tr>
                                <td width="100%" valign="top">
                                    <p>This page will show you updates for QWcrm</p>
                                    {if $status == 1}
                                        Updates are available. Please download <a href="{$file}">{$file}</a> and place it in the top directory of your 
                                        QWcrm install. Once you unpack the file read the README and the INSTALL files for further instructions.<br><br>
                                        Addtional information:<br>
                                        <b>Current Version:</b> {$qwcrm_version}<br>
                                        <b>Latest Version:</b> {$latest_version}<br>
                                        <b>Date:</b> {$date}<br>
                                        <b>File:</b> {$file}<br>
                                        {$update_message}
                                    {else}
                                        No Updates Available
                                    {/if}                                    
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