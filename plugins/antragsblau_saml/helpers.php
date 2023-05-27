<?php

use app\plugins\antragsblau_saml\config\Env;

if (! function_exists('env')) {
    /**
     * Gets the value of an environment variable.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function env(string $key, $default = null)
    {
        if (class_exists('Dotenv\Dotenv')) {
            $dotenv = Dotenv\Dotenv::create(Env::getRepository(), __DIR__);
            $dotenv->load();
            $dotenv->required('SAML2_ENTITY_ID');
            $dotenv->required('SAML2_AUTO_SELECT_IDP')->isBoolean();
            $dotenv->required('SAML2_IDP');
            $dotenv->required('SAML2_IDP_SSO');
            $dotenv->required('SAML2_IDP_x509_CERT');
        } else {
            throw new \InvalidArgumentException('No Dotenv\Dotenv package found. Please run `composer require vlucas/phpdotenv`');
        }

        return Env::get($key, $default);
    }
}


if (! function_exists('value')) {
    /**
     * Return the default value of the given value.
     *
     * @param mixed $value
     * @param mixed $args
     * @return mixed
     */
    function value($value, ...$args)
    {
        return $value instanceof Closure ? $value(...$args) : $value;
    }
}
