# autodiscover
Email Autodiscovery in PHP

PHP script to dynamically generate autodiscovery information for Microsoft Outlook and open source mail clients like Thunderbird.

The XML generated is currently set up for Google for Business (Google Apps for Your Domain) and is domain-independent to allow for alias domains, etc.

The webserver will need rewrite rules, a block for nginx has been supplied.

Test using the 'Outlook Autodiscovery' function of https://testconnectivity.microsoft.com/
