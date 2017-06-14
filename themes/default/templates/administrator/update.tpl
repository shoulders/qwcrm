<!-- update.tpl -->
<table width="100%" border="0" cellpadding="20" cellspacing="0">
    <tr>
        <td>
            <table width="700" cellpadding="5" cellspacing="0" border="0">                
                <tr>
                    <td class="menuhead2" width="80%">{t}Update Status{/t}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}ADMINISTRATOR_UPDATE_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}ADMINISTRATOR_UPDATE_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
                        </a>
                    </td>
                </tr>
                <tr>
                    <td class="menutd2">
                        <table width="100%" class="olotable" cellpadding="5" cellspacing="0" border="0">
                            <tr>
                                <td width="100%" valign="top">
                                    <p>{t}This page will show you updates for QWcrm{/t}</p>
                                    {if $status == 1}
                                        {t}Updates are available.{/t} {t}Please download{/t} <a href="{$file}">{$file}</a>
                                        
                                        and place it in the top directory of your QWcrm install. Once you unpack the file read the README and the INSTALL files for further instructions.<br><br>
                                        
                                        {t}Addtional information{/t}:<br>
                                        <b>{t}Current Version{/t}:</b> {$qwcrm_version}<br>
                                        <b>{t}Latest Version{/t}:</b> {$latest_version}<br>
                                        <b>{t}Date{/t}:</b> {$date}<br>
                                        <b>{t}File{/t}:</b> {$file}<br>
                                        {$update_message}
                                    {else}
                                        {t}No Updates Available{/t}
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