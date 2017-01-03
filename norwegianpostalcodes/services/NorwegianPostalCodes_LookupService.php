<?php
namespace Craft;

class NorwegianPostalCodes_LookupService extends BaseApplicationComponent
{
    const API_URL_FORMAT = 'https://api.bring.com/shippingguide/api/' .
            'postalCode.json?clientUrl=%s&pnr=%s';
    const API_MESSAGE_INVALID_POSTAL_CODE = 'Ugyldig postnummer';

    /**
     * Get the postal place (Poststed) corresponding to a given postal code
     * (Postnummer).
     *
     * @param string $postalCode A string consisting of exactly four ASCII
     * decimal digits (0-9).
     * @return string The name of the postal place, or NULL if there is none
     * for the given postal code
     * @throws \InvalidArgumentException if $postalCode does not conform to the
     * required format
     * @throws \RuntimeException if some error occurred when attempting to
     * perform the lookup, such as if the bring.com API was unavailable
     */
    public function getPostalPlace($postalCode)
    {
        if (preg_match('/^\d{4}$/', $postalCode) !== 1) {
            throw new \InvalidArgumentException('Invalid postal code: ' .
                    $postalCode);
        }
        $requestUrl = sprintf(self::API_URL_FORMAT,
                urlencode(craft()->getSiteUrl()), $postalCode);
        $resultJson = file_get_contents($requestUrl);
        if ($resultJson === FALSE) {
            throw new \RuntimeException('Lookup failed: ' . $postalCode);
        }
        $resultArray = json_decode($resultJson, TRUE);
        if (!$resultArray || !array_key_exists('result', $resultArray)) {
            throw new \RuntimeException('Could not parse result for ' .
                $postalCode . ': ' . $resultJson);
        }
        $postalPlace = $resultArray['result'];
        if ($postalPlace === self::API_MESSAGE_INVALID_POSTAL_CODE) {
            return NULL;
        }
        return $resultArray['result'];
    }
}
