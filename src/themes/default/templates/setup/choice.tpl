<!-- choice.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<script>
    $(function() {

        // Accept License

        // Accept License - Hide license agreement and unhide setup buttons when license is accepted
        $("#accept_license_button").click(function(event) {
            
            // not sure what this does
            event.preventDefault();
            
            // if the checkbox is ticked
            if($("#accept_license_checkbox").is(':checked')) {
                $("#accept_license").hide();
                $("#setup_buttons").show();
            }
        
        } );
        
        // Setup Selection Buttons
        
        // Install QWcrm - When clicked show install message and hide the setup buttons
        $("#install_button").click(function(event) {
            
            // not sure what this does
            event.preventDefault();
            
            // Hide the setup buttons and show the install message
            $("#setup_buttons").hide();
            $("#setup_messages").show();
            $("#install_next").show();            
        
        } );
        
        // Migrate from MyITCRM - When clicked show migrate message and hide the setup buttons
        $("#migrate_button").click(function(event) {
            
            // not sure what this does
            event.preventDefault();
            
            // Hide the setup buttons and show the migrate message
            $("#setup_buttons").hide();
            $("#setup_messages").show();
            $("#migrate_next").show();            
        
        } );        
        
    {*// Upgrade QWcrm- When clicked show upgrade message and hide the setup buttons
        $("#upgrade_button").click(function(event) {
            
            // not sure what this does
            event.preventDefault();
            
            // Hide the setup buttons and show the upgrade message
            $("#setup_buttons").hide();
            $("#setup_messages").show();
            $("#upgrade_next").show();            
        
        } );*}         

    } );
</script>
 
<table width="100%" border="0" cellpadding="20" cellspacing="5">
    <tr>
        <td>            
            <table width="700" cellpadding="3" cellspacing="0" border="0">
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{t}QWcrm Installation / MyITCRM Migration / QWcrm Upgrade{/t}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}SETUP_CHOICE_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}SETUP_CHOICE_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
                        </a>
                    </td>
                </tr>                
                <tr>
                    <td class="menutd2" colspan="2">
                        <table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">                            
                            
                            <!-- Accept License -->
                            
                            <tr id="accept_license">
                                <td>
                                    <table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
                                        <tr>
                                            <td>
                                                <div>{t escape=no}SETUP_LICENSE_AGREEMENT{/t}</div>
                                                                                               
                                            </td>                                            
                                        </tr>
                                        <tr>
                                            <td>
                                                <p><input type="checkbox" id="accept_license_checkbox">{t}I have read and understood the license.{/t}</p>
                                                <button id="accept_license_button" href="javascript:void(0)">{t}Accept License{/t}</button>
                                            </td>
                                        </tr>
                                    </table>                                    
                                </td>                                
                            </tr>
                                        
                            <!-- Setup Selection Buttons -->
                            
                            <tr id="setup_buttons" style="display: none;">
                                <td class="menutd">
                                    <table width="100%" border="0" cellpadding="10" cellspacing="0">
                                        
                                        <tr>
                                            <td>                                                                                                  
                                                {t}Choose choose whether you want to install a fresh copy of QWcrm or migrate from MyITCRM{/t}
                                            </td>
                                        </tr> 
                                        
                                        <!-- Install QWcrm -->   
                                        <tr>
                                            <td>                                                
                                                <button id="install_button" href="javascript:void(0)">{t}Install QWcrm{/t}</button>                                                
                                            </td>
                                        </tr>                                   
                                        
                                        <!-- Migrate from MyITCRM -->  
                                        <tr id="">
                                            <td>                                                                                                 
                                                <button id="migrate_button" href="javascript:void(0)">{t}Migrate from MyITCRM{/t}</button> 
                                            </td>
                                        </tr>                                          
                                        
                                        {*<!-- Upgrade QWcrm -->                                         
                                        <tr>
                                            <td>                                                                                                 
                                                <button id="upgrade_button" href="javascript:void(0)">{t}Upgrade QWcrm{/t}</button> 
                                            </td>
                                        </tr>*}                             
                                        
                                    </table>
                                </td>
                            </tr>
                            
                            <!-- Setup Messages -->
                            
                            <tr id="setup_messages" style="display: none;">
                                <td class="menutd">
                                    <table width="100%" border="0" cellpadding="10" cellspacing="0">
                                        
                                        <!-- Install QWcrm -->   
                                        <tr id="install_next" style="display: none;">
                                            <td>
                                                <div>{t escape=no}SETUP_INSTALL_MESSAGE{/t}</div>
                                                <p><button type="button" class="olotd4" onclick="window.location.href='index.php?component=setup&page_tpl=install';">{t}Next{/t}</button></p>
                                            </td>
                                        </tr>                                        
                                        
                                        <!-- Migrate from MyITCRM -->  
                                        <tr id="migrate_next" style="display: none;">
                                            <td>
                                                <div>{t escape=no}SETUP_MIGRATE_MESSAGE{/t}</div>
                                                <p><button type="button" class="olotd4" onclick="window.location.href='index.php?component=setup&page_tpl=migrate';">{t}Next{/t}</button></p>
                                            </td>
                                        </tr>                                         
                                        
                                        {*<!-- Upgrade QWcrm -->                                         
                                        <tr id="upgrade_next" style="display: none;">
                                            <td>
                                                <div>{t escape=no}SETUP_UPGRADE_MESSAGE{/t}</div>
                                                <p><button type="button" class="olotd4" onclick="window.location.href='index.php?component=setup&page_tpl=upgrade';">{t}Next{/t}</button></p>
                                            </td>
                                        </tr>*}                                                                            
                                        
                                    </table>
                                </td>
                            </tr>
                            
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>