1. Design and create a data model to store data for the entities described above. Please document your ER diagram.
    - Please visit [ER Diagram and information](docs/entity-relationship-diagram.md)

2. Create a back-end service to support the following use cases:
    a. Display a list of funds optionally filtered by Name, Fund Manager, Year
    b. An Update method to update a Fund and all related attributes.

   - Please visit [how-to-test.md](docs/how-to-test.md)

3. Create an event-driven back end process to support:
    a. If a new fund is created with a name and manager that matches the name or an alias of an existing fund with the same manager, throw a duplicate_fund_warning event.
    b. Write a process to Consume the duplicate_fund_warning event
    c. Bonus if time permitting: Add a method to the service created in #2 that will return a list of potentially duplicate 
    
- This event-driven process is handled by both the `DuplicateFundWarningEvent` and `DuplicateFundWarningListener`. Once the event is fired, laravel's queue mechanism will work on the background to handle this event. The default handling is to log on `storage/logs/laravel.log`

- There is a method that return a list of potentially duplicate funds, it's the `FundController@showPossibleDuplicates` method.
- Please visit [how-to-test.md](docs/how-to-test.md) for more information.
