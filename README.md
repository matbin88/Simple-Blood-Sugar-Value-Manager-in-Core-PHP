
There will be two user types - admin and user.

User Flow:

    Users can register to the system.
    Once logged in, user have the following sections:
    A simple form to enter blood sugar record, text field that allows values between 0 - 10, decimals are possible
    A table below showing the already entered data by the current user (SL No, BS Level, Date & Time will be the columns)
    A table showing the prescriptions (files uploaded so far), simple text field to search by file name, and a button to add a file. Columns will be SL No, File Name, Description, File Size, Date. On clicking the button, a simple modal (bootstrap) form to open up with a file field and a description field, where users can upload the file and add some description.

Admin Flow:

    Admins can be created by other admins
    Once admin logs in, admin goes to a dashboard - page called “Admin Dashboard”, where he can get the following:
    A table of all users, with ability to filter by email
    A table showing all blood sugar entries, of all users. Same table as user, just with the addition of the email column.
    A table showing all prescription entries, of all users. Same table as user, just with the addition of the email column.
    Action button to create admin.

Default User

    Username - admin
    Password - admin1234

Sending Mail

Gmail SMTP is used for sending email. Gmail username and password needs to be set in send_mail() function inside functions/functions.php

        $mail->Username   = '';     //SMTP username
        $mail->Password   = '';     //SMTP password

Also “Allow Less Secure Apps ” should be turned on from your Gmail settings.
