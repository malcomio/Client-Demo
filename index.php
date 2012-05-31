<?php
// define the logo image
$logo = 'logo.png';

// build an array of all the image files in the current directory
$directory = opendir("mockups");
$file_types = array('png', 'jpg', 'jpeg', 'gif');
$files = array();
while ($file = readdir($directory)) {
  $dotpos = 
  
  $extension = substr(strtolower(strrchr($file, '.')), 1);
  if (in_array($extension, $file_types)) {
    $filename = explode('_', $file);

    if (array_key_exists(2, $filename)) {
      $bk = substr($filename[2], 0, strpos($filename[2], '.'));
    }
    else {
      $bk = FALSE;
    }
    
    $files[] = array(
      'file' => $file,
      'ext' => $extension,
      'number' => $filename[1],
      'bk' => $bk,
    );
  }
}

sort($files);

if ($_REQUEST['page']) {
  $next_page = $_REQUEST['page'] + 1;
  $image = $_REQUEST['page'] - 1;
  $img = $files[$image]['file'];
  $extension = $files[$image]['ext'];
  $bk = $files[$image]['bk'];

  if ($next_page > count($files)) {
    // Link back to the beginning if we're on the last image.
    $url = 'index.php';
  }
  else {
    // Link to the next image
    $url = 'index.php?page=' . $next_page;
  }
  $output = "<a href='$url' class='full'><img src='mockups/$img'/></a>";

  if ($bk == 'bk') {
    $background = "background: url(bk.jpg) repeat-x 0 -1px;";
  }
  elseif ($bk) {
    $background = "background: #$bk";
  }
  else {
    // Process the image using GD.
    switch ($extension) {
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

    // Get the colour of the first pixel of the image.
    $rgb = imagecolorat($im, 1, 1);
    $r = ($rgb >> 16) & 0xFF;
    $g = ($rgb >> 8) & 0xFF;
    $b = $rgb & 0xFF;
    
    // Set the background colour to match.
    $background = "background-color: rgb($r, $g, $b);";
  }
}
else {
  $output = '<div id="logo" href="http://rubydesign.co.uk"><img src="' . $logo . '"/></div>';
  $output .= '<div class="contact">020 8480 0370<br/><a href="mailto:info@rubydesign.co.uk">info@rubydesign.co.uk</a></div>';
  $output .= '<div class="thumbnails">';
  $counter = 0;
  $row_split = floor(count($files) / 2);

  if(is_dir('thumbnails')) {
    $thumbnail_dir = 'thumbnails';
  }
  else {
    $thumbnail_dir = 'mockups';
  }
  foreach ($files as $image) {
    $counter++;
    $output .= "<a class='thumbnail' href='index.php?page=$counter'><img src='$thumbnail_dir/{$image['file']}' width='240' height='180'/><div class='filename'>{$image['number']}</div></a>";
    if($counter == $row_split) {
      $output .= '</div><div class="thumbnails">';
    }
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
        <?php print $background; ?>
      }
      .thumbnails, .contact, #logo {
        clear: both;
        margin: 20px auto;
        overflow: auto;
        text-align: center;
      }
      .thumbnail {
        margin: 10px 10px 20px;
      }
      .thumbnail img {
        border: 1px solid #000;
      }
      .full {
        width: 100%;
      }
      #logo img, .full img {
        margin: 0 auto;
        display: block;
      }

      a {
        text-decoration: none;
        color: #000;
        display: inline-block;
      }
      
      .filename {
        text-align: center;
        font-weight: bold;
        font-size: 1.2em;
      }
    </style>
  </head>
  <body>
<?php print $output; ?>
  </body>
</html>
