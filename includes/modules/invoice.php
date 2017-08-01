<?php

/*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

/*
 * Mandatory Code - Code that is run upon the file being loaded
 * Display Functions - Code that is used to primarily display records - linked tables
 * New/Insert Functions - Creation of new records
 * Get Functions - Grabs specific records/fields ready for update - no table linking
 * Update Functions - For updating records/fields
 * Close Functions - Closing Work Orders code
 * Delete Functions - Deleting Work Orders
 * Other Functions - All other functions not covered above
 */

defined('_QWEXEC') or die;

/** Mandatory Code **/

/** Display Functions **/

#########################################
#     Display Invoices                  # // Status = IS_PAID  0 = unpaid, 1 = paid
#########################################

function display_invoices($db, $direction = 'DESC', $use_pages = false, $page_no = '1', $records_per_page = '25', $search_term = null, $search_category = null, $status = null, $employee_id = null, $customer_id = null) {

    global $smarty;
    
    /* Filter the Records */
    
    // Default Action
    $whereTheseRecords = " WHERE ".PRFX."invoice.invoice_id";
    
    // Restrict results by search category and search term
    if($search_term != null) {$whereTheseRecords .= " AND ".PRFX."invoice.$search_category LIKE '%$search_term%'";} 
    
    // Restrict by Status
    if($status != null) {$whereTheseRecords = " AND ".PRFX."invoice.is_paid=".$db->qstr($status);} 

    // Restrict by Employee
    if($employee_id != null) {$whereTheseRecords .= " AND ".PRFX."invoice.employee_id=".$db->qstr($employee_id);}        

    // Restrict by Customer
    if($customer_id != null) {$whereTheseRecords .= " AND ".PRFX."invoice.customer_id=".$db->qstr($customer_id);}
    
    /* The SQL code */
    
    $sql = "SELECT        
        ".PRFX."invoice.*,           
        ".PRFX."user.display_name AS employee_display_name,
        ".PRFX."user.work_phone AS employee_work_phone,
        ".PRFX."user.work_mobile_phone AS employee_work_mobile_phone,
        ".PRFX."user.home_mobile_phone AS employee_home_mobile_phone,
        ".PRFX."customer.display_name AS customer_display_name,
        ".PRFX."customer.first_name AS customer_first_name,
        ".PRFX."customer.last_name AS customer_last_name,
        ".PRFX."customer.phone AS customer_phone,
        ".PRFX."customer.mobile_phone AS customer_mobile_phone,
        ".PRFX."customer.fax AS customer_fax
        FROM ".PRFX."invoice
        LEFT JOIN ".PRFX."user ON ".PRFX."invoice.employee_id = ".PRFX."user.user_id
        LEFT JOIN ".PRFX."customer ON ".PRFX."invoice.customer_id = ".PRFX."customer.customer_id
        ".$whereTheseRecords."
        GROUP BY ".PRFX."invoice.invoice_id           
        ORDER BY ".PRFX."invoice.invoice_id
        ".$direction;
            
    /* Restrict by pages */
    
    if($use_pages == true) {
        
        // Get the start Record
        $start_record = (($page_no * $records_per_page) - $records_per_page);        
        
        // Figure out the total number of records in the database for the given search        
        if(!$rs = $db->Execute($sql)) {
            force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to count the matching Invoice records."));
            exit;
        } else {        
            $total_results = $rs->RecordCount();            
            $smarty->assign('total_results', $total_results);
        } 
        
        // Figure out the total number of pages. Always round up using ceil()
        $total_pages = ceil($total_results / $records_per_page);
        $smarty->assign('total_pages', $total_pages);

        // Set the page number
        $smarty->assign('page_no', $page_no);

        // Assign the Previous page
        if($page_no > 1) {
            $previous = ($page_no - 1);            
        } else { 
            $previous = 1;            
        }
        $smarty->assign('previous', $previous);        
        
        // Assign the next page
        if($page_no < $total_pages){
            $next = ($page_no + 1);            
        } else {
            $next = $total_pages;
        }
        $smarty->assign('next', $next);
        
        // Only return the given page's records
        $limitTheseRecords = " LIMIT ".$start_record.", ".$records_per_page;
        
        // add the restriction on to the SQL
        $sql .= $limitTheseRecords;
        
    } else {
        
        // This make the drop down menu look correct
        $smarty->assign('total_pages', 1);
        
    }

    /* Return the records */
         
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to return the matching Invoice records."));
        exit;
    } else {
        
        $records = $rs->GetArray();   // do i need to add the check empty

        if(empty($records)){
            
            return false;
            
        } else {
            
            return $records;
            
        }
        
    }
    
}

