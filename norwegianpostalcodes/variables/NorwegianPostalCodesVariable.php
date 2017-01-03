<?php
namespace Craft;

class NorwegianPostalCodesVariable
{
    /**
     * Get the postal place (Poststed) corresponding to a given postal code (Postnummer).
     *
     * Returns null if no postal place was found (which could happen if the
     * external API could not be reached, or if the input was not a valid
     * postal code).
     */
    public function getPostalPlace($postalCode) {
        try {
            return craft()->norwegianPostalCodes_lookup->getPostalPlace($postalCode);
        } catch (\Exception $e) {
            return NULL;
        }
    }
}
