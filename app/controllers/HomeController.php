<?php
require_once __DIR__ . '/../models/CommonModel.php';
class HomeController extends Controller {
    protected $CommonModel;
    public function __construct() {
        $this->CommonModel = new CommonModel();
    }
    public function index() {
        $users = $this->CommonModel->selectFromTable('users');
        $this->view('home/index', ['users' => $users]);
    }
    public function about()
    {
        echo "about";
    }
     public function store() {
        $validation = new Validation();
        $rules = [
            'name' => 'required|min:3|max:50',
            'email' => 'required|email',
            'message' => 'required|min:6'
        ];

        $data = [
            'name' => $_POST['name'],
            'email' => $_POST['email'],
            'message' => $_POST['message']
        ];

        if ($validation->validate($data, $rules)) {
            // Data is valid, proceed with storing user
            echo 'User created successfully!';
        } else {
            // Data is not valid, show errors
            $errors = $validation->errors();
            foreach ($errors as $field => $messages) {
                foreach ($messages as $message) {
                    echo "<p style='color:red'>$message</p>";
                }
            }
        }
    }
}
