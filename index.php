<?php include ("conn.php"); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Gallery App</title>

    <!-- Style CSS -->
    <link rel="stylesheet" href="style.css">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand ml-3" href="#">Image Gallery App</a>
        </div>
    </nav>

    <div class="main">
 
        <div class="image-container">
            <button type="button" class="btn btn-dark float-right add-image" data-toggle="modal" data-target="#addImageModal">&#43; Add Image</button>

            <button type="button" class="btn btn-secondary float-right cancel-delete" onclick="cancelDelete()" style="display: none;">Cancel</button>
            <button type="button" class="btn btn-danger float-right delete-image mr-1" style="display: none;">Delete Image/s</button>

            <!-- Add Image Modal -->
            <div class="modal fade mt-5" id="addImageModal" tabindex="-1" aria-labelledby="addImage" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Add Image</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="add-image.php" method="POST" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label for="folderName">Folder Name</label>
                                            <select class="form-control" name="tbl_image_folder_id" id="imgFolderID">
                                                <option value="0">All Image</option>
                                                <?php
                                                $stmt = $conn->prepare("SELECT * FROM tbl_image_folder");
                                                $stmt->execute();
                                                $folderResult = $stmt->fetchAll();

                                                foreach ($folderResult as $row) {
                                                    $folderID = $row["tbl_image_folder_id"];
                                                    $folderName = $row["folder_name"];
                                                    ?>
                                                    <option value="<?= $folderID ?>"><?= $folderName ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="imageName">Image/s</label>
                                            <input type="file" class="form-control-file" id="imageName" name="image_name">
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-dark">Add</button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>

            <div class="row mt-5">
                <div class="col-3">
                    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <button class="nav-link active" id="v-pills-all-images-tab" data-toggle="pill" data-target="#v-pills-all-images" type="button" role="tab" aria-controls="v-pills-all-images" aria-selected="true">All Image</button>

                        <?php 
                        
                            $stmt = $conn->prepare("SELECT * FROM tbl_image_folder");
                            $stmt->execute();

                            $result = $stmt->fetchAll();

                            foreach ($result as $row) {
                                $folderID = $row["tbl_image_folder_id"];
                                $folderName = $row["folder_name"];
                                
                                ?>

                                <button class="nav-link" id="v-pills-<?= $folderID ?>-tab" data-toggle="pill" data-target="#v-pills-<?= $folderID ?>" type="button" role="tab" aria-controls="v-pills-<?= $folderID ?>" aria-selected="true" oncontextmenu="showModal(event, <?= $folderID ?>)"><?= $folderName ?></button>

                                
                                <!--  Right clicked Modal -->
                                <div class="modal fade mt-5" id="folderModal-<?= $folderID ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Manage Folder</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="update-folder.php" method="POST">
                                                        <input type="text" class="form-control" name="tbl_image_folder_id" value="<?= $folderID ?>" hidden>
                                                    <div class="form-group">
                                                        <label>Folder Name</label>
                                                        <input type="text" class="form-control" name="folder_name" value="<?= $folderName ?>">
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-dark">Update Folder</button>
                                                        <button type="button" class="btn btn-danger" onclick="deleteFolder(<?= $folderID ?>)">Delete Folder</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <?php

                            }

                        ?>

                    </div>

                    <button id="addFolder" type="button" data-toggle="modal" data-target="#addFolderModal">&#43; Add Folder</button>

                    <!-- Add Folder Modal -->
                    <div class="modal fade mt-5" id="addFolderModal" tabindex="-1" aria-labelledby="addFolder" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Add Folder</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="add-folder.php" method="POST">
                                        <div class="form-group">
                                            <label for="folderName">Folder Name</label>
                                            <input type="text" class="form-control" id="folderName" name="folder_name">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-dark">Add</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="image-display col-9">
                    <div class="tab-content image-tab" id="v-pills-tabContent">
                        <div class="tab-pane fade show active images" id="v-pills-all-images" role="tabpanel" aria-labelledby="v-pills-all-images-tab">

                            <?php 
                            
                                $stmt = $conn->prepare("SELECT * FROM tbl_image");
                                $stmt->execute();

                                $result = $stmt->fetchAll();

                                foreach ($result as $row) { 
                                    $imageID = $row["tbl_image_id"];
                                    $image = $row["image_name"];
                                    ?>
                                    
                                    <div class="image-card">
                                        <input class="delete-check-box" type="checkbox" id="checkbox-<?= $imageID ?>" style="display: none;">
                                        <img src="https://github.com/Meniya-Rohit/gallery-img<?= $image ?>" alt="">
                                    </div>

                                    <?php
                                }
                            
                            ?>

                        </div>

                        <?php 
                            $stmt = $conn->prepare("SELECT * FROM tbl_image_folder");
                            $stmt->execute();

                            $result = $stmt->fetchAll();

                            foreach ($result as $row) {
                                $folderID = $row["tbl_image_folder_id"];
                                $folderName = $row["folder_name"];
                                                        
                                ?>

                                <div class="tab-pane fade show" id="v-pills-<?= $folderID ?>" role="tabpanel" aria-labelledby="v-pills-<?= $folderID ?>-tab">
                                                    
                                <?php 
                                                        
                                    $stmtImages = $conn->prepare("SELECT * FROM tbl_image WHERE tbl_image_folder_id = :folderID");
                                    $stmtImages->bindParam(':folderID', $folderID, PDO::PARAM_INT);
                                    $stmtImages->execute();

                                    $resultImages = $stmtImages->fetchAll();

                                    foreach ($resultImages as $row) {
                                        $image = $row["image_name"];
                                ?>
                                        <div class="image-card">
                                            <img src="https://github.com/Meniya-Rohit/gallery-img<?= $image ?>" alt="">
                                        </div>
                                <?php
                                    }
                                ?>
                                                    
                                </div>

                            <?php
                            }
                        ?>


                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>

    <!-- Script JS -->
    <script src="script.js"></script>


</body>
</html>