# TODO list
-----

This is a TODO list to achieve the bearest minimum that the SPIRIT API bundle has to fulfill. The list is only temporary while we get this bundle up to scratch, with the intention that this file is **deleted after all the features below have been completed**.

If another feature has been identified that needs to be implemented, please write up a ticket in **JIRA**.

-----
## TODO functionality

If a feature below in the TODO list has been completed, please strikethrough entry recorded below and commit to the repo.

###API calls:

* Persons:
	* update person - <http://apidocs.educationcompany.co.uk/#person-update>
* Activities: 
	* activity - <http://apidocs.educationcompany.co.uk/#activities-api-post>
* WebAccounts:
	* ~~update web account~~ - <http://apidocs.educationcompany.co.uk/#webaccounts-update> **DONE**
	* update web account password - <http://apidocs.educationcompany.co.uk/#webaccounts-update>
* Metadata:
	* create Metadata - <http://apidocs.educationcompany.co.uk/#metadatas-api-post>

###Internal functionality:

Please note that the US spelling of `synchronize` has been used throughout the code as this is intentional.

* **Synchroniser** - synchronises data stored in entities with data retrieved from SPIRIT (also requires command)
* **Storing of entities** - store data after receiving from SPIRIT

### Nice to have:

* **Serialiser** - an annotation powered serialiser to serialise SPIRIT API models and entities (e.g. can be used to slim down a Model object for JSON output)
* **Unit tests!!**
    * more than doable to unit test the `ModelNormalizer`

## Completed functionality

* API caller
* Country helper
	* get all
* Data collector
* Default Model values
* Models (with mapping annotations):
	* Country
	* JobType
	* Organisation
	* OrganisationType
	* Person
	* Product
	* ProductUsage
	* ProductUsageTransactionType
	* WebAccount
* Model mapping annotations
* Organisation helper
	* create
	* update 
	* search by postcode
	* get by ID
* SPIRIT helper container
* Unit tests:
	* ApiResponse
* WebAccount helper
	* create
	* update
	* get by ID
* Product helper
	* create
	* list all
	* get by ID (Required EdCo to implement endpoint on spirit API)
* ProductUsage helper
	* create
	* get SumOfValues
	* get by ID (Required EdCo to implement endpoint on spirit API)
* ProductUsageTransactionType helper
	* create
	* update
	* get All
	* get by ID (Required EdCo to implement endpoint on spirit API)