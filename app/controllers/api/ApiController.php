<?php

class ApiController {
    public function getAllUsers() {
        // Your logic to retrieve all users
        echo json_encode(['users' => ['AAA', 'BBB']]);
    }

    public function getUserById($id) {
        // Your logic to retrieve a single user by $id
        echo json_encode(['user' => ['id' => $id, 'name' => 'Sample User']]);
    }
}
