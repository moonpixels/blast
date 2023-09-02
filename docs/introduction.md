---
title: Introduction
---

# Introduction

This is the documentation for the Blast API. It is intended for developers who want to integrate Blast into their own
applications.

The Blast API is a RESTful API that uses JSON as its data format. It is designed to be easy to use, so you can get
started quickly.

## Base URL

The base URL for the API is `https://blst.to/api/v1`.

## Authentication

To authenticate with the API, you need to send an `Authorization` header with your API token. You can find your API
token on the [API page](https://blst.to/settings/api) in the settings.

```bash
curl https://blst.to/api/v1/links \
  -H "Authorization: Bearer {token}"
```

## Errors

The Blast API uses conventional HTTP response codes to indicate the success or failure of an API request. In general,
codes in the `2xx` range indicate success, codes in the `4xx` range indicate an error that failed given the
information provided (e.g. a required parameter was omitted, a charge failed, etc.), and codes in the `5xx` range
indicate an error with Blast's servers.

Responses in the `4xx` range will also include a JSON object with more information about the error. The `message` field
will contain a human-readable description of the error, and the `errors` field will contain a list of specific errors
that caused the request to fail.

```json
{
  "message": "The given data was invalid.",
  "errors": {
    "destination_url": ["The destination url field is required."]
  }
}
```

## Rate limiting

The Blast API has a rate limit of 60 requests per minute on the free tier. If you exceed this limit, you will receive
a `429 Too Many Requests` response. If you receive this response, you should wait 60 seconds before making another
request.

You can increase your rate limit by [upgrading your account](https://blst.to/settings/api).

Rate limits are per account, not per API token. If you have multiple API tokens, they will all share the same rate
limit.

## Pagination

Some endpoints return a list of objects. These endpoints support pagination, which allows you to control how many
objects are returned at once. You can use the `page` and `per_page` parameters to control pagination. The `page`
parameter specifies which page of results to return, and the `per_page` parameter specifies how many results to return
per page. The default value for `page` is `1`, and the default value for `per_page` is `15`.

```bash
curl -G https://blst.to/api/v1/links \
  -H "Authorization: Bearer {token}" \
  -d page=2 \
  -d per_page=10
```

The response will include a `meta` object with information about the pagination. This includes the total number of
objects, the number of objects per page, and the current page.

```json
{
  "meta": {
    "total": 25,
    "per_page": 10,
    "current_page": 2,
    "from": 11,
    "to": 20,
    "last_page": 3
  },
  "data": []
}
```

## Timezones

All dates and times are returned in UTC. You should send any timestamps in ISO 8601 format.
