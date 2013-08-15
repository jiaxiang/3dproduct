<!--
   You can put this page on your web server and see it working on your browser.

   Follow these steps to embed this on your own page:

   1. Copy the lines starting with (1) from the HEAD section to your own page's HEAD
   2. Copy the A tag (2) from the BODY section anwhere inside your BODY
   3. Verify that the page works on your browser.
   4. Fine tune the looks with CSS. Details: http://moot.it/docs/?3dprinting#styling

   For more help:

   http://moot.it/docs/?3dprinting
   http://moot.it/forum/
-->
<!doctype html>

<html>

   <head>
      <meta charset="utf-8">

      <title>Moot Forums</title>

      <!-- (1) Optimize for mobile versions -->
      <meta name="viewport" content="width=device-width, initial-scale=1.0">

      <!-- (1) Moot look and feel -->
      <link rel="stylesheet" href="http://cdn.moot.it/1.0/moot.css"/>

      <!-- (4) Custom CSS -->
      <style>
      body {
         font-family: "myriad pro", tahoma, verdana, arial, sans-serif;
         margin: 0; padding: 0;
      }

      .moot {
         font-size: 20px;
      }
      </style>

      <!-- (1) Moot depends on jQuery v1.7 or greater -->
      <script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>

      <!-- (1) Moot client application -->
      <script src="http://cdn.moot.it/1.0/moot.min.js"></script>

   </head>

   <body>

      <!-- (2) Placeholder for the forum. The forum will be rendered inside this element -->
      <a class="moot" href="http://api.moot.it/3dprinting"></a>

      <!--
         (2) Example tag for commenting, put it on a different page
         <a class="moot" href="http://api.moot.it/3dprinting/blog#my-blog-entry"></a>

         (2) Example tag for threaded commenting
         <a class="moot" href="http://api.moot.it/3dprinting/blog/my-large-blog-entry"></a>

         Moot paths are awesome: http://moot.it/docs/?3dprinting#path
      -->

   </body>

</html>