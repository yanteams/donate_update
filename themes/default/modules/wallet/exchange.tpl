<!-- BEGIN: main -->
<div class="clearfix form-group">
    <h1 class="pull-left">{LANG.sysexchange}</h1>
 
</div>

<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-addon">{LANG.money1}</div>
	    		<select class="form-control" name="exchangeMoneyFrom" id="exchangeMoneyFrom">
	    			<!-- BEGIN: loopmoney1 -->
	    			<option value="{money1}">{money1}</option>
	    			<!-- END: loopmoney1 -->
	    		</select>
            </div>
            
        </div>
    </div>
    <div class="col-sm-12 col-md-12">
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-addon">{LANG.money2}</div>
	    		<select class="form-control" name="exchangeMoneyTo" id="exchangeMoneyTo">
	    			<!-- BEGIN: loopmoney2 -->
	    			<option value="{money2}">{money2}</option>
	    			<!-- END: loopmoney2 -->
	    		</select>
            </div>
            
        </div>
    </div>
</div>
<div class="text-center form-group">
    <label>{LANG.nhaptien}</label>
    <input class="form-control text-center" type="text" id="totalmoneyexchange" name="totalmoneyexchange" />
</div>
<div class="text-center form-group">
	<input class="btn btn-primary" type="button" name="exchangeCheckRate" value="{LANG.checkrate}" />
	<input class="btn btn-success" type="button" name="exchangeCalculate" value="{LANG.viewmoneyrate}"/>
</div>
<script type="text/javascript">
var isnumber = '{LANG.isnumber}';
var isexchange = '{LANG.isexchange}';
var notexchange = '{LANG.notexchange}';
var notexchange1 = '{LANG.notexchange1}';
var okexchange = '{LANG.okexchange}';
</script>
<!-- END: main -->