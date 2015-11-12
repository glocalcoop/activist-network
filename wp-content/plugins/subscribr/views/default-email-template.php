<?php
/**
 * default-email-template.php
 *
 * Modify this to suit your needs by copying it into your active
 * theme folder into a subdirectory named `subscribr`.
 * 
 * Available variables: %post_title%, %post_date%, %post_excerpt%, %permalink%, %site_name%, %site_url%, %user_ip%, %notification_label%, %notifications_label%, %profile_url%
 *
 */


$mail_body = "

A new post matching one of your %notifications_label% is available on %site_name%:

%post_title%  (%post_date%)

%post_excerpt%

Permalink: %permalink%

---------------------------------------
You received this email because you asked to be notified when new updates are published.
Manage your %notifications_label% or unsubscribe here: %profile_url%
---------------------------------------

- The %site_name% Team";
