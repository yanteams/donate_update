
<!-- BEGIN: main -->
<div id="info_tab" class="clearfix">
                                <ol class="breadcrumb">
                                        <li class="active"> {LANG.details_list} </li>
                </ol>
            
            </div>
	<table class="table table-striped table-bordered table-hover">
 <thead>
            <tr>
                <th>#</th>
                <th>{LANG.amount}</th>
                <th>{LANG.name}</th>
                <th>{LANG.email}</th>
                <th>{LANG.phone}</th>
                <th>{LANG.notes}</th>
                <th>{LANG.anonymous}</th>
                <th>{LANG.status}</th>
                <th>Chức năng</th>
            </tr>
        </thead>
	<!-- BEGIN: loop -->
	<tbody>
		 <tr>            <td>{ROW.id}</td>
            <td>{ROW.amount} <sup>VNĐ</sup></td>
            <td>{ROW.name}</td>
            <td>{ROW.email}</td>
            <td>{ROW.phone}</td>
            <td>{ROW.ghichu}</td>
            <td>{ROW.anonymous}</td>
            <td>{ROW.status}</td>
			<td class="center">
				<a href="{EDIT}" class="btn btn-info">{GLANG.edit}</a>
				&nbsp;&nbsp;
				<a href="{DEL}" class="btn btn-danger" onclick="return confirm('{LANG.delete_donate}')">{GLANG.delete}</a>
			</td>
		</tr>
	</tbody>
	<!-- END: loop -->
	<tr>
	<td colspan='3'><span class="add_icon"><a href="{BACK}"><b>{LANG.back_main}</b></a></span></td>
	</tr>
	</table>
<!-- END: main -->