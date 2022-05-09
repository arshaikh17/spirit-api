# Configuration Reference
-----

The following configuration attributes are available under `spirit_api`:

* [`key`](#ref-key)
* [`url`](#ref-url)
* [`version`](#ref-version)
* [`prefix`](#ref-prefix)
* [`model_defaults`](#ref-model_defaults)
* [`options`](#ref-options)
    * [`auto_sync`](#ref-options-auto_sync)
    * [`connection_timeout`](#ref-options-connection_timeout)
    * [`max_auto_sync`](#ref-options-max_auto_sync)
    * [`organisation`](#ref-options-organisation)
         * [`search_limit`](#ref-options-organisation-search_limit)
    * [`sync_expiry`](#ref-options-sync_expiry)
    * [`throw_exception_on_error`](#ref-options-throw_exception_on_error)

-----

###<a name="ref-key"></a>`key`

**Type**: string

**Required**: yes

The is the API key provided by the [Education Company](http://www.educationcompany.co.uk). It is used to authenticate the bundle when connecting to the SPIRIT API service.

-

###<a name="ref-url"></a>`url`

**Type**: string

**Required**: yes

This is the URL of the SPIRIT API service. The live URL can be provided by the [Education Company](http://www.educationcompany.co.uk), however for dev instances <http://uat-phobos.education.co.uk/service> can be used.

-

###<a name="ref-version"></a>`version`

**Type**: string

**Required**: yes

This determines which version of the SPIRIT API service to interact with. The latest version assigned to the provided api [`key`](#ref-key) can be retrieved by calling the `ping` command (see the [**README.md**](../README.md) for more).

-

###<a name="ref-prefix"></a>`prefix`

**Type**: string

**Required**: no

**Default value**: `/API`

If for whatever reason, an additional prefix URI is required to be appended at the end of the API `url` for each request, this can be achieved by setting the `prefix` value. 

-

###<a name="ref-model_defaults"></a>`model_defaults`

**Type**: scalar

**Required**: no

When 'normalizing' a model, any relating models and/or properties that have not been populated are done so using the values defined in the model defaults scalar. The population of these values is performed just before posting a model's data to the SPIRIT API service.

The first indented key(s) must refer to a model class, then the corresponding child keys named the same as the property specifying the default value to. As shown from the example below, recursion is supported.

**Example**:

~~~yaml
model_defaults:
    Edcoms\SpiritApiBundle\Model\Organisation:
        type:
            id: 321
    Edcoms\SpiritApiBundle\Model\Person:
        jobType:
            id: 123
    Edcoms\SpiritApiBundle\Model\WebAccount:
        userType:
            id: 1
        type:
            id: 2
~~~

-

###<a name="ref-options"></a>`options`

**Type**: scalar

**Required**: no

See below for all available options...

-

###<a name="ref-options-auto_sync"></a>`options` - `auto_sync`

**Type**: boolean

**Required**: no

**Default value**: `true`

If set to `true`, this will update any locally stored entities that can be mapped from any received models, made by a SPIRIT API service call.

-

###<a name="ref-options-connection_timeout"></a>`options` - `connection_timeout`

**Type**: integer

**Required**: no

**Default value**: `10`

Number of seconds before any connection to the SPIRIT API service times out.

-

###<a name="ref-options-max_auto_sync"></a>`options` - `max_auto_sync`

**Type**: integer

**Required**: no

**Default value**: `1`

The maximum number of model objects to have received in order to automatically synchronize existing local entities. If the maximum has been exceeded, synchronization is not performed.

-

###<a name="ref-options-organisation"></a>`options` - `organisation`

**Type**: scalar

**Required**: no

See below for all available options...

-

###<a name="ref-options-organisation-search_limit"></a>`options` - `organisation` - `search_limit`

**Type**: integer

**Required:** no

**Default value**: `0`

The number of organisations to limit to when searching via the provided Postcode lookup route.

-

###<a name="ref-options-sync_expiry"></a>`options` - `sync_expiry`

**Type**: integer

**Required**: no

**Default value**: `1`

Number of days until a local entity is declared to be expired. This means it is eligible for synchronization.

-

###<a name="ref-options-throw_exception_on_error"></a>`options` - `throw_exception_on_error`

**Type**: boolean

**Required**: `true`

If `true`, an exception is thrown if an error has occured either making a call to the SPIRIT API service, or with handling a response.