<!-- upgrade_delete_setup_folder_block.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}

<table width="100%" border="0" cellpadding="20" cellspacing="0">
    <tr>
        <td>
            <table width="900" cellpadding="5" cellspacing="0" border="0">
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{t}Delete Setup Folder{/t}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle"></td>
                </tr>
                <tr>
                    <td class="menutd2" colspan="2">
                        <table width="100%" class="olotable" cellpadding="5" cellspacing="0" border="0">
                            <tr>
                                <td width="100%" valign="top">                                    
                                    <form action="index.php?component=setup&page_tpl=migrate" method="post"> 
                                        <table class="menutable" width="100%" border="0" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td class="menutd">
                                                    <table width="100%" cellpadding="2" cellspacing="2" border="0" class="menutd2">
                                                        <tr>
                                                            <td>                                                                
                                                                <table class="olotable" width="100%" cellpadding="5" cellspacing="0" border="0">
                                                                    
                                                                    <!-- Common -->
                                                                    
                                                                    <tr>
                                                                        <td>
                                                                            <div id="delete_setup_folder">
                                                                                
                                                                                <!-- Remove Setup folder message -->
                                                                                <p style="text-align: center;">
                                                                                    <strong>{t}PLEASE REMEMBER TO COMPLETELY REMOVE THE SETUP FOLDER.{/t}</strong>
                                                                                    <br />
                                                                                    {t}You will not be able to use QWcrm until the Setup folder has been removed. This is a security feature of QWcrm!{/t}
                                                                                </p>

                                                                                <!-- Delete Setup folder Button -->       
                                                                                <button id="delete_setup_folder_button" type="button" style="display: block; margin: auto auto;" onclick="$.ajax( { url:'index.php?component=setup&page_tpl=upgrade&action=delete_setup_folder&themeVar=off', success: function(data) { $('body').append(data); } } );">{t}Delete Setup Folder{/t}</button>
                                                                                
                                                                            </div>
                                                                            
                                                                            <div id="setup_folder_removed" style="display: none;">
                                                                                
                                                                                <!-- Setup folder removed message -->
                                                                                <p style="text-align: center;">
                                                                                    <strong>{t}Please login with your credentials.{/t}</strong><br />                                                                                    
                                                                                </p> 
                                                                                
                                                                                <!-- Goto Login Page Button -->                                                                    
                                                                                <button id="login_page_button" type="button" style="display: block; margin: auto auto;" onclick="window.location.href='index.php?component=user&page_tpl=login';">{t}Login{/t}</button>
                                                                            
                                                                            </div>
                                                                            
                                                                        </td>
                                                                    </tr>      
                                                                                                                                        
                                                                </table>                                                                
                                                            </td>
                                                    </table>
                                                </td>
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