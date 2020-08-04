<!-- prefill_items.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<script>
     
    // Page Building Flag
    var pageBuilding = true;
    
    // Key pressed Boolean - Allow me to determine if action was started by a mouse click or typing
    var keyPressed = false; 
     
    // Invoice Prefill items JSON (from the database)
    var prefillItems = {$invoice_prefill_items_json};
    
    // Run these functions when the DOM is ready
    $(document).ready(function() {
        
        processPrefillItemsFromDatabase(prefillItems);
                    
        // Page Building has now completed
        pageBuilding = false;
        
        // Hide and slide Import section
        $(function() {
        
            // Add Toggle to 'Upload a Prefill Items CSV file' link
            $("#importFormHideButton").click(function(event) {
                event.preventDefault();
                $("#importForm").slideToggle();
            } );
            
            // Hide Import section
            $("#importForm").hide();

        } );
        
    });

    // Create and populate Labour and Parts item rows with sotered data from the database
    function processPrefillItemsFromDatabase(items) {
        
        // Form Fields that are submitted, not all item fields submitted are currently used in the backend
        fieldNames = [
            //"invoice_prefill_id",
            "description",
            "type",      
            "unit_net",
            "active"];
    
        // Loop through section items from the database
        $.each(items, function(itemIndex, item) {

            // Create a new row to be populated and get the row identifier
            iteration = createNewTableRow();
            
            // Loop through the various fields and populate with their data as appropriate
            $.each(fieldNames, function(fieldIndex, fieldName) {
                
                // If it is a checkbox
                if(fieldName == "active") {
                    if(item[fieldName] === '1') {
                        $('#qform\\[prefill_items\\]\\['+iteration+'\\]\\['+fieldName+'\\]').prop('checked', true);
                    }
                
                // Standard Input Value
                } else {
                    // Update field value
                    $('#qform\\[prefill_items\\]\\['+iteration+'\\]\\['+fieldName+'\\]').val(item[fieldName]);
                    //$('#qform\\[prefill_items\\]\\['+iteration+'\\]\\['+fieldName+'\\]').attr('value', item[fieldName]);
                    //document.getElementById('qform[prefill_items]['+iteration+']['+fieldName+']').value = item[fieldName]; (this does not work for some reason)                
                }
            });

        });        

    }
    
    // Dynamically Copy, Process and add an new item row to the relevant table
    function createNewTableRow() {
        
        // Get Table
        var tbl = document.getElementById('prefill_items');
        
        // Get Next Row Number
        var iteration = tbl.rows.length - 1; 
        
        // Clone Dummy Row        
        var clonedRow = $('#dummy_prefill_items_row_iteration').clone();
                
        // Get the outerHTML
        var clonedRowStr = clonedRow.prop("outerHTML");
                
        // Refactor variables
        clonedRowStr = clonedRowStr.replace(/style="display: none;"/, "");
        clonedRowStr = clonedRowStr.replace(/ disabled/g, "");
        clonedRowStr = clonedRowStr.replace(/dummy_/g, "");
        clonedRowStr = clonedRowStr.replace(/iteration/g, iteration);        
        
        // Append the row to the end of the table
        $(tbl).append(clonedRowStr);
              
        /* Event Binding - Refreshes All Rows */
        
        // Add row hovering styling        
        $(".item_row").mouseout(function() {                      
            $(this).addClass("row1");
            $(this).removeClass("row2"); 
        });
        $(".item_row").mouseover(function() {            
            $(this).addClass("row2");  
            $(this).removeClass("row1");
        });         
           
        // Monitor for change in Type selectbox
        $(".item_row select[id$='\\[type\\]']" ).off("change").on("change", function() {                       
            refreshPage();            
        });
        
        // Monitor Active Checkboxes for click
        $(".item_row input[id$='\\[active\\]']").click(function () {
            refreshPage();            
        });
                
        /* Monitor all input boxes for changes
        $(".item_row input[type='text']").off("change").on("change", function() {
            refreshPage();            
        });*/
        
        // Monitor all input boxes for Keyup
        $(".item_row input[type='text']").off("keyup").on("keyup", function() {
            refreshPage();            
        });
        
        // Item Delete button action
        $(".item_row .confirmDelete").off("click").on("click", function() {
            hideddrivetip();
            if(!confirmChoice('Are you Sure you want to delete this item?')) { return; }
            $(this).closest('tr').remove();
            refreshPage();                       
        });   
        
        /* Cleaning Up */
            
        // Return the current row index number
        return iteration;
                 
    }
    
    // Refresh all dynamic items onscreen
    function refreshPage() {
        
        // Disable all buttons on page refresh unless on initial page build, if there is a change
        if(pageBuilding === false) {            
            $(".userButton").prop('disabled', true).attr('title', '{t}This button is disabled until you have saved your changes.{/t}');
        }
        
    }
    
    // Check for duplicate entries in description fields
    function checkForDuplicates() {    
        
        // Duplicates found flag
        var duplicatesFound = false;
        
        // Loop through all descriptions
        $.each($(".item_row input[id$='\\[description\\]']"), function (index1, item1) {
            
            // Reset Styling
            $(item1).removeClass("duplicate-entry");

            // Compare this description against all other descriptions
            $.each($(".item_row input[id$='\\[description\\]']").not(this), function (index2, item2) {
                if ($(item1).val() == $(item2).val()) {
                    $(item1).addClass("duplicate-entry");
                    $(item1).attr('title', '{t}This is a duplicate entry and is not allowed.{/t}');
                    duplicatesFound = true;                    
                }
            });
            
        });
        
        // Return logic
        if(duplicatesFound === true) {
            alert("{t}Duplicate entries present. Each description must be unique.{/t}");
            return false;
        } else {
            return true;
        } 
        
    }
  
