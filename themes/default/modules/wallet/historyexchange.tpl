<!-- BEGIN: main -->
<div class="clearfix form-group">
    <h1 class="pull-left">{LANG.historyexchange}</h1>
  
</div>
<div class="table-responsive table_wallet">
    <table class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th class="text-center" style="width: 120px;">{LANG.transaction_code}</th>
                <th class="text-right" style="width: 130px;">{LANG.customer_fullname}</th>
                <th class="text-right" style="width: 130px;">{LANG.customer_company}</th>
                <th class="text-right" style="width: 130px;">{LANG.moneytransaction}</th>
                <!--<th class="text-left">{LANG.infotransaction}</th>-->
                <th class="text-right" style="width: 140px;">{LANG.datetransaction}</th>
                <th class="text-left" style="width: 130px;">{LANG.transition_status}</th>
            </tr>
        </thead>
        <tbody>
            <!-- BEGIN: loop -->
            <tr>
                <td class="text-center">{ROW.transaction_code}</td>
                <td class="text-left">{ROW.customer_name}</td> 
                <td class="text-left">{ROW.customer_company}</td> 
                <td class="text-right"><strong class="text-success">{ROW.status}{ROW.money_net} {ROW.money_unit}</strong></td>
                <!--<td class="text-left">{ROW.transaction_info}</td>-->
                <td class="text-right">{ROW.created_time}</td>
                <td class="text-left">{ROW.transaction_status}</td>
            </tr>
            <!-- END: loop -->
        </tbody>
    </table>
</div>
<!-- BEGIN: generate_page -->
<div class="text-right">
    {GENERATE_PAGE}
</div>
<!-- END: generate_page -->
<!-- END: main -->