org.civicrm.multisite
=====================

org.civicrm.multisite

This is a copy of the version in Core - separated out to work on & deploy updates from. Stable changes will be merged into core

Version 2 of the extension reduces the prevalence of Group Nesting.

In the drupal module / version 1 group nesting is the primary way to determine which groups can be seen 
and which contacts can be seen. In version 2 there is a second (less expensive) mechanism for determining which groups
can be seen (shared group_organization with the domain group).

This means we discourage creating group nesting unless you really want either
1) the group to have multiple parents OR
2) there to be members in the child group who are not in the parent group

Bear in mind that on a multisite contacts are automatically added to the domain group when they are created on that
domain so group nesting is generally a belt & braces approach to permissions
