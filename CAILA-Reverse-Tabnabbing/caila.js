class CAILA {
  constructor({ whitelistedDomains = [] }) {
    this.whitelistedDomains = whitelistedDomains;
  }

  secureAnchorTag(anchorTag) {
    /* only edit below this line */

    // get hostname so we can compare later in the blacklisted domains
    let hostname = anchorTag.hostname;

    anchorTag.setAttribute('rel', 'noopener nofollow noreferrer');

    /* only edit above this line */
    return anchorTag;
  }

  protect() {
    let anchorTags = document.querySelectorAll('a[target=_blank]');

    for (let i = 0; i < anchorTags.length; i++) {
      secureAnchorTag(anchorTags[i]);
    }
  }
}
