<?php
session_start();
include '../includes/db_connection.php';

$response = array('success' => false);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $title = $_POST['title'];
        $author = $_POST['author'];
        $category_id = $_POST['category_id'];
        $publication_year = $_POST['publication_year'];
        $available_copies = $_POST['available_copies'];
        $total_copies = $_POST['total_copies'];

        // Handle file upload
        $cover_image = null;
        if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['cover_image']['tmp_name'];
            $fileName = $_FILES['cover_image']['name'];
            $fileSize = $_FILES['cover_image']['size'];
            $fileType = $_FILES['cover_image']['type'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));
            
            // Check file extension and size
            $allowedExtensions = array('jpg', 'jpeg', 'png', 'gif');
            if (in_array($fileExtension, $allowedExtensions) && $fileSize < 5000000) {
                $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
                $uploadFileDir = '../assets/images/uploads/';
                $dest_path = $uploadFileDir . $newFileName;

                if (move_uploaded_file($fileTmpPath, $dest_path)) {
                    $cover_image = $newFileName;
                }
            }
        }

        // Insert book into database
        $query = "INSERT INTO books (title, author, category_id, publication_year, available_copies, total_copies, cover_image) 
                  VALUES (:title, :author, :category_id, :publication_year, :available_copies, :total_copies, :cover_image)";
        $stmt = $pdo->prepare($query);
        $stmt->execute(array(
            ':title' => $title,
            ':author' => $author,
            ':category_id' => $category_id,
            ':publication_year' => $publication_year,
            ':available_copies' => $available_copies,
            ':total_copies' => $total_copies,
            ':cover_image' => $cover_image
        ));

        $response['success'] = true;
    } catch (Exception $e) {
        $response['message'] = $e->getMessage();
    }
}

echo json_encode($response);
?>
