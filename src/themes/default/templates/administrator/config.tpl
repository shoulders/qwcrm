<!-- edit.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<script src="{$theme_js_dir}tinymce/tinymce.min.js"></script>
<script>{include file="`$theme_js_dir_finc`editor-config.js"}</script>
<script>
    $(document).ready(function() {
        
        // Cron System dropdown - only change option if user confirms
        $('#cronjob_system').focus(function() {            
            cronjobSystemPreviousVal = $(this).val();
        } ).change(function() {             
            if(confirm('{t}Are you sure you want to change the Cron system used by QWcrm?{/t}')) {
                cronjobConfigureOnscreen();
                return true;
            } else {
                $(this).val(cronjobSystemPreviousVal);
                return false;
            }            
        } );                
        cronjobConfigureOnscreen();
    } );
    
    // Refresh Cron information and settings displayed onscreen
    function cronjobConfigureOnscreen() {
        let cronjobSystem = $('#cronjob_system').val();
        let cronjobPseudoInterval = $('#pseudo_interval_row');
        let cronjobServerSettings = $('#server_cron_settings_row');        
        if(cronjobSystem === '0') {
            cronjobPseudoInterval.hide('fast');
            cronjobServerSettings.hide('fast');
        }
        if(cronjobSystem === 'pseudo') {
            cronjobPseudoInterval.show('fast');
            cronjobServerSettings.hide('fast');
        }
        if(cronjobSystem === 'real') {
           cronjobPseudoInterval.hide('fast');
           cronjobServerSettings.show('fast');
        }        
    }
</script>

