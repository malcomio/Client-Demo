Client-Demo
===========

Add this script and a set of images into a directory to create a quick website mockup demo.

Visiting the directory will display a set of thumbnails.

Clicking through to a thumbnail will take the user to a full view of the image.

Clicking anywhere on the full view will take the user to the next image in the directory.

## File naming
Create a directory called mockups, and a directory called thumbnails.
Files should be named with the following convention:
mockup_NUMBER_BACKGROUND
or
mockup_NUMBER

Background should be either a valid hex colour, or the name (without extension) of a background image.

If background is not present in the filename, the script will check for the colour of the top left of the image, and set the HTML background colour to match.

## Configuration
Edit the variables at the top of the file with your own phone, email and URL.

## Example usage
A demonstration is available at http://rubydesign.co.uk/mockup_demo/
