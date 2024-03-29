<?php

include ("../../pquery/pquery.php");
$pquery= new PQuery();

session_start();

if(!isset($_SESSION['todo']))
{
	// default to do 
	$todo=array();
	$done=array();
	
	$_SESSION['todo']=$todo;
	$_SESSION['done']=$done;
} else {
	$todo=$_SESSION['todo'];
	$done=$_SESSION['done'];
}

//our controlling variable
$task=isset($_GET['task'])?$_GET['task']:'view';

switch ($task ) 
{
case "add": {
	$id = end(array_keys($todo)) + 1;
	$_SESSION['todo'][$id]=$_GET['todo_item'];
	$ret .= $pquery->insert_html('append','#todolist',$pquery->escape(show_item($id,$_GET['todo_item'])))."\n";
	echo $ret;
	return ;
	}
	
case "delete": {
	unset($_SESSION['todo'][$_GET['id']]);
	$ret =  $pquery->remove('#todo_'.$_GET['id']);
	echo $ret;
	return;
	}	
	
case "reset": {
	session_destroy();
	echo $pquery->clean('#todolist')."\n";
	echo $pquery->clean('#donelist');
	return;
	}		
	
	
case "done": {
	$id = end(array_keys($done)) + 1;
	$_SESSION['done'][$id]=$_SESSION['todo'][$_GET['id']];
	unset($_SESSION['todo'][$_GET['id']]);
	$ret = $pquery->remove('#todo_'.$_GET['id']).";\n";
	$ret .= $pquery->insert_html('append','#donelist',$pquery->escape('<li id="done_$id">'.$_SESSION['done'][$id]."</li>")).";\n";
	echo $ret;
	return;
	}		
}

function show_item($num,$text){
	global $pquery;
	return "<li id=\"todo_$num\" 
	onmouseover='".$pquery->toggle("#actions_".$num)."' 
	onmouseout='".$pquery->toggle("#actions_".$num)."'>
	$text	
	<span id=\"actions_$num\" style=\"display:none; \">".
	$pquery->link_to_remote('<img src="images/done.gif" border="0" title="Task Completed" />',array('url'=>'index.php?task=done&id='.$num,'dataType'=>'script')).
	$pquery->link_to_remote('<img src="images/delete.gif" border="0" title="Remove Task" />',array('url'=>'index.php?task=delete&id='.$num,'dataType'=>'script')).
	"</span>
	</li>";
}
?>

<script src="../../pquery/js/jquery.js" type="text/javascript"></script>

<link rel='stylesheet' type='text/css' media='all' href='../css/style.css' />

Simple session based ToDo List<br />
<div id="todolisthead"> 
<?=$pquery->link_to_function('<img src="images/add.gif" border="0" title="Add new Entry" />',$pquery->visual_effect("slideToggle","#add_entry"));?>
&nbsp;&nbsp;
<?=$pquery->link_to_remote('<img src="images/refresh.gif" border="0" title="Reset Lists" />',array('url'=>'index.php?task=reset','dataType'=>'script'));?>

</div>

<div id="add_entry" style="display:none">   <br />	
<?=$pquery->form_remote_tag(array('url'=>'index.php?task=add','success'=>'$("#todo_item").val("");','dataType'=>'script'));?>
  <input type="text" name="todo_item" value="" id="todo_item">
  <input type="submit" name="Submit" value="Submit">
</form>
</div>

<br />
<strong>To Do</strong>
<ol id="todolist">
<?php foreach($_SESSION['todo'] as $num=>$text){ 
echo show_item($num,$text); 
}?>
</ol>

<br />
<strong>Done list</strong>
<ol id="donelist">
<?php foreach($_SESSION['done'] as $num=>$text){ echo "<li>".$text."</li>"; }?>
</ol>