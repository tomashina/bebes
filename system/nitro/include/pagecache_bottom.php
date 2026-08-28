<?php

if (isNitroEnabled() && getNitroPersistence('PageCache.Enabled') && !empty($GLOBALS['nitro.start.time'])) {
    $ob_content = ob_get_contents();

    if (!empty($ob_content)) {
        $isCompressed = false;
        foreach ($this->headers as $header) {
            if (strpos($header, 'Content-Encoding: ') !== false) {
                $isCompressed = true;
                break;
            }
        }

        $GLOBALS["nitro_final_output"] = $isCompressed ? gzinflate(substr($ob_content, 10, -8)) : ob_get_contents();
        $GLOBALS["nitro_headers_list"] = array_merge(headers_list(), $this->headers);
        require_once NITRO_CORE_FOLDER . 'bottom.php';
    }
}
