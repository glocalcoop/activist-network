<?php
/*
* Template for the output of the Network Posts List as Highlights module
* Override by placing a file called plugins/glocal-network-content/anp-post-highlights-template.php in your active theme
*/ 

$html .= '<article id="highlights-module" class="' . $style . ' '  . $class . '">';
$html .= '<h2 class="module-heading" ' . $title_image . '>';
$html .= $title;
$html .= '</h2>';
$html .= render_list_html($highlight_posts, $settings);
$html .= '</article>';

?>