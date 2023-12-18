# Architecture

## Docker-compose
- I've used docker-compose since it's a great way to declare on a yaml file the docker components of our project. It's easy to spin up and manage the environment across different platforms, ensuring the installation and dependencies steps from within the dockerfile file.

## Factories, migrations and seeds
- I've used a few laravel built-in libraries and concepts such as factories, migrations and seeds, following the framework standard and SOLID best practices. This made it easier to test and to manage and spin up the database environment with fake data.


## Funds filtering
- There are 4 filters available on the funds list all method: `api/funds`
    - name, manager_id, manager_name, and start_year
- The filtering here is case insensitive for a better user experience -- That's why there is a usage of `ILIKE`
- For more information please check the following file: `FundQueryBuilder.php`


## Event-driven processess
- On the creation of a new Fund, canoe-app sends a FundCreate event. And on the FundCreateListener, we execute the algorithm to check for potential fund duplicates. 
- I've decided to decouple the events, in order to return as fast as possible the response for the `create` API call. Using the FundCreate event, it allows to add other future logic every time an Fund is created and doesn't block the request doing the potential fund duplicates algorithm.

- This event-driven process is handled by both the `DuplicateFundWarningEvent` and `DuplicateFundWarningListener`. Once the event is fired, laravel's queue mechanism will work on the background to handle this event. The default handling is to log on `storage/logs/laravel.log`

- There is a method that return a list of potentially duplicate funds, it's the `FundController@showPossibleDuplicates` method.
- Please visit [how-to-test.md](docs/how-to-test.md) for more information.


# Scalability considerations:
- How will your application work as the data set grows increasingly larger?
- How will your application work as the # of concurrent users grows increasingly larger?

*** Caching for Data Efficiency***: Implementing caching mechanisms, like Redis or Memcached, would be really interesting. This strategy involves storing frequently accessed data in a fast-access cache, reducing the need for repeated database queries. By serving common requests directly from the cache, especially for read-heavy operations, the application's response time and overall efficiency would see substantial improvements.

*** Database Indexing for Query Optimization ***: With an large dataset, strategically creating indexes on heavily queried columns becomes crucial. Indexing would optimize query performance by reducing search times within the database. However, this approach would need to be balanced with the overhead involved in maintaining these indexes, particularly during write operations.

*** Horizontal Scaling for User Load Management ***: To handle an increasing number of concurrent users, horizontal scaling would be a key consideration. This involves adding more servers to distribute the load evenly, preventing any single server from becoming overwhelmed. Load balancers would be essential in this setup.

*** Database Sharding for Write Operations ***: As the database grows, sharding – splitting the database into smaller segments – could be explored. This would likely be based on specific criteria, such as geographical location or fund types, allowing for more efficient data management and enhanced performance in write-heavy operations. Sharding ensures that as the data volume increases, the system remains manageable and continues to perform efficiently.


----
