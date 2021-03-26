
# Dummy Data for Designers

Syrus is designed and packaged to provide designers the freedom to work completely independantly from the back-end with only the Syrus package, which does not even require PHP to be installed to utilize.  As the back-end developer, you will need to provide dummy data to the designer so they have the correct variables and blocks to work with.

For this, simply modify the file at ~/public/vars.php as necessary.  This file contains a single `loadSyrusVars()` method which accepts an instance of `Syrus`, which you can then call the `assign()` and `addBlock()` methods on.  Add any variables / blocks that the back-end will support, and send the vars.php file to the designer.

The designer can then easily setup Syrus as a local server either with PHP's built-in server or via docker-compose.  The vars.php file will be loaded with each page view, and assign the dummary data into Syrus.



  

