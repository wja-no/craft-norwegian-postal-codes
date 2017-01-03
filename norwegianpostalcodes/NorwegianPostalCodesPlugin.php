<?php
namespace Craft;

class NorwegianPostalCodesPlugin extends BasePlugin
{
    function getName()
    {
        return Craft::t('Norwegian postal codes');
    }

    function getVersion()
    {
        return '1.0';
    }

    function getDeveloper()
    {
        return 'WJA';
    }

    function getDeveloperUrl()
    {
        return 'https://wja.no/';
    }

    function init()
    {
        parent::init();

        craft()->on('entries.onBeforeSaveEntry', function(Event $event) {
            $entry = $event->params['entry'];
            $entryTypesString = craft()->config->get(
                    'postalPlaceLookupEntryTypes', 'norwegianpostalcodes');
            $lookupEntryTypes = explode(',', $entryTypesString);
            $entryType = $entry->type['handle'];
            if (in_array($entryType, $lookupEntryTypes) &&
                    isset($entry['postalCode']) &&
                    isset($entry['postalPlace']) &&
                    $entry['postalPlace'] === '') {
                try {
                    $postalPlace = craft()->norwegianPostalCodes_lookup
                            ->getPostalPlace($entry['postalCode']);
                    $entry->setContentFromPost(array(
                        'postalPlace' => $postalPlace,
                    ));
                } catch (\Exception $e) {
                }
            }
        });
    }
}
