<?php
/**
 * email-template.php
 *
 * DO NOT MODIFY THIS FILE DIRECTLY - IT WILL BE OVERWRITTEN -
 * COPY THIS FILE TO YOUR ACTIVE THEME IN A SUBFOLDER NAMED "subscribr"
 *
 * Modify this email template to suit your needs by copying it into your active
 * theme folder into a subdirectory named "subscribr".
 *
 * AVAILABLE VARIABLES: %post_title%, ,%post_type%, %post_date%, %post_excerpt%, %permalink%, %site_name%, %site_url%, %user_ip%, %notification_label%, %notifications_label%, %profile_url%
 *
 */

$mail_body = "

A new %post_type% matching one of your %notifications_label% is available on %site_name%:

%post_title%  (%post_date%)

%post_excerpt%

Permalink: %permalink%

---------------------------------------
You received this email because you asked to be notified when new updates are published.
Manage your %notifications_label% or unsubscribe here: %profile_url%
---------------------------------------

- The %site_name% Team";

