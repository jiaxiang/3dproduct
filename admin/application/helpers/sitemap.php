<?php defined('SYSPATH') or die('No direct script access.');

class Sitemap_Core {
    public static function Render($url,$lastMod,$changeFreq,$priority)
    {
        $r = "";
        $r .= "\t<url>\n";
        $r .= "\t\t<loc>" . self::EscapeXML($url) . "</loc>\n";
        if($lastMod > 0)
            $r .= "\t\t<lastmod>" . date('Y-m-d\TH:i:s+00:00', $lastMod) . "</lastmod>\n";
        if(!empty($changeFreq))
            $r .= "\t\t<changefreq>" . $changeFreq . "</changefreq>\n";
        if($priority !== false && $priority !== "")
            $r .= "\t\t<priority>" . $priority . "</priority>\n";
        $r .= "\t</url>\n";
        return $r;
    }
    
    public static function EscapeXML($string)
    {
        return str_replace(array (
            '&', 
            '"', 
            "'", 
            '<', 
            '>' 
        ), array (
            '&amp;', 
            '&quot;', 
            '&apos;', 
            '&lt;', 
            '&gt;' 
        ), $string);
    }
}
?>