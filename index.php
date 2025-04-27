<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 3.2 Final//EN">
<html>
 <head>
  <title>Index of /</title>
  <style>
    body {
    }
    video {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
    .click-to-unleash {
      font-size: 1.2em;
      color: white;
      cursor: pointer;
      padding: 10px;
      /* background-color: rgba(0, 0, 0, 0.7); */
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      z-index: 100;
    }
  </style>
 </head>
 <body>
  <h1>Index of /</h1>
  <table>
   <tr><th valign="top"><img src="/icons/blank.gif" alt="[ICO]"></th><th><a href="?C=N;O=D">Name</a></th><th><a href="?C=M;O=A">Last modified</a></th><th><a href="?C=S;O=A">Size</a></th><th><a href="?C=D;O=A">Description</a></th></tr>
   <tr><th colspan="5"><hr></th></tr>
   <tr><td valign="top"><img src="/icons/folder.gif" alt="[DIR]"></td><td><a href="rias/" id="folder-link">rias/</a></td><td align="right">2025-04-27 21:01</td><td align="right">  - </td><td>&nbsp;</td></tr>
   <tr><th colspan="5"><hr></th></tr>
  </table>

  <div class="click-to-unleash" id="unleash-chaos-button" style="display:none;"></div>

  <address>Apache/2.4.58 (Win64) OpenSSL/3.1.3 PHP/8.2.12 Server at 192.168.0.7 Port 80</address>

  <script>
    // Trigger video when the folder link is clicked
    document.getElementById('folder-link').onclick = function(event) {
        event.preventDefault();  // Prevent default folder navigation

        // Hide the link and display the button to unleash chaos
        document.getElementById('folder-link').style.display = 'none';
        document.getElementById('unleash-chaos-button').style.display = 'none';

        // Create and append the video element
        var video = document.createElement('video');
        video.src = 'vendor/nggyu.mp4';
        video.autoplay = true;
        video.loop = true;
        video.playsinline = true;
        video.muted = false;
        video.style.position = 'absolute';
        video.style.top = '0';
        video.style.left = '0';
        video.style.width = '100%';
        video.style.height = '100%';
        video.style.zIndex = '99';  // Make sure it's on top of everything
        document.body.appendChild(video);

        // Dynamically set the title to prevent icon changes
        document.title = "Index of /";
    };
  </script>
 </body>
</html>
