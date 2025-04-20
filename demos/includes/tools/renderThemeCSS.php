<?php
/**
 * Gibt CSS aus einem assoziativen Array als Text zurück.
 *
 * Unterstützt normale Selektoren (z. B. "header", ".head-wrapper")
 * und Sonderfälle wie @keyframes.
 *
 * @param array $cssConfig Die CSS-Daten aus z. B. $themeOptions['css']
 * @return string Fertiger CSS-Code als Text
 */
function renderCssFromArray(array $cssConfig): string {
    $out = "";

    foreach ($cssConfig as $selector => $styles) {

        // Sonderfall: Keyframes (z. B. "@keyframes glow")
        if (strpos($selector, '@keyframes') === 0) {
            $out .= "$selector {\n";
            foreach ($styles as $step => $style) {
                $out .= "  $step {\n";
                foreach ($style as $prop => $val) {
                    $out .= "    $prop: $val;\n";
                }
                $out .= "  }\n";
            }
            $out .= "}\n";
            continue;
        }

        // Normale Selektoren
        $out .= "$selector {\n";
        foreach ($styles as $prop => $val) {
            $out .= "  $prop: $val;\n";
        }
        $out .= "}\n";
    }

    return $out;
}
