<!-- BEGIN: main -->
<form name="block_list" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}&amp;bid={BID}" method="get">
    <div class="table-responsive">
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
            </tr>
        </thead>
        
        <tbody>
        <!-- BEGIN: loop -->
    <tr>            <td>{ROW.id}</td>
            <td>{ROW.amount} <sup>VNĐ</sup></td>
            <td>{ROW.name}</td>
            <td>{ROW.email}</td>
            <td>{ROW.phone}</td>
            <td>{ROW.ghichu}</td>
            <td>{ROW.anonymous}</td>
            <td>{ROW.status}</td>
    </tr>
        <!-- END: loop -->

        </tbody>
    
        </table>
    </form>
    
        <!-- END: main -->