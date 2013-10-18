<?php
// Include Projax class and intialize it
include ("../../pquery/pquery.php");
$pquery= new PQuery();
$ujs->true;

//our controlling variable
$task=isset($_GET['task'])?$_GET['task']:'view';

switch($task)
{
	case "validate": 
	$email = $_GET['email'];
	if (eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email)) echo '<font color="GREEN">Valid</font>';
		else echo '<font color="RED">Invalid</font>';
	return ;
}
?>
<!--Include the Javascripts -->
<script src="../../pquery/js/jquery.js" type="text/javascript"></script>


<h1>Simple Events Demos</h1>

<hr />

<p><strong>observe_field</strong> - simple observe field.</p>
<a id="linkclick" href="#">Im being observed for clicks<a><br /><br />
<input type="text" name="name" id="inputchange" value=""/>

<hr />
<p><strong>observe_field</strong> - Ajax validation.</p>


<script type="text/javascript">
$(document).ready(function() {
	<?=$pquery->observe_field('#linkclick',array('event'=>'click','function'=>'alert(\'Someone Clicked me\')'));?> 
	<?=$pquery->observe_field('#inputchange',array('event'=>'change','function'=>'alert(\'Ive got Changed\')'));?> 
	<?=$pquery->observe_field('#inputemail',array('event'=>'keyup','function'=>$pquery->remote_function(array('url'=>'index.php?task=validate','update'=>'#ajaxvalidate','with'=>'email="+$("#inputemail").val()+"'))));?> 
  });
</script>

Email : <input type="text" name="name" id="inputemail" value=""/> (<b><span id='ajaxvalidate'><font color="RED">Invalid</font></span></b>)



