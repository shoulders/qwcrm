<!-- reset_send_email_block.tpl -->

{if $recaptcha}<script src="https://www.google.com/recaptcha/api.js" async defer></script>{/if}

<table width="900" border="0" cellspacing="0" cellpadding="2">
    <tr>
        <td align="center"><img src="{$company_logo}" alt="" height="114"></td>
    </tr>
    <tr>
        <td></td>
        <td>       
            <table class="olotable" border="0" align="center" style="margin: 20px;">
                <tr>
                    <td class="olohead">{t}Enter your account Email{/t}</td>
                </tr>
                <tr>
                    <td class="olotd">
                        <table  cellspacing="5" border="0" cellpadding="5" align="center">
                            <tr>
                                <td>
                                    <form action="index.php?page=user:reset" method="POST">
                                        <table width="50%" cellspacing="0" border="0" cellpadding="5" align="center">
                                            <tr>
                                                <td colspan="2">{t}Please enter the email address for your account. A verification code will be sent to you. Once you have received the verification code, you will be able to choose a new password for your account.{/t}</td>
                                            </tr>
                                            <tr>
                                                <td>{t}Email Address{/t} <span style="color: #ff0000">*</span></td>
                                                <td><input name="email" class="olotd5" size="25" alt="login" type="text" required onkeydown="return onlyEmail(event);"></td>
                                            </tr>                                            
                                            <tr align="center">
                                                <td colspan="2"><button id="submit_button" type="submit" name="submit" value="submit"><img src="{$theme_images_dir}tick.png" alt=""> {t}Submit{/t}</button></td>
                                            </tr>                            

                                        </table>
                                        
                                        {if $recaptcha}
                                            <!-- Google reCaptcha -->
                                            <script>
                                                
                                                // Disable the submit button
                                                document.getElementById('submit_button').disabled = true;
                                                
                                                // Enable the submit button when ReCaptcha is verified
                                                function enableSubmitButton() {
                                                    document.getElementById('submit_button').disabled = false;
                                                }
                                                
                                            </script>
                                            <div class="g-recaptcha" data-sitekey="{$recaptcha_site_key}" data-callback="enableSubmitButton"></div>
                                        {/if}

                                    </form>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>