/** New/Insert Functions **/

#####################################
#     insert invoice                #
#####################################

function insert_invoice($db, $customer_id, $workorder_id, $discount_rate, $tax_rate) {
    
    $sql = "INSERT INTO ".PRFX."invoice SET     
            employee_id     =". $db->qstr( QFactory::getUser()->login_user_id   ).",
            customer_id     =". $db->qstr( $customer_id                         ).",
            workorder_id    =". $db->qstr( $workorder_id                        ).",
            date            =". $db->qstr( time()                               ).",
            due_date        =". $db->qstr( time()                               ).",            
            discount_rate   =". $db->qstr( $discount_rate                       ).",            
            tax_rate        =". $db->qstr( $tax_rate                            );            

    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to insert the invoice record into the database."));
        exit;
    } else {
        
        return $db->insert_id();
        
    }    
    
}

#####################################
#     Insert Labour Items           #
#####################################

function insert_labour_items($db, $invoice_id, $description, $amount, $qty) {
    
    // Insert Labour Items into database (if any)
    if($qty > 0 ) {
        
        $i = 1;
        
        $sql = "INSERT INTO ".PRFX."invoice_labour (invoice_id, description, amount, qty, sub_total) VALUES ";
        
        foreach($qty as $key) {
            
            $sql .="(".
                    
                    $db->qstr( $invoice_id              ).",".                    
                    $db->qstr( $description[$i]         ).",".
                    $db->qstr( $amount[$i]              ).",".
                    $db->qstr( $qty[$i]                 ).",".
                    $db->qstr( $qty[$i] * $amount[$i]   ).
                    
                    "),";
            
            $i++;
            
        }
        
        // Strips off last comma as this is a joined SQL statement
        $sql = substr($sql , 0, -1);
        
        if(!$rs = $db->Execute($sql)) {
            force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to insert Labour item into the database."));
            exit;
        }
        
    }
        
}

#####################################
#     Insert Parts Items            #
#####################################

function insert_parts_items($db, $invoice_id, $description, $amount, $qty) {
    
    // Insert Parts Items into database (if any)
    if($qty > 0 ) {
        
        $i = 1;
        
        $sql = "INSERT INTO ".PRFX."invoice_parts (invoice_id, description, amount, qty, sub_total) VALUES ";
        
        foreach($qty as $key) {
            
            $sql .="(".
                    
                    $db->qstr( $invoice_id              ).",".                    
                    $db->qstr( $description[$i]         ).",".                  
                    $db->qstr( $amount[$i]              ).",".
                    $db->qstr( $qty[$i]                 ).",".
                    $db->qstr( $qty[$i] * $amount[$i]   ).
                    
                    "),";
            
            $i++;
            
        }
        
        // Strips off last comma as this is a joined SQL statement
        $sql = substr($sql ,0,-1);
        
        if(!$rs = $db->Execute($sql)) {
            force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to insert Parts item into the database."));
            exit;
        }
        
    }

}

#####################################
#   insert invoice prefill item     #
#####################################

