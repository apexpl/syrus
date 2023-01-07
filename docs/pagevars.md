
# Page Variables

As an extension of per-template page titles and layouts, Syrus also allows for easy addition of any per-template based page variable.  Within the site.yml configuration file (default ~/config/site.yml) there is a "page_vars" section available, with each element beng an associative array of filename - value pairs.

By default, Syrus uses the variables "title" and "layout" for page titles and layouts respectively.  However, you may add any additional variables you wish, for example:

~~~
page_vars:

  contact_email:
    locations/houston.html: anne@domain.com
    location/chicago.html: jared@domain.com
    locations/
  

