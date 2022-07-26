class CAILA {
  constructor({ options, onNormalLink, onBlacklistedLink, onWhitelistedLink }) {
    // save the options and assign default value of [] to them
    this.options = {
      blacklistedDomains: options?.blacklistedDomains || [],
      whitelistedDomains: options?.whitelistedDomains || [],
    };
    // assign default functions so it won't error if empty
    this.onNormalLink = onNormalLink || function () {};
    this.onBlacklistedLink = onBlacklistedLink || function () {};
    this.onWhitelistedLink = onWhitelistedLink || function () {};
  }

  /**
   * This function will determine if the hostname is blacklisted, whitelisted, or normal
   *
   * @param {String} hostname - The hostname (for example: www.youtube.com)
   * @returns {String} either 'blacklisted', 'whitelisted', or 'normal'
   */
  getHostClassification(hostname) {
    /* edit only below this line */

    // if ipv6
    //   convert to ipv4

    // if ()
    //   return 'blacklisted';

    // if ()
    //   return 'whitelisted';

    return 'normal';

    /* edit only above this line */
  }

  protect() {
    let anchorTags = document.getElementsByTagName('a');
    for (let i = 0; i < anchorTags.length; i++) {
      anchorTags[i].addEventListener('click', (e) => {
        e.preventDefault();
        let currentTag = anchorTags[i];

        // this is an alternative to the String.prototype.normalize
        // this also converts ｡ to a normal dot
        // and also converts the IP address into its proper format
        // except for the IPv6/IPv4 embedded addresses
        // thats why you need to use the convertIPv6ToIPv4 later
        let host = currentTag.host;
        let hostClassification = this.getHostClassification(host);

        if (hostClassification === 'blacklisted') {
          this.onBlacklistedLink(
            currentTag.href,
            currentTag.host,
            currentTag.target
          );
        } else if (hostClassification === 'whitelisted') {
          this.onWhitelistedLink(
            currentTag.href,
            currentTag.host,
            currentTag.target
          );
        } else {
          this.onNormalLink(
            currentTag.href,
            currentTag.host,
            currentTag.target
          );
        }
      });
    }
  }

  /* Helper Functions */

  // this will convert [::ffff:8efa:c74e] to 142.250.199.78
  convertIPv6ToIPv4(hostname) {
    hostname = hostname.substring(1, hostname.length - 1); // remove the opening and closing brackets in the IP
    let parts = hostname.split(':'); // split the hostname by the colon character
    hostname = parts[3] + parts[4]; // join the last 2 parts
    hostname = parseInt(hostname, 16); // convert the hex to integer

    return this.intToIP(hostname);
  }

  // checks if a hostname is in this format [::ffff:8efa:c74e]
  isIPv6(hostname) {
    return hostname.match(/^\[::ffff:[a-f0-9]{4}:[a-f0-9]{4}\]/);
  }

  // converts integer IP to IPv4
  intToIP = (int) => {
    let part1 = int & 255;
    let part2 = (int >> 8) & 255;
    let part3 = (int >> 16) & 255;
    let part4 = (int >> 24) & 255;

    return part4 + '.' + part3 + '.' + part2 + '.' + part1;
  };
}
