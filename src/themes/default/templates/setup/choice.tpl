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

        // Enable 'Accept License' button when license is agreed to (both .click() and .change() work)
        $("#setup_accept_license_checkbox").click( function() {
            
            // If this method is called, the default action of the event will not be triggered.
            //event.preventDefault();
            
            // Both these methods work. Always use .prop() to add or remove properties
            
            // if the checkbox is ticked
            if($("#setup_accept_license_checkbox").is(':checked')) {
                $("#setup_accept_license_button").prop("disabled", false);              
            } else {                
                $("#setup_accept_license_button").prop("disabled", true);
            }
            
            /* if the checkbox is ticked
            if((this.checked)) {
                $("#setup_accept_license_button").prop("disabled", false);              
            } else {                
                $("#setup_accept_license_button").prop("disabled", true);
            }*/
        
        } );
        
        // Accept License - Hide license agreement and unhide compatibility block
        $("#setup_accept_license_button").click(function(event) {
            
            // if the checkbox is ticked
            if($("#setup_accept_license_checkbox").is(':checked')) {
                $("#setup_accept_license_block").hide();
                $("#setup_compatibility_test_block").show();
            }
        
        } );
        
        // If enviroment is compatible the next button will be enabled, hide compatibily tests and show Setup Buttons
        $("#setup_compatibility_test_next_button").click(function(event) {
            
            // This prevents the default action of the element (i.e. button)
            event.preventDefault();
            
            // Do these
            $("#setup_compatibility_test_block").hide();
            $("#setup_buttons_block").show();
            
        
        } );
        
        // Setup Selection Buttons
        
        // Install QWcrm - When clicked show install message and hide the setup buttons
        $("#setup_install_button").click(function(event) {
            
            // If this method is called, the default action of the event will not be triggered.
            //event.preventDefault();
            
            // Hide the setup buttons and show the install message
            $("#setup_buttons_block").hide();
            $("#setup_messages_block").show();
            $("#setup_install_message").show();            
        
        } );
        
        // Migrate from MyITCRM - When clicked show migrate message and hide the setup buttons
        $("#setup_migrate_button").click(function(event) {
            
            // If this method is called, the default action of the event will not be triggered.
            //event.preventDefault();
            
            // Hide the setup buttons and show the migrate message
            $("#setup_buttons_block").hide();
            $("#setup_messages_block").show();
            $("#setup_migrate_message").show();            
        
        } );        
        
    {*// Upgrade QWcrm- When clicked show upgrade message and hide the setup buttons
        $("#setup_upgrade_button").click(function(event) {
            
            // If this method is called, the default action of the event will not be triggered.
            //event.preventDefault();
            
            // Hide the setup buttons and show the upgrade message
            $("#setup_buttons_block").hide();
            $("#setup_messages_block").show();
            $("#setup_upgrade_message").show();            
        
        } );*}         

    } );
</script>
<script>
    // Disable Back Button
    history.pushState(null, null, location.href);
    window.onpopstate = function () { alert('{t}The Back Button has been disabled.{/t}'); };
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
                            
                            <tr id="setup_accept_license_block">
                                <td>
                                    <table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
                                        <tr>
                                            <td>
                                                <div>{t escape=no}SETUP_LICENSE_AGREEMENT{/t}</div>
                                                                                               
                                            </td>                                            
                                        </tr>
                                        <tr>
                                            <td>
                                                <p><input type="checkbox" id="setup_accept_license_checkbox">{t}I have read and understood the license.{/t}</p>
                                                <button id="setup_accept_license_button" href="javascript:void(0)" disabled>{t}Accept License{/t}</button>
                                            </td>
                                        </tr>
                                    </table>                                    
                                </td>                                
                            </tr>
                            
                            <!-- Setup Compatibility Test -->                            
                                                                  
                            <tr id="setup_compatibility_test_block" style="display: none;">
                                <td>                                                                                                  
                                    {include file='setup/blocks/choice_compatibility_test_block.tpl'}
                                </td>
                            </tr>                                       
                                        
                            <!-- Setup Selection Buttons -->
                            
                            <tr id="setup_buttons_block" style="display: none;">
                                <td class="menutd">
                                    <table width="100%" border="0" cellpadding="10" cellspacing="0">
                                        
                                        {if $setup_type != 'upgrade'}
                                            <tr>
                                                <td>                                                                                                  
                                                    {t}Choose choose whether you want to install a fresh copy of QWcrm or migrate from MyITCRM{/t}
                                                </td>
                                            </tr> 

                                            <!-- Install QWcrm -->   
                                            <tr>
                                                <td>                                                
                                                    <button id="setup_install_button" href="javascript:void(0)">{t}Install QWcrm{/t}</button>                                                
                                                </td>
                                            </tr>                                   

                                            <!-- Migrate from MyITCRM -->  
                                            <tr id="">
                                                <td>                                                                                                 
                                                    <button id="setup_migrate_button" href="javascript:void(0)">{t}Migrate from MyITCRM{/t}</button> 
                                                </td>
                                            </tr>
                                            
                                        {else}
                                            
                                            <!-- Upgrade QWcrm -->
                                             
                                            <tr>
                                                <td>                                                                                                  
                                                    {t}There is an upgrade pending for QWcrm and needs to be applied before you can use this software.{/t}
                                                </td>
                                            </tr> 
                                        
                                            <tr>
                                                <td>                                                                                                 
                                                    <button id="setup_upgrade_button" type="button" class="olotd4" onclick="window.location.href='index.php?component=setup&page_tpl=upgrade';">{t}Start QWcrm Upgrade{/t}</button>
                                                </td>
                                            </tr>
                                        {/if}                                        
                                        
                                    </table>
                                </td>
                            </tr>
                            
                            <!-- Setup Messages -->
                            
                            <tr id="setup_messages_block" style="display: none;">
                                <td class="menutd">
                                    <table width="100%" border="0" cellpadding="10" cellspacing="0">
                                        
                                        <!-- Install QWcrm -->   
                                        <tr id="setup_install_message" style="display: none;">
                                            <td>
                                                <div>{t escape=no}SETUP_INSTALL_MESSAGE{/t}</div>
                                                <p><button type="button" class="olotd4" onclick="window.location.href='index.php?component=setup&page_tpl=install';">{t}Next{/t}</button></p>
                                            </td>
                                        </tr>                                        
                                        
                                        <!-- Migrate from MyITCRM -->  
                                        <tr id="setup_migrate_message" style="display: none;">
                                            <td>
                                                <div>{t escape=no}SETUP_MIGRATE_MESSAGE{/t}</div>
                                                <p><button type="button" class="olotd4" onclick="window.location.href='index.php?component=setup&page_tpl=migrate';">{t}Next{/t}</button></p>
                                            </td>
                                        </tr>                                         
                                        
                                        {*<!-- Upgrade QWcrm -->                                         
                                        <tr id="setup_upgrade_message" style="display: none;">
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