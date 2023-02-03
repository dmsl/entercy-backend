

# VIDEO uploading - DONE but check large file sizes 
DIR/POI_ID/vid_NAME.EXT

# TODO DELETE: confirmation

# DATABASE admin tasks
## version history of data
in phpmyadmin on save trigger... copy on _table_LOG_ ..


# LARAVEL
## Login/Register: EMAILS FOR RESET/ACTIVATION...
(activation might be manually for mods)
Email verifications, forgot password, enable account:
it will need to send some emails.
you can send from any email address you like.
BUT server will verify the origin:
entercy@cs.ucy.ac.cy

see: emails at laravel.. how to send..
there are blade views for sending emails

see what it needs. might need a mail service out of laravel..

e.g. .env must be updated with stuff like:
MAIL_DRIVER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=null
MAIL_FROM_NAME="${APP_NAME}"


entercy@entercy:~/entercy-alpha/storage/app/public$ mv media ~/media-alpha
entercy@entercy:~/entercy-alpha/storage/app/public$ ln -s /home/entercy/media-alpha/ media



