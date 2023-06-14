<?php

class MVCCodeGenerator extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set("Africa/Nairobi");
    }

    /*
     * Main entry point to the system
     */

    public function index() {
        $loggedIn = $this->session->userdata('logged_in');
        if ($loggedIn !== NULL && $loggedIn['login_type'] === 'admin') {

            $data['content'] = 'index';

            if ($loggedIn['login_type'] === 'admin') {
                $this->load->view('templates/template', $data);
            } elseif ($loggedIn['login_type'] === 'frontoffice') {
                $this->load->view('templates/template_f', $data);
            }
        } else {
            redirect('modules/auth/login/adminloginPage');
        }
    }

    /*
     * Loads HTML View 'create' where you can type in the entity fields
     */

    public function create() {
        $loggedIn = $this->session->userdata('logged_in');
        if ($loggedIn !== NULL && $loggedIn['login_type'] === 'admin') {

            $data['content'] = 'create';

            if ($loggedIn['login_type'] === 'admin') {
                $this->load->view('templates/template', $data);
            } elseif ($loggedIn['login_type'] === 'frontoffice') {
                $this->load->view('templates/template_f', $data);
            }
        } else {
            redirect('modules/auth/login/adminloginPage');
        }
    }

    /*
     * Gets the entity fields and executes functions to create the respective MVC files
     */

    public function getFields() {
        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('component_name', 'component_name', 'required');
        $this->form_validation->set_rules('table_name', 'table_name', 'required');
        $this->form_validation->set_rules('record_id', 'record_id', 'required');
        $this->form_validation->set_rules('record_id_segment', 'record_id_segment', 'required');
        $this->form_validation->set_rules('fields', 'fields', 'required');
        $this->form_validation->set_rules('model_filename', 'model_filename', 'required');
        $this->form_validation->set_rules('view_filepath', 'view_filepath', 'required');
        $this->form_validation->set_rules('loggedin_variable', 'loggedin_variable', 'required');

        if ($this->form_validation->run()) {
            $component_name = $this->input->post('component_name');
            $table_name = $this->input->post('table_name');
            $record_id = $this->input->post('record_id');
            $record_id_segment = $this->input->post('record_id_segment');
            $fields = $this->input->post('fields');
            $model_filename = $this->input->post('model_filename');
            $view_filepath = $this->input->post('view_filepath');
            $loggedin_variable = $this->input->post('loggedin_variable');
            $deletion_status = "deletion_status";
            $record_deletion_status = "DELETED";

            $dollarsign = '$';
            $dateTimeNow = date("Y m d h i sa");
            $dir_name = "GeneratedCodeFiles/" . $component_name . "FILES " . $dateTimeNow;

            mkdir($dir_name);

            $explodedFields = explode(',', $fields);
            $noOfFields = count($explodedFields);

            //Create Files
            $this->createModelFile($component_name, $table_name, $record_id, $deletion_status, $explodedFields, $dir_name, $dollarsign);
            $this->createVIEWMainFile($explodedFields, $component_name, $dir_name);
            $this->createVIEWViewComponentFile($explodedFields, $component_name, $dir_name, $dollarsign);
            $this->createControllerFile($component_name, $model_filename, $view_filepath, $record_id, $record_id_segment, $record_deletion_status, $loggedin_variable, $explodedFields, $dir_name, $dollarsign);
            $this->createViewComponentControllerCode($explodedFields, $component_name, $model_filename, $view_filepath, $record_id, $record_id_segment, $loggedin_variable, $dir_name, $dollarsign);
            echo 'Successfully created the Code Files';
        } else {
            echo 'There was a problem creating the files, please try again later';
        }
    }

    /*
     * Creates the Model File Code
     */

    public function createModelFile($component_name, $table_name, $record_id, $deletion_status, $explodedFields, $dir_name, $dollarsign) {
        $phpOpenTag = "<? \n\n";
        $PHP2LINEBREAKS = "\n\n";

        $componentFields = "";

        foreach ($explodedFields as $field) {
            $componentFields = $componentFields . "'$field' =>" . $dollarsign . "this->input->post('$field')," . "\n";
        }

        //addComponent Function Creation
        $addComponentCodeSegment1 = "public function add" . $component_name . "() {\n" . $dollarsign . "data = array(" . "\n";
        $addComponentCodeSegment2 = ");\n" . $dollarsign . "this->db->insert('$table_name', " . $dollarsign . "data);
                        \nreturn " . $dollarsign . "this->db->insert_id();
                    }";
        $addComponentFunctionCode = $addComponentCodeSegment1 . $componentFields . $addComponentCodeSegment2;

        //editComponent Function Creation
        $editComponentSegment1 = "public function edit" . $component_name . "(" . $dollarsign . "$record_id" . ") {\n" . $dollarsign . "data = array(" . "\n";
        $editComponentSegment2 = ");
        " . $dollarsign . "this->db->where('$record_id'," . $dollarsign . "$record_id);
        " . $dollarsign . "this->db->update('$table_name'," . $dollarsign . "data);\n}";

        $editComponentFunctionCode = $editComponentSegment1 . $componentFields . $editComponentSegment2;

        //updateComponentDeletionStatus Function Creation
        $updateComponentDeletionStatusSegment1 = "public function update" . $component_name . "DELETIONStatus" . "(" . $dollarsign . "$record_id" . "," . $dollarsign . "$deletion_status" . ") {\n" . $dollarsign . "data = array(" . "\n";
        $updateComponentDeletionStatusSegment2 = "'deletion_status' =>" . $dollarsign . "$deletion_status,";
        $updateComponentDeletionStatusSegment3 = ");
        " . $dollarsign . "this->db->where('$record_id'," . $dollarsign . "$record_id);
        " . $dollarsign . "this->db->update('$table_name'," . $dollarsign . "data);
            \nreturn " . $dollarsign . "this->db->affected_rows();
            \n}";

        $updateComponentDeletionStatusFunctionCode = $updateComponentDeletionStatusSegment1 . $updateComponentDeletionStatusSegment2 . $updateComponentDeletionStatusSegment3;

        $fileInfo = $phpOpenTag .
                $addComponentFunctionCode .
                $PHP2LINEBREAKS .
                $editComponentFunctionCode .
                $PHP2LINEBREAKS .
                $updateComponentDeletionStatusFunctionCode;

        $file_path = $dir_name . "/" . $component_name . "MODEL.php";
        $myfile = fopen($file_path, "w") or die("Unable to open file!");
        fwrite($myfile, $fileInfo);
        fclose($myfile);
    }

    /*
     * Creates the Controller File Code
     */

    public function createControllerFile($component_name, $model_filename, $view_filepath, $record_id, $record_id_segment, $record_deletion_status, $loggedin_variable, $explodedFields, $dir_name, $dollarsign) {
        $phpOpenTag = "<? \n\n";
        $PHP2LINEBREAKS = "\n\n";

        $componentFields = "";

        foreach ($explodedFields as $field) {
            $componentFields = $componentFields . $dollarsign . "this->form_validation->set_rules('$field', '$field', 'required');\n";
        }

        $loginCheckInitial = $dollarsign . "loggedIn = " . $dollarsign . "this->session->userdata('$loggedin_variable');\n"
                . "if ($dollarsign" . "loggedIn !== NULL && $dollarsign" . "loggedIn['login_type'] === 'admin') {";

        $loginCheckFinal = " else {
                redirect('modules/auth/login/adminloginPage');
            }
        }";

        $viewComponentFunctionCode = $this->createViewComponentControllerCode($explodedFields, $component_name, $model_filename, $view_filepath, $record_id, $record_id_segment, $loggedin_variable, $dir_name, $dollarsign);
        //addComponent Function Creation
        $addComponentFunctionStart = "public function add" . $component_name . "() {\n";
        $addComponentCodeSegment1 = $dollarsign . "this->load->helper('form');\n" .
                $dollarsign . "this->load->library('form_validation');\n\n";
        $addComponentCodeSegment2 = "\nif (" . $dollarsign . "this->form_validation->run()) {\n" .
                $dollarsign . "record_createdby = 1;\n" .
                $dollarsign . "this->" . $model_filename . "->add" . $component_name . "(" . $dollarsign . "record_createdby);
                    echo '$component_name added successfully';
                } else {
                    echo 'There was a problem adding the $component_name, please try again later';
                }
            }";

        $addComponentFunctionCode = $addComponentFunctionStart . $loginCheckInitial . $addComponentCodeSegment1 . $componentFields . $addComponentCodeSegment2 . $loginCheckFinal;

        //editComponent Function Creation Code
        $editComponentFunctionStart = "public function edit" . $component_name . "Details() {\n";
        $editComponentCodeSegment1 = $dollarsign . "this->load->helper('form');\n" .
                $dollarsign . "this->load->library('form_validation');\n\n";
        $editComponentCodeSegment2 = "\nif (" . $dollarsign . "this->form_validation->run()) {\n" .
                $dollarsign . "$record_id = " . $dollarsign . "this->uri->segment($record_id_segment);\n" .
                $dollarsign . "this->" . $model_filename . "->edit" . $component_name . "(" . $dollarsign . "$record_id);
                echo '$component_name edited successfully';
            } else {
                echo 'There was a problem editing the $component_name, please try again later';
            }
        }";

        $editComponentFunctionCode = $editComponentFunctionStart . $loginCheckInitial . $editComponentCodeSegment1 . $componentFields . $editComponentCodeSegment2 . $loginCheckFinal;

        //deleteComponent Function Creation Code
        $deleteComponentFunctionStart = "public function update" . $component_name . "DeletionStatusDELETE() {\n";
        $deleteComponentCodeSegment1 = $dollarsign . "$record_id = " . $dollarsign . "this->uri->segment($record_id_segment);\n" .
                $dollarsign . "deletion_status = " . "'$record_deletion_status';\n" .
                $dollarsign . "affected_rows = " . $dollarsign . "this->" . $model_filename . "->update" . $component_name . "DELETIONStatus(" . $dollarsign . "$record_id, " . $dollarsign . "deletion_status" . " );\n" .
                "if (" . $dollarsign . "affected_rows > 0) {
                echo '$component_name removed successfully';
            } else {
                echo 'There was a problem removing the $component_name, please try again later';
            }
        }";

        $deleteComponentFunctionCode = $deleteComponentFunctionStart . $loginCheckInitial . $deleteComponentCodeSegment1 . $loginCheckFinal;

        $fileInfo = $phpOpenTag .
                $viewComponentFunctionCode .
                $PHP2LINEBREAKS .
                $addComponentFunctionCode .
                $PHP2LINEBREAKS .
                $editComponentFunctionCode .
                $PHP2LINEBREAKS .
                $deleteComponentFunctionCode;

        $file_path = $dir_name . "/" . $component_name . "CONTROLLER.php";
        $myfile = fopen($file_path, "w") or die("Unable to open file!");
        fwrite($myfile, $fileInfo);
        fclose($myfile);
    }

    /*
     * Creates the View File Code
     */

    public function createVIEWMainFile($explodedFields, $component_name, $dir_name) {
        $phpOpenTag = "<? \n\n";
        $PHP2LINEBREAKS = "\n\n";

        $componentFields = "";

        foreach ($explodedFields as $field) {
            $componentFields = $componentFields .
                    '<div class="form-group">
                        <label class="control-label col-sm-3" for="' . $field . '">' . $field . ':</label>
                        <div class="col-sm-9">
                            <input type="text" name="' . $field . '" class="form-control" id="' . $field . '" placeholder="' . $field . '" required="">
                        </div>
                    </div>' . "\n" . '';
        }
        $fileInfo = $componentFields;
        $file_path = $dir_name . "/" . $component_name . "VIEW_MAIN.php";
        $myfile = fopen($file_path, "w") or die("Unable to open file!");
        fwrite($myfile, $fileInfo);
        fclose($myfile);
    }

    /*
     * Creates View for Individual Entity Record
     */

    public function createVIEWViewComponentFile($explodedFields, $component_name, $dir_name, $dollarsign) {
        $phpOpenTag = "<? \n\n";
        $PHP2LINEBREAKS = "\n\n";

        $componentFields = "";

        foreach ($explodedFields as $field) {
            $componentFields = $componentFields .
                    '<div class="form-group">
                        <label class="control-label col-sm-3" for="' . $field . '">' . $field . ':</label>
                        <div class="col-sm-9">
                            <input type="text" name="' . $field . '" value="<?php echo ' . $dollarsign . $field . '
        ?>" class="form-control" id="' . $field . '" placeholder="' . $field . '" required="">
</div>
</div>' . "\n" . '';
        }
        $fileInfo = $componentFields;
        $file_path = $dir_name . "/" . $component_name . "ViewComponent.php";
        $myfile = fopen($file_path, "w") or die("Unable to open file!");
        fwrite($myfile, $fileInfo);
        fclose($myfile);
    }

    /*
     * Creates Controller Code for View Individual Record
     */

    public function createViewComponentControllerCode($explodedFields, $component_name, $model_filename, $view_filepath, $record_id, $record_id_segment, $loggedin_variable, $dir_name, $dollarsign) {
        $phpOpenTag = "<? \n\n";
        $PHP2LINEBREAKS = "\n\n";

        $componentFields = "";

        foreach ($explodedFields as $field) {
            $componentFields = $componentFields .
                    $dollarsign . "data['$field'] = " . $dollarsign . "$component_name->" . $field . ";\n"
            //$dollarsign . $field . " = " . $dollarsign . "$component_name->" . $field . ";\n"
            ;
        }

        $loginCheckInitial = $dollarsign . "loggedIn = " . $dollarsign . "this->session->userdata('$loggedin_variable');\n"
                . "if ($dollarsign" . "loggedIn !== NULL && $dollarsign" . "loggedIn['login_type'] === 'admin') {";

        $loginCheckFinal = " }else {
                redirect('modules/auth/login/adminloginPage');
            }
        }";

        $viewComponentFunctionStart = "public function view_" . $component_name . "() {\n";
        $viewComponentCodeSegment1 = $dollarsign . "$record_id = " . $dollarsign . "this->uri->segment($record_id_segment);\n" .
                $dollarsign . "all" . $component_name . " = " . $dollarsign . "this->$model_filename" . "->get" . $component_name . "WithId(" . $dollarsign . "$record_id);" .
                "foreach (" . $dollarsign . "all" . $component_name . " as " . $dollarsign . $component_name . ") {"
        ;
        $viewComponentCodeSegment2 = "}\n" . $dollarsign . "data['$record_id'] =" . $dollarsign . $record_id . ";\n" .
                $dollarsign . "data['all$component_name'] = " . $dollarsign . "all" . $component_name . ";\n" .
                $dollarsign . "data['content'] = '$view_filepath';" .
                "if (" . $dollarsign . "loggedIn['login_type'] === 'admin') {" .
                $dollarsign . "this->load->view('templates/template'," . $dollarsign . "data);" .
                "} elseif (" . $dollarsign . "loggedIn['login_type'] === 'frontoffice') {" .
                $dollarsign . "this->load->view('templates/template_f', " . $dollarsign . "data);
            }";

        $viewComponentFunctionCode = $viewComponentFunctionStart . $loginCheckInitial . $viewComponentCodeSegment1 . $componentFields . $viewComponentCodeSegment2 . $loginCheckFinal;

        $fileInfo = $viewComponentFunctionCode;
        $file_path = $dir_name . "/" . $component_name . "ViewComponentControllerCode.php";
        $myfile = fopen($file_path, "w") or die("Unable to open file!");
        fwrite($myfile, $fileInfo);
        fclose($myfile);

        return $viewComponentFunctionCode;
    }

    /*
     * Function to create a file
     */

    public function createFile() {
        $dateTimeNow = date("Y m d h i sa");
        $dir_name = "GeneratedCodeFiles/CLIENTFILES" . $dateTimeNow;
        mkdir($dir_name);
        $file_path = $dir_name . "/newFileToday.txt";
        $myfile = fopen($file_path, "w") or die("Unable to open file!");
        $txt = "text that is written";
        fwrite($myfile, $txt);
        fclose($myfile);
    }
}
