# Comments

## 1. Routing logic

 Refactored the routes (aside from default console/channels and auth-demo as requested on comment)
 - to isolate the logic from the router config files, as this is not a good practice and over time, it will 
   become unmanageable.
 - For API routes, added a new directory. Within it will have a controller by each model/entity group 
   (guestbook routes group in this case). This makes easier to maintain the api routes as the api begins 
   to increase its size. This method is to keep something between "everything in one controller" (too much code
   in one file) and "controller per route" (large number of files), which makes it fast to navigate while 
   coding, without sacrificing the readability/debugging difficulty
 - For Web, could be the same strategy, simplified for this case

Another thing that could be done, is to have "api-<group>.config" file for the routes of the specific group of api routes

For tests:

API
- index - Assert if it returns HTTP STATUS 200; if it returns entries
- get - Assert if returns HTTP STATUS 200; if it returns the entry; if it returns HTTP STATUS 404 if entry not found
- my - Assert if returns HTTP STATUS 200; if it returns respective entries; if it returns HTTP STATUS 401 if user 
  not logged in; if returns empty when no entries found
- delete - Assert if it returns HTTP STATUS 200 when deleted; if the response content is "deleted"; if returns HTTP 
  STATUS 500 if something goes wrong with the delete
- sign - Assert if it returns HTTP STATUS 200; if the validation rules are working (ok and not ok cases); if it returns
  the newly created record; if it returns HTTP STATUS 500 if something goes wrong

Web
- index - Assert if it returns HTTP STATUS 200; if it returns the correct view (assertViewIs function) and with data
- submit (get) - Assert if it returns HTTP STATUS 200; if it returns the correct view (assertViewIs function);

## 2. Completing the form

 Finished implementing the form. This was done on the WebController and web routes because the result of the creation
 should be a view. Api should never return a view or manage redirects, but only deliver responses.

For tests:
- submit (post) - Same as sign + if it returns the correct view (assertViewIs function); if it returns message/errors 
  when successful / unsuccessful

## 3. Separating the submitter information from the `GuestbookEntry`

Extracted submitter information from the entries model to a new model "Submitters". These have relationship, one to 
many (one submitter can have many guest entries). This enable the search for the submitters entries and the entry 
submitter. The "submitter_id" of the entry model is the "id" (primary key with index) of the submitters model

When adding a new entry, the code is checking if the submitter exists. If not, it creates a new one and therefore, 
creates a new entry with the submitter.

For tests;
- Assert the creation of a new entry with an existent submitter; if the creation of a new entry with a new submitter; 
  check if the submitter info and relationship are correct

## 4. Update an entry

Important information:
- For this task, Submitter is the Logged user, so it can be done the authorization on the request. Could be an Policy,
  but i simplified for this task

Created new api endpoint to update the entries, which only the logged in user (which was its creator).

For tests:
- Assert if the data has updated and returns HTTP status code 200; empty request should throw an HTTP status code 400; 
  should return HTTP 401 if the logged user is not the owner of the entry

## 5. Generate an hourly report

Created a command "generate:guestbook_report" to generate reports hourly.

## 6. React to an entry being deleted

Created event/listener combo, triggered when an entry is deleted on GuestbookEntryService. Instead of having the code 
on the listener, it just calls methods from the service, making this more testable. If there is need to add some task, 
the only thing needed is to add the method call on the listener file.

## Information
- Could have used Resources/Responses, however did not implement to maintain this challenge simpler to review
- Some important tests were created. In the comments of the respective task, it was mentioned what should be tested.
- Added 2 small fixes to the readme file regarding the setup instructions