function insert_invoice_prefill_item($db, $VAR){
    
    $sql = "INSERT INTO ".PRFX."invoice_prefill_items SET
            description =". $db->qstr( $VAR['description']  ).",
            type        =". $db->qstr( $VAR['type']         ).",
            amount      =". $db->qstr( $VAR['amount']       ).",
            active      =". $db->qstr( $VAR['active']       );

    if(!$rs = $db->execute($sql)){        
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to insert an invoice prefill item into the database."));
        exit;
    }
    
}

/** Get Functions **/

#####################################
#   Get invoice details             #
#####################################

function get_invoice_details($db, $invoice_id, $item = null) {
    
    $sql = "SELECT * FROM ".PRFX."invoice WHERE invoice_id =".$db->qstr($invoice_id);
    
    if(!$rs = $db->execute($sql)){        
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to get invoice details."));
        exit;
    } else {
        
        if($item === null){
            
            return $rs->GetRowAssoc(); 
            
        } else {
            
            return $rs->fields[$item];   
            
        } 
        
    }
        
}

#########################################
#   Get All invoice labour details      #
#########################################

function get_invoice_labour_items($db, $invoice_id) {
    
    $sql = "SELECT * FROM ".PRFX."invoice_labour WHERE invoice_id=".$db->qstr( $invoice_id );
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to get invoice labour items."));
        exit;
    } else {
        
        if(!empty($rs)) {
        
            return $rs->GetArray();
        
        }
        
    }    
    
}
#######################################
#   Get invoice labour item details   #
#######################################

function get_invoice_labour_item_details($db, $labour_id, $item = null) {
    
    $sql = "SELECT * FROM ".PRFX."invoice_labour WHERE invoice_labour_id =".$db->qstr($labour_id);
    
    if(!$rs = $db->execute($sql)){        
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to get invoice labour item details."));
        exit;
    } else {
        
        if($item === null){
            
            return $rs->GetArray(); 
            
        } else {
            
            return $rs->fields[$item];   
            
        } 
        
    }
        
}

#####################################
#   Get All invoice parts details   #
#####################################

function get_invoice_parts_items($db, $invoice_id) {
    
    $sql = "SELECT * FROM ".PRFX."invoice_parts WHERE invoice_id=".$db->qstr( $invoice_id );
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to get invoice parts items."));
        exit;
    } else {
        
        if(!empty($rs)) {
        
            return $rs->GetArray();
        
        }
        
    }    
    
}

#######################################
#   Get invoice parts item details    #
#######################################

function get_invoice_parts_item_details($db, $parts_id, $item = null) {
    
    $sql = "SELECT * FROM ".PRFX."invoice_parts WHERE invoice_parts_id =".$db->qstr($parts_id);
    
    if(!$rs = $db->execute($sql)){        
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to get invoice parts item details."));
        exit;
    } else {
        
        if($item === null){
            
            return $rs->GetArray(); 
            
        } else {
            
            return $rs->fields[$item];   
            
        } 
        
    }
        
}

#######################################
#   Get invoice prefill items         #
#######################################

function get_invoice_prefill_items($db, $type = null, $status = null) {
    
    $sql = "SELECT * FROM ".PRFX."invoice_prefill_items";
    
    // prepare the sql for the optional filter
    $sql .= " WHERE invoice_prefill_id >= 1";

    // filter by type
    if($type) { $sql .= " AND type=".$db->qstr($type);}    
    
    // filter by status
    if($status) {$sql .= " AND active=".$db->qstr($status);}
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to get the invoice prefill items for the selected status."));
        exit;
    } else {
        
        if(!empty($rs)) {
        
            return $rs->GetArray();
        
        }
        
    }    
    
}

/** Update Functions **/

######################
#   update invoice   #
######################

function update_invoice($db, $invoice_id, $date, $due_date, $discount_rate) {
    
    $sql = "UPDATE ".PRFX."invoice SET
            date                =". $db->qstr( date_to_timestamp($date)     ).",
            due_date            =". $db->qstr( date_to_timestamp($due_date) ).",
            discount_rate       =". $db->qstr( $discount_rate               )."
            WHERE invoice_id    =". $db->qstr( $invoice_id                  );

    if(!$rs = $db->execute($sql)){        
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to update the invoice dates and discount rate."));
        exit;
    }
    
}

