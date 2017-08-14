<!-- print_customer_envelope -->
<!DOCTYPE html>
<html lang="en-GB">
<head>
    <meta charset="utf-8">
    
    <!-- PDF Title -->
    <title>{t}INVOICE_PRINT_CUSTOMER_ENVELOPE_PAGE_TITLE{/t}</title>   
        
    <!-- PDF Subject -->
    <meta name="description" content="{t}INVOICE_PRINT_CUSTOMER_ENVELOPE_META_DESCRIPTION{/t}">
    
    <!-- PDF Keywords -->
    <meta name="keywords" content="{t}INVOICE_PRINT_CUSTOMER_ENVELOPE_META_KEYWORDS{/t}">
    
    <!-- PDF Author -->
    <meta name="author" content="QWcrm - QuantumWarp.com">       
    
    <link rel="icon" href="favicon.ico">
    <link rel="stylesheet" href="{$theme_css_dir}template.css">    
</head>

<body>
    
    <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse; margin: 20px;">
        
        <!-- LOGO and Company Name-->
        <tr>            
            <td width="250px">
                <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
                    <tr>
                        <td align="center"><img src="{$theme_images_dir}logo.png" width="150px" alt="" border="0"></td>
                    </tr>
                    <tr><td style="text-align:center">{$company_details.name}</td></tr>
                </table>
            </td>
        </tr>
        
        <!-- Divider -->
        <tr>
            <td>&nbsp;</td>
        </tr>
        
        <!-- Customer Address -->
        <tr>
            <td>&nbsp;</td>
            <td align="center">
                <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
                    <tr>                        
                        <td>
                            <span style="font-size: 20px;">
                                {$customer_details.display_name}<br>
                                {$customer_details.address|nl2br|regex_replace:"/[\r\t\n]/":" "}<br>
                                {$customer_details.city}<br>
                                {$customer_details.state}<br>
                                {$customer_details.zip}<br>
                                {$customer_details.country}
                            </span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    
</body>
</html>