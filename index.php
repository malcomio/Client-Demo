<?php
// define the logo image
$logo = 'http://rubydesign.co.uk/images/logo_small.gif';

// build an array of all the image files in the current directory
$directory = opendir(".");
$file_types = array('png', 'jpg', 'jpeg', 'gif');
$files = array();
while($file = readdir($directory)) {
  $extension = substr(strtolower(strrchr($file, '.')), 1);
  if(in_array($extension, $file_types)) {
    $files[] = array('file' => $file, 'ext' => $extension);
  }
}

sort($files);

if($_REQUEST['page']) {
  $next_page = $_REQUEST['page'] + 1;
  $image = $_REQUEST['page'] - 1;
  $img = $files[$image]['file'];
  $extension = $files[$image]['ext'];

  if($next_page > count($files)) {
    $url = 'index.php';
  }
  else {
    $url = 'index.php?page=' . $next_page;
  }
  $output = "<a href='$url' class='full'><img src='$img'/></a>";

  // get the background colour from the first pixel of the image
  switch($extension) {
    case 'png' :
      $im = imagecreatefrompng($img);
      break;
    case 'jpeg':
    case 'jpg' :
      $im = imagecreatefromjpeg($img);
      break;
    case 'gif' :
      $im = imagecreatefromgif($img);
      break;
  }

  $rgb = imagecolorat($im, 1, 1);
  $r = ($rgb >> 16) & 0xFF;
  $g = ($rgb >> 8) & 0xFF;
  $b = $rgb & 0xFF;
}
else {
  $output = '<div id="logo"><img src="'.$logo.'"/></div>';
  $output .= '<div id="thumbnails">';
  $counter = 0;
  foreach($files as $image) {
    $counter++;
    $output .= "<a class='thumbnail' href='index.php?page=$counter'><img src='{$image['file']}' width='180' height='120'/><div class='filename'>{$image['file']}</div></a>";
  }
  $output .= '</div>';
}
?>      
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title></title>
    <style>
      html {
        font-family: Arial,Helvetica,sans-serif;
        margin: 0;
      }
      body {
        margin: 0;
        <?php
        if($rgb) {
          print "background-color: rgb($r, $g, $b)";
        }
        ?>
      }
      #thumbnails {
        width: 80%;
        margin: 0 auto;
      }
      .thumbnail {
        margin: 10px 10px 20px;
      }
      #logo {
        clear: both;
      }
      .full {
        width: 100%;
        min-height: 600px;
      }
      #logo img, .full img {
        margin: 0 auto;
        display: block;
      }

      a {
        text-decoration: none;
        color: #000;
        float: left;
      }
    </style>
  </head>
  <body>
    <?php print $output; ?>
</body>
</html>
