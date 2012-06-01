<?php
// define the logo image
$logo = 'logo.png';
$website = 'http://rubydesign.co.uk';
$email = 'info@rubydesign.co.uk';
$phone = '020 8480 0370';
$title = 'Ruby Design - mockups';

// build an array of all the image files in the current directory
$directory = opendir("mockups");
$file_types = array('png', 'jpg', 'jpeg', 'gif');
$files = array();
while ($file = readdir($directory)) {
  // Is this one of our accepted file types?
  $extension = substr(strtolower(strrchr($file, '.')), 1);
  if (in_array($extension, $file_types)) {

    // Split the filename so we can check for colours.
    $filename = explode('_', $file);

    if (array_key_exists(2, $filename)) {
      $bk = substr($filename[2], 0, strpos($filename[2], '.'));
    }
    else {
      $bk = FALSE;
    }
    
    $number = $filename[1];

    // If there is no filename[2], remove the file extension.
    if($dotpos = strpos($number, '.')) {
      $number = substr($number, 0, $dotpos);
    }

    $files[] = array(
      'file' => $file,
      'ext' => $extension,
      'number' => $number,
      'bk' => $bk,
    );
  }
}

sort($files);

// Are we displaying the full version of one image?
if ($_REQUEST['page']) {
  $next_page = $_REQUEST['page'] + 1;
  
  $key = $_REQUEST['page'] - 1;
  
  $image = $files[$key];
  
  
  $img = $image['file'];
  $extension = $image['ext'];
  $bk = $image['bk'];
  $title = $image['number'];

  if ($next_page > count($files)) {
    // Link back to the beginning if we're on the last image.
    $url = 'index.php';
  }
  else {
    // Link to the next image
    $url = 'index.php?page=' . $next_page;
  }
  $output = "<a href='$url' class='full'><img src='mockups/$img'/></a>";

  if ($bk) {
    // Is this a valid hex colour?
    if (preg_match('/^([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/i', $bk)) {
      $background = "background: #$bk";
    }
    else {
      // Is there a matching file?
      foreach ($file_types as $file_type) {
        $filename = $bk . '.' . $file_type;
        if (file_exists($filename)) {
          $background = "background: url(bk.jpg) repeat-x 0 -1px;";
          break;
        }
      }
    }
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
  // Display thumbnails of all the designs.
  $output = '<div id="logo" href="' . $website . '"><img src="' . $logo . '"/></div>';
  $output .= '<div class="contact">' . $phone . '<br/><a href="mailto:' . $email . '">' . $email . '</a></div>';
  $output .= '<div class="help">Click on the thumbnails to view the full image.<br/>Click anywhere on the full image to view the next image.</div>';
  $output .= '<div class="thumbnails">';
  
  $counter = 0;
  $row_split = floor(count($files) / 2);

  foreach ($files as $image) {
    $counter++;
    
    $filename = $image['file'];
    
    // Display a proper thumbnail if possible.
    $thumbnail = 'thumbnails/' . $filename;
    if(!file_exists($thumbnail)) {
      $thumbnail = 'mockups/' . $filename;
    }
    
    $output .= "<a class='thumbnail' href='index.php?page=$counter'><img src='$thumbnail' width='240' height='180'/><div class='filename'>{$image['number']}</div></a>";
    if ($counter == $row_split) {
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
    <title><?php print $title; ?></title>
    <style>
      html {
        font-family: Arial,Helvetica,sans-serif;
        margin: 0;
      }
      body {
        margin: 0;
        <?php print $background; ?>
      }
      .thumbnails, .contact, #logo, .help {
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
