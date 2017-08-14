<!-- reset_enter_password_block.tpl -->

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
                    <td class="olohead">{t}Enter a new password{/t}</td>
                </tr>
                <tr>
                    <td class="olotd">
                        <table  cellspacing="5" border="0" cellpadding="5" align="center">
                            <tr>
                                <td>
                                    <form action="index.php?page=user:reset" method="post">
                                        <table width="75%" cellspacing="0" border="0" cellpadding="5" align="center">
                                            <tr>
                                                <td colspan="2">{t}To complete the password reset process, please enter a new password.{/t}</td>
                                            </tr>
                                            <tr>
                                                <td align="right"><strong>{t}Password{/t} <span style="color: #ff0000">*</span></strong></td>
                                                <td><input id="password" name="password" class="olotd5" type="password" minlength="8" maxlength="20" onkeydown="return onlyPassword(event);"></td>
                                            </tr>
                                            <tr>
                                                <td align="right"><strong>{t}Confirm Password{/t} <span style="color: #ff0000">*</span></strong></td>
                                                <td>
                                                    <input id="confirmPassword" name="confirmPassword" class="olotd5" type="password" minlength="8" maxlength="20" onkeyup="checkPasswordsMatch('{t}Passwords Match!{/t}', '{t}Passwords Do Not Match!{/t}');" onkeydown="onlyPassword(event);">
                                                    <div id="passwordMessage" style="min-height: 5px;"></div>
                                                </td>
                                            </tr>
                                            <tr align="center">                                                
                                                <td colspan="2">
                                                    <input type="hidden" name="reset_code" value="{$reset_code}">
                                                    <button id="submit_button" type="submit" name="submit" value="submit"><img src="{$theme_images_dir}tick.png" alt=""> {t}Submit{/t}</button>
                                                </td>
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