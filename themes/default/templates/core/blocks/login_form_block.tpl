<!-- login_form_block.tpl -->
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<table width="900" border="0" cellspacing="0" cellpadding="2">
    <tr>
        <td align="center"><img src="{$company_logo}" alt="" height="114"></td>
    </tr>
    <tr>
        <td><br /><br /></td>
        <td>       
            <table class="olotable" border="0" align="center" style="margin: 20px;">
                <tr>
                    <td class="olohead"> {$translate_core_login_login}</td>
                </tr>
                <tr>
                    <td class="olotd">
                        <table  cellspacing="5" border="0" cellpadding="5" align="center">
                            <tr>
                                <td>
                                    <form action="index.php" method="POST">
                                        <table width="25%" cellspacing="0" border="0" cellpadding="5" align="center">
                                            <tr>
                                                <td>{$translate_core_login_login}</td>
                                                <td><input name="login_usr" class="olotd5" size="25" alt="login" type="text" required onkeydown="return onlyUsername(event);"></td>
                                            </tr>
                                            <tr>
                                                <td>{$translate_core_login_password}</td>
                                                <td><input name="login_pwd" class="olotd5" size="25" alt="password" type="password" required onkeydown="return onlyPassword(event);"></td>
                                            </tr>
                                            <tr align="center">
                                                <td colspan="2"><button id="login_button" type="submit" name="action" value="login"><img src="{$theme_images_dir}tick.png" alt=""> {$translate_core_login_login}</button></td>
                                            </tr>                            

                                            <!-- Information Message -->
                                            {if $information_msg != '' }
                                                <tr align="center">
                                                    <td colspan="2" class="olotablegreen" style="text-align: center;">{$information_msg}</td>
                                                </tr>
                                            {/if}

                                            <!-- Warning Message -->
                                            {if $warning_msg != '' }
                                                <tr align="center">
                                                    <td colspan="2" class="error">{$warning_msg}</td>
                                                </tr>
                                            {/if}                            

                                            <!-- Forgotten Password -->
                                            <tr align="center">
                                                <td colspan="2"><a href="index.php?page=user:password_reset">{$translate_core_login_forgot_your_password}</a></td>
                                            </tr>
                                        </table>
                                        
                                        {if $captcha}
                                            <!-- Google reCaptcha -->
                                            <script>
                                                
                                                // Disable the submit button
                                                document.getElementById('login_button').disabled = true;
                                                
                                                // Enable the submit button when ReCaptcha is verified
                                                function enableSubmitButton() {
                                                    document.getElementById('login_button').disabled = false;
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