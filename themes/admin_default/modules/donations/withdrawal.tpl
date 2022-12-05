<!-- BEGIN: main -->
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.css" rel="stylesheet" />

<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.js"></script>

<div class="wraper">
	<div class="row">
		<div class="col-sm-8">
			<div class="well">
				{LANG.search_acount}
			</div>
			<select class="iduser form-control" name="iduser">
				<option value="0"> {LANG.search_acount} </option>
			 </select>
		</div>
		<div class="col-sm-8">
			<div class="well">
				{LANG.search_date_from}
			</div>
			<div class="form-group">
				<div class="input-group">
					  <input type="text" class="form-control" readonly name="rtf" id="rtf" value="" placeholder="{LANG.search_date_from}"/>
					   <div class="input-group-btn">
							<button type="buttom" class="btn btn-default" data-toggle="pickdate"><i class="fa fa-calendar"></i></button>
					   </div>
				</div>
			</div>
		</div>
		<div class="col-sm-8">
			<div class="well">
				{LANG.search_date_to}
			</div>
			<div class="form-group">
				<div class="input-group">
					  <input type="text" class="form-control" readonly name="trf" id="trf" value="" placeholder="{LANG.search_date_to}"/>
					   <div class="input-group-btn">
							<button type="buttom" class="btn btn-default" data-toggle="pickdate"><i class="fa fa-calendar"></i></button>
					   </div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="wraper-acount" id="hienthi">

<!-- BEGIN: view -->

<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th class="w100">{LANG.weight}</th>
                    <th>{LANG.userid}</th>
                    <th>{LANG.acount}</th>
                    <th>{LANG.information}</th>
                    <th>{LANG.money_out}</th>
                    <th>{LANG.transaction_time}</th>
                    <th>{LANG.transaction_info}</th>
					<td> {LANG.transaction_time_update} </td>
                    <td> {LANG.userid_update} </td>
                    <th class="w100 text-center">{LANG.active}</th>
                </tr>
            </thead>
            <!-- BEGIN: generate_page -->
            <tfoot>
                <tr>
                    <td class="text-center" colspan="10">{NV_GENERATE_PAGE}</td>
                </tr>
            </tfoot>
            <!-- END: generate_page -->
            <tbody>
                <!-- BEGIN: loop -->
                <tr>
                    <td>
                        <select class="form-control" id="id_weight_{VIEW.id}" onchange="nv_change_weight('{VIEW.id}');">
                        <!-- BEGIN: weight_loop -->
                            <option value="{WEIGHT.key}"{WEIGHT.selected}>{WEIGHT.title}</option>
                        <!-- END: weight_loop -->
						</select>
					</td>
                    <td> {VIEW.userid} </td>
                    <td> {VIEW.acount} </td>
                    <td> {VIEW.information} </td>
                    <td> {VIEW.money_out} </td>
                    <td> {VIEW.transaction_time} </td>
                    <td> {VIEW.transaction_info} </td>
					<td> {VIEW.transaction_time_update} </td>
                    <td> {VIEW.userid_update} </td>

					<td style="width: 15%" id="hienthi">
                        <select class="form-control" {check} id="change_status_{VIEW.id}" name="status" onchange="nv_change_status({VIEW.id});">
                        <!-- BEGIN: status_loop -->
                            <option value="{status.key}"{status.selected}>{status.title}</option>
                        <!-- END: status_loop -->
						</select>
					</td>
                </tr>
                <!-- END: loop -->
            </tbody>
        </table>
    </div>
