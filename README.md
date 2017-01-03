# Norwegian Postal Codes support for the Craft CMS

This is a plugin for [Craft CMS](https://craftcms.com/) which provides lookup
of Norwegian postal place (poststed) given a postal code (postnummer).

Entry types with one field for postal code and one for postal place can be
configured so that (upon saving) the latter is auto-filled based on the former.

Lookup is also available to use in plugins and templates.

## Installation

1. Copy or move the norwegianpostalcodes directory into `craft/plugins`
2. Find the plugin in Craft settings (`/admin/settings/plugins`) and click
   Install

## Usage

### Auto-filled entry fields in the control panel

To enable postal place lookup for an entry type, make sure its fields include
two Plain Text fields (must be fields added directly to the entry type; e.g.
fields inside matrix blocks are not supported), one with handle `postalCode`
and one with handle `postalPlace`. Then add the handle of the entry type to the
config setting `postalPlaceLookupEntryTypes`, in
`craft/config/norwegianpostalcodes.php`. Example:

```php
<?php

return array(
    'postalPlaceLookupEntryTypes' => 'company',
);
```

It is possible to add multiple entry types to this setting by separating the
handles with commas.

When an entry of specified type is saved, if its `postalPlace` field is empty,
the plugin will attempt to fill it out based on the value of `postalCode`.
(E.g. if `postalCode` is set to `0159` and `postalPlace` is empty, and
`Ctrl`+`S` is pressed, `postalPlace` will be changed to `OSLO` before the entry
is saved.) However, if the `postalPlace` field has already been filled out, no
lookup is performed and the fields remain at their specified values.

### Postal place lookup in a plugin

Example:

```php
$postalPlace = craft()->norwegianPostalCodes_lookup->getPostalPlace('0159');
```

Exceptions may be thrown if the lookup fails. See code documentation for
details.

### Postal place lookup in templates

Example:

```
{% set place = craft.norwegianPostalCodes.getPostalPlace('0159') %}
```

Note that this may return `null` if the lookup fails for some reason, even
though there are several possible explanations for such a failure:

- inability to connect to the external API that provides the postal code
  information
- the input not being a valid format, i.e. not being a string of exactly four
  decimal digits (0-9)
- the input not being a valid postal code despite being the correct format (not
  all 10000 possible four-digit codes correspond to actual postal codes in use)
