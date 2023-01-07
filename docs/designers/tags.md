
# &lt;s:...&gt; Template Tags

Syrus comes with a default set of `<s:...>` tags, which are used as short hand for larger HTML snippets, allow themes to be interchangeable, plus provide PHP functionality with each tag.  The HTML snippets `<s:...>` tags are replaced with can be found within the tags.txt file, located within the theme directory (eg. ~/views/themes/mysite/tags.txt), which you are welcome to modify as desired.

**NOTE:** Additional `<s:...>` tags can be easily added, and please consult with the back-end developers as to whether or not they've added any additional tags.


## Tags List

For a list of all supported `<s:...>` tags, please view the default site Syrus comes with, as it also contains examples of how the tags look rendered within the web browser.  If you have docker-compose installed, you can spin up the local web server by running the commands:

~~~
git clone https://github.com/apexpl/syrus.git
cd syrus
sudo docker-compose up -d
~~~

That's it, and the default site will be available via web browser at http://127.0.0.1:8180/.  Alternatively, if you have PHP installed on your system, you may utilize PHP's built-in web server by running the following command within the ~/public/ directory:

~~~
php -S 127.0.0.1:8180
~~~



