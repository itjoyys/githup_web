

<select name="atype" id="ttype">

		<option value="./bet_record.php?uid=<?=$_GET['uid'] ?>&gid=<?=$_GET['gid'] ?>&did=<?=$_GET['did'] ?>" <?if($_GET['ttype']=='sport'){ echo 'selected="selected"'; }?>>體育</option>
		<option value="./bet_record_fc.php?ttype=caipiao&uid=<?=$_GET['uid'] ?>&gid=<?=$_GET['gid'] ?>&did=<?=$_GET['did'] ?>" <?if($_GET['ttype']=='caipiao'){ echo 'selected="selected"'; }?>>彩票</option>
		<option value="./bet_record_video.php?ttype=lebo&uid=<?=$_GET['uid'] ?>&gid=<?=$_GET['gid'] ?>&did=<?=$_GET['did'] ?>" <?if($_GET['ttype']=='lebo'){ echo 'selected="selected"'; }?>>視訊</option>
	</select>