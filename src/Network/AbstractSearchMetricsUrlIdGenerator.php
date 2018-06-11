<?php declare(strict_types = 1);

namespace Searchmetrics\SeniorTest\Network;


abstract class AbstractSearchMetricsUrlIdGenerator implements UrlIdGenerator
{

    /**
     * Map of all hex representations of ASCII special chars.
     *
     * @var string[]
     * @see http://www.w3schools.com/tags/ref_urlencode.asp
     */
    private const ASCII_ENCODINGS = [
        '%2F',
        '%30',
        '%31',
        '%32',
        '%33',
        '%34',
        '%35',
        '%36',
        '%37',
        '%38',
        '%39',
        '%3A',
        '%3B',
        '%3C',
        '%3D',
        '%3E',
        '%3F',
        '%40',
        '%41',
        '%42',
        '%43',
        '%44',
        '%45',
        '%46',
        '%47',
        '%48',
        '%49',
        '%4A',
        '%4B',
        '%4C',
        '%4D',
        '%4E',
        '%4F',
        '%50',
        '%51',
        '%52',
        '%53',
        '%54',
        '%55',
        '%56',
        '%57',
        '%58',
        '%59',
        '%5A',
        '%5B',
        '%5C',
        '%5D',
        '%5E',
        '%5F',
        '%60',
        '%61',
    ];

    /**
     * Removes the port from the authority of an URL.
     * @param string $url
     * @return string
     */
    private function removePort(string $url) : string
    {
        $defaultPort = (0 === \strpos($url, self::PROTOCOL_HTTPS) ? self::PORT_HTTPS : self::PORT_HTTP);
        $actualPort = (string) parse_url($url, PHP_URL_PORT);
        if ($defaultPort === $actualPort) {
            $url = \preg_replace("#:$actualPort#", '', $url, 1);
        }
        return $url;
    }

    /**
     * Adds the default http protocol if it does not exist.
     * @param string $url
     * @return string
     */
    private function addHttpProrocol(string $url) : string
    {
        if (!(preg_match('/^([A-Za-z0-9_]*)' . str_replace(self::PROTOCOL_DIVIDER, '', $protocol) . ':\/\//i', $url))) {
            $url = self::PROTOCOL_HTTP . $url;
        }
        return $url;
    }

    /**
     * Validate URL to remove port add protocol and fix character encoded if necessary
     * @param string $url
     * @return string
     */
    private function normalizeUrl(string $url) : string
    {
        $url = $this->removePort($url);
        $url = $this->addHttpProrocol($url);
        // Replace lowercase ASCII encoded characters with uppercase
        $url = \str_ireplace(self::ASCII_ENCODINGS, self::ASCII_ENCODINGS, $url);
        return $url;
    }

    /**
     * Generates a long integer ID from an URL.
     */
    final public function generate(string $url) : string
    {
        $url = \trim($url);

        if ($url === '') {
            throw new \InvalidArgumentException('URL string must not be empty!');
        }

        return $this->generateId($this->normalizeUrl($url));
    }

    abstract protected function generateId(string $url) : string;
}