- Login to Moodle from admin user.
- Install the plugin and update the correct values to the plugin setting page appears while installing (If you forget to fill the settings page please navigate to Site administration -> Plugin -> Plugin Overview and search for the backupscheduler plugin and click on the setting)
- After filling the settings form, save it. 
- Now navigate to Site administration -> Server -> Scheduled tasks and search for "Task backup schedule".
- Click on the Gear Icon of Task backup schedule and set the cron accordingly. (If you do not change the time, the backup file will be created every minute)

NOTE: After completing the setup if your backup folder is not created (at the same location that you mentioned in settings page) please create it manually and give write permission to the folder.
