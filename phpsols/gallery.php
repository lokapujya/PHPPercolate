<?php 
include('./includes/title.inc.php');
include('./includes/connection.inc.php');
// define number of columns in table
define('COLS',3);
// initialize variables for the horizontal looper
$pos = 0;
$firstRow = true;
// set maximum number of records
define('SHOWMAX',3);
$conn = dbConnect('read');
$getTotal = 'SELECT COUNT(*) FROM images';

$total = $conn->query($getTotal);
$row = $total->fetch_row();
$totalPix = $row[0];
// set the current page
$curPage = isset($_GET['curPage']) ? $_GET['curPage'] : 0;
if (isset($_GET['curPage'])) {
	$curPage = $_GET['curPage'];
} else {
	$curPage =0;
}
//calculae the start row of the subset
$startRow = $curPage * SHOWMAX;

$sql = "SELECT filename, caption FROM images LIMIT $startRow," . SHOWMAX;
$result = $conn->query($sql) or die(mysqli_error());
$row = $result->fetch_assoc();
// get the name for the main image
if(isset($_GET['image'])) {
	$mainImage = $_GET['image'];
} else {
  $mainImage = $row['filename'];	
}
$imageSize = getimagesize('images/'.$mainImage);
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>Japan Journey
<?php if (isset($title)) {echo "&#8212;{$title}";} ?>
</title>
<link href="styles/journey.css" rel="stylesheet" type="text/css" media="screen">
</head>
<body>
<div id="header">
    <h1>Japan Journey </h1>
</div>
<div id="wrapper">
    <?php include('./includes/menu.inc.php'); ?>
    <div id="maincontent">
        <h2>Images of Japan</h2>
      <p id = "picCount">Displaying <?php echo $startRow+1;
      if($startRow+1 < $totalPix) {
        echo ' to ';
        if($startRow+SHOWMAX < $totalPix) {
          echo $startRow+SHOWMAX;
        } else {
          echo $totalPix;
        }
      }
      echo " of $totalPix";
      ?></p>
        <div id="gallery">
            <table id="thumbs">
                <tr>
					<!--This row needs to be repeated-->
					<?php do  {
					   if($row['filename']==$mainImage) {
                $caption = $row['caption'];
					   }
					   //if remainder is 0 and not first row, close row and start new one
					   if($pos++ % COLS === 0 && !$firstRow){
					   	echo '</tr><tr>';
					   }
					   //once loop begins, this is no longer true
					   $firstRow = false;
					?>					     
                    <td><a href="gallery.php?image=<?php echo $row['filename'];?>&amp;curPage=<?php echo $curPage; ?>">
                    <img src="images/thumbs/<?php echo $row['filename']; ?>" alt="<?php echo $row['caption']; ?>"  width="80" height="54"></a></td>
          <?php } while ($row = $result->fetch_assoc()); 
          while($pos++ % COLS){
            echo '<td>&nbsp;</td>';
          }?>
                </tr>
				<!-- Navigation link needs to go here -->
				<tr><td>
				<?php
				//create a back link
				if($curPage>0) {
				  echo '<a href="' . $_SERVER['PHP_SELF'] . '?curPage=' . ($curPage-1) . '"> &lt; Prev</a>';
				} else {
					echo '&nbsp;';
				}
				?>
				<?php
				// pad the final row
				if(COLS-2 >0) {
					for ($i =0; $i < COLS-2; $i++) {
						echo '<td>&nbsp;</td>';
					}
				} 
				?>
				<td>
				<?php 
				//create a fowward link if more records exist
				if($startRow+SHOWMAX < $totalPix) {
					echo '<a href="' . $_SERVER['PHP_SELF'] . '?curPage=' . ($curPage+1) . '"> Next &gt;</a>';
				} else {
					// otherwise leave the cell empty
					echo '&nbsp;';
				}
				?>
				</td></tr>
            </table>
            <div id="main_image">
                <p><img src="images/<?php echo $mainImage;?>" alt="<?php echo $caption?>" <?php echo  $imageSize[3]; ?>></img></p>
                <p><?php echo $caption?></p>
            </div>
        </div>
    </div>
    <?php include('./includes/footer.inc.php'); ?>
</div>
</body>
</html>