<form method="post" action="index.php?component=administrator&page_tpl=config">                   
    <table width="600" cellpadding="5" cellspacing="0" border="0">
        <tr>
            <td class="menuhead2" width="80%">&nbsp;{t}QWcrm Config Settings{/t}</td>
            <td class="menuhead2" width="20%" align="right" valign="middle">  <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}ADMINISTRATOR_CONFIG_HELP_TITLE{/t}</strong></div><hr><div>{t escape=js}ADMINISTRATOR_CONFIG_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();"></td>
        </tr>
        <tr>
            <td class="menutd2" colspan="2">
                <table width="600" class="olotable" cellpadding="5" cellspacing="0" border="0">
                    
                    <!-- Database --> 
                    
                    <tr class="row2">
                        <td class="menuhead" colspan="2" width="100%">&nbsp;{t}Database{/t}</td>
                    </tr>
                    
                    <tr>
                        <td align="right"><b>{t}Host{/t}</b> <span style="color: blue">*</span></td>
                        <td>
                            {$qwcrm_config.db_host}
                            {*<input name="qform[db_host]" class="olotd5" size="25" value="{$qwcrm_config.db_host}" type="text" maxlength="50" placeholder="localhost" required onkeydown="return onlyAlphaNumeric(event);" readonly/>*}
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}Host{/t}</strong></div><hr><div>{t escape=js}The hostname for your database entered during the installation process. Do not edit this field unless absolutely necessary (eg the transfer of the database to a new hosting provider).{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><b>{t}Database Name{/t}</b> <span style="color: blue">*</span></td>
                        <td>
                            {$qwcrm_config.db_name}
                            {*<input name="qform[db_name]" class="olotd5" size="25" value="{$qwcrm_config.db_name}" type="text" maxlength="50" required onkeydown="return onlyMysqlDatabaseName(event);" readonly/>*}
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}Database Name{/t}</strong></div><hr><div>{t escape=js}The name for your database entered during the installation process. Do not edit this field unless absolutely necessary (eg the transfer of the database to a new hosting provider).{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>                    
                    <tr>
                        <td align="right"><b>{t}Database Username{/t}</b> <span style="color: blue">*</span></td>
                        <td>
                            {$qwcrm_config.db_user}
                            {*<input name="qform[db_user]" class="olotd5" size="25" value="{$qwcrm_config.db_user}" type="text" maxlength="50" required onkeydown="return onlyAlphaNumeric(event);" readonly/>*}
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}Database Username{/t}</strong></div><hr><div>{t escape=js}The username for access to your database entered during the installation process. Do not edit this field unless absolutely necessary (eg the transfer of the database to a new hosting provider).{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>                    
                    <tr>
                        <td align="right"><b>{t}Database Password{/t}</b> <span style="color: blue">*</span></td>
                        <td>
                            ********
                            {*<input name="qform[db_pass]" class="olotd5" size="25" value="{$qwcrm_config.db_pass}" type="password" maxlength="20" onkeydown="return onlyAlphaNumeric(event);" readonly/>*}
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}Database Password{/t}</strong></div><hr><div>{t escape=js}The password for access to your database entered during the installation process. Do not edit this field unless absolutely necessary (eg the transfer of the database to a new hosting provider).{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>               
                    <tr>
                        <td align="right"><b>{t}Database Tables Prefix{/t}</b> <span style="color: blue">*</span></td>
                        <td>
                            {$qwcrm_config.db_prefix}
                            {*<input name="qform[db_prefix]" class="olotd5" size="6" value="{$qwcrm_config.db_prefix}" type="text" maxlength="6" required onkeydown="return onlyMysqlDatabaseName(event);" readonly/>*}
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}Database Tables Prefix{/t}</strong></div><hr><div>{t escape=js}The prefix used for your database tables, created during the installation process. Do not edit this field unless absolutely necessary (eg the transfer of the database to a new hosting provider).{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>                
                    
                    <!-- Other -->
                    
                    <tr class="row2">
                        <td class="menuhead" colspan="2" width="100%">&nbsp;{t}Other{/t}</td>
                    </tr>
                    
                    <tr>
                        <td align="right"><b>{t}Search Engine Friendly URLs{/t}</b></td>
                        <td>
                            <select class="olotd5" id="sef" name="qform[sef]">                                                       
                                <option value="0"{if $qwcrm_config.sef == '0'} selected{/if}>{t}No{/t}</option>
                                <option value="1"{if $qwcrm_config.sef == '1'} selected{/if}>{t}Yes{/t}</option>
                            </select>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}Gzip Page Compression{/t}</strong></div><hr><div>{t escape=js}Select if the URLs are optimised for Search Engines.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>                        
                    </tr>                    
                    <tr>
                        <td align="right"><b>{t}Gzip Page Compression{/t}</b></td>
                        <td>
                            <select class="olotd5" id="gzip" name="qform[gzip]">                                                       
                                <option value="0"{if $qwcrm_config.gzip == '0'} selected{/if}>{t}No{/t}</option>
                                <option value="1"{if $qwcrm_config.gzip == '1'} selected{/if}>{t}Yes{/t}</option>
                            </select>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}Gzip Page Compression{/t}</strong></div><hr><div>{t escape=js}Compress buffered output if supported.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>                        
                    </tr>                      
                    <tr>
                        <td align="right"><b>{t}Site Maintenance{/t}</b></td>
                        <td>
                            <select class="olotd5" id="maintenance" name="qform[maintenance]">                                                       
                                <option value="0"{if $qwcrm_config.maintenance == '0'} selected{/if}>{t}No{/t}</option>
                                <option value="1"{if $qwcrm_config.maintenance == '1'} selected{/if}>{t}Yes{/t}</option>
                            </select>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}Site Maintenance{/t}</strong></div><hr><div>{t escape=js}Select if access to the Site Frontend is available. If Yes, the Frontend will display a message via the maintenance page.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><b>{t}Theme Name{/t}</b> <span style="color: blue">*</span></td>
                        <td>
                            {$qwcrm_config.theme_name}
                            <input name="qform[theme_name]" class="olotd5" value="{$qwcrm_config.theme_name}" maxlength="20" required onkeydown="return onlyAlphaNumeric(event);" hidden/>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}Theme Name{/t}</strong></div><hr><div>{t escape=js}This is the theme QWcrm is using. The ability to change the theme is not currently available, although the templating code is all present.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><b>{t}Default Language{/t}</b></td>
                        <td>
                            <select class="olotd5" id="default_language" name="qform[default_language]">
                                {section name=l loop=$available_languages}  
                                    <option value="{$available_languages[l]}"{if $available_languages[l] == $qwcrm_config.default_language} selected{/if}>{t}{$available_languages[l]}{/t}</option>
                                {/section} 
                            </select>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}Default Language{/t}</strong></div><hr><div>{t escape=js}This is the default language QWcrm uses. If \'Autodetect Language\' is disabled or the user\'s language is not availabe then this language will be used to display translations. If for some reason this fails, the language will default to english. This only works if your PHP enviroment supports Internationalization.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><b>{t}Autodetect Language{/t}</b></td>
                        <td>
                            <select class="olotd5" id="autodetect_language" name="qform[autodetect_language]">                                                       
                                <option value="0"{if $qwcrm_config.autodetect_language == '0'} selected{/if}>{t}No{/t}</option>
                                <option value="1"{if $qwcrm_config.autodetect_language == '1'} selected{/if}>{t}Yes{/t}</option>
                            </select>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}Autodetect Language{/t}</strong></div><hr><div>{t escape=js}If enabled QWcrm will try to set the language based on your browser. If your language is not available then the default language will be used.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><b>{t}Google Server{/t}</b> <span style="color: #ff0000">*</span></td>                                                          
                        <td>
                            <input name="qform[google_server]" class="olotd5" value="{$qwcrm_config.google_server}" size="50" type="text" maxlength="50" placeholder="https://www.google.com/" pattern="^https://.+" required onkeydown="return onlyURL(event);"/>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}Google Server{/t}</strong></div><hr><div>{t escape=js}This is your regionally prefered Google website. It is currently used for Google Maps to generate directions.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr> 
                    
                    <!-- Mail Settings -->
                    
                    <tr class="row2">
                        <td class="menuhead" colspan="2" width="100%">&nbsp;{t}Mail Settings{/t}</td>
                    </tr>                                                        

                        <!-- Common -->
                        
                    <tr>
                        <td align="right"><b>{t}Send Mail{/t}:</b></td>
                        <td>
                            <select class="olotd5" name="qform[email_online]">                                                                    
                                <option value="0" {if $qwcrm_config.email_online == '0'} selected{/if}>No</option>
                                <option value="1" {if $qwcrm_config.email_online == '1'} selected{/if}>Yes</option>                                                                    
                            </select>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}Send Mail{/t}</strong></div><hr><div>{t escape=js}Select Yes to turn on mail sending, select No to turn off mail sending. Warning: It is advised to put the site offline when disabling the mail function!{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><b>{t}Mailer{/t}:</b></td>
                        <td>
                            <select class="olotd5" name="qform[email_mailer]">
                                <option value="phpmail" {if $qwcrm_config.email_mailer == 'phpmail'} selected{/if}>{t}PHP Mail{/t}</option>
                                <option value="sendmail" {if $qwcrm_config.email_mailer == 'sendmail'} selected{/if}>Sendmail</option>
                                <option value="smtp" {if $qwcrm_config.email_mailer == 'smtp'} selected{/if}>SMTP</option>                                                                    
                            </select>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}Mailer{/t}</strong></div><hr><div>{t escape=js}Select which mailer for the delivery of site email. Sendmail only works on Linux/UNIX.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><b>{t}From Email{/t}:</b> <span style="color: #ff0000">*</span></td> 
                        <td>
                            <input name="qform[email_mailfrom]" class="olotd5" size="55" value="{$qwcrm_config.email_mailfrom}" type="email" maxlength="50" placeholder="no-reply@quantumwarp.com" required onkeydown="return onlyEmail(event);">
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}From Email{/t}</strong></div><hr><div>{t escape=js}The email address that will be used to send site email.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><b>{t}From Name{/t}:</b></td>
                        <td>
                            <input name="qform[email_fromname]" class="olotd5" size="25" value="{$qwcrm_config.email_fromname}" type="text" maxlength="20" placeholder="QuantumWarp" onkeydown="return onlyName(event);">
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}From Name{/t}</strong></div><hr><div>{t escape=js}Text displayed in the header &quot;From:&quot; field when sending a site email. Usually the site name.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>                        
                    </tr>
                    <tr>
                        <td align="right"><b>{t}Reply To Email{/t}:</b></td>
                        <td>
                            <input name="qform[email_replyto]" class="olotd5" size="55" value="{$qwcrm_config.email_replyto}" type="email" maxlength="50" placeholder="no-reply@quantumwarp.com" onkeydown="return onlyEmail(event);">
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}Reply To Email{/t}</strong></div><hr><div>{t escape=js}The email address that will be used to receive end user(s) reply.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>                        
                    </tr>
                    <tr>
                        <td align="right"><b>{t}Reply To Name{/t}:</b></td>
                        <td>
                            <input name="qform[email_replytoname]" class="olotd5" size="25" value="{$qwcrm_config.email_replytoname}" type="text" maxlength="20" placeholder="QuantumWarp" onkeydown="return onlyName(event);">
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}Reply To Name{/t}</strong></div><hr><div>{t escape=js}Text displayed in the header &quot;To:&quot; field when end user(s) replying to received email.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>                                                        

                        <!-- Sendmail -->

                    <tr>
                        <td colspan="2" width="100%">&nbsp;</td>
                    </tr>

                    <tr>
                        <td align="right"><b>{t}Sendmail Path{/t}:</b></td>
                        <td>
                            <input name="qform[email_sendmail_path]" class="olotd5" size="55" value="{$qwcrm_config.email_sendmail_path}" type="text" maxlength="100" placeholder="/usr/sbin/sendmail" onkeydown="return onlyFilePath(event);">
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}Sendmail Path{/t}</strong></div><hr><div>{t escape=js}Enter the path to the sendmail program folder on the host server.<br/>This is only needed when using sendmail and usually does not need to be changed.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>

                        <!-- SMTP -->

                    <tr>
                        <td colspan="2" width="100%">&nbsp;</td>
                    </tr>

                    <tr>
                        <td align="right"><b>{t}SMTP Host{/t}:</b></td>
                        <td>
                            <input name="qform[email_smtp_host]" class="olotd5" size="55" value="{$qwcrm_config.email_smtp_host}" type="text" maxlength="50" placeholder="mail.quantumwarp.com" onkeydown="return onlyURL(event);">
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}SMTP Host{/t}</strong></div><hr><div>{t escape=js}Enter the name of the SMTP host.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><b>{t}SMTP Port{/t}:</b></td>
                        <td>
                            <input name="qform[email_smtp_port]" class="olotd5" size="5" value="{$qwcrm_config.email_smtp_port}" type="text" maxlength="7" onkeydown="return onlyNumber(event);">
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}SMTP Port{/t}</strong></div><hr><div>{t escape=js}Enter the port number of the SMTP server QWcrm will use to send emails. Usually:<br /><br />- 25 or 26 when using an unsecure mail server.<br /><br />- 465 when using a secure server with SMTPS.<br /><br />- 25, 26 or 587 when using a secure server with SMTP with STARTTLS extension.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><b>{t}SMTP Security{/t}:</b></td>
                        <td>
                            <select class="olotd5" name="qform[email_smtp_security]">
                                <option value="" {if !$qwcrm_config.email_smtp_security} selected{/if}>{t}None{/t}</option>
                                <option value="ssl" {if $qwcrm_config.email_smtp_security == 'ssl' } selected{/if}>SSL/TLS</option>
                                <option value="tls" {if $qwcrm_config.email_smtp_security == 'tls' } selected{/if}>STARTTLS</option>                                                                    
                            </select>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}SMTP Security{/t}</strong></div><hr><div>{t escape=js}Select the security model of the SMTP server QWcrm will use to send emails.<br /><br />- <strong>None</strong>: No encryption<br /><br />- <strong>SSL/TLS</strong> for SMTPS: This specifies that encryption should be explicitly used. The strongest available cipher will be used (SSL/TLS/TLSv1.2).<br /><br />- <strong>STARTTLS</strong> for SMTP with STARTTLS extension: This allows an encrypted connection be brought up over a normally unencrypted email port.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><b>{t}SMTP Authentication{/t}:</b></td>
                        <td>
                            <select class="olotd5" name="qform[email_smtp_auth]">                                                                    
                                <option value="0" {if $qwcrm_config.email_smtp_auth == '0' } selected{/if}>No</option>
                                <option value="1" {if $qwcrm_config.email_smtp_auth == '1' } selected{/if}>Yes</option>                                                                    
                            </select>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}SMTP Authentication{/t}</strong></div><hr><div>{t escape=js}Select Yes if your SMTP Host requires SMTP Authentication.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>                                                       
                    <tr>
                        <td align="right"><b>{t}SMTP Username{/t}:</b></td>
                        <td>
                            <input name="qform[email_smtp_username]" class="olotd5" size="55" value="{$qwcrm_config.email_smtp_username}" type="text" maxlength="50" onkeydown="return onlyUsername(event);">
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}SMTP Username{/t}</strong></div><hr><div>{t escape=js}Enter the username for access to the SMTP host.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><b>{t}SMTP Password{/t}:</b></td>
                        <td>
                            <input name="qform[email_smtp_password]" class="olotd5" size="25" value="{$qwcrm_config.email_smtp_password}" type="password" maxlength="20" onkeydown="return onlyPassword(event);">
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}SMTP Password{/t}</strong></div><hr><div>{t escape=js}Enter the password for the SMTP host.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>
                    
                        <!-- Send Test Mail -->
                        
                    <tr>
                        <td colspan="2" width="100%">&nbsp;</td>
                    </tr>
                    
                    <tr>
                        <td align="right">&nbsp;</td>
                        <td>                                                                                                                   
                            <button type="button" onclick="$.ajax( { url:'index.php?component=administrator&page_tpl=config&send_test_mail=true', success: function(data) { $('body').append(data); } } );">{t}Send Test Mail{/t}</button>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}Send Test Mail{/t}</strong></div><hr><div>{t escape=js}You must save your changes before using this as the test uses the saved settings not those on the page.<br><br>The email will be sent to the logged in user\'s email address{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>
                    
                    <!-- Cronjobs -->
                    
                    <tr class="row2">
                        <td class="menuhead" colspan="2" width="100%">&nbsp;{t}Cronjob{/t}</td>
                    </tr>
                    
                    <tr>
                        <td align="right"><b>{t}System{/t}</b></td>
                        <td>
                            <select class="olotd5" id="cronjob_system" name="qform[cronjob_system]">                                                       
                                <option value="0"{if $qwcrm_config.cronjob_system == '0'} selected{/if}>{t}None{/t}</option>
                                <option value="pseudo"{if $qwcrm_config.cronjob_system == 'pseudo'} selected{/if}>{t}Pseudo{/t}</option>*}
                                <option value="real"{if $qwcrm_config.cronjob_system == 'real'} selected{/if}>{t}Real{/t}</option>
                            </select>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}Cron{/t}</strong></div><hr><div>{t escape=js}The System Cron automates tasks within QWcrm and is required by QWcrm so time based actions happen when they are required such as invoice reminders.<br /><br />Do <strong>NOT</strong> turn this feature off unless you have been advised to as certain things might break or not function as expected.<br /><br />There are two types of cron, <strong>Pseudo</strong> and <strong>Real</strong>, which do the same job but from different starting points.<br /><br /><strong>Pseudo:</strong> When you do not have access to a cron system on your server you can use QWcrm to trigger cron events on page loads. This can cause page loading to become slow on large sites.<br /><br /><strong>Real:</strong> This is the traditional option where your server handles cron events but requires your server to support this feature. This is the <strong>preferred</strong> option.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>
                    <tr id="pseudo_interval_row">
                        <td align="right"><b>{t}Pseudo Interval{/t}</b> <span style="color: #ff0000">*</span></td>
                        <td>
                            <input name="qform[cronjob_pseudo_interval]" class="olotd5" size="5" value="{$qwcrm_config.cronjob_pseudo_interval}" type="text" maxlength="20" placeholder="15" required onkeydown="return onlyNumber(event);"/>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}Pseudo Cron Interval{/t}</strong></div><hr><div>{t escape=js}When using Cron system type `Pseudo` you need to tell QWcrm how often the system cron should be run. This setting does not guarantee that the system cron will be run on a regular basis or at the time specified because it depends on page loads but should offer an affective alternative to a real cron system.<br /><br />This setting is defined in minutes.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>
                    <tr id="server_cronjob_settings_row">
                        <td align="right"><b>{t}Server Cron Settings{/t}</b><br>({$server_os})</td>
                        {if $server_os === 'Windows'}
                            <td>                            
                                <p>{t}You need to add the following schedule to your Windows Task Scheduler. It will run the QWcrm cron every 15 minutes.{/t}</p>
                                <ul>
                                    <li>{t}Name{/t}: <strong>QWCron</strong></li>                               
                                    <li>{t}Trigger{/t}:</li>
                                    <ul>
                                        <li><strong>{t}On a schedule{/t}</strong></li>
                                        <li><strong>{t}Daily{/t}</strong></li>
                                        <li><strong>{t}Start any day in the future with a time of 00:00:00{/t}</strong></li>
                                        <li><strong>{t}Repeat task every 15 minutes for a duration of 1 day{/t}</strong></li>
                                    </ul>                                
                                    <li>{t}Action{/t}:</li>
                                    <ul>
                                        <li>{t}Action{/t}: <strong>{t}Start a program{/t}</strong></li>
                                        <li>{t}Program/script{/t}: <strong>php</strong></li>
                                        <li>{t}Add arguments{/t}: <strong>cron.php</strong></li>
                                        <li>{t}Start in{/t}: <strong>{$qwcrm_physical_path}</strong></li>
                                    </ul>
                                </ul>                            
                            </td>
                        {else}
                            <td>                            
                                <p>{t}You need to add the following cron command to your server. It will run the QWcrm cron every 15 minutes.{/t}</p>
                                <p>{t}Make sure your provider allows for the cronjob to be run this often. Most providers will allow this frequency.{/t}</p>
                                <p><strong>*/15 * * * * {$qwcrm_physical_path}cron.php</strong></p>                            
                            </td>
                        {/if}
                    </tr> 
                        
                    <!-- Security -->
                    
                    <tr class="row2">
                        <td class="menuhead" colspan="2" width="100%">&nbsp;{t}Security{/t}</td>
                    </tr>
                    
                    <tr>
                        <td align="right"><b>{t}Force SSL/HTTPS{/t}</b></td>
                        <td>
                            <select class="olotd5" id="force_ssl" name="qform[force_ssl]">                                                       
                                <option value="0"{if $qwcrm_config.force_ssl == '0'} selected{/if}>{t}None{/t}</option>
                                {*<option value="1"{if $qwcrm_config.force_ssl == '1'} selected{/if}>{t}Administrator Only{/t}</option>*}
                                <option value="2"{if $qwcrm_config.force_ssl == '2'} selected{/if}>{t}Entire Site{/t}</option>
                            </select>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}Force SSL/HTTPS{/t}</strong></div><hr><div>{t escape=js}Force site access in the selected areas to occur only with HTTPS (encrypted HTTP connections with the https:// protocol prefix) and also force the use of secure cookies. Note, you must have HTTPS enabled on your server to utilise this option.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><b>{t}reCAPTCHA{/t}</b></td>
                        <td>
                            <select class="olotd5" id="recaptcha" name="qform[recaptcha]">                                                       
                                <option value="0"{if $qwcrm_config.recaptcha == '0'} selected{/if}>{t}No{/t}</option>
                                <option value="1"{if $qwcrm_config.recaptcha == '1'} selected{/if}>{t}Yes{/t}</option>
                            </select>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}reCAPTCHA{/t}</strong></div><hr><div>{t escape=js}Protect the login procedure with Google reCAPTCHA. reCAPTCHA is a free service that protects your website from spam and abuse. reCAPTCHA uses an advanced risk analysis engine and adaptive CAPTCHAs to keep automated software from engaging in abusive activities on your site. It does this while letting your valid users pass through with ease.{/t}</div>');" onMouseOut="hideddrivetip();">
                            <a href="https://developers.google.com/recaptcha/" target="_blank">{t}Get Keys{/t}</a>
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><b>{t}reCAPTCHA Site Key{/t}</b></td>
                        <td>
                            <input name="qform[recaptcha_site_key]" class="olotd5" size="45" value="{$qwcrm_config.recaptcha_site_key}" type="text" maxlength="40" onkeydown="return onlyAlphaNumeric(event);"/>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}reCAPTCHA Site Key{/t}</strong></div><hr><div>{t escape=js} The site key is used to invoke reCAPTCHA service on your site or mobile application.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><b>{t}reCAPTCHA Secret Key{/t}</b></td>
                        <td>
                            <input name="qform[recaptcha_secret_key]" class="olotd5" size="45" value="{$qwcrm_config.recaptcha_secret_key}" type="text" maxlength="40" onkeydown="return onlyAlphaNumeric(event);"/>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}reCAPTCHA Secret Key{/t}</strong></div><hr><div>{t escape=js}The secret key authorizes communication between your application backend and the reCAPTCHA server to verify the user\'s response. The secret key needs to be kept safe for security purposes.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>
                    
                    <!-- Session -->
                    
                    <tr class="row2">
                        <td class="menuhead" colspan="2" width="100%">&nbsp;{t}Session{/t}</td>
                    </tr>
                    
                    <tr>
                        <td align="right"><b>{t}Session Handler{/t}</b></td>
                        <td>
                            <select class="olotd5" id="session_handler" name="qform[session_handler]">                                                       
                                <option value="none"{if $qwcrm_config.session_handler == 'none'} selected{/if}>{t}None{/t}</option>
                                <option value="database"{if $qwcrm_config.session_handler == 'database'} selected{/if}>{t}Database{/t}</option>
                            </select>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}Session Handler{/t}</strong></div><hr><div>{t escape=js}The mechanism by which QWcrm identifies a User once they are connected to the website using non-persistent cookies. None/PHP is susceptible to garbage collection usually every 1440 seconds which means that inactive users after 1440 will be logged out and can loose data. The PHP garbage collection time is set by the server in php.ini by several settings, but the main one is \'session.gc_maxlifetime = 1440\'. {/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><b>{t}Session Lifetime{/t}</b> <span style="color: #ff0000">*</span></td>
                        <td>
                            <input name="qform[session_lifetime]" class="olotd5" size="25" value="{$qwcrm_config.session_lifetime}" type="text" maxlength="10" placeholder="15" required onkeydown="return onlyNumber(event);"/>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}Session Lifetime{/t}</strong></div><hr><div>{t escape=js}Auto log out a User after they have been inactive for the entered number of minutes. Do not set too high.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>                    
                    <tr>
                        <td align="right"><b>{t}Shared Session{/t}</b> <span style="color: blue">*</span></td>
                        <td>
                            <select class="olotd5" id="shared_session" name="qform[shared_session]">                                                       
                                <option value="0"{if $qwcrm_config.shared_session == '0'} selected{/if}>{t}No{/t}</option>
                                <option value="1"{if $qwcrm_config.shared_session == '1'} selected{/if} disabled>{t}Yes{/t}</option>
                            </select>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}Shared Session{/t}</strong></div><hr><div>{t escape=js}When enabled, a user\'s session is shared between the frontend and administrator sections of the site. Note that changing this value will invalidate all existing sessions on the site. This is not available when the \'Force HTTPS\' option is set to \'Administrator Only\'.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>
                    
                    <!-- Remember Me -->
                    
                    <tr class="row2">
                        <td class="menuhead" colspan="2" width="100%">&nbsp;{t}Remember Me{/t}</td>
                    </tr>
                                        
                    <tr>
                        <td align="right"><b>{t}Remember Me{/t}</b></td>
                        <td>
                            <select class="olotd5" id="remember_me" name="qform[remember_me]">                                                       
                                <option value="0"{if $qwcrm_config.remember_me == '0'} selected{/if}>{t}No{/t}</option>
                                <option value="1"{if $qwcrm_config.remember_me == '1'} selected{/if}>{t}Yes{/t}</option>
                            </select>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}Remember Me{/t}</strong></div><hr><div>{t escape=js}Provides remember me functionality so when a user logs in to QWcrm they can stay logged in even if they close the browser. This works by generatating an authentication cookie which has an Expiry Date set by the \'Cookie Lifetime\'.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><b>{t}Cookie Lifetime{/t}</b> <span style="color: #ff0000">*</span></td>
                        <td>
                            <input name="qform[cookie_lifetime]" class="olotd5" size="25" value="{$qwcrm_config.cookie_lifetime}" type="text" maxlength="10" placeholder="60" required onkeydown="return onlyNumber(event);"/>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}Cookie Lifetime{/t}</strong></div><hr><div>{t escape=js}The number of days until the authentication cookie will expire. Other factors may cause it to expire before this. Longer lengths are less secure.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><b>{t}Cookie Token Length{/t}</b> <span style="color: #ff0000">*</span></td>
                        <td>
                            <input name="qform[cookie_token_length]" class="olotd5" size="25" value="{$qwcrm_config.cookie_token_length}" type="text" maxlength="10" placeholder="16" required onkeydown="return onlyNumber(event);"/>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}Cookie Token Length{/t}</strong></div><hr><div>{t escape=js}The length of the key to use to encrypt the cookie. Longer lengths are more secure, but they will slow performance.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>
                    
                    <!-- Cookies -->
                    
                    <tr class="row2">
                        <td class="menuhead" colspan="2" width="100%">&nbsp;{t}Cookies{/t}</td>
                    </tr>
                    
                    <tr>
                        <td align="right"><b>{t}Cookie Domain{/t}</b></td>
                        <td>
                            <input name="qform[cookie_domain]" class="olotd5" size="55" value="{$qwcrm_config.cookie_domain}" type="text" maxlength="50" placeholder="quantumwarp.com" onkeydown="return onlyURL(event);"/>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}Cookie Domain{/t}</strong></div><hr><div>{t escape=js}Domain to use when setting session cookies. Precede domain with \'.\' if cookie should be valid for all subdomains.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><b>{t}Cookie Path{/t}</b></td>
                        <td>
                            <input name="qform[cookie_path]" class="olotd5" size="55" value="{$qwcrm_config.cookie_path}" type="text" maxlength="20" placeholder="qwcrm/" onkeydown="return onlyURL(event);"/>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}Cookie Path{/t}</strong></div><hr><div>{t escape=js}Path the cookie should be valid for.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>
                    
                    <!-- Logging -->
                    
                    <tr class="row2">
                        <td class="menuhead" colspan="2" width="100%">&nbsp;{t}Logging{/t}</td>
                    </tr>
                    
                    <tr>
                        <td align="right"><b>{t}Work Order History Notes{/t}</b></td>
                        <td>
                            <select class="olotd5" id="workorder_history_notes" name="qform[workorder_history_notes]">                                                       
                                <option value="0"{if $qwcrm_config.workorder_history_notes == '0'} selected{/if}>{t}No{/t}</option>
                                <option value="1"{if $qwcrm_config.workorder_history_notes == '1'} selected{/if}>{t}Yes{/t}</option>
                            </select>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}Work Order History Notes{/t}</strong></div><hr><div>{t escape=js}Enable work order history notes. This will log all activity related to a work order and display them in the work order details. This feature is useful for tracking how jobs are progressing.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>                                        
                    <tr>
                        <td align="right"><b>{t}Access Log{/t}</b></td>
                        <td>
                            <select class="olotd5" id="qwcrm_access_log" name="qform[qwcrm_access_log]">                                                       
                                <option value="0"{if $qwcrm_config.qwcrm_access_log == '0'} selected{/if}>{t}No{/t}</option>
                                <option value="1"{if $qwcrm_config.qwcrm_access_log == '1'} selected{/if}>{t}Yes{/t}</option>
                            </select>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}QWcrm Access Log{/t}</strong></div><hr><div>{t escape=js}Enable access logging for QWcrm. This will log all page accesses and store the data in the Access Log. This log file is in apache log format and can be found in the logs folder.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><b>{t}Activity Log{/t}</b></td>
                        <td>
                            <select class="olotd5" id="qwcrm_activity_log" name="qform[qwcrm_activity_log]">                                                       
                                <option value="0"{if $qwcrm_config.qwcrm_activity_log == '0'} selected{/if}>{t}No{/t}</option>
                                <option value="1"{if $qwcrm_config.qwcrm_activity_log == '1'} selected{/if}>{t}Yes{/t}</option>
                            </select>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}QWcrm Activity Log{/t}</strong></div><hr><div>{t escape=js}Enable activity logging for QWcrm. This will log all user activity from within QWcrm and store the data in the Activity Log. This log file is in apache log format and can be found in the logs folder.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><b>{t}Error Log{/t}</b></td>
                        <td>
                            <select class="olotd5" id="qwcrm_error_log" name="qform[qwcrm_error_log]">                                                       
                                <option value="0"{if $qwcrm_config.qwcrm_error_log == '0'} selected{/if}>{t}No{/t}</option>
                                <option value="1"{if $qwcrm_config.qwcrm_error_log == '1'} selected{/if}>{t}Yes{/t}</option>
                            </select>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}QWcrm Error Log{/t}</strong></div><hr><div>{t escape=js}Enable error logging for QWcrm. This will log all errors and store the data in the Error Log. This log file is in apache log format and can be found in the logs folder.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><b>{t}SQL Logging{/t}</b></td>
                        <td>
                            <select class="olotd5" id="qwcrm_sql_logging " name="qform[qwcrm_sql_logging]">                                                       
                                <option value="0"{if $qwcrm_config.qwcrm_sql_logging == '0'} selected{/if}>{t}No{/t}</option>
                                <option value="1"{if $qwcrm_config.qwcrm_sql_logging == '1'} selected{/if}>{t}Yes{/t}</option>
                            </select>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}QWcrm SQL Log{/t}</strong></div><hr><div>{t escape=js}Enable SQL logging for QWcrm. This attach the SQL query when present to the standard QWcrm Error Log. This is disabled by default because it can cause your logs to get large quickly.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>                    
                    <tr>
                        <td align="right"><b>{t}Email Error Log{/t}</b></td>
                        <td>
                            <select class="olotd5" id="qwcrm_email_error_log" name="qform[qwcrm_email_error_log]">                                                       
                                <option value="0"{if $qwcrm_config.qwcrm_email_error_log == '0'} selected{/if}>{t}No{/t}</option>
                                <option value="1"{if $qwcrm_config.qwcrm_email_error_log == '1'} selected{/if}>{t}Yes{/t}</option>
                            </select>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}QWcrm Email Error Log{/t}</strong></div><hr><div>{t escape=js}Enable email error logging for QWcrm. This will log all email system errors from within QWcrm and store the data in the Activity Log. This log file can be found in the logs folder.<br>This should only be used for diagnosing problems because the log file will grow in size quicky.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><b>{t}Email Transport Log{/t}</b></td>
                        <td>
                            <select class="olotd5" id="qwcrm_email_transport_log" name="qform[qwcrm_email_transport_log]">                                                       
                                <option value="0"{if $qwcrm_config.qwcrm_email_transport_log == '0'} selected{/if}>{t}No{/t}</option>
                                <option value="1"{if $qwcrm_config.qwcrm_email_transport_log == '1'} selected{/if}>{t}Yes{/t}</option>
                            </select>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}QWcrm Email Transport Log{/t}</strong></div><hr><div>{t escape=js}Enable email transport logging for QWcrm. This will log all email transport transactions from within QWcrm and store the data in the Email Transport Log. This log file can be found in the logs folder.<br>The log will show you the SMTP handshakes and other relevant information.<br>This should only be used for diagnosing problems because the log file will grow in size quicky.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr> 
                    
                    <!-- Error Reporting -->
                    
                    <tr class="row2">
                        <td class="menuhead" colspan="2" width="100%">&nbsp;{t}Error Reporting{/t}</td>
                    </tr>
                                        
                    <tr>
                        <td align="right"><b>{t}Error Reporting{/t}</b></td>
                        <td>
                            <select class="olotd5" id="error_reporting" name="qform[error_reporting]">                                
                                <option value="default"{if $qwcrm_config.error_reporting == 'default'} selected{/if}>{t}System Default{/t}</option>
                                <option value="none"{if $qwcrm_config.error_reporting == 'none'} selected{/if}>{t}None{/t}</option>
                                <option value="verysimple"{if $qwcrm_config.error_reporting == 'verysimple'} selected{/if}>{t}Very Simple{/t}</option>
                                <option value="simple"{if $qwcrm_config.error_reporting == 'simple'} selected{/if}>{t}Simple{/t}</option>
                                <option value="maximum"{if $qwcrm_config.error_reporting == 'maximum'} selected{/if}>{t}Maximum{/t}</option>
                                <option value="development"{if $qwcrm_config.error_reporting == 'development'} selected{/if}>{t}Development{/t}</option>                                
                            </select>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}Error Reporting{/t}</strong></div><hr><div>{t escape=js}Select the level of PHP reporting for your needs. Do not leave error reporting on for live sites as this can be a security risk.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><b>{t}Error Page Raw Output{/t}</b></td>
                        <td>
                            <select class="olotd5" id="error_page_raw_output" name="qform[error_page_raw_output]">                                                       
                                <option value="0"{if $qwcrm_config.error_page_raw_output == '0'} selected{/if}>{t}No{/t}</option>
                                <option value="1"{if $qwcrm_config.error_page_raw_output == '1'} selected{/if}>{t}Yes{/t}</option>
                            </select>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}Error Page Raw Output{/t}</strong></div><hr><div>{t escape=js}Normally when an error occurs the error page is display with the relevant information, however sometimes looping or white screens can occur and this option strips back all uneeded functionality so just the error data is shown to negate these issues. This is only needed for development or unless otherwise instructed. QWcrm Debug does not need to be enabled.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><b>{t}Whoops Error Handler{/t}</b></td>
                        <td>
                            <select class="olotd5" id="error_handler_whoops" name="qform[error_handler_whoops]">                                                       
                                <option value="0"{if $qwcrm_config.error_handler_whoops == '0'} selected{/if}>{t}No{/t}</option>
                                <option value="1"{if $qwcrm_config.error_handler_whoops == '1'} selected{/if}>{t}Yes{/t}</option>
                            </select>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}Whoops Error Handler{/t}</strong></div><hr><div>{t escape=js}Whoops will replace all of the custom QWcrm and PHP error handling systems. It provides a pretty error interface that helps you debug your web projects, but at heart its a simple yet powerful stacked error handling system.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>  
                    
                    <!-- Debugging -->
                    
                    <tr class="row2">
                        <td class="menuhead" colspan="2" width="100%">&nbsp;{t}Debugging{/t}</td>
                    </tr>
                                        
                    <tr>
                        <td align="right"><b>{t}QWcrm Debug{/t}</b></td>
                        <td>
                            <select class="olotd5" id="qwcrm_debug" name="qform[qwcrm_debug]">                                                       
                                <option value="0"{if $qwcrm_config.qwcrm_debug == '0'} selected{/if}>{t}No{/t}</option>
                                <option value="1"{if $qwcrm_config.qwcrm_debug == '1'} selected{/if}>{t}Yes{/t}</option>
                            </select>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}QWcrm Debug{/t}</strong></div><hr><div>{t escape=js}This on it\'s own gives basic information such as the page and module names aswell as their load time. QWcrm Debug needs to be enabled to access the rest of the debugging options.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>                    
                    <tr>
                        <td align="right"><b>{t}QWcrm Advanced Debug{/t}</b></td>
                        <td>
                            <select class="olotd5" id="qwcrm_advanced_debug" name="qform[qwcrm_advanced_debug]">                                                       
                                <option value="0"{if $qwcrm_config.qwcrm_advanced_debug == '0'} selected{/if}>{t}No{/t}</option>
                                <option value="1"{if $qwcrm_config.qwcrm_advanced_debug == '1'} selected{/if}>{t}Yes{/t}</option>
                            </select>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}QWcrm Advanced Debug{/t}</strong></div><hr><div>{t escape=js}This does a full varible and class dump from PHP. This is a security risk and should only be used for QWcrm development.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><b>{t}Smarty Debugging (QWcrm){/t}</b></td>
                        <td>
                            <select class="olotd5" id="qwcrm_smarty_debugging" name="qform[qwcrm_smarty_debugging]">                                                       
                                <option value="0"{if $qwcrm_config.qwcrm_smarty_debugging == '0'} selected{/if}>{t}No{/t}</option>
                                <option value="1"{if $qwcrm_config.qwcrm_smarty_debugging == '1'} selected{/if}>{t}Yes{/t}</option>
                            </select>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}Smarty Debugging (QWcrm){/t}</strong></div><hr><div>{t escape=js}Because of the way QWcrm is structured I needed to implement a custom method to call the Smarty Debugging template.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><b>{t}Smarty Debugging{/t}</b> <span style="color: blue">*</span></td>
                        <td>
                            <select class="olotd5" id="smarty_debugging" name="qform[smarty_debugging]">                                                       
                                <option value="0"{if $qwcrm_config.smarty_debugging == '0'} selected{/if}>{t}No{/t}</option>
                                <option value="1"{if $qwcrm_config.smarty_debugging == '1'} selected{/if} disabled>{t}Yes{/t}</option>
                            </select>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}Smarty Debugging{/t}</strong></div><hr><div>{t escape=js}This enables the debugging console. The console is a javascript popup window that informs you of the included templates, variables assigned from php and config file variables for the current script. It does not show variables assigned within a template with the { assign } function. This is the standard way to enable Smarty Debugging. This currently does not work.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><b>{t}Smarty Debugging Control{/t}</b> <span style="color: blue">*</span></td>
                        <td>
                            <select class="olotd5" id="smarty_debugging_ctrl" name="qform[smarty_debugging_ctrl]">                                
                                <option value="NONE"{if $qwcrm_config.smarty_debugging_ctrl == 'NONE'} selected{/if}>{t}None{/t}</option>
                                <option value="URL"{if $qwcrm_config.smarty_debugging_ctrl == 'URL'} selected{/if} disabled>{t}URL{/t}</option>
                            </select>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}Smarty Debugging Control{/t}</strong></div><hr><div>{t escape=js}This allows alternate ways to enable debugging. NONE means no alternate methods are allowed. URL means when the keyword SMARTY_DEBUG is found in the QUERY_STRING, debugging is enabled for that invocation of the script. If $debugging is TRUE, this value is ignored.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>
                    
                    <!-- Smarty -->
                    
                    <tr class="row2">
                        <td class="menuhead" colspan="2" width="100%">&nbsp;{t}Smarty{/t}</td>
                    </tr>                                        
                    
                    
                    <tr>
                        <td align="right"><b>{t}Force Compile{/t}</b></td>
                        <td>
                            <select class="olotd5" id="smarty_force_compile" name="qform[smarty_force_compile]">                                                       
                                <option value="0"{if $qwcrm_config.smarty_force_compile == '0'} selected{/if}>{t}No{/t}</option>
                                <option value="1"{if $qwcrm_config.smarty_force_compile == '1'} selected{/if}>{t}Yes{/t}</option>
                            </select>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}Force Compile{/t}</strong></div><hr><div>{t escape=js}This forces Smarty to (re)compile templates on every invocation. This setting overrides \'compile_check\'. By default this is FALSE. This is handy for development and debugging. It should never be used in a production environment. If \'caching\' is enabled, the cache file(s) will be regenerated every time. Compiling referenced here is the process of converting the Smarty templates into pure PHP code.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>
                    <tr>
                        <td align="right">&nbsp;</td>
                        <td>                                                                                                                   
                            <button type="button" onclick="$.ajax( { url:'index.php?component=administrator&page_tpl=config&clear_smarty_compile=true', success: function(data) { $('body').append(data); } } );">{t}Clear Smarty Compile{/t}</button>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}Clear Smarty Compile{/t}</strong></div><hr><div>{t escape=js}This clears all of the Smarty compiled template files.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>
                    
                    
                    
                    <tr>
                        <td align="right"><b>{t}Force Cache{/t}</b> <span style="color: blue">*</span></td>
                        <td>
                            <select class="olotd5" id="smarty_force_cache" name="qform[smarty_force_cache]">                                                       
                                <option value="0"{if $qwcrm_config.smarty_force_cache == '0'} selected{/if}>{t}No{/t}</option>
                                <option value="1"{if $qwcrm_config.smarty_force_cache== '1'} selected{/if} disabled>{t}Yes{/t}</option>
                            </select>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}Force Cache{/t}</strong></div><hr><div>{t escape=js}This forces Smarty to (re)cache templates on every invocation. It does not override the $caching level, but merely pretends the template has never been cached before. {/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><b>{t}Caching{/t}</b> <span style="color: blue">*</span></td>
                        <td>
                            <select class="olotd5" id="smarty_caching" name="qform[smarty_caching]">                                                       
                                <option value="0"{if $qwcrm_config.smarty_caching == '0'} selected{/if}>{t}None{/t}</option>
                                <option value="1"{if $qwcrm_config.smarty_caching == '1'} selected{/if} disabled>{t}Current{/t}</option>
                                <option value="2"{if $qwcrm_config.smarty_caching == '2'} selected{/if} disabled>{t}Saved{/t}</option>
                            </select>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}Caching{/t}</strong></div><hr><div>{t escape=js}This tells Smarty whether or not to cache the output of the templates to the Cache Directory. This is untested with QWcrm.<hr>A value of Smarty::CACHING_LIFETIME_CURRENT tells Smarty to use the current $cache_lifetime variable to determine if the cache has expired.<br><br>A value of Smarty::CACHING_LIFETIME_SAVED tells Smarty to use the $cache_lifetime value at the time the cache was generated. This way you can set the $cache_lifetime just before fetching the template to have granular control over when that particular cache expires.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>                    
                    <tr>
                        <td align="right"><b>{t}Cache Lifetime{/t}</b> <span style="color: blue">*</span></td>
                        <td>
                            <input name="qform[smarty_cache_lifetime]" class="olotd5" value="{$qwcrm_config.smarty_cache_lifetime}" type="text" maxlength="20" required onkeydown="return onlyNumber(event);" readonly/>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}Cache Lifetime{/t}</strong></div><hr><div>{t escape=js}This is the length of time in seconds that a template cache is valid. Once this time has expired, the cache will be regenerated.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><b>{t}Cache Modified Check{/t}</b> <span style="color: blue">*</span></td>
                        <td>
                            <select class="olotd5" id="smarty_cache_modified_check" name="qform[smarty_cache_modified_check]">                                                       
                                <option value="0"{if $qwcrm_config.smarty_cache_modified_check == '0'} selected{/if}>{t}No{/t}</option>
                                <option value="1"{if $qwcrm_config.smarty_cache_modified_check == '1'} selected{/if} disabled>{t}Yes{/t}</option>
                            </select>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}Cache Modified Check{/t}</strong></div><hr><div>{t escape=js}If set to TRUE, Smarty will respect the \'If-Modified-Since\' header sent from the client. If the cached file timestamp has not changed since the last visit, then a \'304: Not Modified\' header will be sent instead of the content. This works only on cached content without { insert } tags.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><b>{t}Cache Locking{/t}</b> <span style="color: blue">*</span></td>
                        <td>
                            <select class="olotd5" id="smarty_cache_locking" name="qform[smarty_cache_locking]">                                                       
                                <option value="0"{if $qwcrm_config.smarty_cache_locking == '0'} selected{/if}>{t}No{/t}</option>
                                <option value="1"{if $qwcrm_config.smarty_cache_locking == '1'} selected{/if} disabled>{t}Yes{/t}</option>
                            </select>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}Cache Locking{/t}</strong></div><hr><div>{t escape=js}Cache locking avoids concurrent cache generation. This means resource intensive pages can be generated only once, even if they\'ve been requested multiple times in the same moment.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><span style="color: blue">*</span></td>
                        <td>                                                                                                                   
                            <button type="button" onclick="$.ajax( { url:'index.php?component=administrator&page_tpl=config&clear_smarty_cache=true', success: function(data) { $('body').append(data); } } );" disabled>{t}Clear Smarty Cache{/t}</button>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}Clear Smarty Cache{/t}</strong></div><hr><div>{t escape=js}This clears the Smarty cache.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>
                    
                    <!-- Update -->
                    
                    <tr class="row2">
                        <td class="menuhead" colspan="2" width="100%">&nbsp;</td>
                    </tr> 
                    
                    <tr>
                        <td colspan="2">
                            <p><span style="color: #ff0000">*</span> {t}Mandatory{/t}</p>
                            <p><span style="color: blue">*</span> {t}Cannot Change{/t}</p>
                        </td>
                    </tr>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: center;">
                            <button class="olotd5" type="submit" name="submit" value="update">{t}Update{/t}</button>&nbsp;                            
                            <button type="button" class="olotd4" onclick="window.location.href='index.php';">{t}Cancel{/t}</button>
                        </td>
                    </tr> 
                    
                </table>
            </td>
        </tr>
    </table>                        
</form>