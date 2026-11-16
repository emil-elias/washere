# washere

A website displaying a collection of visited places pulled from a database on a map

## setup

1) Create a SQL database
2) Import the structure from `/db_structure/database.sql`
3) Put all of the other files on an Apache webserver or another webserver of your choice
4) Fill in the credentials for your database in `/api/connectdb.php`
5) Set a password for adding/editing places by setting the variable `$hashed_control_value` in `/api/connectdb.php`
6) You can add new places by using the form on `<your-server>/washere/add`
7) You can edit and delete places using the form on `<your-server>/washere/edit`
8) Countries need to be added manually in the database in the table `washere_countries`
9) If you want to display country outlines, you need to add a corresponding geojson file to the `countries` folder. The file needs to have the same name as the entry in your `washere_countries` table, omitting spaces and ignoring casing. A good source for country outlines: https://github.com/georgique/world-geojson
