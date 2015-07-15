Follow the official Wordpress instructions to set up Wordpress Multisite:
[http://codex.wordpress.org/Create_A_Network](http://codex.wordpress.org/Create_A_Network)

##PHP

On a Mac, you can use MAMP to run PHP locally. Follow this handy guide from Perishable Press:
[http://perishablepress.com/wordpress-multisite-mamp/](http://perishablepress.com/wordpress-multisite-mamp/)



##Sass (CSS)

We use [Sass](http://sass-lang.com) to make our CSS modular, scalable, extensible and manageable. If you've never used it, take 30 minutes to learn; you won't know how you ever lived without it.

A Book Apart's [Sass for Web Designers](http://www.abookapart.com/products/sass-for-web-designers) is a great place to start.

### Structure
Sass allows you to split your CSS files into subfiles called partials. This makes it much easier to find and maintain CSS on large sites. Each plugin has its own Sass partial, so inactive or deleted plugins can also have their CSS deactivated or deleted.

We use different folders of partials for the global styles, and for each theme. This helps future developers understand what sites/sections each CSS change will affect. 

### Mixins
Mixins are reusable pieces of CSS (similar to HTML includes). There are many libraries of mixins, so you don't have to write (and maintain) each border-radius or drop-shadow mixin yourself. We use [Bourbon](http://bourbon.io), a more basic library that has thorough and easy-to-read documentation. The complementary grid system [Bourbon Neat](http://neat.bourbon.io) is also simple, and is easy to use in responsive web designs.

You'll probably want to keep these two pages open in your browser as you work:  
[Bourbon docs](http://bourbon.io/docs/)  
[Bourbon Neat docs](http://neat.bourbon.io/docs/)

### Responsive styling
All three themes are fully responsive, mobile first. Media queries are done as a mixin so that responsive styling is right there in each partial. The CSS output is a bit longer, but it's worth it for clarity and ease of maintainence; all the behavior of each module is right there in one place. 

### Compiling
Browsers can't read Sass, only CSS. So you'll compile your Sass locally, using Codekit or a similar app, and upload the compiled (and minified) style.css.