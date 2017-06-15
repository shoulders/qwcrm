<!-- print_customer_workorder_slip.tpl - Customer Work Order Slip Print Template -->
<!DOCTYPE html>
<html lang="en-GB">
<head>
    <meta charset="utf-8">
    
    <!-- PDF Title -->
    <title>{t}Invoice{/t}{$invoice_details.INVOICE_ID}</title>   
        
    <!-- PDF Subject -->
    <meta name="description" content="{$meta_description}">
    
    <!-- PDF Keywords -->
    <meta name="keywords" content="{$meta_keywords}">
    
    <!-- PDF Author -->
    <meta name="author" content="QWcrm - QuantumWarp.com">       
    
    <link rel="icon" href="favicon.ico">
    <link rel="stylesheet" href="{$theme_css_dir}template.css">    
</head>

<body>

    <!-- Header Section -->
    <table width="900" border="0" cellpadding="2" cellspacing="0" style="border-collapse: collapse;">
        <tr bgcolor="#999999">
            <td width="20%" valign="middle" align="center"><img src="{$company_logo}" alt="" height="50"></td>
            <td valign="top" align="center">            
                <font size="+3">{t}Customer Workorder Slip{/t}</font><br />
                {t}Workorder ID{/t} {$single_workorder.WORK_ORDER_ID}
            </td>
            <td width="20%" valign="middle" align="center"></td>
        </tr>
    </table>

    <!-- Contact Information -->
    <table width="900" border="1" cellpadding="2" cellspacing="0" style="border-collapse: collapse;">
        <tr>
            <td width="50%" align="center" valign="middle"><b>{t}Customer Details{/t}</b></td>
            <td width="50%" align="center" valign="middle"><b>{t}Company Details{/t}</b></td>        
        </tr>    
        <tr>

            <!-- Client Details -->
            <td valign="top">
                <table border="0" cellpadding="4" cellspacing="0">
                    <tr>
                        <td>
                            <p><b><font size="+1">{$single_workorder.CUSTOMER_DISPLAY_NAME}</font></b></p>
                            <p>                            
                                <b>{t}Address{/t}:</b><br>
                                {$single_workorder.CUSTOMER_ADDRESS}<br>
                                {$single_workorder.CUSTOMER_CITY}, {$single_workorder.CUSTOMER_STATE} {$single_workorder.CUSTOMER_ZIP}
                            </p>
                            <p>
                                <b>{t}Contact{/t}: </b>{$single_workorder.CUSTOMER_FIRST_NAME} {$single_workorder.CUSTOMER_LAST_NAME}<br />
                                <b>{t}Phone{/t}: </b>{$single_workorder.CUSTOMER_PHONE}<br>
                                <b>{t}Work{/t}: </b>{$single_workorder.CUSTOMER_WORK_PHONE}<br>
                                <b>{t}Mobile{/t}: </b>{$single_workorder.CUSTOMER_MOBILE_PHONE}<br>
                                <b>{t}Email{/t}: </b>{$single_workorder.CUSTOMER_EMAIL}<br>                            
                            </p>
                            <p>
                                <b>{t}Type{/t}: </b> 
                                {if $single_workorder.CUSTOMER_TYPE == '1'}{t}CUSTOMER_TYPE_1{/t}{/if} 
                                {if $single_workorder.CUSTOMER_TYPE == '2'}{t}CUSTOMER_TYPE_2{/t}{/if} 
                                {if $single_workorder.CUSTOMER_TYPE == '3'}{t}CUSTOMER_TYPE_3{/t}{/if} 
                                {if $single_workorder.CUSTOMER_TYPE == '4'}{t}CUSTOMER_TYPE_4{/t}{/if} 
                                {if $single_workorder.CUSTOMER_TYPE == '5'}{t}CUSTOMER_TYPE_5{/t}{/if} 
                                {if $single_workorder.CUSTOMER_TYPE == '6'}{t}CUSTOMER_TYPE_6{/t}{/if} 
                                {if $single_workorder.CUSTOMER_TYPE == '7'}{t}CUSTOMER_TYPE_7{/t}{/if}
                                {if $single_workorder.CUSTOMER_TYPE == '8'}{t}CUSTOMER_TYPE_8{/t}{/if}
                                {if $single_workorder.CUSTOMER_TYPE == '9'}{t}CUSTOMER_TYPE_9{/t}{/if} 
                                {if $single_workorder.CUSTOMER_TYPE == '10'}{t}CUSTOMER_TYPE_10{/t}{/if}                            
                            </p>
                        </td>
                    </tr>
                </table>            
            </td>

            <!-- Company Info -->
            <td valign="top">
                <table cellpadding="4" cellspacing="0" border="0">                                  
                    <tr>
                        <td>
                            <p><b><font size="+1">{$company_details.COMPANY_NAME}</font></b><br></p>
                            <p>
                                <b>{t}Address{/t}:</b><br>
                                {$company_details.COMPANY_ADDRESS}<br>
                                {$company_details.COMPANY_CITY}, {$company_details.COMPANY_STATE} {$company_details.COMPANY_ZIP}
                            </p>
                            <p>
                                <b>{t}Phone{/t}: </b>{$single_workorder.EMPLOYEE_WORK_PHONE}<br>                        
                                <b>{t}Mobile{/t}: </b>{$company_details.COMPANY_MOBILE}<br>
                                <b>{t}Fax{/t}: </b>{$company_details.COMPANY_FAX}<br>
                                <b>{t}Website{/t}: </b>{$company_details.COMPANY_WWW}<br>   
                                <b>{t}Email{/t}: </b>{$company_details.COMPANY_EMAIL}
                            </p>
                        </td>
                    </tr>                                  
                </table>    
            </td>

        </tr>
    </table>

    <!-- Work Order Details -->
    <table width="900" border="1" cellpadding="2" cellspacing="0" style="border-collapse: collapse;">
        <tr>        
            <td valign="top" align="center" nowrap><b>{t}Work Order Details{/t}</b></td>        
            <td valign="top" align="center" nowrap><b>{t}Summary{/t}</b></td>
        </tr>
        <tr>

            <!-- Left Column -->
            <td valign="top" width="60%">
                <table border="0" cellpadding="4" cellspacing="0">               
                    <tr>
                        <td valign="top" nowrap><b>{t}Scope{/t}:</td>
                    </tr>
                    <tr>
                        <td valign="top" nowrap>{$single_workorder.WORK_ORDER_SCOPE}</td>
                    </tr>
                </table>

                <!-- Description -->
                <table border="0" cellpadding="4" cellspacing="0">
                    <tr>
                        <td><b>{t}Description{/t}:</b></td>
                    </tr>
                    <tr>
                        <td><div>{$single_workorder.WORK_ORDER_DESCRIPTION}</div></td>
                    </tr>
                </table>

            <!-- Right Column -->
            <td valign="top" width="20%">

                <!-- Summary -->
                <table border="0" cellpadding="4" cellspacing="0">
                    <tr>
                        <td valign="top" width="50%"><b>{t}Workorder ID{/t}</b></td>
                        <td valign="top">{$single_workorder.WORK_ORDER_ID}</td>
                    </tr>
                    <tr>
                        <td valign="top"><b>{t}Today's Date{/t}</b></td>
                        <td valign="top">{$smarty.now|date_format:$date_format}</td>
                    </tr>
                    <tr>
                        <td valign="top" nowrap><b>{t}Opened{/t}</b></td>
                        <td valign="top">{$single_workorder.WORK_ORDER_OPEN_DATE|date_format:$date_format}</td>
                    </tr>                
                    <tr>
                        <td valign="top" nowrap><b>{t}Technician{/t}</b></td>
                        <td valign="top">{$single_workorder.EMPLOYEE_DISPLAY_NAME}</td>
                    </tr>
                    <tr>
                        <td valign="top"><b>{t}workorder_status{/t}</b></td>
                        <td valign="top">
                            {if $single_workorder.WORK_ORDER_STATUS == '1'}{t}WORKORDER_STATUS_1{/t}{/if}
                            {if $single_workorder.WORK_ORDER_STATUS == '2'}{t}WORKORDER_STATUS_2{/t}{/if}
                            {if $single_workorder.WORK_ORDER_STATUS == '3'}{t}WORKORDER_STATUS_3{/t}{/if}
                            {if $single_workorder.WORK_ORDER_STATUS == '6'}{t}WORKORDER_STATUS_6{/t}{/if}
                            {if $single_workorder.WORK_ORDER_STATUS == '7'}{t}WORKORDER_STATUS_7{/t}{/if}
                            {if $single_workorder.WORK_ORDER_STATUS == '8'}{t}WORKORDER_STATUS_8{/t}{/if}
                            {if $single_workorder.WORK_ORDER_STATUS == '9'}{t}WORKORDER_STATUS_9{/t}{/if}
                            {if $single_workorder.WORK_ORDER_STATUS == '10'}{t}WORKORDER_STATUS_10{/t}{/if}
                        </td>
                    </tr>
                    <tr>
                        <td><b>{t}Last Activity{/t}:</b></td>
                        <td>{$single_workorder.LAST_ACTIVE|date_format:$date_format}</td>
                    </tr>
                </table>                

                <!-- Signatures -->
                <hr>
                <table width="100%" border="0" cellpadding="4" cellspacing="0">
                    <tr>
                        <td align="center" colspan="2"><b>{t}Signatures{/t}</b></td>
                    </tr>
                    <tr>
                        <td><b>{t}Client Name{/t}</b></td>
                        <td>__________________</td>
                    </tr>
                    <tr>
                        <td><b>{t}Signature{/t}</b></td>
                        <td>__________________</td>
                    </tr>
                    <tr>
                        <td><b>{t}Technician{/t}</b></td>
                        <td>__________________</td>
                    </tr>
                    <tr>
                        <td><b>{t}Signature{/t}</b></td>
                        <td>__________________</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                    </tr>
                </table>                    

            </td>
        </tr>
    </table>

    <!-- Equipment Receipt -->
    <table width="900" border="1" cellpadding="2" cellspacing="0" style="border-collapse: collapse;">   
        <tr border="0">
            <td border="0" align="center"><b>{t}Equipment Receipt{/t}</b></td>
        </tr>
        <tr>
            <td border="0" align="center">{t}Below is a list of the equipment you have left with us. Please check and make sure the list is correct as it cannot be remedied later.{/t}</td>        
        </tr>
        <tr>
            <td>
                <br>
                <table width="100%" align="center" border="0" cellpadding="2" cellspacing="0" style="border-collapse: collapse;">
                    <tr>
                        <td>
                            {t}Description{/t} : <input type="text" size="40" name="ddt1"><br><br>
                            {t}Description{/t} : <input type="text" size="40" name="ddt2"><br><br>
                            {t}Description{/t} : <input type="text" size="40" name="ddt3"><br><br>
                            {t}Description{/t} : <input type="text" size="40" name="ddt4"><br><br>
                            {t}Description{/t} : <input type="text" size="40" name="ddt5"><br><br>                                                                     
                        </td>
                        <td>
                            {t}Make/Model{/t} : <input type="text" size="40" name="ddt1"><br><br>
                            {t}Make/Model{/t} : <input type="text" size="40" name="ddt2"><br><br>
                            {t}Make/Model{/t} : <input type="text" size="40" name="ddt3"><br><br>
                            {t}Make/Model{/t} : <input type="text" size="40" name="ddt4"><br><br>
                            {t}Make/Model{/t} : <input type="text" size="40" name="ddt5"><br><br>                                                                       
                        </td>
                        <td>
                            {t}Qty{/t} : <input type="text" size="3" name="tt1"><br><br>
                            {t}Qty{/t} : <input type="text" size="3" name="tt2"><br><br>
                            {t}Qty{/t} : <input type="text" size="3" name="tt3"><br><br>
                            {t}Qty{/t} : <input type="text" size="3" name="tt4"><br><br>
                            {t}Qty{/t} : <input type="text" size="3" name="tt5"><br><br>                                                                    
                        </td>
                    </tr>
                </table>
            </td>
        </tr>  
    </table>

    <!-- Disclaimer -->
    <table width="900" border="1" cellpadding="2" cellspacing="0" style="border-collapse: collapse;">   
        <tr border="0">
            <td border="0" align="center"><b>{t}Disclaimer{/t}</b></td>
        </tr>
        <tr>
            <td border="0" align="center">{t}We have a duty of care to preserve your computers data whilst we are servicing it, however it is the customers responsibilty to ensure that this data is reliably backed before work is started just incase data loss should occur. If you do not agree to these terms you cannot use our services.{/t}</td>        
        </tr>
    </table>

    <!-- Important Notes -->
    <table width="900" border="1" cellpadding="2" cellspacing="0" style="border-collapse: collapse;">   
        <tr border="0">
            <td border="0" align="center">
                <b>{t}Important Notes{/t}</b></td>
        </tr>
        <tr>
            <td border="0">{t}<ul><li>Please hold onto this receipt as proof of service request</li><li>This document (copies will not be accepted) MUST be produce this at time of pickup. If this can't be provided then photo identification is required.</li></ul>{/t}</td>        
        </tr>
    </table>

    <!-- Footer Section -->
    <table width="900" border="1" cellpadding="2" cellspacing="0" style="border-collapse: collapse;">   
        <tr border="0">
            <td border="0" align="center">{t}This Work Order is confidential and contains privileged information.{/t}</td>
        </tr>
    </table>
        
</body>
</html>