</form>
<!-- END: view -->
</div>
<script type="text/javascript">
$("#trf,#rtf").datepicker({
    showOn : "both",
    dateFormat : "dd.mm.yy",
    changeMonth : true,
    changeYear : true,
    showOtherMonths : true,
    buttonText : null,
    buttonImage : null,
    buttonImageOnly : true,
    yearRange : "-99:+0",
    beforeShow : function() {
        setTimeout(function() {
            $('.ui-datepicker').css('z-index', 999999999);
        }, 0);
    }
});
	 $('.iduser').select2({
        ajax: {
            url: 'index.php' + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=ajax&mod=userid',
            dataType: 'json',
            delay: 250,
            data: function (params) {

                return {
                    q: params.term,
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    });
	$('#rtf').change(function(){
             var dayto=document.getElementById('trf').value;
             var dayfrom=document.getElementById('rtf').value;
			 var userid=$('select[name=iduser]').find('option:selected').val();
			
			if(dayfrom==''){
				dayfrom=0;
			}
			if(dayto ==''){
				dayto=0;
			}
			if(dayfrom>dayto&&dayfrom!=0&&dayto!=0){
				alert("Ngày sau không thể bé hơn ngày trước");
				return false;
			}else{
				  $.ajax({
				url: "index.php?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + "=" + nv_module_name + "&" +  nv_fc_variable + "=withdrawal",
				type: 'get',
				data: 'mod=check&userid='+userid+'&dayfrom='+dayfrom+'&dayto='+dayto,
				success: function (data2) {
					res2 = JSON.parse(data2);
					if (res2.status == 'OK') {

						$('#hienthi').html(res2.html);
					} else {
						res2.mess;
					}
				},

			});
		}
	});
		$('#trf').change(function(){
             var dayto=document.getElementById('trf').value;
             var dayfrom=document.getElementById('rtf').value;
			 var userid=$('select[name=iduser]').find('option:selected').val();
			
			if(dayfrom==''){
				dayfrom=0;
			}
			if(dayto ==''){
				dayto=0;
			}
			if(dayfrom>dayto&&dayfrom!=0&&dayto!=0){
				alert("Ngày sau không thể bé hơn ngày trước");
				return false;
			}else{
				  $.ajax({
				url: "index.php?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + "=" + nv_module_name + "&" +  nv_fc_variable + "=withdrawal",
				type: 'get',
				data: 'mod=check&userid='+userid+'&dayfrom='+dayfrom+'&dayto='+dayto,
				success: function (data2) {
					res2 = JSON.parse(data2);
					if (res2.status == 'OK') {

						$('#hienthi').html(res2.html);
					} else {
						res2.mess;
					}
				},

			});
		}
	});
			$('select[name=iduser]').change(function(){
             var dayto=document.getElementById('trf').value;
             var dayfrom=document.getElementById('rtf').value;
			 var userid=$(this).find('option:selected').val();
			
			if(dayfrom==''){
				dayfrom=0;
			}
			if(dayto ==''){
				dayto=0;
			}
			if(dayfrom>dayto&&dayfrom!=0&&dayto!=0){
				alert("Ngày sau không thể bé hơn ngày trước");
				return false;
			}else{
				  $.ajax({
				url: "index.php?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + "=" + nv_module_name + "&" +  nv_fc_variable + "=withdrawal",
				type: 'get',
				data: 'mod=check&userid='+userid+'&dayfrom='+dayfrom+'&dayto='+dayto,
				success: function (data2) {
					res2 = JSON.parse(data2);
					if (res2.status == 'OK') {

						$('#hienthi').html(res2.html);
					} else {
						res2.mess;
					}
				},

			});
		}
	});
//<![CDATA[
    function nv_change_weight(id) {
        var nv_timer = nv_settimeout_disable('id_weight_' + id, 5000);
        var new_vid = $('#id_weight_' + id).val();
        $.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=withdrawal&nocache=' + new Date().getTime(), 'ajax_action=1&id=' + id + '&new_vid=' + new_vid, function(res) {
            var r_split = res.split('_');
            if (r_split[0] != 'OK') {
                alert(nv_is_change_act_confirm[2]);
            }else if (res.status == 'OK') {

                    $('#hienthi').html(res.html);
            } 
            window.location.href = script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=withdrawal';
            return;
        });
        return;
    }


    function nv_change_status(id) {
        //var new_status = $('#change_status_' + id).is(':checked') ? true : false;
		var status = $('#change_status_' + id).find('option:selected').val();
		console.log(status);
        if (confirm(nv_is_change_act_confirm[0])) {
            $.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=withdrawal&nocache=' + new Date().getTime(), 'change_status=1&id='+id+'&status='+status, function(res) {
                var r_split = res.split('_');
                if (r_split[0] != 'OK') {
                    alert(nv_is_change_act_confirm[2]);
                }
            });
        }
        return;
    }


//]]>
</script>
<!-- END: main -->