<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>User Profile</title>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#"><?php echo $user['name'] ?></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="#" data-toggle="modal" data-target="#userInfoModal">Profile</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" data-toggle="modal" data-target="#userpassword">Settings</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/logout">Logout</a>
            </li>
        </ul>
        <form class="form-inline my-2 my-lg-0">
            <input id="search" class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
            <button class="btn btn-outline-success my-2 my-sm-0" type="button" onclick="getSearch()">Search</button>
        </form>
    </div>
</nav>
<div class="container mt-4">
        <h1 class="text-center text-success">Welcome  Back <?php echo $user['name'] ?></h1>
    </div>

    <!-- Search Results Section -->
    <div class="container mt-4">
        <div id="searchResults" class="row"></div>
    </div>

    <!-- Modal to Show User Info -->
    <div class="modal fade" id="userInfoModal" tabindex="-1" role="dialog" aria-labelledby="userInfoModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userInfoModalLabel">User Information</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <img src="/img?img=<?php echo $user['image']; ?>" class="rounded-circle mb-3" alt="Profile Picture" width="100px" height="80px">
                    <p><strong>Name:</strong> <?php echo $user['name'] ?></p>
                    <p><strong>Email:</strong> <?php echo $user['email'] ?></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" data-toggle="modal" data-target="#userInfoModalupdate">Edit</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit User Info  -->

    <div class="modal fade" id="userInfoModalupdate" tabindex="-1" role="dialog" aria-labelledby="userInfoModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userInfoModalLabel">User Information Update</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                <form action="/user/update" method="post" enctype="multipart/form-data">
                            <?= csrf_field(); ?>
                            <input type="hidden" name="id" value="<?php echo $user['id'] ?>">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name" required value="<?php echo $user['name']; ?>">
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required value='<?php echo $user['email']; ?>'>
                            </div>
                            <div class="mb-3">
                                <label for="picture" class="form-label">Upload Picture</label>
                                <input type="file" class="form-control" id="picture" name="image" accept="image/*"  value="<?php echo $user['image']; ?>">
                                <img src="/img?img=<?php echo $user['image']; ?>" class="rounded-circle mb-3" alt="Profile Picture" width="100px" height="80px">
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Update</button>
                        </form>
                  
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


    <!-- password Update  -->
    <div class="modal fade" id="userpassword" tabindex="-1" role="dialog" aria-labelledby="userInfoModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userInfoModalLabel">User Information</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                <form action="/user/update/password" method="post" enctype="multipart/form-data">
                            <?= csrf_field(); ?>
                            <input type="hidden" name="id" value="<?php echo $user['id'] ?>">
                            <div class="mb-3">
                                <label for="password" class="form-label"> Old Password</label>
                                <input type="password" class="form-control" id="opassword" name="current_password" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label"> New Password</label>
                                <input type="password" class="form-control" id="password" name="new_password" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Conform Password</label>
                                <input type="password" class="form-control" id="cpassword" name="confirm_password" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Update</button>
                        </form>
                  
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- End  -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

    <script>
        function getSearch() {
            const query = document.getElementById("search").value;
            fetchPixabayImages(query);
        }

        function fetchPixabayImages(query) {
            const URL = "https://pixabay.com/api/?key=46663908-2d06466342773aaaa383cf592&q=" + query;

            // Using jQuery AJAX to fetch data
            $.ajax({
                url: URL,
                method: "GET",
                dataType: "json",
                success: function(data) {
                    if (parseInt(data.totalHits) > 0) {
                        displaySearchResults(data.hits);
                    } else {
                        $('#searchResults').html('<div class="col-12"><p>No Data found.</p></div>');
                    }
                },
                error: function() {
                    $('#searchResults').html('<div class="col-12"><p>An error occurred while fetching data.</p></div>');
                }
            });
        }

        function displaySearchResults(hits) {
            let resultsHtml = '';
            hits.forEach(hit => {
                resultsHtml += `
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <img src="${hit.webformatURL}" class="card-img-top" alt="${hit.tags}" width="400px" height="400px">
                            <div class="card-body">
                                <h5 class="card-title">${hit.tags}</h5>
                                <p class="card-text">Likes: ${hit.likes}</p>
                            </div>
                        </div>
                    </div>
                `;
            });
            $('#searchResults').html(resultsHtml);
        }
    </script>
</body>
</html>
