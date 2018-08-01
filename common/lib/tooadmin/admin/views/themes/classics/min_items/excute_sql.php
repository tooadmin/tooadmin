<div class="row-fluid sortable ui-sortable">
    <div class="box span12">
        <div class="box-header well" data-original-title="">
            <h2><i class="icon-edit"></i>excel执行</h2>
            <div class="box-icon">
                <a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
                <a href="#" class="btn btn-close btn-round"><i class="icon-remove"></i></a>
            </div>
        </div>
        <div class="box-content">
           <div class="sortable row-fluid ui-sortable">
		   <form method="post"> 
			   <table>
				   <tr>
					   <td> <textarea name="sqltxt" style="width: 500px;height:150px;"> <?php echo isset($_POST["sqltxt"]) ? $_POST["sqltxt"] : ""; ?> </textarea> </td>
					   <td style="width: 300px;padding-left: 10px;"> <?php echo $result; ?></td>
				   </tr>
				   <tr>
					   <td align="right"> 
						<button type="submit" class="btn btn-primary"><?php echo TDLanguage::$sys_operate_excute; ?></button>
					   </td>
					   <td></td>
				   </tr>
			   </table>
		   </form>
            </div>
        </div>
    </div>
</div>
