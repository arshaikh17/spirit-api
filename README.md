# SPIRIT API Bundle
-----

This is a **Symfony bundle** used to make API calls to the **SPIRIT** service provided by the [Education Company](http://www.educationcompany.co.uk).

All documentation for the SPIRIT API can be found via <http://apidocs.educationcompany.co.uk>.

## Prerequisits

You must have an `apiKey` provided by the **Education Company**. Without this, the SPIRIT service will not authenticate any of your requests.

The `url` is required, but a generic one can be used during setup, as we can use the `ping` command to retrieve the custom URL to the specific SPIRIT instance.

## Installation

Installed via the use of [Composer](https://getcomposer.org).

Add the repository URL into the `repositories` array in `composer.json`:

```
"repositories": [
    {
        "type": "vcs",
        "url": "git@bitbucket.org:edcomsDigital/spirit-api.git"
    }
]
```

Then, simply require the dependency by running the following command:

```composer require edcoms/spirit-api```

## Configuration

Simply register the bundle in your `AppKernel` class:

```
public function registerBundles()
{
    $bundles [
        ...

        // Spirit API bundle.
        new \SpiritApiBundle\SpiritApiBundle()
    ];
}
```

Insert a new field in the `config{-env}.yml` file(s):

```
spirit_api:
    key: [apiKey]
    url: [url]
```

below are optional fields:

```
    prefix: [url-prefix (default: '/API')]
    options:
    	connection_timeout: [timeout-secs (default: 10)
    	throw_exception_on_error: [true|false (default: true)
        proxy: #proxy configurations
            scheme: [url scheme http || https (default 'http')]
            base_url: [base URL(no scheme) format: username:password@host:port (default '')]
```

If you have the URL already, insert it into the configuration. Otherwise, use: 

<http://uat-phobos.education.co.uk/service>

then run:

`php bin/console spirit:ping`

... which will return the custom URL to use for the specific SPIRIT instance assigned to the API key. This URL then needs to be set in the `config{-env}.yml` file.

done.

