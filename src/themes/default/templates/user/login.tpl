<!-- login.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
{if $recaptcha}<script src="https://www.google.com/recaptcha/api.js" async defer></script>{/if}

<table width="900" border="0" cellspacing="0" cellpadding="2">
    <tr>
        <td align="center">
            <img src="{$company_logo}" alt="" style="max-height: 150px; max-width: 250px;">
        </td>
    </tr>    
    {if !$login_token}
        <tr>        
            <td align="center">       
                <table class="olotable" border="0" align="center" style="margin: 20px;">
                    <tr>
                        <td class="olohead">{t}Login{/t}</td>
                    </tr>
                    <tr>
                        <td class="olotd">
                            <table  cellspacing="5" border="0" cellpadding="5" align="center">
                                <tr>
                                    <td>
                                        <form action="index.php?component=user&page_tpl=login" method="post">
                                            <table width="25%" cellspacing="0" border="0" cellpadding="5" align="center">
                                                
                                                <!-- Login Form -->
                                                <tr>
                                                    <td>{t}Login{/t}</td>
                                                    <td><input name="login_username" class="olotd5" size="25" alt="login" type="text" required onkeydown="return onlyUsername(event);"></td>
                                                </tr>
                                                <tr>
                                                    <td>{t}Password{/t}</td>
                                                    <td><input name="login_pwd" class="olotd5" size="25" alt="password" type="password" required onkeydown="return onlyPassword(event);"></td>
                                                </tr>
                                                {if $remember_me}
                                                    <tr>
                                                        <td>{t}Remember me{/t}</td>
                                                        <td><input type="checkbox" name="remember" value="1"></td>                                                
                                                    </tr>
                                                {/if}
                                                <tr align="center">
                                                    <td colspan="2"><button id="login_button" type="submit" name="action" value="login"><img src="{$theme_images_dir}tick.png" alt=""> {t}Login{/t}</button></td>
                                                </tr>                            

                                                <!-- Forgotten Password -->
                                                <tr align="center">
                                                    <td colspan="2"><a href="index.php?component=user&page_tpl=reset">{t}Forgot your password?{/t}</a></td>
                                                </tr>
                                                
                                            </table>

                                            {if $recaptcha}
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
    {else}
        <tr>
            <td align="center">
                <button id="logout_button" type="submit" name="logout" onclick="window.location.href='index.php?component=user&page_tpl=login&action=logout';">
                    <img src="{$theme_images_dir}tick.png" alt=""> {t}Logout{/t}
                </button>
            </td>                    
        </tr>
    {/if}
</table>