########################################################
#   update invoice after a transaction has been added  #
########################################################

function update_invoice_transaction_only($db, $invoice_id, $paid_amount, $balance, $paid_status, $paid_date = '') {
    
    $sql = "UPDATE ".PRFX."invoice SET
            paid_amount         =". $db->qstr( $paid_amount ).",
            balance             =". $db->qstr( $balance     ).",
            is_paid             =". $db->qstr( $paid_status ).",
            paid_date           =". $db->qstr( $paid_date   )."                       
            WHERE invoice_id    =". $db->qstr( $invoice_id  );

    if(!$rs = $db->execute($sql)){        
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to update the invoice's financial totals."));
        exit;
    }
    
}

#####################################
#     update invoice (full)         # // not used anywhere
#####################################

function update_invoice_full($db, $invoice_id, $employee_id, $customer_id, $workorder_id, $date, $due_date, $discount_rate, $discount_amount, $tax_rate, $tax_amount, $sub_total, $total, $paid_amount, $balance, $is_paid, $paid_date) {
    
    $sql = "UPDATE ".PRFX."invoice SET     
            employee_id         =". $db->qstr( $employee_id     ).", 
            customer_id         =". $db->qstr( $customer_id     ).",
            workorder_id        =". $db->qstr( $workorder_id    ).",
            date                =". $db->qstr( $date            ).",
            due_date            =". $db->qstr( $due_date        ).", 
            discount_rate       =". $db->qstr( $discount_rate   ).",
            discount_amount     =". $db->qstr( $discount_amount ).",   
            tax_rate            =". $db->qstr( $tax_rate        ).",
            tax_amount          =". $db->qstr( $tax_amount      ).", 
            sub_total           =". $db->qstr( $sub_total       ).",
            total               =". $db->qstr( $total           ).", 
            paid_amount         =". $db->qstr( $paid_amount     ).",
            balance             =". $db->qstr( $balance         ).",      
            is_paid             =". $db->qstr( $is_paid         ).",
            paid_date           =". $db->qstr( $paid_date       )."
            WHERE invoice_id    =". $db->qstr( $invoice_id      );

    if(!$rs = $db->execute($sql)){        
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to update the invoice."));
        exit;
    }    
    
}

#####################################
#     update invoice rate item      #
#####################################

function update_invoice_prefill_item($db, $VAR){
    
    $sql = "UPDATE ".PRFX."invoice_prefill_items SET
            description                 =". $db->qstr( $VAR['description']          ).",
            type                        =". $db->qstr( $VAR['type']                 ).",
            amount                      =". $db->qstr( $VAR['amount']               ).",
            active                      =". $db->qstr( $VAR['active']               )."            
            WHERE invoice_prefill_id    =". $db->qstr( $VAR['invoice_prefill_id']   );

    if(!$rs = $db->execute($sql)){        
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to update an invoice labour rates item."));
        exit;
    }
    
}

/** Close Functions **/

/** Delete Functions **/

#####################################
#   Delete Invoice                  #
#####################################

function delete_invoice($db, $invoice_id) {
    
    $sql = "DELETE FROM ".PRFX."invoice WHERE invoice_id=".$db->qstr($invoice_id);

    if(!$rs = $db->execute($sql)){        
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to delete the invoice."));
        exit;
    } else {
        
        return true;
        
    }
    
}

#####################################
#   Delete Labour Item              #
#####################################

function delete_invoice_labour_item($db, $labour_id) {
    
    $sql = "DELETE FROM ".PRFX."invoice_labour WHERE invoice_labour_id=" . $db->qstr($labour_id);

    if(!$rs = $db->execute($sql)){        
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to delete an invoice labour item."));
        exit;
    } else {
        
        return true;
        
    }
}

#####################################
#   Delete Parts Item               #
#####################################

