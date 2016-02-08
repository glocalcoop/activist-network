#####
INSERT INTO civicrm_group_contact (contact_id, group_id, `status`)
SELECT DISTINCT child_group_contact.contact_id, domain_group.domain_group_id, 'Added'
FROM civicrm_group_organization go RIGHT JOIN (
SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(value,'";',1),':"',-1) AS domain_group_id,
value, domain_id
FROM civicrm_setting s
WHERE group_name = 'Multi Site Preferences'
AND name = 'domain_group_id'
AND SUBSTRING_INDEX(SUBSTRING_INDEX(value,'";',1),':"',-1) > 0
) as domain_group
ON domain_group.domain_group_id = go.group_id
LEFT JOIN civicrm_group child_group ON go.group_id = child_group.parents
LEFT JOIN civicrm_group_organization cgo ON child_group.id = cgo.group_id
LEFT JOIN civicrm_group_contact child_group_contact ON child_group_contact.group_id = child_group.id AND child_group_contact.`status` = 'Added'
LEFT JOIN civicrm_group_contact parent_group_contact ON domain_group.domain_group_id = parent_group_contact.group_id
AND child_group_contact.contact_id = parent_group_contact.contact_id
WHERE
child_group.id IS NOT NULL
AND cgo.organization_id IS NULL
AND parent_group_contact.id IS NULL
AND child_group_contact.id IS NOT NULL
AND domain_group.domain_group_id IS NOT NULL
AND child_group.parents NOT LIKE '%,%'
;

##
# Set Status on parent group to reflect child group
###
UPDATE
civicrm_group_organization go RIGHT JOIN (
SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(value,'";',1),':"',-1) AS domain_group_id,
value, domain_id
FROM civicrm_setting s
WHERE group_name = 'Multi Site Preferences'
AND name = 'domain_group_id'
AND SUBSTRING_INDEX(SUBSTRING_INDEX(value,'";',1),':"',-1) > 0
) as domain_group
ON domain_group.domain_group_id = go.group_id
LEFT JOIN civicrm_group child_group ON go.group_id = child_group.parents
LEFT JOIN civicrm_group_organization cgo ON child_group.id = cgo.group_id
LEFT JOIN civicrm_group_contact child_group_contact ON child_group_contact.group_id = child_group.id AND child_group_contact.`status` = 'Added'
LEFT JOIN civicrm_group_contact parent_group_contact ON domain_group.domain_group_id = parent_group_contact.group_id
AND child_group_contact.contact_id = parent_group_contact.contact_id
SET parent_group_contact.`status` = child_group_contact.`status`
WHERE
child_group.id IS NOT NULL
AND cgo.organization_id IS NULL
AND parent_group_contact.`status` <> 'Added'
AND child_group_contact.id IS NOT NULL
AND domain_group.domain_group_id IS NOT NULL
AND child_group.parents NOT LIKE '%,%'
;

###############################################################################################
##
##  associated all groups with same org as their parent (domain) org
##
###############################################################################################
INSERT INTO civicrm_group_organization (group_id, organization_id)
SELECT child_group.id as group_id, go.organization_id as organization_id
FROM civicrm_group_organization go RIGHT JOIN (
SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(value,'";',1),':"',-1) AS domain_group_id,
value, domain_id
FROM civicrm_setting s
WHERE group_name = 'Multi Site Preferences'
AND name = 'domain_group_id'
AND SUBSTRING_INDEX(SUBSTRING_INDEX(value,'";',1),':"',-1) > 0
) as se
ON se.domain_group_id = go.group_id
LEFT JOIN civicrm_group child_group ON go.group_id = child_group.parents
LEFT JOIN civicrm_group_organization cgo ON child_group.id = cgo.group_id
WHERE
child_group.id IS NOT NULL
AND cgo.organization_id IS NULL
AND se.domain_group_id IS NOT NULL
AND child_group.parents NOT LIKE '%,%'
;

DELETE gn FROM civicrm_group_nesting gn RIGHT JOIN (
SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(value,'";',1),':"',-1) AS domain_group_id,
value, domain_id
FROM civicrm_setting s
WHERE group_name = 'Multi Site Preferences'
AND name = 'domain_group_id'
AND SUBSTRING_INDEX(SUBSTRING_INDEX(value,'";',1),':"',-1) > 0
) as se
ON se.domain_group_id = gn.parent_group_id
LEFT JOIN civicrm_group child_group ON child_group_id = child_group.id
WHERE child_group_id IS NOT NULL
AND child_group.parents NOT LIKE '%,%';

UPDATE civicrm_group g RIGHT JOIN (
SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(value,'";',1),':"',-1) AS domain_group_id,
value, domain_id
FROM civicrm_setting s
WHERE group_name = 'Multi Site Preferences'
AND name = 'domain_group_id'
AND SUBSTRING_INDEX(SUBSTRING_INDEX(value,'";',1),':"',-1) > 0
) as se
ON se.domain_group_id = g.parents
SET parents = NULL
WHERE se.domain_group_id IS NOT NULL
AND parents NOT LIKE '%,%'
;

UPDATE civicrm_group g RIGHT JOIN (
SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(value,'";',1),':"',-1) AS domain_group_id,
value, domain_id
FROM civicrm_setting s
WHERE group_name = 'Multi Site Preferences'
AND name = 'domain_group_id'
AND SUBSTRING_INDEX(SUBSTRING_INDEX(value,'";',1),':"',-1) > 0
) as se
ON se.domain_group_id = g.id

SET g.children = NULL
WHERE se.domain_group_id IS NOT NULL

;

truncate civicrm_cache;
UPDATE civicrm_setting SET
  value = 'i:0;'
WHERE
  group_name = 'Multi Site Preferences'
  AND name = 'is_enabled'
  AND domain_id = 1;
  
UPDATE civicrm_setting SET
  value = 's:i:"0";'
WHERE
  group_name = 'Multi Site Preferences'
  AND name = 'domain_group_id'
  AND domain_id = 1;
  
INSERT INTO `civicrm_setting` (
 `group_name`, 
 `name`, 
 `value`, 
 `domain_id`, 
 `is_domain`) 
 
 VALUES (
 'Multi Site Preferences', 
 'multisite_acl_enabled', 
 'i:0;', 
  1, 
  1);
 
 INSERT INTO `civicrm_setting` (
 `group_name`, 
 `name`, 
 `value`, 
 `domain_id`, 
 `is_domain`) 
 
 SELECT 

 'Multi Site Preferences' as group_name, 
 'multisite_acl_enabled' as name, 
 'i:1;' as value, 
  domain.id as domain_id, 
  0 as is_domain
 
 
 FROM 
 civicrm_domain domain LEFT JOIN civicrm_setting s ON domain.id = domain_id AND s.name = 'multisite_acl_enabled'
 WHERE s.id IS NULL;

