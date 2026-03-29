# User Stories
## Guest :
As a Guest, I want to :
+ view the homepage, so that I can understand what the website is about.
+ view a list of public events, so that I can see what is happening.
+ view event details (date, location, description), so that I can decide if I want to attend.
+ register for an account, so that I can gain access to user features.
+ login to my account, so that I can access my personal dashboard.
+ be redirected to login when clicking "Participate", so that I know I need an account.
+ search for events by name, so that I can find specific topics quickly.
## User :
As a Guest, I want to :
+ create a new event, so that others can see it.
+ participate in an event, so that I am registered as attending.
+ view my dashboard, so that I see events I created or joined, the participants' reviews.
+ leave a rating (1-5) and comment so others can gauge quality.
+ edit or delete only my own reviews.

+ edit my own events, so that I can fix mistakes.
+ delete my own events, so that I can remove cancelled plans.
+ logout, so that I can secure my account on shared devices. -> Clears session, returns to homepage.
## Admin :
As an Admin, I want to :
+ access a dedicated admin dashboard, so that I can manage the system.
+ delete any event (even those created by others), so that I can remove inappropriate content.
+ edit any event, so that I can fix errors made by users.
+ remove inappropriate reviews.
+ ban or delete users, so that I can stop spam or abusive accounts.
+ view a list of all registered users, so that I can monitor community growth.
## Feature Priority :
### Must: 
- User Registration & Login & Logout -> (Security is required for creating events).
- Create Event -> The core purpose of the app.
- View Event List -> Users need to see what exists.
- View Event Details -> Users need to see info about a specific event.
- Delete Own Event -> Users need to manage their own content.
- Create a new event (User)
- Participate in an event (User)
- Delete their own events (User)
### Should :
- Edit Own Event -> If missing, users can delete and recreate, which is annoying but workable.
- Search Events -> Users can scroll through the list instead.
- Profile Page -> Users can function without seeing their own profile page.
- Logout Functionality -> Critical for security, but technically browsers handle session expiry.
- View their dashboard (User)
- Edit their own events (User)
### Could :
- Admin Dashboard -> Only needed if there is inappropriate content.
- Upload Event Images -> Text-only events work fine.
- Filter by Category -> e.g., Sports, Music.
- Contact Form -> Users can email directly instead.
### Won't Have (This Time) :
- Payment Integration -> Too complex for now.
- Email Verification -> Login works without it.
- Social Login (Google/Facebook) -> Standard login is enough.
- Mobile App Version -> This is a web project.