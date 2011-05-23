<script type="text/javascript">
{literal}

// Performs a redirect to the new Search Page Number - takes the inputted number and adds it ot the end
function GotoPageNumber() {
        document.page_select.action='?page=expense%3Aview&submit=submit&pagetitle={}page_no=' + document.page_select.page_select_number.value
        return true;
}

// Shows a Confirm Delete Dialogue Box for Labour Item
    function confirmLabourDelete(labourID, invoice_id, wo_id, customer_id){

        var answer = confirm ("{/literal}{$translate_invoice_labour_delete_mes_confirmation}{literal}")
        if (answer){
            window.location='?page=invoice:delete&deleteType=labourRecord&labourID=' + labourID + '&invoice_id=' + invoice_id + '&wo_id=' + wo_id + '&customer_id=' + customer_id
            alert("{/literal}{$translate_invoice_delete_mes_recorddeleted}{literal}")
    }
    else{
        alert("{/literal}{$translate_invoice_delete_mes_recordnotdeleted}{literal}")
    }
}

// Shows a Confirm Delete Dialogue Box for Parts
    function confirmPartsDelete(partsID, invoice_id, wo_id, customer_id){

        var answer = confirm ("{/literal}{$translate_invoice_parts_delete_mes_confirmation}{literal}")
        if (answer){
            window.location='?page=invoice:delete&deleteType=partsRecord&partsID=' + partsID + '&invoice_id=' + invoice_id + '&wo_id=' + wo_id + '&customer_id=' + customer_id
            alert("{/literal}{$translate_invoice_delete_mes_recorddeleted}{literal}")
    }
    else{
        alert("{/literal}{$translate_invoice_delete_mes_recordnotdeleted}{literal}")
    }
}

{/literal}
</script>