function delete_invoice_parts_item($db, $parts_id) {
    
    $sql = "DELETE FROM ".PRFX."invoice_parts WHERE invoice_parts_id=" . $db->qstr($parts_id);

    if(!$rs = $db->execute($sql)){        
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to delete an invoice parts item."));
        exit;
    } else {
        
        return true;
        
    }

}

#####################################
#     delete labour rate item       #
#####################################

function delete_invoice_prefill_item($db, $invoice_prefill_id){
    
    $sql = "DELETE FROM ".PRFX."invoice_prefill_items WHERE invoice_prefill_id =".$invoice_prefill_id;

    if(!$rs = $db->execute($sql)){        
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to delete an invoice prefill item."));
        exit;
    }
    
}

/** Other Functions **/

#####################################
#   Sum Labour Sub Totals           #
#####################################

function labour_sub_total($db, $invoice_id) {
    
    $sql = "SELECT SUM(sub_total) AS sub_total_sum FROM ".PRFX."invoice_labour WHERE invoice_id=". $db->qstr($invoice_id);
    
    if(!$rs = $db->execute($sql)){        
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to calculate the invoice labour sub total."));
        exit;
    } else {
        
        return $rs->fields['sub_total_sum'];
        
    }    
    
}

#####################################
#   Sum Parts Sub Total             #
#####################################

function parts_sub_total($db, $invoice_id) {
    
    $sql = "SELECT SUM(sub_total) AS sub_total_sum FROM ".PRFX."invoice_parts WHERE invoice_id=" . $db->qstr($invoice_id);
    
    if(!$rs = $db->execute($sql)){        
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to calculate the invoice parts sub total."));
        exit;
    } else {
        
        return  $rs->fields['sub_total_sum'];
        
    }
  
}

#####################################
#   Recalculate Invoice Totals      #
#####################################

function recalculate_invoice_totals($db, $invoice_id) {
    
    $sub_total = labour_sub_total($db, $invoice_id) + parts_sub_total($db, $invoice_id);    
    $discount_rate = get_invoice_details($db, $invoice_id, 'discount_rate');
    $discount_amount = $sub_total * ($discount_rate / 100); // divide by 100; turns 17.5 in to 0.17575
    $tax_amount = ($sub_total - $discount) * ((get_invoice_details($db, $invoice_id, 'tax_rate')/ 100)); // divide by 100; turns 17.5 in to 0.175  
    $total = ($sub_total - $discount) + $tax;    
    $balance = $total - $paid_amount = get_invoice_details($db, $invoice_id, 'paid_amount');

    $sql = "UPDATE ".PRFX."invoice SET      
            discount_amount     =". $db->qstr( $discount_amount ).",
            tax_amount          =". $db->qstr( $tax_amount      ).", 
            sub_total           =". $db->qstr( $sub_total       ).", 
            total               =". $db->qstr( $total           ).",
            balance             =". $db->qstr( $balance         )."                
            WHERE invoice_id    =". $db->qstr( $invoice_id      );

    if(!$rs = $db->execute($sql)){        
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed update the invoice totals."));
        exit;
    }
    
}

###################################
#  Does invoice have a workorder  #
###################################

function check_invoice_has_workorder($db, $invoice_id) {
    
    $sql = "SELECT workorder_id FROM ".PRFX."invoice WHERE invoice_id=".$invoice_id;
    
    if(!$rs = $db->Execute($sql)) {        
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to check if the invoice has a work order."));
        exit;
    } else {        
        
        $temp = $rs->Fields('workorder_id');
        
        if($temp == 0) {
            
            return false;
            
        } else {          
            
            return true;
            
        }
        
    }
    
}

#####################################
#   Upload labour rates CSV file    #
#####################################

