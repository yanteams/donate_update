<!-- BEGIN: main -->

	<form name="edit_donate" action="{ACTION}" method="POST">
	<input name="save" type="hidden" value="1" />
		<table class="table table-striped table-bordered table-hover">
		
		<tbody>
			<tr>
				<td>{LANG.add_amount}</td>
			<td><input type="text" class="form-control" name="amount" value="{ROW.amount}"  style="background:#FFFFCC"></td>
			</tr>
		</tbody>
		
		<tbody>
			<tr>
				<td>{LANG.add_name}</td>
			<td><input type="text" class="form-control" name="name" value="{ROW.name}"></td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td>{LANG.add_email}</td>
			<td><input type="text" class="form-control" name="email" value="{ROW.email}"></td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td>{LANG.add_phone}</td>
			<td><input type="text" class="form-control" name="phone" value="{ROW.phone}"></td>
			</tr>
		</tbody>
		
		<tbody>
			<tr>
				<td>{LANG.add_notes}</td>
			<td><input type="text" class="form-control" name="ghichu" value="{ROW.ghichu}"></td>
			</tr>
		</tbody>
		
		<tbody>
			<tr>
				<td>{LANG.add_anonymous}</td>
			<td><select name="anonymous" class="form-control">
				<option value="1">Ẩn danh</option>
				<option value="0">Không ẩn danh</option>
			</select></td>
			</tr>
		</tbody>
		
	<input class="form-control" type="hidden" name="status" value="{ROW.status}" " />
		
		
		<tbody>
			<tr>
				<td><input type="submit" value="{LANG.rec}" class="btn btn-success" name="confirm" /></td>
				<td><b>{ERROR}</b></td>
			</tr>
		</tbody>
		</table>
	</form>
	{D}
<!-- END: main -->