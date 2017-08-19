<!-- reset_enter_token_block.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}

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
                    <td class="olohead">{t}Enter your Verification code{/t}</td>
                </tr>
                <tr>
                    <td class="olotd">
                        <table  cellspacing="5" border="0" cellpadding="5" align="center">
                            <tr>
                                <td>
                                    <form action="index.php?page=user:reset" method="post">
                                        <table width="75%" cellspacing="0" border="0" cellpadding="5" align="center">
                                            <tr>
                                                <td colspan="2">{t}An email has been sent to your email address. The email contains a verification code, please paste the verification code in the field below to prove that you are the owner of this account.{/t}</td>
                                            </tr>
                                            <tr>
                                                <td>{t}Verification Code{/t} <span style="color: #ff0000">*</span></td>
                                                <td><input name="token" class="olotd5" size="72" type="text" value="{$token}" required onkeydown="return onlyAlphaNumeric(event);"></td>
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