function upload_invoice_prefill_items_csv($db, $VAR) {

    // Allowed extensions
    $allowedExts = array('csv');
    
    // Get file extension
    $filename_info = pathinfo($_FILES['invoice_prefill_csv']['name']);
    $extension = $filename_info['extension'];
    
    // Validate the uploaded file is allowed (extension, mime type, 0 - 2mb)
    if ((($_FILES['invoice_prefill_csv']['type'] == 'text/csv'))            
            || ($_FILES['invoice_prefill_csv']['type'] == 'application/vnd.ms-excel')     // CSV files created by excel - i might remove this
                //|| ($_FILES['invoice_prefill_csv']['type'] == 'text/plain')             // this seems a bit dangerous   
            && ($_FILES['invoice_prefill_csv']['size'] > 0)   
            && ($_FILES['invoice_prefill_csv']['size'] < 2048000)
            && in_array($extension, $allowedExts)) {

        // Check for file submission errors and echo them
        if ($_FILES['invoice_prefill_csv']['error'] > 0 ) {
            echo gettext("Return Code").': ' . $_FILES['invoice_prefill_csv']['error'] . '<br />';                

        // If no errors then proceed to processing the data
        } else {        

            // Empty Current Invoice Rates Table (if set)
            if($VAR['empty_prefill_items_table'] === '1'){
                
                $sql = "TRUNCATE ".PRFX."invoice_prefill_items";
                
                if(!$rs = $db->execute($sql)) {
                    force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to empty the prefill items table."));
                    exit;                    
                }
            }
            
            // Open CSV file            
            $handle = fopen($_FILES['invoice_prefill_csv']['tmp_name'], 'r');

            // Row counter to allow for header line
            $row = 1;

            // Read CSV data and insert into database            
            while (($data = fgetcsv($handle)) !== FALSE) {
                
                // Skip the first line with the column names
                if($row == 1) {                    
                    $row++;
                    continue;               
                }

                $sql = "INSERT INTO ".PRFX."invoice_prefill_items(description, type, amount, active) VALUES ('$data[0]','$data[1]','$data[2]','$data[3]')";

                if(!$rs = $db->execute($sql)) {
                    force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to insert the new prefill items into the database."));
                    exit;                    
                }
                
                $row++;

            }

            // Close CSV file
            fclose($handle);

            // Delete CSV file - not sure this is needed becaus eit is temp
            unlink($_FILES['invoice_prefill_csv']['tmp_name']);

        }

    // If file is invalid then load the error page  
    } else {
        
        /*
        echo "Upload: "    . $_FILES['invoice_prefill_csv']['name']           . '<br />';
        echo "Type: "      . $_FILES['invoice_prefill_csv']['type']           . '<br />';
        echo "Size: "      . ($_FILES['invoice_prefill_csv']['size'] / 1024)  . ' Kb<br />';
        echo "Temp file: " . $_FILES['invoice_prefill_csv']['tmp_name']       . '<br />';
        echo "Stored in: " . MEDIA_DIR . $_FILES['file']['name']       ;
         */
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to update the invoice labour rates because the submitted file was invalid."));

    }
    
}

##################################################
#   Export Invoice Prefill Items as a  CSV file  #
##################################################

function export_invoice_prefill_items_csv($db) {
    
    $sql = "SELECT description, type, amount, active FROM ".PRFX."invoice_prefill_items";
    
    if(!$rs = $db->Execute($sql)) {        
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to get invoice prefill items from the database."));
        exit;
    } else {        
        
        $prefill_items = $rs->GetArray();
        
        // output headers so that the file is downloaded rather than displayed
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=qwcrm_invoice_prefill_items.csv');
        
        // create a file pointer connected to the output stream
        $output_stream = fopen('php://output', 'w');
        
        // output the column headings
        fputcsv($output_stream, array(gettext("Description"), gettext("Type"), gettext("Amount"), gettext("Active")));

        // loop over the rows, outputting them
        foreach($prefill_items as $key => $value) {
            $row = array($value['description'], $value['type'], $value['amount'], $value['active']);
            fputcsv($output_stream, $row);
            
        }       
        
        // close the csv file
        fclose($output_stream);        
        
    }    
    
}