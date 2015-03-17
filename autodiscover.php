<?php

$configXML = explode('?', $_SERVER['REQUEST_URI']);
$configXML = $configXML[0];

switch(strtolower($configXML)) {
        case '/autodiscover':
        case '/autodiscover/':
        case '/autodiscover/autodiscover.xml': {
                $data = file_get_contents('php://input');
                preg_match("/\<EMailAddress\>(.*?)\<\/EMailAddress\>/", $data, $matches);
                $output = autodiscover($matches[1]);
                break;
        }
        case '/mail/config-v1.1.xml':
        case '/well-known/autoconfig/mail/config-v1.1.xml': {
                $output = autoconfig(@$_GET['emailaddress']);
                break;
        }
        default: {
                header("HTTP/1.0 404 Not Found");
                exit;
                break;
        }
}

header("Content-type: text/xml; charset=utf-8");
print $output;
exit;


function autodiscover($LoginName) {
return <<<XML
<?xml version="1.0" encoding="utf-8"?>
<Autodiscover xmlns="http://schemas.microsoft.com/exchange/autodiscover/responseschema/2006">
        <Response xmlns="http://schemas.microsoft.com/exchange/autodiscover/outlook/responseschema/2006a">
                <Account>
                        <AccountType>email</AccountType>
                        <Action>settings</Action>
                        <Protocol>
                                <Type>IMAP</Type>
                                <Server>imap.gmail.com</Server>
                                <Port>993</Port>
                                <DomainRequired>on</DomainRequired>
                                <SPA>off</SPA>
                                <SSL>on</SSL>
                                <AuthRequired>on</AuthRequired>
                                <LoginName>$LoginName</LoginName>
                        </Protocol>
                        <Protocol>
                                <Type>POP3</Type>
                                <Server>pop.gmail.com</Server>
                                <Port>995</Port>
                                <DomainRequired>on</DomainRequired>
                                <SPA>off</SPA>
                                <SSL>on</SSL>
                                <AuthRequired>on</AuthRequired>
                                <LoginName>$LoginName</LoginName>
                        </Protocol>
                        <Protocol>
                                <Type>SMTP</Type>
                                <Server>smtp.gmail.com</Server>
                                <Port>465</Port>
                                <DomainRequired>on</DomainRequired>
                                <SPA>off</SPA>
                                <SSL>on</SSL>
                                <AuthRequired>on</AuthRequired>
                                <UsePOPAuth>on</UsePOPAuth>
                                <SMTPLast>off</SMTPLast>
                                <LoginName>$LoginName</LoginName>
                        </Protocol>
                </Account>
        </Response>
</Autodiscover>
XML;
}





function autoconfig($emailaddress = null) {
        $domain = str_replace(array('autoconfig.', 'autodiscover.'), '', $_SERVER['HTTP_HOST']);

        return <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<clientConfig version="1.1">
        <emailProvider id="$domain">
                <domain>$domain</domain>
                <displayName>$domain Mail</displayName>
                <displayShortName>$domain</displayShortName>
                <incomingServer type="imap">
                        <hostname>imap.gmail.com</hostname>
                        <port>993</port>
                        <socketType>SSL</socketType>
                        <authentication>password-encrypted</authentication>
                        <username>%EMAILADDRESS%</username>
                </incomingServer>
                <incomingServer type="pop3">
                        <hostname>pop.gmail.com</hostname>
                        <port>995</port>
                        <socketType>SSL</socketType>
                        <authentication>password-cleartext</authentication>
                        <username>%EMAILADDRESS%</username>
                </incomingServer>
                <outgoingServer type="smtp">
                        <hostname>smtp.gmail.com</hostname>
                        <port>465</port>
                        <socketType>SSL</socketType>
                        <authentication>password-cleartext</authentication>
                        <username>%EMAILADDRESS%</username>
                </outgoingServer>
                <documentation url="https://support.google.com/mail/troubleshooter/1668960?rd=1">
                        <descr lang="en">Getting started with IMAP and POP3</descr>
                </documentation>
        </emailProvider>
</clientConfig>

XML;
}
