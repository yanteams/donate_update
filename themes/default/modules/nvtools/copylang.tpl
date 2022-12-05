<!-- BEGIN: main -->
<h1>Chức năng copy các cấu hình ngôn ngữ tiếng việt của site sang ngôn ngữ khác (copy các module mặc định và cấu hình site)</h1>
<b>Hướng dẫn thực hiện:</b>
<br>
<br>
- cài đặt site đa ngôn ngữ
<br>
- Chọn ngôn ngữ muốn copy sang
<br>


<form action="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
	Chọn ngôn ngữ muốn copy: 
	<select name="lang">
		<option value="{LANG}">{LANG}</option>
	</select>
	<input name="submit_copy" type="submit" value="Thực hiện">
</form>

<!-- END: main -->