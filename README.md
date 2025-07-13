# CitiRoad

CitiRoad is a transportation infrastructure integrity citizen engagement for Cambodia that operates on a provincial level. It provides an accessible and convenient method for citizens to report on infrastructure issues anywhere and track the progress.

## Components

### Frontend

- Landing pages to inform citizens about the platform and provide guidance
- A dashboard for citizens to create new reports and track their previous reports
- A dashboard for officers to view and manage reports in their province

> Responsibility of Puthiroth Kong (@kiminato-roto)

### Backend

Dashboard for admins to manage all citizen accounts, officer accounts, and reports. Provide search and filter functionalities for an easier management experience.

> Responsibility of Heang Piv Phour (@piru168)

### API

Provides all platform functionalities as API endpoints with JSON responses.

> Responsibility of Visoth Kim (@vistis)

## Platform Logic

### Citizens

- Citizen accounts go through admin review when signing up. Only once an account has the "Approved" status can it be used to make a report. This process is to ensure that the citizen signing up exists and be legally associatable. Citizens must ensure the information they provide for the sign up process are correct such as their legal name and National ID
- Only citizens can make reports and they are able report on issues in any province; not limited to their province of residence
- Citizens can view and track their own previous reports. They are informed of the status, remark, and proof images of resolved reports 
-  Report information cannot be altered after being posted by any entity; a citizen must ensure that the provided information are accurate; a citizen cannot delete their own reports
- Citizens cannot view information of other citizens or read reports not of their own
- Citizens cannot view officers or admins
- They can update some of their own account information
- Citizens can delete their own account. Their reports will be moved to a "ghost"/"shell" account
- Sign in with email or phone number

### Officers

- Officer accounts are issued and managed by admins. They are assigned a role and designated province
- There are two roles. Municipality Deputy can update the status of a report to the next stage or reject them at any stage, but cannot mark as resolved or reopen rejected or resolved reports. Municipality Head can update the status like a deputy and mark a report as resolved as well as reopen reports
- Report starts at "Reviewing" stage, implying an officer is reviewing the post itself. Next is "Investigating" which implies that the municipality is investigating the issue to confirm. Then "Resolving" - issue is being fixed. Finally "Resolved". A report can be "Rejected" at any stages before "Resolved", except for Municipality Deputy who cannot reject report after it is in "Resolving" status
- To proceed with a report means to push it to the next status stage. It is applicable if the current status is "Reviewing" or "Investigating"
- Officers can only view and update reports (not changing information; update status only along with giving remarks and proof images) in the province their account is assigned to, regardless of roles
- Officers must give remarks when they update the report status (not implemented correctly in frontend due to oversight by the contributor, but is present in API)
- When marking a report as resolved, the officer must provide images as proof of resolution. The report will be updated to show the before and after images for all entities that can view it
- Officers cannot delete reports 
- Officers can view the information of the citizen associated with a report
- Officers can view the information of other officers in their province, but cannot manage. The report also display the officer that last updated it (if updated from frontend, it does not track; another oversight)
- Officers are not able to update their own account (fully managed by admins)
- Officers can utilize the bookmark report functionality, however it is not implemented in the frontend
- Sign in with governments/badge ID (frontend wrongly uses email instead; login at route /officer/login)

### Admins

- Can view all reports, citizens, officers, and admins
- View which officers last updated a report
- Cannot update reports; only delete
- Review citizen sign up requests
- Moderate citizens accounts by restricting them if they provide false information or spam. Accounts can also be deleted but cannot be updated by an admin
- If a citizen account is deleted, their reports are not automatically deleted. Instead the reports are moved to the owner ship of a "ghost"/"shell" account for archival purposes
- Issue accounts for officers. Accounts can be updated or deleted
- View other admins on platform (read-only; cannot update or delete)
- Can update their own account
- Admins can use bookmarks to "save" reports they are interested in
- Sign in with ID

## Instructions

### Required Dependencies

- PHP
- Node.js/npm
- Composer
- An SQL database

### Setup

1. Clone each of the branches into separate folders
    1. API: `git clone -b api git@github.com:vistis/CitiRoad <folder>`
    2. Frontend: `git clone -b frontend git@github.com:vistis/CitiRoad <folder>`
    3. Backend: `git clone -b backend git@github.com:vistis/CitiRoad <folder>`

2. In each folder:
    1. Run `composer install`
    2. Copy duplicate `.env.example` as `.env`. Open `.env` and edit the database (DB) section to match your local configuration
    3. Run `php artisan key:generate`
    4. Run `php artisan storage:link`

3. In the frontend and backend folder, run `npm install`
4. Replace `storage/app/public` of frontend and backend with a symbolic link that point to the same directory in API instead (important for images to work across the different components)
5. In API, run `php artisan migrate:fresh --seed` to build the tables in the database and create dummy data for testing

### Run

- API: `php artisan serve`
- Frontend and Backend: `composer run dev`

## Credentials for Testing

> All account has the same password which is `password`

### Citizens

| Email | Phone Number | Status |
| - | - | - |
| rung@proton.me | 018392648 | Approved |
| kpak@icloud.com | 072891948 | Pending |
| kty@gmail.com | 027481936 | Approved |
| hly@yahoo.com | 097517492 | Restricted |
| bsomang@hotmail.com | 023749172 | Rejected |

### Officers

| ID | Email | Role | Province |
| - | - | - | - |
| 1 | lkim@phnompenh.gov.kh | Municipality Head | Phnom Penh |
| 2 | lpeng@phnompenh.gov.kh | Municipality Deputy | Phnom Penh |
| 3 | theng@phnompenh.gov.kh | Municipality Deputy | Phnom Penh |
| 4 | pleang@kampongspeu.gov.kh | Municipality Head | Kampong Speu |
| 5 | tyong@kandal.gov.kh | Municipality Head | Kandal |

### Admins

Valid IDs: 1, 2, 3

## Developer Notices

- In the web UI, using the back button on the page can look like a loop since the button leads back to whatever the last URL was in the browser history of the site. Using this button also does not refresh data, so a manual refresh is required to see updates
- Frontend and backend uses session based authentication. API uses token based authentication. API is also stateful and can be used to provide session based authentication using CORS if needed
