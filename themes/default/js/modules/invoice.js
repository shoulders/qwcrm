{literal}

// Shows a Confirm Delete Dialogue Box for Labour Item
function confirmLabourDelete(labour_id, invoice_id) {

    var answer = confirm ("{/literal}{$translate_invoice_labour_delete_mes_confirmation}{literal}");
    if (answer) {
        window.location='?page=invoice:edit&invoice_id=' + invoice_id + '&deleteType=labourRecord&labour_id=' + labour_id;
        alert("{/literal}{$translate_invoice_delete_mes_recorddeleted}{literal}");         
    } else {
        alert("{/literal}{$translate_invoice_delete_mes_recordnotdeleted}{literal}");
    }
    
}

// Shows a Confirm Delete Dialogue Box for Parts
function confirmPartsDelete(parts_id, invoice_id) {

    var answer = confirm ("{/literal}{$translate_invoice_parts_delete_mes_confirmation}{literal}");
    if (answer){
        window.location='?page=invoice:edit&invoice_id=' + invoice_id + '&deleteType=partsRecord&parts_id=' + parts_id;
        alert("{/literal}{$translate_invoice_delete_mes_recorddeleted}{literal}");      
    } else {
        alert("{/literal}{$translate_invoice_delete_mes_recordnotdeleted}{literal}");
    }    
    
}

{/literal}

