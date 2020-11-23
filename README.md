

# PHP Developer Assignment

## What is it?

A Leave Management System that allows a company's employees to request a leave of absence from work. 

Employees that are logged in can make requests by filling out a form with their absence dates along with their reasoning for making the request. Once a request has been made, the system's administrator is notified of it via email. 

A logged in administrator can either approve or reject an employee's application by clicking on the appropriate link in the email that he/she received. When a link is clicked, an email is sent to the employee notifying him/her of the application's outcome. 

## What technologies were used?

- PHP 7.0
- Docker (for running the database)
- HTML
- CSS
- JavaScript
- MySQL
- Composer (for managing PHP plug-ins)



## How can I run it?

### Prerequisites

In order to run the applications, you must have the following installed : 

- Docker (https://docs.docker.com/get-docker/)
- PHP (https://www.php.net/manual/en/install.php)
- Composer (https://getcomposer.org/download/)



Also, you need to sign in to [mailtrap.io](https://mailtrap.io/) in order to view the emails that are being sent & received. To do that, you can use the following account I have created: 

- Email: email.php.assignment@gmail.com

- Password: phpemail1234

  

### Running the application

You can run the application by executing the bash script named **run.sh**. To do that simply type the following on the command line :

`./run.sh`

## How can I stop the execution?

 In order to stop the app's execution, follow these instructions :

1. Press Ctrl-C in the terminal window that's running PHP, to stop PHP from running
2. Type `docker-compose down` to stop the database from running.



## What does it look like?

### Home Page

When a user (admin or employee) visits the portal's homepage (*here, by using localhost:8080 as the URL since the application is running locally*), a login form is displayed prompting him/her to fill in his/her credentials in order to sign in.

(Note: All pre-existing users have <first_name>.<last_name>@mailtrap.io as their email address and "1234" as their password. In the following examples for the employee I will be using annie.edison@mailtrap.io with 1234 and for the admin craig.pelton@mailtrap.io with 1234)

##### Image 1: The home page

![Home Page](https://github.com/frinipanteliadi/Epignosis-Assessment/blob/master/screenshots/1%20Login.png)

The following are checked when a user attempts to login :

- There's an account with the provided email
- The provided password matches the one that's linked with the email that was provided



### Employees

#### List of Applications

Once a user is logged in, he/she is met with a list of all the applications he/she has submitted so far. If there aren't any applications, a message is displayed instead.

##### Image 2: An employee's list of applications

![List of Applications](https://github.com/frinipanteliadi/Epignosis-Assessment/blob/master/screenshots/2%20Home%20Page%20New.png)

------

##### Image 3: An employee's empty list of applications

![No Applications](https://github.com/frinipanteliadi/Epignosis-Assessment/blob/master/screenshots/3%20Home%20Page.png)



### Submission Form

If a user clicks on either the **Submit Request** button, located on top of the list of applications, or the **Submit New Application** option of the menu, located on the left side of the page, he/she is redirected to the request for a leave of absence submission form. There, he/she <u>must fill out all of the fields</u> (from-to dates & reason) and click on the **Submit** button in order for the form to be submitted. If at least one field has been left empty or hasn't been correctly filled, an error message is displayed. If everything's okay, the employee is redirected to his/her list of applications, which now includes the one that was just submitted.

The following are checked when an employee attempts to request a leave of absence :

- The start date is greater than the current date
- The start date is less than the end date
- The end date is greater than the current date
- The end date is greater than the start date
- There aren't any existing approved/pending applications that include the provided dates

##### Image 4: An employee's leave of absence request form

![Submission Form](https://github.com/frinipanteliadi/Epignosis-Assessment/blob/master/screenshots/4%20Request.png)

------

##### Image 5: An employee's list of applications after submitting one

![List of Applications (New)](https://github.com/frinipanteliadi/Epignosis-Assessment/blob/master/screenshots/5%20Home%20Page.png)

### Admin's Email Notification

When an employee submits a leave of absence request form, an email is sent to the administrator notifying him/her of the employee's request. 

A <u>logged in</u> administrator can either approve or reject an application by clicking on either of the links that are provided in the email.

##### Image 6: Email notifying the admin of an employee's request

![Admin's Notification Email](https://github.com/frinipanteliadi/Epignosis-Assessment/blob/master/screenshots/6%20Mail.png)   

------

##### Image 7: Approval message

![The application has been approved](https://github.com/frinipanteliadi/Epignosis-Assessment/blob/master/screenshots/7%20Approve.png)

------

##### Image 8: Application has already been approved/rejected message

![The application has already been approved](https://github.com/frinipanteliadi/Epignosis-Assessment/blob/master/screenshots/9%20Already%20Set.png)

The following are checked when an admin attempts to approve/reject an application :

- The administrator is logged in when trying to click on one of the links
- The request hasn't already been approved/rejected by an administrator

### Employee's Email Notification

When an administrator either approves or rejects an employee's application, the employee is notified via email of its outcome. 

The employee's list of applications will now display the application's new status (either approved or rejected).

##### Image 9: Employee's Email Notification

![Employee's Notification Email](https://github.com/frinipanteliadi/Epignosis-Assessment/blob/master/screenshots/11%20Mail.png)

------

##### Image 10: An employee's list of applications after an admin has approved/rejected on

![List of Applications (New)](https://github.com/frinipanteliadi/Epignosis-Assessment/blob/master/screenshots/12%20Home%20Page%20New.png)

### Administrators

#### List of Users

When an administrator is logged in he/she is met with a list of the system's existing users. From there he/she can do the following :

- Edit a user's properties by clicking on his/her name
- Create a new user by clicking either on the **Create a User** button that's located on top of of the list or on the **Create a User** option of the menu located on the left

##### Image 11: Admin's Welcome Page: List of Existing Users

![List of existing users](https://github.com/frinipanteliadi/Epignosis-Assessment/blob/master/screenshots/8%20Home%20Page%20.png)

#### Editing a user's properties

An administrator can edit a user's properties. Specifically, he/she can change the user's :

- First name
- Last name
- Email address 
- Type (Employee / Admin)
- Profile photo 

The following are checked when an admin attempts to update an employee's properties :

- See if any changes were made
- If the password was changed then
  - Both the Password and Confirm Password field must be filled
  - Values of Password and Confirm Password must be the same
- If the email was changed then check if the address is already being used by another user
- If the user's type is changed from employee to admin, delete the user's existing list of applications

##### Image 12: User Properties

![User Properties](https://github.com/frinipanteliadi/Epignosis-Assessment/blob/master/screenshots/10%20User%20Properties.png)

### Both Types of Users

#### Log Out

A logged in user (administrator / employee) can log out of the system by clicking on the **Log Out** option on the top right corner. Once clicked, the user is redirected to the home page of the application.
