# Event Management

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

## 🚀 Installation

### Prerequisites
- PHP 8.1+
- Composer
- MySQL/MariaDB
- Git

### Setup Steps

```bash
# 1. Clone the repository
git clone https://github.com/alielkateb25/event-portal.git
cd event-portal

# 2. Install dependencies
composer install

# 3. Configure database
cp .env .env.local
# Edit .env.local with your MySQL credentials:
# DATABASE_URL="mysql://username:password@127.0.0.1:3306/event_portal_db"

# 4. Create database
php bin/console doctrine:database:create --if-not-exists

# 5. Run migrations
php bin/console doctrine:migrations:migrate

# 6. Clear cache
php bin/console cache:clear

# 7. Start the server
php bin/console server:run
```

### Review Permissions
| Action | Allowed For |
|--------|------------|
| View reviews | All users (public) |
| Submit review | Authenticated users who registered for the event AND event date has passed |
| Edit/Delete review | Only the review author |
| Delete any review | ROLE_ADMIN only |