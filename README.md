1. Importing CSV file into DB
2. Processing the data with help of Queues and Jobs.
3. Automating the Job with Supervisor.
   [program:import-csv-worker]
   process_name=%(program_name)s_%(process_num)02d
   command=php /var/www/html/ManagementCompany/artisan queue:work --sleep=3 --tries=3 --max-time=3600
   autostart=true
   autorestart=true
   ;user=your-user
   numprocs=1
   redirect_stderr=true
   stdout_logfile=/var/log/import-csv-worker.log
   stopwaitsecs=3600
3.a
    Execute the following commands for run updated Supervisor
   sudo supervisorctl reread

    sudo supervisorctl update

    sudo supervisorctl start "import-csv-worked:*"
// Re-do test for types, phpstan, lint...
