---
title: Teams
---

# Teams

Teams are central to the way that we work. They serve two main purposes:

1. They organise links into groups that make sense to you.
2. They allow you to share links with other people.

All links must belong to a team, and you can create as many teams as you like. Everyone starts off with a team
called `Personal Team` which is private to you. You can rename this team, but you can't delete it or invite other people
to it.

You can invite people to other teams that you create, and you can be invited to other people's non-personal teams. When
you invite someone to a team, they have full access to manage the links within that team. They will not be able to
invite other members or delete the team.

## The team object

A team is represented by the following JSON object:

```json
{
  "id": "01h8htjfdcg8v7yvphj1xaa8g6",
  "name": "Example Team",
  "personal_team": false,
  "created_at": "2023-08-23T18:30:08.000000Z",
  "updated_at": "2023-08-23T18:30:08.000000Z"
}
```

### Attributes

| Name            | Type      | Description                                       |
| --------------- | --------- | ------------------------------------------------- |
| `id`            | `string`  | The unique identifier for the team.               |
| `name`          | `string`  | The name of the team.                             |
| `personal_team` | `boolean` | Whether or not the team is a personal team.       |
| `created_at`    | `string`  | The date and time that the team was created.      |
| `updated_at`    | `string`  | The date and time that the team was last updated. |

## Create a team

To create a team, you need to send a `POST` request to the `/teams` endpoint.

### Parameters

| Name   | Type     | Description           |
| ------ | -------- | --------------------- |
| `name` | `string` | The name of the team. |

### Request

```bash
curl https://blst.to/api/v1/teams \
  -H "Authorization: Bearer {token}" \
  -d name="Example Team"
```

### Response

```json
{
  "data": {
    "id": "01h8htjfdcg8v7yvphj1xaa8g6",
    "name": "Example Team",
    "personal_team": false,
    "created_at": "2023-08-23T18:30:08.000000Z",
    "updated_at": "2023-08-23T18:30:08.000000Z"
  }
}
```

## Retrieve a team

To retrieve a team, you need to send a `GET` request to the `/teams/{team}` endpoint.

### Request

```bash
curl https://blst.to/api/v1/teams/{team} \
  -H "Authorization: Bearer {token}"
```

### Response

```json
{
  "data": {
    "id": "01h8htjfdcg8v7yvphj1xaa8g6",
    "name": "Example Team",
    "personal_team": false,
    "created_at": "2023-08-23T18:30:08.000000Z",
    "updated_at": "2023-08-23T18:30:08.000000Z"
  }
}
```

## Update a team

To update a team, you need to send a `PUT` request to the `/teams/{team}` endpoint.

### Parameters

| Name   | Type     | Description           |
| ------ | -------- | --------------------- |
| `name` | `string` | The name of the team. |

### Request

```bash
curl -X PUT https://blst.to/api/v1/teams/{team} \
  -H "Authorization: Bearer {token}" \
  -d name="Example Team"
```

### Response

```json
{
  "data": {
    "id": "01h8htjfdcg8v7yvphj1xaa8g6",
    "name": "Example Team",
    "personal_team": false,
    "created_at": "2023-08-23T18:30:08.000000Z",
    "updated_at": "2023-08-23T18:30:08.000000Z"
  }
}
```

## Delete a team

To delete a team, you need to send a `DELETE` request to the `/teams/{team}` endpoint.

### Request

```bash
curl -X DELETE https://blst.to/api/v1/teams/{team} \
  -H "Authorization: Bearer {token}"
```

### Response

```
204 No content
```

## List all teams

To list all teams, you need to send a `GET` request to the `/teams` endpoint.

### Request

```bash
curl https://blst.to/api/v1/teams \
  -H "Authorization: Bearer {token}"
```

### Response

```json
{
  "data": [
    {
      "id": "01h8htjfdcg8v7yvphj1xaa8g6",
      "name": "Example Team",
      "personal_team": false,
      "created_at": "2023-08-23T18:30:08.000000Z",
      "updated_at": "2023-08-23T18:30:08.000000Z"
    },
    {
      "id": "01h8htjfth0ahfk1k1gpcw8qgq",
      "name": "Another Example Team",
      "personal_team": false,
      "created_at": "2023-08-23T18:30:08.000000Z",
      "updated_at": "2023-08-23T18:30:08.000000Z"
    }
  ]
}
```
