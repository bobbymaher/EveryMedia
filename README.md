# How it works
you can login or register. once in you can upload files.
When you upload a file it gets stored temporarily on the server, then processed and uploaded to s3.

the media is put on a background queue to:
- upload the original file to S3
- get a thumbnail from a video using FFMPEG
- resize thumbnails and upload to S3

You can view the status of the jobs at http://localhost:8000/horizon/dashboard

After you upload the file, for a few seconds you will see "File is being processed" on
the main screen, after a few seconds it will process and you can use the file.



#to run

`docker-compose up` then wait.. it has to do a composer install and DB setup on first run

leave it for a bit and once composer container completes, exit and start back up again
 as the migrations container needs composer to be done. [@todo] make this flow more cleaner


#developing

- `npm install`
- changing css ? `npm run dev`


#credits / tools used
    - docker
    - php8
    - mysql8
    - redis
    - laravel for the backend framework
    - laravel horizon for the queue management
    - ffmpeg for getting video thumbnails
    - bootstrap
    - bugsnag for error moritoring
    - flysystem and aws-sdk for managing files on S3


    
```
    https://github.com/PHP-FFMpeg/PHP-FFMpeg
    - generating thumbnails from video files using ffmpeg
```

```
    https://sweetalert2.github.io/
   -  popups and toasts
```

#to do if there was more time
```
    - more media types e.g pdf, csv all common types
    - show artwork for mp3 files from ID3
    - better authentication - email verification, 2FA. currently its a simple login/register
    - user account removal - allow user to delete account and wipe all files including thumbnails
    - websockets to notify when file is processes and auto update the page. also could be used if logged in on 2 devices, new media will auto appear
    - share media with other users
    - use redis to store where you were when listening/watching media, and resume from that last location
    - add the ability to send media over chromecast
    - use more of FFMPEG abilities, e.g convert videos
    - upload multiple files at once, using chunks. currently if we upload a movie over 2GB it fails
    -- also no reload on upload, elements should be added from data by websockets or SPA where the audio does not stop playing
    - tests, integragration, unit, code style checkers e.t.c 
    - CICD..pipelines .e.t.c for deployment
    -- currently deployments are just a git push to ec2, and that triggers some docker commands with git hooks
    - media delivered over cloudfront instead of direct from S3
    - thumbnails could have been stored locally, but having it all on S3 makes it easier to share the project
    - would have preferred to do it in vue but was more familiar with bootstrap+jquery so that was faster
    - use tailwind for css
    - dark theme
    - better button layout for files, there are too many buttons for download, delete, edit. better to have a right click menu instead of all the different buttons for e
    - use cron jobs to "watch" a folder and auto import media
    - lazy load the page if there is a lot of content
    
    
```
