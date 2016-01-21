<!DOCTYPE html>
<html>
<title>Rushee Profile</title>
<head>
<style>
#header {
    background-color:black;
    color:white;
    text-align:center;
    padding:5px;
}
#nav {
    line-height:30px;
    background-color:#eeeeee;
    height:300px;
    width:350px;
    float:left;
    padding:5px;	      
}
#section1 {
    width:200px;
    float:left;
    padding:10px;	 	 
}
#section2 {
    width:200px;
    float:left;
    padding:10px;	 	 
}
#section3 {
    width:200px;
    float:left;
    padding:10px;	 	 
}
#footer {
    background-color:black;
    color:white;
    clear:both;
    text-align:center;
   padding:5px;	 	 
}
</style>
</head>



<?php $usr = $_POST["usr"]; $pwd = $_POST["pwd"]; $computing = $_POST["computing"]; $database="rush";
		
$link = mysql_connect("durush.cvs5ktof74fc.us-east-1.rds.amazonaws.com:3306",$usr,$pwd);
					
// Check if RDS is connected. !$link means that $link is not null. If its null
//RDS is not connected, then !$link will be 1 to make the below condition true.
						
//Select Database
	@mysql_select_db($database) or die( "Unable to select database");
						
$presql = 'SELECT * FROM rushees WHERE computing = \''.$computing.'\'';
$prerun = mysql_query($presql);
if (!$prerun) {
	die('Error running prerun: ' . mysql_error()); 
}
$row = mysql_fetch_assoc($prerun)
?>

<body link="blue" vlink="grey" alink="navy">

<div id="header">
	<h1><?php echo $row['first'].' '.$row['last']?></h1><br>
	
</div>




<div id="nav">
<img src = '<?php echo $row['image_url']?>' width=350px>

</div>

<div id="section1">
<h2>Current Status</h2>
<p>

</p>
<p>
<?php

echo '<b>Round 1: </b> ';
if( $row['r1'] == 'Y' ){
	echo 'Invited';
}
else if( $row['r1'] == 'N' ){
	echo 'Balled';
}
else{
	echo 'TBD';
}
echo '<br><br><b>Round 2: </b> ';
if( $row['r2'] == 'Y' ){
	echo 'Invited';
}
else if( $row['r2'] == 'N' ){
	echo 'Balled';
}
else{
	echo 'TBD';
}
echo '<br><br><b>Round 3: </b> ';
if( $row['r3'] == 'Y' ){
	echo 'Invited';
}
else if( $row['r3'] == 'N' ){
	echo 'Balled';
}
else{
	echo 'TBD';
}
echo '<br><br><b>Bid: </b>';
if( $row['bid'] == 'Y' ){
	echo 'Granted';
}
else if( $row['bid'] == 'N' ){
	echo 'Balled';
}
else{
	echo 'TBD';
}
?>

<br><br>

	<form action ="invite.suphp" method="post" id="invite">
	<input type="hidden" name="usr" value="<?php echo $usr ; ?>" >
	<input type="hidden" name="pwd" value="<?php echo $pwd ; ?>" >
	<input type="hidden" name="computing" value="<?php echo $row['computing'] ; ?>" >
	<a href="javascript:{}" onclick="document.getElementById('invite').submit(); return false;"><h2><font color = 'green'>R1 Invite</font></h2></a></form>

	<form action ="tbd.suphp" method="post" id="tbd">
	<input type="hidden" name="usr" value="<?php echo $usr ; ?>" >
	<input type="hidden" name="pwd" value="<?php echo $pwd ; ?>" >
	<input type="hidden" name="computing" value="<?php echo $row['computing'] ; ?>" >
	<a href="javascript:{}" onclick="document.getElementById('tbd').submit(); return false;"><h2><font color = 'black'>TBD</font></h2></a></form>

	<form action ="drop.suphp" method="post" id="drop">
	<input type="hidden" name="usr" value="<?php echo $usr ; ?>" >
	<input type="hidden" name="pwd" value="<?php echo $pwd ; ?>" >
	<input type="hidden" name="computing" value="<?php echo $row['computing'] ; ?>" >
	<a href="javascript:{}" onclick="document.getElementById('drop').submit(); return false;"><h2><font color="red">Ball</font></h2></a></form>

