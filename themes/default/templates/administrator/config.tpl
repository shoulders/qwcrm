<!-- edit.tpl -->
<script src="{$theme_js_dir}tinymce/tinymce.min.js"></script>
<script>{include file="`$theme_js_dir_finc`editor-config.js"}</script>}

<form method="post" action="index.php?page=administrator:config">                   
    <table width="600" cellpadding="5" cellspacing="0" border="0">
        <tr>
            <td class="menuhead2" width="80%">&nbsp;{t}QWcrm Config Settings{/t}</td>
            <td class="menuhead2" width="20%" align="right" valign="middle">  <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}ADMINISTRATOR_CONFIG_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}ADMINISTRATOR_CONFIG_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();"></td>
        </tr>
        <tr>
            <td class="menutd2">
                <table width="600" class="olotable" cellpadding="5" cellspacing="0" border="0">
                    
                    <!-- Database --> 
                    
                    <tr class="row2">
                        <td class="menuhead" colspan="5" width="100%">&nbsp;{t}Database{/t}</td>
                    </tr>
                    
                    <tr>
                        <td align="right"><b>{t}Host{/t}</b> <span style="color: #ff0000">*</span></td>
                        <td>
                            <input name="db_host" class="olotd5" size="25" value="{$qwcrm_config.db_host}" type="text" maxlength="20" required onkeydown="return onlyAlphaNumeric(event);"/>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}Host{/t}</strong></div><hr><div>{t escape=tooltip}The hostname for your database entered during the installation process. Do not edit this field unless absolutely necessary (eg the transfer of the database to a new hosting provider).{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><b>{t}Database Name{/t}</b> <span style="color: #ff0000">*</span></td>
                        <td>
                            <input name="db_name" class="olotd5" size="25" value="{$qwcrm_config.db_name}" type="text" maxlength="20" required onkeydown="return onlyMysqlDatabaseName(event);"/>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}Database Name{/t}</strong></div><hr><div>{t escape=tooltip}The name for your database entered during the installation process. Do not edit this field unless absolutely necessary (eg the transfer of the database to a new hosting provider).{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>                    
                    <tr>
                        <td align="right"><b>{t}Database Username{/t}</b> <span style="color: #ff0000">*</span></td>
                        <td>
                            <input name="db_user" class="olotd5" size="25" value="{$qwcrm_config.db_user}" type="text" maxlength="20" required onkeydown="return onlyAlphaNumeric(event);"/>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}Database Username{/t}</strong></div><hr><div>{t escape=tooltip}The username for access to your database entered during the installation process. Do not edit this field unless absolutely necessary (eg the transfer of the database to a new hosting provider).{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>                    
                    {*<tr>
                        <td align="right"><b>{t}Database Password{/t}</b></td>
                        <td>
                            <input name="db_pass" class="olotd5" size="25" value="{$qwcrm_config.db_pass}" type="password" maxlength="20" onkeydown="return onlyAlphaNumeric(event);"/>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}Database Password{/t}</strong></div><hr><div>{t escape=tooltip}The password for access to your database entered during the installation process. Do not edit this field unless absolutely necessary (eg the transfer of the database to a new hosting provider).{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>*}                
                    <tr>
                        <td align="right"><b>{t}Database Tables Prefix{/t}</b> <span style="color: #ff0000">*</span></td>
                        <td>
                            <input name="db_prefix" class="olotd5" size="6" value="{$qwcrm_config.db_prefix}" type="text" maxlength="6" required onkeydown="return onlyAlphaNumeric(event);"/>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}Database Tables Prefix{/t}</strong></div><hr><div>{t escape=tooltip}The prefix used for your database tables, created during the installation process. Do not edit this field unless absolutely necessary (eg the transfer of the database to a new hosting provider).{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>
                    
                    
                    <!-- Other -->
                    
                    <tr class="row2">
                        <td class="menuhead" colspan="5" width="100%">&nbsp;{t}Other{/t}</td>
                    </tr>
                    
                    <tr>
                        <td align="right"><b>{t}Gzip Page Compression{/t}</b></td>
                        <td>
                            <select class="olotd5" id="gzip" name="gzip">                                                       
                                <option value="0"{if $qwcrm_config.gzip == '0'} selected{/if}>{t}No{/t}</option>
                                <option value="1"{if $qwcrm_config.gzip == '1'} selected{/if}>{t}Yes{/t}</option>
                            </select>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}Gzip Page Compression{/t}</strong></div><hr><div>{t escape=tooltip}Compress buffered output if supported.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>                        
                    </tr>                    
                    <tr>
                        <td align="right"><b>{t}Site Maintenance{/t}</b></td>
                        <td>
                            <select class="olotd5" id="maintenance" name="maintenance">                                                       
                                <option value="0"{if $qwcrm_config.maintenance == '0'} selected{/if}>{t}No{/t}</option>
                                <option value="1"{if $qwcrm_config.maintenance == '1'} selected{/if}>{t}Yes{/t}</option>
                            </select>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}Site Maintenance{/t}</strong></div><hr><div>{t escape=tooltip}Select if access to the Site Frontend is available. If Yes, the Frontend will display a message via the maintenance page.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><b>{t}Theme Name{/t}</b></td>
                        <td>
                            {$qwcrm_config.theme_name}
                            <input name="theme_name" class="olotd5" value="{$qwcrm_config.theme_name}" type="hidden" maxlength="20" required onkeydown="return onlyAlphaNumeric(event);"/>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}Theme Name{/t}</strong></div><hr><div>{t escape=tooltip}This is the theme QWcrm is using. The ability to change the theme is not currently available, although the templating code is all present.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><b>{t}Default Language{/t}</b></td>
                        <td>
                            <select class="olotd5" id="default_language" name="default_language">                                                       
                                <option value="en_GB"{if $qwcrm_config.default_language == 'en_GB'} selected{/if}>{t}English{/t}</option>                                
                            </select>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}Default Language{/t}</strong></div><hr><div>{t escape=tooltip}This is the default language QWcrm uses. If \'Autodetect Language\' is disabled or the user\'s language is not availabe then this language will be used to display translations. If for some reason this fails, the language will default to english.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><b>{t}Autodetect Language{/t}</b></td>
                        <td>
                            <select class="olotd5" id="autodetect_language" name="autodetect_language">                                                       
                                <option value="0"{if $qwcrm_config.autodetect_language == '0'} selected{/if}>{t}No{/t}</option>
                                <option value="1"{if $qwcrm_config.autodetect_language == '1'} selected{/if}>{t}Yes{/t}</option>
                            </select>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}Autodetect Language{/t}</strong></div><hr><div>{t escape=tooltip}If enabled QWcrm will try to set the language based on your browser. If your language is not available then the default language will be used.{/t}</div>');" onMouseOut="hideddrivetip();">
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
                            <select class="olotd5" name="email_online">                                                                    
                                <option value="0" {if $qwcrm_config.email_online == '0' } selected{/if}>No</option>
                                <option value="1" {if $qwcrm_config.email_online == '1' } selected{/if}>Yes</option>                                                                    
                            </select>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}Send Mail{/t}</strong></div><hr><div>{t escape=tooltip}Select Yes to turn on mail sending, select No to turn off mail sending. Warning: It is advised to put the site offline when disabling the mail function!{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><b>{t}Mailer{/t}:</b></td>
                        <td>
                            <select class="olotd5" name="email_mailer">
                                <option value="phpmail" {if $qwcrm_config.email_mailer != '' } selected{/if}>{t}PHP Mail{/t}</option>
                                <option value="sendmail" {if $qwcrm_config.email_mailer == 'sendmail' } selected{/if}>Sendmail</option>
                                <option value="smtp" {if $qwcrm_config.email_mailer == 'smtp' } selected{/if}>SMTP</option>                                                                    
                            </select>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}Mailer{/t}</strong></div><hr><div>{t escape=tooltip}Select which mailer for the delivery of site email. Sendmail only works on Linux/UNIX.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><b>{t}From Email{/t}:</b></td>
                        <td>
                            <input name="email_mailfrom" class="olotd5" size="55" value="{$qwcrm_config.email_mailfrom}" type="email" maxlength="50" placeholder="no-reply@quantumwarp.com" onkeydown="return onlyEmail(event);">
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}From Email{/t}</strong></div><hr><div>{t escape=tooltip}The email address that will be used to send site email.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><b>{t}From Name{/t}:</b></td>
                        <td>
                            <input name="email_fromname" class="olotd5" size="25" value="{$qwcrm_config.email_fromname}" type="text" maxlength="20" onkeydown="return onlyAlpha(event);">
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}From Name{/t}</strong></div><hr><div>{t escape=tooltip}Text displayed in the header &quot;From:&quot; field when sending a site email. Usually the site name.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>                        
                    </tr>
                    <tr>
                        <td align="right"><b>{t}Reply To Email{/t}:</b></td>
                        <td>
                            <input name="email_replyto" class="olotd5" size="55" value="{$qwcrm_config.email_replyto}" type="email" maxlength="50" placeholder="no-reply@quantumwarp.com" onkeydown="return onlyEmail(event);">
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}Reply To Email{/t}</strong></div><hr><div>{t escape=tooltip}The email address that will be used to receive end user(s) reply.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>                        
                    </tr>
                    <tr>
                        <td align="right"><b>{t}Reply To Name{/t}:</b></td>
                        <td>
                            <input name="email_replytoname" class="olotd5" size="25" value="{$qwcrm_config.email_replytoname}" type="text" maxlength="20" onkeydown="return onlyAlpha(event);">
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}Reply To Name{/t}</strong></div><hr><div>{t escape=tooltip}Text displayed in the header &quot;To:&quot; field when end user(s) replying to received email.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>                                                        

                        <!-- Sendmail -->

                    <tr>
                        <td colspan="2" width="100%">&nbsp;</td>
                    </tr>

                    <tr>
                        <td align="right"><b>{t}Sendmail Path{/t}:</b></td>
                        <td>
                            <input name="email_sendmail_path" class="olotd5" size="55" value="{$qwcrm_config.email_sendmail_path}" type="text" maxlength="100" placeholder="/usr/sbin/sendmail" onkeydown="return onlyFilePath(event);">
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}Sendmail Path{/t}</strong></div><hr><div>{t escape=tooltip}Enter the path to the sendmail program folder on the host server.<br/>This is only needed when using sendmail and usually does not need to be changed.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>

                        <!-- SMTP -->

                    <tr>
                        <td colspan="2" width="100%">&nbsp;</td>
                    </tr>

                    <tr>
                        <td align="right"><b>{t}SMTP Host{/t}:</b></td>
                        <td>
                            <input name="email_smtp_host" class="olotd5" size="55" value="{$qwcrm_config.email_smtp_host}" type="text" maxlength="50" placeholder="quantumwarp.com" onkeydown="return onlyURL(event);">
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}SMTP Host{/t}</strong></div><hr><div>{t escape=tooltip}Enter the name of the SMTP host.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><b>{t}SMTP Port{/t}:</b></td>
                        <td>
                            <input name="email_smtp_port" class="olotd5" size="5" value="{$qwcrm_config.email_smtp_port}" type="text" maxlength="50" onkeydown="return onlyNumbers(event);">
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}SMTP Port{/t}</strong></div><hr><div>{t escape=tooltip}Enter the port number of the SMTP server QWcrm will use to send emails. Usually:<br />- 25 when using an unsecure mail server<br />- 465 when using a secure server with SMTPS<br />- 25 or 587 when using a secure server with SMTP with STARTTLS extension.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><b>{t}SMTP Security{/t}:</b></td>
                        <td>
                            <select class="olotd5" name="email_smtp_security">
                                <option value="" {if $qwcrm_config.email_smtp_security != '' } selected{/if}>{t}None{/t}</option>
                                <option value="ssl" {if $qwcrm_config.email_smtp_security == 'ssl' } selected{/if}>SSL</option>
                                <option value="tls" {if $qwcrm_config.email_smtp_security == 'tls' } selected{/if}>TLS</option>                                                                    
                            </select>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}SMTP Security{/t}</strong></div><hr><div>{t escape=tooltip}Select the security model of the SMTP server Joomla will use to send emails.<br />- None for no encryption<br />- SSL/TLS for SMTPS (usually on port 465)<br />- STARTTLS for SMTP with STARTTLS extension (usually on port 25 or port 587){/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><b>{t}SMTP Authentication{/t}:</b></td>
                        <td>
                            <select class="olotd5" name="email_smtp_auth">                                                                    
                                <option value="0" {if $qwcrm_config.email_smtp_auth == '0' } selected{/if}>No</option>
                                <option value="1" {if $qwcrm_config.email_smtp_auth == '1' } selected{/if}>Yes</option>                                                                    
                            </select>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}SMTP Authentication{/t}</strong></div><hr><div>{t escape=tooltip}Select Yes if your SMTP Host requires SMTP Authentication.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>                                                       
                    <tr>
                        <td align="right"><b>{t}SMTP Username{/t}:</b></td>
                        <td>
                            <input name="email_smtp_username" class="olotd5" size="55" value="{$qwcrm_config.email_smtp_username}" type="text" maxlength="50" onkeydown="return onlyUsername(event);">
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}SMTP Username{/t}</strong></div><hr><div>{t escape=tooltip}Enter the username for access to the SMTP host.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><b>{t}SMTP Password{/t}:</b></td>
                        <td>
                            <input name="email_smtp_password" class="olotd5" size="25" value="{$qwcrm_config.email_smtp_password}" type="password" maxlength="20" onkeydown="return onlyPassword(event);">
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}SMTP Password{/t}</strong></div><hr><div>{t escape=tooltip}Enter the password for the SMTP host.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>
                    
                        <!-- Send Test Mail -->
                        
                    <tr>
                        <td colspan="2" width="100%">&nbsp;</td>
                    </tr>
                    
                    <tr>
                        <td align="right">&nbsp;</td>
                        <td>                                                                                                                   
                            <button type="button" onClick="$.ajax( { url:'index.php?page=administrator:config&send_test_mail=true&theme=print', success: function(data) { $('body').append(data); } } );">{t}Send Test Mail{/t}</button>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}Send Test Mail{/t}</strong></div><hr><div>{t escape=tooltip}You must save your changes before using this as the test uses the saved settings not those on the page.<br><br>The email will be sent to the logged in user\'s email address{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>
                        
                    <!-- Security -->
                    
                    <tr class="row2">
                        <td class="menuhead" colspan="5" width="100%">&nbsp;{t}Security{/t}</td>
                    </tr>
                    
                    <tr>
                        <td align="right"><b>{t}Force SSL/HTTPS{/t}</b></td>
                        <td>
                            <select class="olotd5" id="force_ssl" name="force_ssl">                                                       
                                <option value="0"{if $qwcrm_config.force_ssl == '0'} selected{/if}>{t}None{/t}</option>
                                {*<option value="1"{if $qwcrm_config.force_ssl == '1'} selected{/if}>{t}Administrator Only{/t}</option>*}
                                <option value="1"{if $qwcrm_config.force_ssl == '2'} selected{/if}>{t}Entire Site{/t}</option>
                            </select>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}Force SSL/HTTPS{/t}</strong></div><hr><div>{t escape=tooltip}Force site access in the selected areas to occur only with HTTPS (encrypted HTTP connections with the https:// protocol prefix) and also force the use of secure cookies. Note, you must have HTTPS enabled on your server to utilise this option.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><b>{t}reCAPTCHA{/t}</b></td>
                        <td>
                            <select class="olotd5" id="recaptcha" name="recaptcha">                                                       
                                <option value="0"{if $qwcrm_config.recaptcha == '0'} selected{/if}>{t}No{/t}</option>
                                <option value="1"{if $qwcrm_config.recaptcha == '1'} selected{/if}>{t}Yes{/t}</option>
                            </select>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}reCAPTCHA{/t}</strong></div><hr><div>{t escape=tooltip}Protect the login procedure with Google reCAPTCHA. reCAPTCHA is a free service that protects your website from spam and abuse. reCAPTCHA uses an advanced risk analysis engine and adaptive CAPTCHAs to keep automated software from engaging in abusive activities on your site. It does this while letting your valid users pass through with ease.{/t}</div>');" onMouseOut="hideddrivetip();">
                            <a href="https://developers.google.com/recaptcha/" target="_blank">{t}Get Keys{/t}</a>
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><b>{t}reCAPTCHA Site Key{/t}</b></td>
                        <td>
                            <input name="recaptcha_site_key" class="olotd5" size="45" value="{$qwcrm_config.recaptcha_site_key}" type="text" maxlength="40" onkeydown="return onlyAlphaNumeric(event);"/>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}reCAPTCHA Site Key{/t}</strong></div><hr><div>{t escape=tooltip} The site key is used to invoke reCAPTCHA service on your site or mobile application.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><b>{t}reCAPTCHA Secret Key{/t}</b></td>
                        <td>
                            <input name="recaptcha_secret_key" class="olotd5" size="45" value="{$qwcrm_config.recaptcha_secret_key}" type="text" maxlength="40" onkeydown="return onlyAlphaNumeric(event);"/>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}reCAPTCHA Secret Key{/t}</strong></div><hr><div>{t escape=tooltip}The secret key authorizes communication between your application backend and the reCAPTCHA server to verify the user\'s response. The secret key needs to be kept safe for security purposes.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>
                    
                    <!-- Session -->
                    
                    <tr class="row2">
                        <td class="menuhead" colspan="5" width="100%">&nbsp;{t}Session{/t}</td>
                    </tr>
                    
                    <tr>
                        <td align="right"><b>{t}Session Handler{/t}</b></td>
                        <td>
                            <select class="olotd5" id="session_handler" name="session_handler">                                                       
                                <option value="none"{if $qwcrm_config.session_handler == 'none'} selected{/if}>{t}None{/t}</option>
                                <option value="database"{if $qwcrm_config.session_handler == 'database'} selected{/if}>{t}Database{/t}</option>
                            </select>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}Session Handler{/t}</strong></div><hr><div>{t escape=tooltip}The mechanism by which QWcrm identifies a User once they are connected to the website using non-persistent cookies. None/PHP is susceptible to garbage collection usually every 1440 seconds which means that inactive users after 1440 will be logged out and can loose data. The PHP garbage collection time is set by the server in php.ini by several settings, but the main one is \'session.gc_maxlifetime = 1440\'. {/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><b>{t}Session Lifetime{/t}</b> <span style="color: #ff0000">*</span></td>
                        <td>
                            <input name="session_lifetime" class="olotd5" size="25" value="{$qwcrm_config.session_lifetime}" type="text" maxlength="20" required onkeydown="return onlyNumbers(event);"/>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}Session Lifetime{/t}</strong></div><hr><div>{t escape=tooltip}Auto log out a User after they have been inactive for the entered number of minutes. Do not set too high.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>                    
                    {*<tr>
                        <td align="right"><b>{t}Shared Session{/t}</b></td>
                        <td>
                            <select class="olotd5" id="shared_session" name="shared_session">                                                       
                                <option value="0"{if $qwcrm_config.shared_session == '0'} selected{/if}>{t}No{/t}</option>
                                <option value="1"{if $qwcrm_config.shared_session == '1'} selected{/if}>{t}Yes{/t}</option>
                            </select>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}Shared Session{/t}</strong></div><hr><div>{t escape=tooltip}When enabled, a user\'s session is shared between the frontend and administrator sections of the site. Note that changing this value will invalidate all existing sessions on the site. This is not available when the \'Force HTTPS\' option is set to \'Administrator Only\'.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>*}
                    
                    <!-- Remember Me -->
                    
                    <tr class="row2">
                        <td class="menuhead" colspan="5" width="100%">&nbsp;{t}Remember Me{/t}</td>
                    </tr>
                                        
                    <tr>
                        <td align="right"><b>{t}Remember Me{/t}</b></td>
                        <td>
                            <select class="olotd5" id="remember_me" name="remember_me">                                                       
                                <option value="0"{if $qwcrm_config.remember_me == '0'} selected{/if}>{t}No{/t}</option>
                                <option value="1"{if $qwcrm_config.remember_me == '1'} selected{/if}>{t}Yes{/t}</option>
                            </select>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}Remember Me{/t}</strong></div><hr><div>{t escape=tooltip}Provides remember me functionality so when a user logs in to QWcrm they can stay logged in even if they close the browser. This works by generatating an authentication cookie which has an Expiry Date set by the \'Cookie Lifetime\'.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><b>{t}Cookie Lifetime{/t}</b> <span style="color: #ff0000">*</span></td>
                        <td>
                            <input name="cookie_lifetime" class="olotd5" size="25" value="{$qwcrm_config.cookie_lifetime}" type="text" maxlength="20" required onkeydown="return onlyNumbers(event);"/>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}Cookie Lifetime{/t}</strong></div><hr><div>{t escape=tooltip}The number of days until the authentication cookie will expire. Other factors may cause it to expire before this. Longer lengths are less secure.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><b>{t}Cookie Token Length{/t}</b> <span style="color: #ff0000">*</span></td>
                        <td>
                            <input name="cookie_token_length" class="olotd5" size="25" value="{$qwcrm_config.cookie_token_length}" type="text" maxlength="20" required onkeydown="return onlyNumbers(event);"/>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}Cookie Token Length{/t}</strong></div><hr><div>{t escape=tooltip}The length of the key to use to encrypt the cookie. Longer lengths are more secure, but they will slow performance.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>
                    
                    <!-- Cookies -->
                    
                    <tr class="row2">
                        <td class="menuhead" colspan="5" width="100%">&nbsp;{t}Cookies{/t}</td>
                    </tr>
                    
                    <tr>
                        <td align="right"><b>{t}Cookie Domain{/t}</b></td>
                        <td>
                            <input name="cookie_domain" class="olotd5" size="55" value="{$qwcrm_config.cookie_domain}" type="text" maxlength="50" placeholder="quantumwarp.com" onkeydown="return onlyURL(event);"/>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}Cookie Domain{/t}</strong></div><hr><div>{t escape=tooltip}Domain to use when setting session cookies. Precede domain with \'.\' if cookie should be valid for all subdomains.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><b>{t}Cookie Path{/t}</b></td>
                        <td>
                            <input name="cookie_path" class="olotd5" size="55" value="{$qwcrm_config.cookie_path}" type="text" maxlength="20" placeholder="qwcrm/" onkeydown="return onlyURL(event);"/>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}Cookie Path{/t}</strong></div><hr><div>{t escape=tooltip}Path the cookie should be valid for.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>
                    
                    <!-- Logging -->
                    
                    <tr class="row2">
                        <td class="menuhead" colspan="5" width="100%">&nbsp;{t}Logging{/t}</td>
                    </tr>
                                        
                    <tr>
                        <td align="right"><b>{t}Access Log{/t}</b></td>
                        <td>
                            <select class="olotd5" id="qwcrm_access_log" name="qwcrm_access_log">                                                       
                                <option value="0"{if $qwcrm_config.qwcrm_access_log == '0'} selected{/if}>{t}No{/t}</option>
                                <option value="1"{if $qwcrm_config.qwcrm_access_log == '1'} selected{/if}>{t}Yes{/t}</option>
                            </select>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}QWcrm Access Log{/t}</strong></div><hr><div>{t escape=tooltip}Enable access logging for QWcrm. This will log all page accesses and store the data in the Access Log. This log file is in apache log format and can be found in the logs folder.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><b>{t}Activity Log{/t}</b></td>
                        <td>
                            <select class="olotd5" id="qwcrm_activity_log" name="qwcrm_activity_log">                                                       
                                <option value="0"{if $qwcrm_config.qwcrm_activity_log == '0'} selected{/if}>{t}No{/t}</option>
                                <option value="1"{if $qwcrm_config.qwcrm_activity_log == '1'} selected{/if}>{t}Yes{/t}</option>
                            </select>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}QWcrm Activity Log{/t}</strong></div><hr><div>{t escape=tooltip}Enable activity logging for QWcrm. This will log all user activity from within QWcrm and store the data in the Activity Log. This log file is in apache log format and can be found in the logs folder.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><b>{t}Error Log{/t}</b></td>
                        <td>
                            <select class="olotd5" id="qwcrm_error_log" name="qwcrm_error_log">                                                       
                                <option value="0"{if $qwcrm_config.qwcrm_error_log == '0'} selected{/if}>{t}No{/t}</option>
                                <option value="1"{if $qwcrm_config.qwcrm_error_log == '1'} selected{/if}>{t}Yes{/t}</option>
                            </select>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}QWcrm Error Log{/t}</strong></div><hr><div>{t escape=tooltip}Enable error logging for QWcrm. This will log all errors and store the data in the Error Log. This log file is in apache log format and can be found in the logs folder.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><b>{t}SQL Logging{/t}</b></td>
                        <td>
                            <select class="olotd5" id="qwcrm_sql_logging " name="qwcrm_sql_logging">                                                       
                                <option value="0"{if $qwcrm_config.qwcrm_sql_logging == '0'} selected{/if}>{t}No{/t}</option>
                                <option value="1"{if $qwcrm_config.qwcrm_sql_logging == '1'} selected{/if}>{t}Yes{/t}</option>
                            </select>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}QWcrm SQL Log{/t}</strong></div><hr><div>{t escape=tooltip}Enable SQL logging for QWcrm. This attach the SQL query when present to the standard QWcrm Error Log. This is deisabled by default because it can cause your logs to get large quickly.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>                    
                    <tr>
                        <td align="right"><b>{t}Email Error Log{/t}</b></td>
                        <td>
                            <select class="olotd5" id="qwcrm_email_error_log" name="qwcrm_email_error_log">                                                       
                                <option value="0"{if $qwcrm_config.qwcrm_email_error_log == '0'} selected{/if}>{t}No{/t}</option>
                                <option value="1"{if $qwcrm_config.qwcrm_email_error_log == '1'} selected{/if}>{t}Yes{/t}</option>
                            </select>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}QWcrm Email Error Log{/t}</strong></div><hr><div>{t escape=tooltip}Enable email error logging for QWcrm. This will log all email system errors from within QWcrm and store the data in the Activity Log. This log file can be found in the logs folder.<br>This should only be used for diagnosing problems because the log file will grow in size quicky.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><b>{t}Email Transport Log{/t}</b></td>
                        <td>
                            <select class="olotd5" id="qwcrm_email_transport_log" name="qwcrm_email_transport_log">                                                       
                                <option value="0"{if $qwcrm_config.qwcrm_email_transport_log == '0'} selected{/if}>{t}No{/t}</option>
                                <option value="1"{if $qwcrm_config.qwcrm_email_transport_log == '1'} selected{/if}>{t}Yes{/t}</option>
                            </select>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}QWcrm Email Transport Log{/t}</strong></div><hr><div>{t escape=tooltip}Enable email transport logging for QWcrm. This will log all email trasnsport transactions from within QWcrm and store the data in the Email Transport Log. This log file can be found in the logs folder.<br>The log will show you the SMTP handshakes and other relevant information.<br>This should only be used for diagnosing problems because the log file will grow in size quicky.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr> 
                    
                    <!-- Error Reporting -->
                    
                    <tr class="row2">
                        <td class="menuhead" colspan="5" width="100%">&nbsp;{t}Error Reporting{/t}</td>
                    </tr>
                                        
                    <tr>
                        <td align="right"><b>{t}Error Reporting{/t}</b></td>
                        <td>
                            <select class="olotd5" id="error_reporting" name="error_reporting">                                
                                <option value="default"{if $qwcrm_config.error_reporting == 'default'} selected{/if}>{t}System Default{/t}</option>
                                <option value="none"{if $qwcrm_config.error_reporting == 'none'} selected{/if}>{t}None{/t}</option>
                                <option value="verysimple"{if $qwcrm_config.error_reporting == 'verysimple'} selected{/if}>{t}Very Simple{/t}</option>
                                <option value="simple"{if $qwcrm_config.error_reporting == 'simple'} selected{/if}>{t}Simple{/t}</option>
                                <option value="maximum"{if $qwcrm_config.error_reporting == 'maximum'} selected{/if}>{t}Maximum{/t}</option>
                                <option value="development"{if $qwcrm_config.error_reporting == 'development'} selected{/if}>{t}Development{/t}</option>                                
                            </select>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}Error Reporting{/t}</strong></div><hr><div>{t escape=tooltip}Select the level of PHP reporting for your needs. Do not leave error reporting on for live sites as this can be a security risk.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><b>{t}Error Page Raw Output{/t}</b></td>
                        <td>
                            <select class="olotd5" id="error_page_raw_output" name="error_page_raw_output">                                                       
                                <option value="0"{if $qwcrm_config.error_page_raw_output == '0'} selected{/if}>{t}No{/t}</option>
                                <option value="1"{if $qwcrm_config.error_page_raw_output == '1'} selected{/if}>{t}Yes{/t}</option>
                            </select>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}Error Page Raw Output{/t}</strong></div><hr><div>{t escape=tooltip}Normally when an error occurs the error page is display with the relevant information, however sometimes looping or white screens can occur and this option strips back all uneeded functionality so just the error data is shown to negate these issues. This is only needed for development or unless otherwise instructed. QWcrm Debug does not need to be enabled.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>                    
                    
                    <!-- Debugging -->
                    
                    <tr class="row2">
                        <td class="menuhead" colspan="5" width="100%">&nbsp;{t}Debugging{/t}</td>
                    </tr>
                                        
                    <tr>
                        <td align="right"><b>{t}QWcrm Debug{/t}</b></td>
                        <td>
                            <select class="olotd5" id="qwcrm_debug" name="qwcrm_debug">                                                       
                                <option value="0"{if $qwcrm_config.qwcrm_debug == '0'} selected{/if}>{t}No{/t}</option>
                                <option value="1"{if $qwcrm_config.qwcrm_debug == '1'} selected{/if}>{t}Yes{/t}</option>
                            </select>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}QWcrm Debug{/t}</strong></div><hr><div>{t escape=tooltip}This on it\'s own gives basic infomration such as the page and module names aswell as their load time. QWcrm Debug needs to be enabled to access the rest of the debugging options.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>                    
                    <tr>
                        <td align="right"><b>{t}QWcrm Advanced Debug{/t}</b></td>
                        <td>
                            <select class="olotd5" id="qwcrm_advanced_debug" name="qwcrm_advanced_debug">                                                       
                                <option value="0"{if $qwcrm_config.qwcrm_advanced_debug == '0'} selected{/if}>{t}No{/t}</option>
                                <option value="1"{if $qwcrm_config.qwcrm_advanced_debug == '1'} selected{/if}>{t}Yes{/t}</option>
                            </select>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}QWcrm Advanced Debug{/t}</strong></div><hr><div>{t escape=tooltip}This does a full varible and class dump from PHP. This is a security risk and should only be used for QWcrm development.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><b>{t}Smarty Debugging (QWcrm){/t}</b></td>
                        <td>
                            <select class="olotd5" id="qwcrm_smarty_debugging" name="qwcrm_smarty_debugging">                                                       
                                <option value="0"{if $qwcrm_config.qwcrm_smarty_debugging == '0'} selected{/if}>{t}No{/t}</option>
                                <option value="1"{if $qwcrm_config.qwcrm_smarty_debugging == '1'} selected{/if}>{t}Yes{/t}</option>
                            </select>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}Smarty Debugging (QWcrm){/t}</strong></div><hr><div>{t escape=tooltip}Because of the way QWcrm is structured I needed to implement a custom method to call the Smarty Debugging template.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>
                    {*<tr>
                        <td align="right"><b>{t}Smarty Debugging{/t}</b></td>
                        <td>
                            <select class="olotd5" id="smarty_debugging" name="smarty_debugging">                                                       
                                <option value="0"{if $qwcrm_config.smarty_debugging == '0'} selected{/if}>{t}No{/t}</option>
                                <option value="1"{if $qwcrm_config.smarty_debugging == '1'} selected{/if}>{t}Yes{/t}</option>
                            </select>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}Smarty Debugging{/t}</strong></div><hr><div>{t escape=tooltip}This enables the debugging console. The console is a javascript popup window that informs you of the included templates, variables assigned from php and config file variables for the current script. It does not show variables assigned within a template with the { assign } function. This is the standard way to enable Smarty Debugging. This currently does not work.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><b>{t}Smarty Debugging Control{/t}</b></td>
                        <td>
                            <select class="olotd5" id="smarty_debugging_ctrl" name="smarty_debugging_ctrl">
                                <option value=""{if $qwcrm_config.smarty_debugging_ctrl == ''} selected{/if}>{t}No Control{/t}</option>
                                <option value="NONE"{if $qwcrm_config.smarty_debugging_ctrl == 'NONE'} selected{/if}>{t}None{/t}</option>
                                <option value="URL"{if $qwcrm_config.smarty_debugging_ctrl == 'URL'} selected{/if}>{t}URL{/t}</option>
                            </select>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}Smarty Debugging Control{/t}</strong></div><hr><div>{t escape=tooltip}This allows alternate ways to enable debugging. NONE means no alternate methods are allowed. URL means when the keyword SMARTY_DEBUG is found in the QUERY_STRING, debugging is enabled for that invocation of the script. If $debugging is TRUE, this value is ignored.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>*}
                    
                    <!-- Smarty -->
                    
                    <tr class="row2">
                        <td class="menuhead" colspan="5" width="100%">&nbsp;{t}Smarty{/t}</td>
                    </tr>                                        
                    
                    <tr>
                        <td align="right"><b>{t}Force Compile{/t}</b></td>
                        <td>
                            <select class="olotd5" id="smarty_force_compile" name="smarty_force_compile">                                                       
                                <option value="0"{if $qwcrm_config.smarty_force_compile == '0'} selected{/if}>{t}No{/t}</option>
                                <option value="1"{if $qwcrm_config.smarty_force_compile == '1'} selected{/if}>{t}Yes{/t}</option>
                            </select>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}Force Compile{/t}</strong></div><hr><div>{t escape=tooltip}This forces Smarty to (re)compile templates on every invocation. This setting overrides \'compile_check\'. By default this is FALSE. This is handy for development and debugging. It should never be used in a production environment. If \'caching\' is enabled, the cache file(s) will be regenerated every time. Compiling referenced here is the process of converting the Smarty templates into pure PHP code.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>
                    {*<tr>
                        <td align="right"><b>{t}Force Cache{/t}</b></td>
                        <td>
                            <select class="olotd5" id="smarty_force_cache" name="smarty_force_cache">                                                       
                                <option value="0"{if $qwcrm_config.smarty_force_cache == '0'} selected{/if}>{t}No{/t}</option>
                                <option value="1"{if $qwcrm_config.smarty_force_cache== '1'} selected{/if}>{t}Yes{/t}</option>
                            </select>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}Force Cache{/t}</strong></div><hr><div>{t escape=tooltip}This forces Smarty to (re)cache templates on every invocation. It does not override the $caching level, but merely pretends the template has never been cached before. {/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><b>{t}Caching{/t}</b></td>
                        <td>
                            <select class="olotd5" id="smarty_caching" name="smarty_caching">                                                       
                                <option value="0"{if $qwcrm_config.smarty_caching == '0'} selected{/if}>{t}None{/t}</option>
                                <option value="1"{if $qwcrm_config.smarty_caching == '1'} selected{/if}>{t}Current{/t}</option>
                                <option value="2"{if $qwcrm_config.smarty_caching == '2'} selected{/if}>{t}Saved{/t}</option>
                            </select>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}Caching{/t}</strong></div><hr><div>{t escape=tooltip}This tells Smarty whether or not to cache the output of the templates to the Cache Directory. This is untested with QWcrm.<hr>A value of Smarty::CACHING_LIFETIME_CURRENT tells Smarty to use the current $cache_lifetime variable to determine if the cache has expired.<br><br>A value of Smarty::CACHING_LIFETIME_SAVED tells Smarty to use the $cache_lifetime value at the time the cache was generated. This way you can set the $cache_lifetime just before fetching the template to have granular control over when that particular cache expires.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>                    
                    <tr>
                        <td align="right"><b>{t}Cache Lifetime{/t}</b> <span style="color: #ff0000">*</span></td>
                        <td>
                            <input name="smarty_cache_lifetime" class="olotd5" value="{$qwcrm_config.smarty_cache_lifetime}" type="text" maxlength="20" required onkeydown="return onlyNumbers(event);"/>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}Cache Lifetime{/t}</strong></div><hr><div>{t escape=tooltip}This is the length of time in seconds that a template cache is valid. Once this time has expired, the cache will be regenerated.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><b>{t}Cache Modified Check{/t}</b></td>
                        <td>
                            <select class="olotd5" id="smarty_cache_modified_check" name="smarty_cache_modified_check">                                                       
                                <option value="0"{if $qwcrm_config.smarty_cache_modified_check == '0'} selected{/if}>{t}No{/t}</option>
                                <option value="1"{if $qwcrm_config.smarty_cache_modified_check == '1'} selected{/if}>{t}Yes{/t}</option>
                            </select>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}Cache Modified Check{/t}</strong></div><hr><div>{t escape=tooltip}If set to TRUE, Smarty will respect the \'If-Modified-Since\' header sent from the client. If the cached file timestamp has not changed since the last visit, then a \'304: Not Modified\' header will be sent instead of the content. This works only on cached content without { insert } tags.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><b>{t}Cache Locking{/t}</b></td>
                        <td>
                            <select class="olotd5" id="smarty_cache_locking" name="smarty_cache_locking">                                                       
                                <option value="0"{if $qwcrm_config.smarty_cache_locking == '0'} selected{/if}>{t}No{/t}</option>
                                <option value="1"{if $qwcrm_config.smarty_cache_locking == '1'} selected{/if}>{t}Yes{/t}</option>
                            </select>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}Cache Locking{/t}</strong></div><hr><div>{t escape=tooltip}Cache locking avoids concurrent cache generation. This means resource intensive pages can be generated only once, even if they\'ve been requested multiple times in the same moment.{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>*}
                    
                    <!-- Update -->
                    
                    <tr class="row2">
                        <td class="menuhead" colspan="5" width="100%">&nbsp;</td>
                    </tr> 
                    
                    <tr>
                        <td colspan="2" style="text-align: center;"><button class="olotd5" type="submit" name="submit" value="update">{t}Update{/t}</td>
                    </tr> 
                    
                </table>
            </td>
        </tr>
    </table>                        
</form>