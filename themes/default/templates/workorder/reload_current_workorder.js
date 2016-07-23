<!-- reload_current_workorder.js -->
<!-- This is required to load a Work Order's details after it has been created -->
{literal}
<script type="text/javascript">
    location.href={/literal}"?page=workorder:details&wo_id={$wo_id}&customer_id={$customer_id}&page_title={$translate_workorder_page_title} {$wo_id}"{literal}
</script>
{/literal}