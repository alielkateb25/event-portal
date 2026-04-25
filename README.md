# event-portal

This Event Management web-app provides you with the means to create events, invite people to participate in the chosen event and gain insight on guest reviews.

## Team Roles
- **Chayma Ellejmi** Backend, Security, Entities
- **Farouk Hadj Mabrouk** Frontend, Twig, Styling
- **Ali El Kateb** API, Ajax, Bonus Features, Documentation

## Database schema

You will find the database structure in the link bellow :

[Database](./docs/database-schema.md)

## User Stories

You will find the user story details in the link bellow :

[User Stories](./docs/user-stories.md)

## API Endpoints

You will find the API endpoint details in the link bellow :

[API](./docs/api-endpoints.md)

### Review Permissions
| Action | Allowed For |
|--------|------------|
| View reviews | All users (public) |
| Submit review | Authenticated users who registered for the event AND event date has passed |
| Edit/Delete review | Only the review author |
| Delete any review | ROLE_ADMIN only |