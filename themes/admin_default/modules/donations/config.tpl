<!-- BEGIN: main -->
	<form name="config" action="{ACTION}" method="post">
		<table class="table table-striped table-bordered table-hover">
			<tbody class="second">
				<tr>
					<td style="width: 150px">{LANG.toplip}</td>
					<td><input type="checkbox" name="toplip" value="1" {CHECK}/></td>
				</tr>
			</tbody>
			
			<tbody>
				<tr>
					<td>{LANG.search}</td>
					<td><input type="checkbox" name="search" value="1" {CHECK_S}/></td>
				</tr>
			</tbody>
			
			<tbody class="second">
				<tr>
					<td>{LANG.page}</td>
					<td><input type="text" name="per_page" value="{CONF.per_page}"/></td>
				</tr>
			</tbody>
			
			<tbody>
				<tr>
					<td><input type="submit" value="{LANG.rec}" name="config"></input></td>
					<td>{ERROR}</td>
				</tr>
			</tbody>
			
		</table>
	</form>
	<!-- BEGIN: edit -->
		<form action="{FORM_ACTION}" method="post">
			<input name="save" type="hidden" value="1" />
				<table class="tab1">
					<tbody>
						<tr>
							<td>{DATA}</td>
						</tr>
					</tbody>
					<tfoot>
						<tr>
							<td class="center"><input name="submit1" type="submit" value="{LANG.rec}" /></td>
						</tr>
					</tfoot>
				</table>
		</form>
		<!-- END: edit -->
		<!-- BEGIN: data -->
			<table class="tab1">
				<tbody>
					<tr>
						<td class="center"><a href="{URL_EDIT}" title="{GLANG.edit}" class="button1"><span><span>{GLANG.edit}</span></span></a></td>
					</tr>
				</tbody>
			</table>
			{DATA}
	<!-- END: data -->
<!-- END: main -->