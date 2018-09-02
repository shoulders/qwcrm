<!-- migrate_delete_setup_folder_block.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<script src="{$theme_js_dir}tinymce/tinymce.min.js"></script>

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
                                                                    
                                                                                                                   
                                                                    
                                                                    <!-- Submit Button -->
                                                                    
                                                                    <tr>                                                                        
                                                                        <td colspan="2">
                                                                            <button id="submit_button" class="olotd5" type="submit" name="submit" value="stage10">{t}Next{/t}</button>
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