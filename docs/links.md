---
title: Links
---

# Links

Links are the core of Blast, they are the shortened URLs that you create and share with other people.

## The link object

A link is represented by the following JSON object:

```json
{
  "id": "01h8s7d7z5nj7e0529g99h9rj8",
  "short_url": "https://blst.to/SvN5qSQ",
  "destination_url": "https://example.com",
  "alias": "SvN5qSQ",
  "has_password": false,
  "visit_limit": null,
  "expires_at": null,
  "created_at": "2023-08-26T15:29:09.000000Z",
  "updated_at": "2023-08-26T15:29:09.000000Z"
}
```

### Attributes

| Name              | Type      | Description                                       |
| ----------------- | --------- | ------------------------------------------------- |
| `id`              | `string`  | The unique identifier for the link.               |
| `short_url`       | `string`  | The shortened URL.                                |
| `destination_url` | `string`  | The destination URL.                              |
| `alias`           | `string`  | The alias of the link.                            |
| `has_password`    | `boolean` | Whether or not the link has a password.           |
| `visit_limit`     | `integer` | The visit limit of the link.                      |
| `expires_at`      | `string`  | The date and time that the link expires.          |
| `created_at`      | `string`  | The date and time that the link was created.      |
| `updated_at`      | `string`  | The date and time that the link was last updated. |

## Create a link

To create a link, you need to send a `POST` request to the `/links` endpoint.

### Parameters

| Name              | Type      | Description                                        |
| ----------------- | --------- | -------------------------------------------------- |
| `team_id`         | `string`  | The ID of the team the link should be assigned to. |
| `destination_url` | `string`  | The destination URL.                               |
| `alias`           | `string`  | (optional) A custom alias for the link.            |
| `password`        | `string`  | (optional) A password for the link.                |
| `visit_limit`     | `integer` | (optional) The visit limit of the link.            |
| `expires_at`      | `string`  | (optional) The expiry date of the link.            |

### Request

```bash
curl https://blst.to/api/v1/links \
  -H "Authorization: Bearer {token}" \
  -d team_id="01h8htjfdcg8v7yvphj1xaa8g6" \
  -d destination_url="https://example.com"
```

### Response

```json
{
  "data": {
    "id": "01h8s7d7z5nj7e0529g99h9rj8",
    "short_url": "https://blst.to/SvN5qSQ",
    "destination_url": "https://example.com",
    "alias": "SvN5qSQ",
    "has_password": false,
    "visit_limit": null,
    "expires_at": null,
    "created_at": "2023-08-26T15:29:09.000000Z",
    "updated_at": "2023-08-26T15:29:09.000000Z"
  }
}
```

## Retrieve a link

To retrieve a link, you need to send a `GET` request to the `/links/{link}` endpoint.

### Request

```bash
curl https://blst.to/api/v1/links/{link} \
  -H "Authorization: Bearer {token}"
```

### Response

```json
{
  "data": {
    "id": "01h8s7d7z5nj7e0529g99h9rj8",
    "short_url": "https://blst.to/SvN5qSQ",
    "destination_url": "https://example.com",
    "alias": "SvN5qSQ",
    "has_password": false,
    "visit_limit": null,
    "expires_at": null,
    "created_at": "2023-08-26T15:29:09.000000Z",
    "updated_at": "2023-08-26T15:29:09.000000Z"
  }
}
```

## Update a link

To update a link, you need to send a `PUT` request to the `/links/{link}` endpoint.

All parameters are optional, and any parameters that are not provided will not be updated.

### Parameters

| Name              | Type      | Description                                                   |
| ----------------- | --------- | ------------------------------------------------------------- |
| `team_id`         | `string`  | (optional) The ID of the team the link should be assigned to. |
| `destination_url` | `string`  | (optional) The destination URL.                               |
| `alias`           | `string`  | (optional) A custom alias for the link.                       |
| `password`        | `string`  | (optional) A password for the link.                           |
| `visit_limit`     | `integer` | (optional) The visit limit of the link.                       |
| `expires_at`      | `string`  | (optional) The expiry date of the link.                       |

### Request

```bash
curl -X PUT https://blst.to/api/v1/links/{link} \
  -H "Authorization: Bearer {token}" \
  -d alias="example"
```

### Response

```json
{
  "data": {
    "id": "01h8s7d7z5nj7e0529g99h9rj8",
    "short_url": "https://blst.to/example",
    "destination_url": "https://example.com",
    "alias": "example",
    "has_password": false,
    "visit_limit": null,
    "expires_at": null,
    "created_at": "2023-08-26T15:29:09.000000Z",
    "updated_at": "2023-08-26T15:29:09.000000Z"
  }
}
```

## Delete a link

To delete a link, you need to send a `DELETE` request to the `/links/{link}` endpoint.

### Request

```bash
curl -X DELETE https://blst.to/api/v1/links/{link} \
  -H "Authorization: Bearer {token}"
```

### Response

```
204 No Content
```

## List all links

To list all links, you need to send a `GET` request to the `/links` endpoint.

### Parameters

| Name      | Type     | Description                                       |
| --------- | -------- | ------------------------------------------------- |
| `team_id` | `string` | (optional) The ID of the team to filter by.       |
| `query`   | `string` | (optional) A search query to filter the links by. |

### Request

```bash
curl https://blst.to/api/v1/links \
  -H "Authorization: Bearer {token}"
```

### Response

```json
{
  "data": [
    {
      "id": "01h8s7d7z5nj7e0529g99h9rj8",
      "short_url": "http://localhost/SvN5qSQ",
      "destination_url": "https://example.com",
      "alias": "SvN5qSQ",
      "has_password": false,
      "visit_limit": null,
      "expires_at": null,
      "created_at": "2023-08-26T15:29:09.000000Z",
      "updated_at": "2023-08-26T15:29:09.000000Z"
    },
    {
      "id": "01h8htjd823exkbk5xmddhd3mq",
      "short_url": "http://localhost/eMvD9Uz",
      "destination_url": "https://example.com",
      "alias": "eMvD9Uz",
      "has_password": false,
      "visit_limit": null,
      "expires_at": null,
      "created_at": "2023-08-23T18:30:06.000000Z",
      "updated_at": "2023-08-23T18:30:06.000000Z"
    }
  ]
}
```
