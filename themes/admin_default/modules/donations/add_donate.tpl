<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div class="alert alert-danger">{ERROR}</div>
<!-- END: error -->
<!-- BEGIN: success -->
<div class="alert alert-success">{SUCCESS}</div>
<!-- END: success -->
<div class="panel panel-default">
<div class="panel-body">
<form class="form-horizontal" action="{ACTION_FILE}" method="post">
	<input type="hidden" name="id" value="{ROW.id}" />
	<input type="hidden" name="paymentid" value="{ROW.paymentid}" />
	<div class="form-group">
		<label class="col-sm-5 col-md-4 control-label"><strong>{LANG.add_amount}</strong> <span class="red">(*)</span></label>
		<div class="col-sm-19 col-md-20">
			<input class="form-control" type="text" name="amount" value="{ROW.amount}" required="required" oninvalid="setCustomValidity( nv_required )" oninput="setCustomValidity('')" />
		</div>
	</div>
		<div class="form-group">
		<label class="col-sm-5 col-md-4 control-label"><strong>{LANG.add_name}</strong> <span class="red">(*)</span></label>
		<div class="col-sm-19 col-md-20">
			<input class="form-control" type="text" name="name" value="{ROW.name}" required="required" oninvalid="setCustomValidity( nv_required )" oninput="setCustomValidity('')" />
		</div>
	</div>
		<div class="form-group">
		<label class="col-sm-5 col-md-4 control-label"><strong>{LANG.add_email}</strong> <span class="red">(*)</span></label>
		<div class="col-sm-19 col-md-20">
			<input class="form-control" type="text" name="email" value="{ROW.email}" required="required" oninvalid="setCustomValidity( nv_required )" oninput="setCustomValidity('')" />
		</div>
	</div>
		<div class="form-group">
		<label class="col-sm-5 col-md-4 control-label"><strong>{LANG.add_phone}</strong> <span class="red">(*)</span></label>
		<div class="col-sm-19 col-md-20">
			<input class="form-control" type="text" name="phone" value="{ROW.phone}" required="required" oninvalid="setCustomValidity( nv_required )" oninput="setCustomValidity('')" />
		</div>
	</div>
		<div class="form-group">
		<label class="col-sm-5 col-md-4 control-label"><strong>{LANG.add_notes}</strong> <span class="red">(*)</span></label>
		<div class="col-sm-19 col-md-20">
			<input class="form-control" type="text" name="ghichu" value="{ROW.ghichu}" required="required" oninvalid="setCustomValidity( nv_required )" oninput="setCustomValidity('')" />
		</div>
	</div>
		<div class="form-group">
		<label class="col-sm-5 col-md-4 control-label"><strong>{LANG.add_anonymous}</strong> <span class="red">(*)</span></label>
		<div class="col-sm-19 col-md-20">
			<select name="anonymous" class="form-control">
				<option value="1">Ẩn danh</option>
				<option value="0">Không ẩn danh</option>
			</select>
		</div>
	</div>
	<input class="form-control" type="hidden" name="status" value="{ROW.status}" " />

		
	
	<div class="form-group" style="text-align: center"><button class="btn btn-primary" name="submit" type="submit" value="{LANG.save}" />Cập nhật</button></div>
</form>
</div></div>

<!-- END: main -->
