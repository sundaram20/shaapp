READ ME:
********

This software is developed and copyrighted by HIOX Softwares.
This is given under The GNU General Public License (GPL).
The version is HES 1.0.

Description:
------------
A simple javascript code that expands blocks of text. 
For example, when you click the title or image, the text under it will expand.
when you press the title or image again, it will collapse the text.
A function that basically toggles the visibility of an element e.g. click,
show, click, hide.

Downloads:
------------
Please visit our site http://www.hscripts.com and do the download

Installation:
--------------
Please take 5 minutes time and read installation instructions carefully and
completely! This will ensure a proper and easy installation 

a) Unzip the file hexpander.zip to extract the files to hexpander\hexpander to
get the files movingjs.js and images.

Embedding the Eyes on Cursor Script:

    a) Now just include the following code in the file where you want to display
the Toggle / Expander script, make sure to have the hexpander folder in the
same directory path.

   <!-- Script by hscripts.com -->
    <script type="text/javascript" src="hexpander/movingjs.js"></script>
    <table align="center" height="100px" border=1 width="500px" cellspacing=0
cellpadding=0>
    <tr><td>
    <img src="hexpander/insert.jpg" id="insert1"
style="vertical-align:middle;" onClick="toggleSlide('div1',this.id);"
>Hioxindia
    <div id="div1" style="display:none; overflow: hidden;
height:75px;margin:10px;">
<p>HIOX INDIA is currently involved in web services, software/application
development, web content development, web hosting, domain registration,
internet solutions and web design.</p>
    </div>
    <img src="hexpander/insert.jpg" id="insert2"
style="vertical-align:middle;" onClick="toggleSlide('div2',this.id);"
>Hscripts
    <div id="div2" style="display:none; overflow: hidden;
height:75px;margin:10px;">
    <p>Free php, javascript and jsp scripts, free tutorials, free online web
site tools, free gif myspace icons, free clipart images and other webmaster
resources.</p>
    </div>
    <img src="hexpander/insert.jpg" id="insert3"
style="vertical-align:middle;"onClick="toggleSlide('div3',this.id);" >Funmin
    <div id="div3" style="display:none; overflow: hidden;
height:75px;margin:10px;">
    <p>Free online games , offline games and flash games are available. All
the games are very interesting and challenging.</p>
    </div>
    </td></tr></table>
    <!-- Script by hscripts.com -->

Display Features:

      a) Set the ID for div and image tag inside toggleSlide() function.
          For Example,
            <a style="font-family:verdana;font-size:12px;">
            <img src="hexpander/insert.jpg"id="insert3" align="absmiddle" onClick="toggleSlide('div3',this.id);">Funmin</a>
            <div id="div3" style="display:none;overflow:hidden;height:75px;margin:10px;">
b) List any number of div elements to show and hide the text.
c) This script is useful for display the content with simple animation. 
d) If you click show the content, again click hide the content.

Releases:
---------
Release Date HES 1.0: 17-12-2008
On any suggestions mail to us at support@hscripts.com

Visit us at http://www.hscripts.com
Visit us at http://www.hioxindia.com
