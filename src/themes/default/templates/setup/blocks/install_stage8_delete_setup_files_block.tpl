<!-- install_stage8_delete_setup_files_block.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<script src="{$theme_js_dir}tinymce/tinymce.min.js"></script>
<script>{include file="../`$theme_js_dir_finc`editor-config.js"}</script>

<table width="100%" border="0" cellpadding="20" cellspacing="0">
    <tr>
        <td>
            <table width="900" cellpadding="5" cellspacing="0" border="0">
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{t}Stage 8 - Delete Setup Files{/t}</td>
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
                                                                            <div id="remove_setup_folder">
                                                                                
                                                                                <!-- Remove Setup folder message -->
                                                                                <p style="text-align: center;">
                                                                                    <strong>{t}PLEASE REMEMBER TO COMPLETELY REMOVE THE INSTALLATION FOLDER.{/t}</strong><br />
                                                                                    {t}You will not be able to use QWcrm until the Setup folder has been removed. This is a security feature of QWcrm!{/t}
                                                                                </p>                                                                                    

                                                                                <!-- Delete Setup folder Button -->       
                                                                                <button id="delete_setup_folder_button" type="button" style="margin: auto auto;" onclick="$.ajax( { url:'index.php?component=setup&page_tpl=install&stage=8action=delete_setup_folder', success: function(data) { $('body').append(data); } } );">{t}Delete Setup Folder{/t}</button>
                                                                                
                                                                            </div>
                                                                            
                                                                            <!-- Goto Login Page Button -->                                                                    
                                                                            <button id="goto_login_page_button" type="button" style="display: none;" onclick="window.location.href='index.php?component=user&page_tpl=login';">{t}Login{/t}</button>
                                                                    
                                                                            
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