<p>
	



	<?php
	$navsql = 'SELECT first, last, computing FROM rushees ORDER BY first, last ASC'; //ADD WHERE CLAUSE EACH ROUND (see notes)
	$navscan = mysql_query($navsql);
	if (!$navscan) {
		die('Error running prerun: ' . mysql_error()); 
	}
	
	$c_list = array();
	$f_list = array();
	$l_list = array();
						
	while ($needle = mysql_fetch_assoc($navscan)) {
		 $f_list[] = $needle['first'];
		 $l_list[] = $needle['last'];
		 $c_list[] = $needle['computing'];
	}
	
	$currentkey = array_search($computing, $c_list);
	$nextkey = $currentkey + 1;
	$lastkey = $currentkey - 1;
	$arraycount = count($c_list);
	?>	
	
	<hr>
	
	
	<?php if($nextkey < $arraycount ){ ?>
	<form action ="onebyone.suphp" method="post" id="next">
	<input type="hidden" name="usr" value="<?php echo $usr ; ?>" >
	<input type="hidden" name="pwd" value="<?php echo $pwd ; ?>" >
	<input type="hidden" name="computing" value="<?php echo $c_list[$nextkey] ; ?>" >
	<a href="javascript:{}" onclick="document.getElementById('next').submit(); return false;"><h2>Next</h2></a></form>
	<?php } ?>
	
	<?php if($lastkey >= 0){ ?>
	<form action ="onebyone.suphp" method="post" id="previous">
	<input type="hidden" name="usr" value="<?php echo $usr ; ?>" >
	<input type="hidden" name="pwd" value="<?php echo $pwd ; ?>" >
	<input type="hidden" name="computing" value="<?php echo $c_list[$lastkey] ; ?>" >
	<a href="javascript:{}" onclick="document.getElementById('previous').submit(); return false;"><h3>Previous</h3></a></form>
	<?php } ?>

	
	<br><hr><br>
	<form action ="onebyone.suphp" method="post" id="other">
	<input type="hidden" name="usr" value="<?php echo $usr ; ?>" >
	<input type="hidden" name="pwd" value="<?php echo $pwd ; ?>" >
	<select name="computing">
		<?php
		for($i=0; $i<$arraycount; $i++){
			$c_temp = $c_list[$i];
			echo '<option value="'.$c_temp.'">'.$f_list[$i].' '.$l_list[$i].'</option>';
		}
		?>
	</select>
	<a href="javascript:{}" onclick="document.getElementById('other').submit(); return false;"><br><h3>Jump To</h3></a></form><Br>
	<br><hr><br>
	<form action ="admin.suphp" method="post" id="back">
	<input type="hidden" name="usr" value="<?php echo $usr ; ?>" >
	<input type="hidden" name="pwd" value="<?php echo $pwd ; ?>" >
	<a href="javascript:{}" onclick="document.getElementById('back').submit(); return false;">Directory</a></form>
</p>

</p>
</div>

<div id="section2">
<h2>Events Attended</h2>
<p>

<?php

echo '<b><u>Open House</b></u><br>';
echo '<br><u>Open House-Thurs:</u> ';
if( $row['oh_thurs'] == 'Y' ){
	echo 'Attended';
}
echo '<br><u>Open House-Sat:</u> ';
if( $row['oh_sat'] == 'Y' ){
	echo 'Attended';
}

echo '<br><br><b><u>First Round</b></u><br>';
echo '<br><u>40s and BBQ:</u> ';
if( $row['40s'] == 'Y' ){
	echo 'Attended';
}
echo '<br><u>Battleshots:</u> ';
if( $row['battle'] == 'Y' ){
	echo 'Attended';
}
echo '<br><u>Tower of Power:</u> ';
if( $row['tower'] == 'Y' ){
	echo 'Attended';
}

echo '<br><br><b><u>Second Round</b></u><br>';
echo '<br><u>Red vs. Blue:</u> ';
if( $row['rvb'] == 'Y' ){
	echo 'Attended';
}
echo '<br><u>Paintball:</u> ';
if( $row['paintball'] == 'Y' ){
	echo 'Attended';
}
echo '<br><u>*Golf:</u> ';
if( $row['golf'] == 'Y' ){
	echo 'Attended';
}
echo '<br><u>VTech Game:</u> ';
if( $row['basketball'] == 'Y' ){
	echo 'Attended';
}

echo '<Br><br><b><u>Third Round</b></u><br>';
echo '<br><u>Bombs:</u> ';
if( $row['bombs'] == 'Y' ){
	echo 'Attended';
}
echo '<br><u>Dinner:</u> ';
if( $row['dinner'] == 'Y' ){
	echo 'Attended';
}
echo '<br><u>Date Function:</u> ';
if( $row['datefunction'] == 'Y' ){
	echo 'Attended';
}

?>
	
</p>


</div>

<div id="section3">
<h2>Basic Info</h2>
<b>Dorm </b><?php echo $row['dorm']; ?><br><br>
<b>Year: </b><?php echo $row['year']; ?> Year<br><br>
<b>Hometown: </b><?php echo $row['hometown']; ?><br><br><hr>
<h2>Questions</h2>
<b>Favorite Alcohol: </b><?php echo $row['alc']; ?><br><br>
<b>Nolte or Tobey: </b><?php echo $row['']; ?><br><br>
<b>Question 1: </b><?php echo $row['q1']; ?><br><br>
</div>


<div id="footer">
Copyright Delta Upsilon
</div>

</body>
</html>








						
						
						
                            
	  </body>
</html>