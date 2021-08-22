# About This API

This is a simple Laravel REST API-based application that allows users to upload and then search and consume data. For this project, portuguese public tender data was used but the task is valid with any other dataset. The API has the following capabilities:

- An endpoint to upload a .xls file with data for processing. The data always follows the same structure/format and that format can be seen on the linked file above. Handles both small and large files as well as concurrency.
- The uploaded data will be imported into a database containing all columns necessary for the storage of data as per the xls. The uploading process allows no duplicates.
- An endpoint to query the status of processing the uploaded xls and adding it to the database.
- An endpoint to query/search data from the database based on: date (dataCelebracaoContrato in xls), amount (range) (precoContratual in xls), winning company (adjudicatarios in the xls).
An endpoint to get all data for a given contract as long as provided with an ID. Ids are incremental. This marks row as 'read'.
An endpoint to check if a certain contract (row) was ever read or not.

## Usage

With a simple API call, all you need to do is upload your data in xls format:

```curl
curl --location --request POST 'localhost:8000/api/upload-for-processing' \
--header 'Accept: application/json' \
--form 'upload_file=@"/C:/Users/xxx/xxx/contracts-small-xlsx.xlsx"'
```

Then keep querying the processing status of your upload until it returns processed:

```curl
curl --location --request GET 'localhost:8000/api/fetch-upload-status/1'
```

```json
{
    "status": "success",
    "message": "successfully fetched upload status",
    "data": {
        "status": "processed"
    }
}
```

And that's it. Contracts can then be fetched, read or searched via other endpoints on the API.

## Project Setup

Start by cloning the project using the following command:

`git clone https://github.com/onubrooks/public-tender-api.git`

Next install dependencies:

`composer install`

After installation, if you already have a database setup, you can run migrations as follows:

`php artisan migrate`

Serve the project:

`php artisan serve`

Start the queue:

`php artisan queue:listen --timeout=3600`

After that, you are ready to give the API a go, break a leg!

## API Documentation

Full documentation can be found in the following links:

1. OpenAPI 2.0 specificaiton compliant API documentation: [Public Tender API](https://public-tender-api-ozcgz.ondigitalocean.app/docs).
2. Postman documentation with real request and response examples at: [Public Tender Collection](https://documenter.getpostman.com/view/4758703/TzzDJaKs).

## About Onubrooks

My name is Onu Abah and you can find more of my work [here](https://github.com/onubrooks). Let's catch up on mobile **(+2348062550416)** or social media via [twitter](https://twitter.com/onubrooks) and [linkedin](https://www.linkedin.com/in/onu-abah)

## License

The repository is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
