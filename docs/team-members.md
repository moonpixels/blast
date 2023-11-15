---
title: Team Members
---

# Team Members

Team members are the users that have access to your team. They can manage the links within your team.

::: info
Only team owners have access to the team member endpoints.
:::

## The team member object

A team member is represented by the following JSON object:

```json
{
  "id": "01h8htjenz9wwtwwpyw7bn5c60",
  "name": "Example User",
  "email": "user@example.com",
  "initials": "EU"
}
```

### Attributes

| Name       | Type     | Description                                |
| ---------- | -------- | ------------------------------------------ |
| `id`       | `string` | The unique identifier for the team member. |
| `name`     | `string` | The name of the team member.               |
| `email`    | `string` | The email address of the team member.      |
| `initials` | `string` | The initials of the team member.           |

## Retrieve a team member

To retrieve a team member, you need to send a `GET` request to the `/teams/{team}/members/{member}` endpoint.

### Request

```bash
curl https://blst.to/api/v1/teams/{team}/members/{member} \
  -H "Authorization: Bearer {token}"
```

### Response

```json
{
  "data": {
    "id": "01h8htjenz9wwtwwpyw7bn5c60",
    "name": "Example User",
    "email": "user@example.com",
    "initials": "EU"
  }
}
```

## Delete a team member

To delete a team member, you need to send a `DELETE` request to the `/teams/{team}/members/{member}` endpoint.

### Request

```bash
curl -X DELETE https://blst.to/api/v1/teams/{team}/members/{member} \
  -H "Authorization: Bearer {token}"
```

### Response

```
204 No Content
```

## List all team members

To list all team members, you need to send a `GET` request to the `/teams/{team}/members` endpoint.

### Parameters

| Name             | Type     | Description                                     |
| ---------------- | -------- | ----------------------------------------------- |
| `filter[search]` | `string` | The search query to filter the team members by. |

### Request

```bash
curl https://blst.to/api/v1/teams/{team}/members \
  -H "Authorization: Bearer {token}"
```

### Response

```json
{
  "data": [
    {
      "id": "01h8htjenz9wwtwwpyw7bn5c60",
      "name": "Example User",
      "email": "user@example.com",
      "initials": "EU"
    },
    {
      "id": "01h8htjenw5cverscebx9ny6ph",
      "name": "Another User",
      "email": "another@example.com",
      "initials": "AU"
    }
  ]
}
```