</script>
<style>
    .duplicate-entry {
        border: 1px solid red;
        color: red;
        font-weight: bold;
    }
</style>
                                                
<table width="100%" border="0" cellpadding="20" cellspacing="5">
    <tr>
        <td>
            <table width="100%" cellpadding="4" cellspacing="0" border="0" >
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{t}Edit Invoice Prefill Items{/t}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}INVOICE_PREFILL_ITEMS_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}INVOICE_PREFILL_ITEMS_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
                    </td>
                </tr>
                <tr>
                    <td class="menutd2" colspan="2">
                        <table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
                            <tr>
                                <td class="menutd">                                   
                                    <table width="100%" cellpadding="5" cellspacing="5">

                                        <!-- Prefill Items -->
                                        <tr>
                                            <td>
                                                <form method="post" action="index.php?component=invoice&page_tpl=prefill_items">
                                                    <table id="prefill_items" class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">

                                                        <!-- Table Headings -->
                                                        <tr>
                                                            <td class="olohead">{t}ID{/t}</td>
                                                            <td class="olohead">{t}Description{/t}</td>
                                                            <td class="olohead" align="center">{t}Type{/t}</td>
                                                            <td class="olohead" align="center"><b>{t}Unit Net{/t}</b></td>                                                        
                                                            <td class="olohead" align="center">{t}Active{/t}</td>                                                                                                               
                                                            <td class="olohead" align="center">{t}Action{/t}</td>
                                                        </tr>

                                                        <!-- Prefill Items Dummy Row -->   
                                                        <tr id="dummy_prefill_items_row_iteration" class="dummy_item_row olotd4 row1" style="display: none;">
                                                            <td class="olotd4" nowrap id="qform[prefill_items][iteration][invoice_prefill_id]">iteration</td>
                                                            <td class="olotd4" nowrap=""><input id="qform[prefill_items][iteration][description]" name="qform[prefill_items][iteration][description]" class="olotd5" size="50" value="" type="text" maxlength="50" required disabled onkeydown="return onlyAlphaNumericPunctuation(event);"></td>
                                                            <td class="olotd4" nowrap="">
                                                                <select id="qform[prefill_items][iteration][type]" name="qform[prefill_items][iteration][type]" class="olotd5" value="" required disabled>
                                                                    <option hidden disabled></option>
                                                                    <option value="Labour">{t}Labour{/t}</option>
                                                                    <option value="Parts">{t}Parts{/t}</option>
                                                                </select>
                                                            </td>                                                            
                                                            <td class="olotd4" nowrap="">{$currency_sym}<input id="qform[prefill_items][iteration][unit_net]" name="qform[prefill_items][iteration][unit_net]" class="olotd5" size="10" value="" type="text" maxlength="10" pattern="{literal}[0-9]{1,7}(.[0-9]{0,2})?{/literal}" required disabled onkeydown="return onlyNumberPeriod(event);"></td>
                                                            <td class="olotd4" nowrap=""><input id="qform[prefill_items][iteration][active]" name="qform[prefill_items][iteration][active]" class="olotd5" value="1" type="checkbox" disabled></td>
                                                            <td class="olotd4" nowrap="">                                                                
                                                                <img src="{$theme_images_dir}icons/delete.gif" alt="" border="0" height="14" width="14" class="confirmDelete" onmouseover="ddrivetip('<b>Delete Prefill Record</b>');" onmouseout="hideddrivetip();">
                                                            </td>
                                                        </tr>

                                                    </table>
                                                            
                                                    <!-- Button and Hidden Section -->
                                                    <table id="prefill_items" class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
                                                        <tr>
                                                            <td class="olotd4" colspan="6" nowrap>
                                                                <button type="button" onclick="createNewTableRow();">{t}Add{/t}</button>
                                                                <button type="submit" name="submit" value="submit" onclick="return checkForDuplicates() && confirmChoice('{t}Are you sure you want to update the invoice prefill items.{/t}');">{t}Submit{/t}</button>
                                                                <button type="button" class="olotd4" onclick="window.location.href='index.php?component=invoice&page_tpl=prefill_items';">{t}Cancel{/t}</button>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                            
                                                </form>
                                            </td>
                                        </tr>

                                        <!-- Export -->
                                        <tr>
                                            <td>
                                                <form method="post" action="index.php?component=invoice&page_tpl=prefill_items">
                                                    <strong><span style="color: green;">{t}Export Prefill Items as a CSV file{/t}</span></strong>                                                        
                                                    <button type="submit" name="submit" value="export" class="userButton">{t}Export{/t}</button>
                                                </form>
                                            </td>
                                        </tr>
                                        
                                        
                                        <!-- Import -->
                                        <tr>
                                            <td>                                                
                                                <strong><span style="color: red;">{t}Import Prefill Items from a CSV file{/t}</span></strong>                                                        
                                                <button id="importFormHideButton" href="javascript:void(0)" class="userButton">{t}Import{/t}</button>
                                                <div id="importForm">
                                                    <table width="100%">
                                                        <tr>
                                                            <td><a>{t}CSV File example{/t}</a></td>
                                                        </tr>
                                                        <tr>
                                                            <td><img src="{$theme_images_dir}csv_example_screenshot.png" alt="CSV Example Screenshot" height="150"/></td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <form action="index.php?component=invoice&page_tpl=prefill_items" method="post" enctype="multipart/form-data">
                                                                    <table width="350" border="0" cellpadding="1" cellspacing="1" class="box">
                                                                        <tr>
                                                                            <td width="246">                                                                                    
                                                                                <input name="invoice_prefill_csv" class="userButton" type="file" accept=".csv" required>
                                                                            </td>
                                                                            <td width="80"><button name="submit" type="submit" class="userButton box" value="import" onclick="return confirmChoice('{t}Are You sure you want to upload this CSV file with new prefill items.{/t}');">{t}Upload{/t}</button></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td width="246"><input name="empty_prefill_items_table" type="checkbox" value="1">{t}Empty Prefill Table{/t}</td>                                                                                
                                                                        </tr>
                                                                    </table>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>

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