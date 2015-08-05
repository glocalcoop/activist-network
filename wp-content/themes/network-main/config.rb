preferred_syntax = :scss
http_path = '/'
css_dir = 'library/css'
sass_dir = 'library/sass'
images_dir = 'library/images'
javascripts_dir = 'library/js'
relative_assets = true

#environment = :development
environment = :production
output_style = :compressed

sass_options = {
    :cache => false
}

# Enable Debugging (Line Comments, FireSass)
# Invoke from command line: compass watch -e development --force
if environment == :development
  output_style = :expanded
  sass_options = { 
  	:debug_info => true
  }
  line_comments = true
  line_numbers = true
end