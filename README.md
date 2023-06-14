# CodeIgniter-MVC-Code-Generator
This project enables you to automatically generate the Model-View-Controller code files for any database entity you're writing code logic for. It auto-generates boiler-plate code for CRUD operations with respect to Codeigniters MVC architecture and ActiveRecord.
You can really save a lot of time for your projects.

# User Guide
Step 1: Copy the MVCCodeGenerator.php file in your controllers  
Step 2: Copy the generate_code_form.php file in your views  
Step 3: Fill out the form fields to generate boiler-plate code  

# Form Fields Description
(i). component_name - refers to the entity you're generating code for, e.g., Employee, User
(ii). table_name - refers to the database table that will be linked to the Model
(iii). record_id - refers to the name of the Primary Key field in your table
(iv). record_id_segment - refers to the URL segment for viewing records, e.g., http://yourproject/index.php/eventscontroller/events/1 would have record_id_segment as 3 as per CodeIgniters guide on URL segments. This depends on the directory/url structure of your project.
(v). fields - comma-separated field names that reference the data a user will enter to submit info to the database
(vi). model_filename - refers to your preferred name for your Model file
(vii).view_filepath - refers to the path to your view file for referencing by the Controller
(viii). loggedin_variable - refers to the variable holding login information in case of a system that requires users to log in

Edit as can fit your needs
