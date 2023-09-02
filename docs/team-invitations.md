---
title: Team Invitations
---

# Team Invitations

Team invitations allow you to invite other people to join your team using their email address. They will need to create
an account on Blast if they don't already have one. Once they have accepted the invitation, they will be able to manage
the links within your team.

::: info
Only team owners have access to the team invitation endpoints.
:::

## The team invitation object

A team invitation is represented by the following JSON object:

```json
{
  "id": "01h8htjewrwmaxwvh954r46c0h",
  "email": "user@example.com",
  "created_at": "2023-08-23T18:30:08.000000Z",
  "updated_at": "2023-08-23T18:30:08.000000Z",
  "team": {
    "id": "01h8htjfdcg8v7yvphj1xaa8g6",
    "name": "Example Team",
    "personal_team": false,
    "created_at": "2023-08-23T18:30:08.000000Z",
    "updated_at": "2023-08-23T18:30:08.000000Z"
  }
}
```

### Attributes

| Name         | Type     | Description                                                    |
| ------------ | -------- | -------------------------------------------------------------- |
| `id`         | `string` | The unique identifier for the team invitation.                 |
| `email`      | `string` | The email address of the user that the invitation was sent to. |
| `created_at` | `string` | The date and time that the team invitation was created.        |
| `updated_at` | `string` | The date and time that the team invitation was last updated.   |
| `team`       | `object` | The team that the invitation is for.                           |

## Create a team invitation

To create a team invitation, you need to send a `POST` request to the `/teams/{team}/invitations` endpoint.

### Parameters

| Name    | Type     | Description                                            |
| ------- | -------- | ------------------------------------------------------ |
| `email` | `string` | The email address of the user that you want to invite. |

### Request

```bash
curl https://blst.to/api/v1/teams/{team}/invitations \
  -H "Authorization: Bearer {token}" \
  -d email="user@example.com"
```

### Response

```json
{
  "data": {
    "id": "01h8htjewrwmaxwvh954r46c0h",
    "email": "user@example.com",
    "created_at": "2023-08-23T18:30:08.000000Z",
    "updated_at": "2023-08-23T18:30:08.000000Z",
    "team": {
      "id": "01h8htjfdcg8v7yvphj1xaa8g6",
      "name": "Example Team",
      "personal_team": false,
      "created_at": "2023-08-23T18:30:08.000000Z",
      "updated_at": "2023-08-23T18:30:08.000000Z"
    }
  }
}
```

## Retrieve a team invitation

To retrieve a team invitation, you need to send a `GET` request to the `/teams/{team}/invitations/{invitation}`
endpoint.

### Request

```bash
curl https://blst.to/api/v1/teams/{team}/invitations/{invitation} \
  -H "Authorization: Bearer {token}"
```

### Response

```json
{
  "data": {
    "id": "01h8htjewrwmaxwvh954r46c0h",
    "email": "user@example.com",
    "created_at": "2023-08-23T18:30:08.000000Z",
    "updated_at": "2023-08-23T18:30:08.000000Z",
    "team": {
      "id": "01h8htjfdcg8v7yvphj1xaa8g6",
      "name": "Example Team",
      "personal_team": false,
      "created_at": "2023-08-23T18:30:08.000000Z",
      "updated_at": "2023-08-23T18:30:08.000000Z"
    }
  }
}
```

## Delete a team invitation

To delete a team invitation, you need to send a `DELETE` request to the `/teams/{team}/invitations/{invitation}`

### Request

```bash
curl -X DELETE https://blst.to/api/v1/teams/{team}/invitations/{invitation} \
  -H "Authorization: Bearer {token}"
```

### Response

```
204 No Content
```

## List all team invitations

To list all team invitations, you need to send a `GET` request to the `/teams/{team}/invitations` endpoint.

### Parameters

| Name    | Type     | Description                                                  |
| ------- | -------- | ------------------------------------------------------------ |
| `query` | `string` | (optional) A search query to filter the team invitations by. |

### Request

```bash
curl https://blst.to/api/v1/teams/{team}/invitations \
  -H "Authorization: Bearer {token}"
```

### Response

```json
{
  "data": [
    {
      "id": "01h8htjewrwmaxwvh954r46c0h",
      "email": "user@example.com",
      "created_at": "2023-08-23T18:30:08.000000Z",
      "updated_at": "2023-08-23T18:30:08.000000Z",
      "team": {
        "id": "01h8htjfdcg8v7yvphj1xaa8g6",
        "name": "Example Team",
        "personal_team": false,
        "created_at": "2023-08-23T18:30:08.000000Z",
        "updated_at": "2023-08-23T18:30:08.000000Z"
      }
    }
  ]
}
```
