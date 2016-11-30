<!-- Begin Login.tpl-->        
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
                                                <td><input type="text" name="login_usr" size="25" class="olotd5" alt="login"></td>
                                            </tr>
                                            <tr>
                                                <td>{$translate_core_login_password}</td>
                                                <td><input type="password" name="login_pwd" size="25" class="olotd5" alt="password"></td>
                                            </tr>
                                            <tr align="center">
                                                <td colspan="2"><button type="submit" name="action" value="login"><img src="{$theme_images_dir}tick.png" alt=""> {$translate_core_login_login}</button></td>
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