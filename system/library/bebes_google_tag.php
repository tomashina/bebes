<?php
final class BebesGoogleTag {
    // This GA4 property is configured in GTM. Keep its ID in compgafad for ecommerce.
    const GTM_MEASUREMENT_ID = 'G-39SC882CQV';

    public static function pageview(array $analyticsIds, $adsId) {
        $analyticsIds = array_filter($analyticsIds);
        if (!$analyticsIds) {
            return '';
        }

        $directIds = array_diff($analyticsIds, array(self::GTM_MEASUREMENT_ID));
        $loader = '';
        $commands = array();
        if ($directIds) {
            $loader = '<script async src="https://www.googletagmanager.com/gtag/js?id='
                . rawurlencode(reset($directIds)) . '"></script>';
            $commands[] = "gtag('js', new Date());";
            foreach ($directIds as $id) {
                $commands[] = "gtag('config', " . json_encode($id, JSON_HEX_TAG) . ");";
            }
        }
        if ($adsId) {
            $commands[] = "gtag('config', " . json_encode($adsId, JSON_HEX_TAG)
                . ", {'allow_enhanced_conversions':true});";
        }

        if (!$commands) {
            return '';
        }
        return $loader . '<script>window.dataLayer = window.dataLayer || [];'
            . 'window.gtag = window.gtag || function () { window.dataLayer.push(arguments); };'
            . implode("\n", $commands) . '</script>';
    }
}
