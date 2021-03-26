
# Using Multiple Themes

Syrus allows the use of multiple themes based on location of the template file being rendered.  For example, you may wish to utilize one theme for an administration panel, another theme for the client's area, and another for the public web site.

First, ensure all necessary themes are created and located within the ~/views/themes/ directory, so using the above example of three themes, you may have something like:

* /views/themes/admin_panel
* /views/themes/client_area
* /views/themes/public

Open the site.yml file (defaults to ~/config/site.yml), and the first element is `themes`, which defines which theme to utilize based on location of the template file being rendered.  For example, you may wish to change to:

~~~
themes:
  admin/: admin_panel
  clients/: client_area
  default: public
~~~

With the above in place, the structure of the body content pages in ~/views/html would look like:

* /views/html/admin/ (any files rendered will utilize admin_panel theme).
* /views/html/clients/ (any files rendered will utilize client_area theme).
* /views/html/ (all other files will utilize public theme)



