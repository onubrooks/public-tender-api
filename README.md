# About This API

This is a simple Laravel REST API-based application that allows users to upload and then search and consume data. For this project, portuguese public tender data was used but the task is valid with any other dataset. The API has the following capabilities:

- An endpoint to upload a .xls file with data for processing. The data always follows the same structure/format and that format can be seen on the linked file above. Handles both small and large files as well as concurrency.
- The uploaded data will be imported into a database containing all columns necessary for the storage of data as per the xls. The uploading process allows no duplicates.
- An endpoint to query the status of processing the uploaded xls and adding it to the database.
- An endpoint to query/search data from the database based on: date (dataCelebracaoContrato in xls), amount (range) (precoContratual in xls), winning company (adjudicatarios in the xls).
An endpoint to get all data for a given contract as long as provided with an ID. Ids are incremental. This marks row as ‘read’.
An endpoint to check if a certain contract (row) was ever read or not.

## Usage

## License

The repository is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
