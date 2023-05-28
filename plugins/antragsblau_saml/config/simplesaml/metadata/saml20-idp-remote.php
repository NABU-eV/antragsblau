<?php

/**
 * SAML 2.0 remote IdP metadata for SimpleSAMLphp.
 *
 * Remember to remove the IdPs you don't use from this file.
 *
 * See: https://simplesamlphp.org/docs/stable/simplesamlphp-reference-idp-remote
 */

$metadata[env('SAML2_IDP')] = [
    'certificate'           => env('SAML2_SIGN_CERT_FILE'),
    'signature.privatekey'  => env('SAML2_SIGN_PRIVATE_KEY_FILE'),
    'signature.certificate' => env('SAML2_SIGN_CERT_FILE'),
    'SingleSignOnService'   => [
        [
            'Binding'  => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST',
            'Location' => env('SAML2_IDP_SSO'),
        ],
        [
            'Binding'  => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
            'Location' => env('SAML2_IDP_SSO'),
        ],
    ],
    'SingleLogoutService'   => [
        [
            'Binding'  => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST',
            'Location' => env('SAML2_IDP_SLO'),
        ],
        [
            'Binding'  => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
            'Location' => env('SAML2_IDP_SLO'),
        ],
    ],
    'ArtifactResolutionService' => [
        [
            'Binding'  => 'urn:oasis:names:tc:SAML:2.0:bindings:SOAP',
            'Location' => env('SAML2_IDP_ARS'),
            'index'    => 0,
        ],
    ],
    'NameIDFormats'         => 'urn:oasis:names:tc:SAML:1.1:nameid-format:emailAddress',
    'sign.authnrequest'     => env('SAML2_SIGN_REQUEST', false),
    'sign.logout'           => env('SAML2_SIGN_REQUEST', false),
    'keys'                  => [
        [
            'encryption'      => false,
            'signing'         => true,
            'type'            => 'X509Certificate',
            'X509Certificate' => env('SAML2_IDP_x509_CERT'),
        ],
    ],
];
