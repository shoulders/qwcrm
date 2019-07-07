<!-- reset_enter_password_block.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}

<table width="900" border="0" cellspacing="0" cellpadding="2">
    <tr>        
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
                                    <form action="index.php?component=user&page_tpl=reset" method="post" onsubmit="return confirmPasswordsMatch();">
                                        <table width="75%" cellspacing="0" border="0" cellpadding="5" align="center">
                                            <tr>
                                                <td colspan="2">{t}To complete the password reset process, please enter a new password.{/t}</td>
                                            </tr>
                                            <tr>
                                                <td align="right"><strong>{t}Password{/t} <span style="color: #ff0000">*</span></strong></td>
                                                <td><input id="password" name="password" class="olotd5" type="password" minlength="8" maxlength="20" required oninput="checkPasswordsMatch('{t}Passwords Match!{/t}', '{t}Passwords Do Not Match!{/t}', true);" onkeydown="return onlyPassword(event);"></td>
                                            </tr>
                                            <tr>
                                                <td align="right"><strong>{t}Confirm Password{/t} <span style="color: #ff0000">*</span></strong></td>
                                                <td>
                                                    <input id="confirmPassword" name="confirmPassword" class="olotd5" type="password" minlength="8" maxlength="20" requireed oninput="checkPasswordsMatch('{t}Passwords Match!{/t}', '{t}Passwords Do Not Match!{/t}', true);" onkeydown="onlyPassword(event);">
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
                                        
                                        <script>
                                                
                                            // Disable the submit button
                                            disableSubmitButton();                                               
                                                
                                        </script>

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