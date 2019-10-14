<!-- reset_enter_email_block.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}

{if $recaptcha}<script src="https://www.google.com/recaptcha/api.js" async defer></script>{/if}

<table width="900" border="0" cellspacing="0" cellpadding="2">
    <tr>        
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
                                    <form action="index.php?component=user&page_tpl=reset" method="post">
                                        <table width="50%" cellspacing="0" border="0" cellpadding="5" align="center">
                                            <tr>
                                                <td colspan="2">{t}Please enter the email address for your account. A verification code will be sent to you. Once you have received the verification code, you will be able to choose a new password for your account.{/t}</td>
                                            </tr>
                                            <tr>
                                                <td>{t}Email Address{/t} <span style="color: #ff0000">*</span></td>
                                                <td><input name="email" class="olotd5" size="25" alt="login" type="email" required onkeydown="return onlyEmail(event);"></td>
                                            </tr>                                            
                                            <tr align="center">
                                                <td colspan="2">
                                                    <button id="submit_button" type="submit" name="submit" value="submit" disabled><img src="{$theme_images_dir}tick.png" alt=""> {t}Submit{/t}</button>
                                                    <button type="submit" onclick="window.location.href='index.php';">{t}Cancel{/t}</button>
                                                </td>
                                            </tr>                            

                                        </table>
                                        
                                        {if $recaptcha}
                                            <!-- Google reCaptcha -->
                                            <script>
                                                
                                                // Disable the submit button
                                                disableSubmitButton();                                               
                                                
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