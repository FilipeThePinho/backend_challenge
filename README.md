## Setup

-   You will need `docker` and `npm`/`node` installed on your machine.

-   Spin up with Laravel sail

    ```bash
    ./vendor/bin/sail up -d
    ```

-   You should then run the demo seeder:

    ```bash
    ./vendor/bin/sail artisan migrate:refresh
    ./vendor/bin/sail artisan db:seed
    ```

## The application

This is a basic guestbook application.

There is a stateful API layer (uses cookies & Laravel sessions) and some
basic views.

Overall, the app is incomplete and employs some very questionable design
decisions.

**You should not refer to any of the current patterns as best practice,
and instead use your own knowledge & experience to improve the app, bringing
it to a high standard.**

### Useful information

```bash
# this requires `httpie` - https://httpie.io/
http get  localhost/api/guestbook

http post localhost/api/guestbook/sign \
    title="Wowsers..." content="This is amazing" name="Gatesy" real_name="Gill Bates" email="retired@msft.com"

http get localhost/api/guestbook/some-id

http delete localhost/api/guestbook/some-id

# As user-a
http --session=user-a post localhost/auth/login \
    email="user-a@example.com" password="user-a"

http --session=user-a post localhost/api/guestbook/sign \
    title="Foo" content="123" name="user-a" real_name="User Alpha" email="user-a@example.com"

http --session=user-a get  localhost/api/guestbook/my

# As user-b
http --session=user-b post localhost/auth/login g \
    email="user-b@example.com" password="user-b"

http --session=user-a post localhost/api/guestbook/sign \
    title="Foo" content="123" name="user-b" real_name="User Bravo" email="user-b@example.com"

http --session=user-b get  localhost/api/guestbook/my
```

## Tasks

1.  **Routing logic**

    Currently, the logic for each route is colocated with the route definition.

    If you think it needs refactoring, please do so and and explain what you did
    and the reason why in `COMMENTS.md`.

2.  **Completing the form**

    The form at `/submit` currently isn't wired up to submit entries.

    This needs completing, taking mind of best practices in this area.

    To run the frontend, you can use  `./vendor/bin/sail npm install` and then `./vendor/bin/sail npm run dev`.

    _Please add notes around your decisions into `COMMENTS.md`_

3.  **Separating the submitter information from the `GuestbookEntry`**

    Currently, each `GuestbookEntry` contains information about the submitter.

    We want to split this information into its own model, so that we can manage
    this model independently.

    You will need to do the following:

    -   Separate the `submitter_*` fields from the `GuestbookEntry` into their
        own model.

    -   Make it possible to look up the associated `GuestbookEntry`s from your
        new model, and look up your new model from the `GuestbookEntry`.

4.  **Update an entry**

    We require the ability to update a `GuestbookEntry` via. an API route, but
    only the user who submitted the entry should be able to do this.

    Check out `routes/auth-demo.php` for the auth API routes available, and the
    credentials are available in `database/seeders/DemoSeeder.php`.

    **Don't worry about the fact that there is no auth in the form - Just
    complete the task as if every entry is created by an authenticated user**

    _Please add notes around your decisions into `COMMENTS.md`_

5.  **Generate an hourly report**

    Every hour we want to we want to generate a report of all the entries that
    have been created.

    We are going to pretend that the report takes 60 seconds to generate and
    sometimes times out, but we want to ensure that the report always gets generated.

    To complete the task, please generate a JSON list of all current `GuestbookEntry`s
    and write them to the log in resillient, asynchronous manner.
 
    _Please add notes around your decisions into `COMMENTS.md`_

7.  **React to an entry being deleted**

    When somebody deletes a `GuestbookEntry`, we want to be able to run any
    tasks associated to the deletion & cleanup.

    There are 3 example tasks created in `GuestbookEntryDeletionService`:

    -   `notifyUserOfDeletion`
    -   `generateNewReport`
    -   `performCleanupTasks`

    These need to be called upon deletion of an entry, but we also want to be
    able to easily change these and add more tasks later.
 
    _Please add notes around your decisions into `COMMENTS.md`_
