<?php
class CAILA
{
  // Properties
  public $blacklistedDomains;
  public $whitelistedDomains;

  function __construct($options)
  {
    $this->blacklistedDomains = isset($options['blacklistedDomains'])
      ? $options['blacklistedDomains']
      : [];
    $this->whitelistedDomains = isset($options['whitelistedDomains'])
      ? $options['whitelistedDomains']
      : [];
  }

  function getURLClassification($url)
  {
    $host = $this->getSimplifiedHost($url);

    // return 'invalid' if the host failed to be simplified
    if ($host === 'invalid') {
      return 'invalid';
    }

    
    /* only edit below this line */

    
    // run against the blacklist
    // return 'blacklisted' if domain is blacklisted

    // run against the whitelist
    // return 'whitelisted' if domain is blacklisted


    
    /* only edit above this line */

    
    // return 'normal' if the domain is not in blacklisted or whitelisted
    return 'normal';
  }

  function getSimplifiedHost($url)
  {
    // get the hostname from the URL
    $host = parse_url($url, PHP_URL_HOST);
    $port = parse_url($url, PHP_URL_PORT);

    // if the URL contains a port number, append it
    if ($port) {
      $host .= ':' . $port;
    }

    if ($this->isHostIsDomain($host)) {
      return strtolower($host);
    } elseif ($this->isHostIsIP($host)) {
      // if host is a proper IPv4
      // just fine
      return $host;
    } elseif ($this->isHostIPv4v6Embedding($host)) {
      // if host is IPv6/IPv4 embedding format
      // get the IPv4 part by separating using ":"
      $parts = explode(':', $host);
      $host = $parts[count($parts) - 1];
      // then remove ] in the right
      $host = rtrim($host, '] ');
      return $host;
    } else {
      return 'invalid';
    }
  }

  function isHostIsDomain($host)
  {
    // if IP is in format of domain.tld:PORT
    $parts = explode(':', $host);
    if (count($parts) > 2) {
      return false;
    }

    $host = $parts[0];
    $port = $parts[1];

    if (
      preg_match(
        '/^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$|^(([a-zA-Z0-9]|[a-zA-Z0-9][a-zA-Z0-9\-]*[a-zA-Z0-9])\.)+([A-Za-z]|[A-Za-z][A-Za-z0-9\-]*[A-Za-z0-9])$/',
        $host
      ) &&
      // port must be empty or in range of 1-65535
      (is_null($port) || ($port >= 1 && $port <= 65535))
    ) {
      return true;
    }

    return false;
  }

  function isHostIsIP($host)
  {
    // if IP is in format of IP:PORT
    $parts = explode(':', $host);
    if (count($parts) > 2) {
      return false;
    }

    $ip = $parts[0];
    $port = $parts[1];

    if (
      preg_match(
        '/^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/',
        $ip
      ) &&
      // port must be empty or in range of 1-65535
      (is_null($port) || ($port >= 1 && $port <= 65535))
    ) {
      return true;
    }

    return false;
  }

  function isHostIPv4v6Embedding($host)
  {
    return preg_match(
      '/^(\[::ffff:|\[0:0:0:0:0:ffff:)(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\]$/',
      $host
    );
  }
}
?>
