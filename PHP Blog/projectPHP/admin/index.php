<?php

require_once('../includes/config.php');

if(!$user->is_logged_in()){ header('Location: login.php'); }


if(isset($_GET['delpost'])){

    $stmt = $db->prepare('DELETE FROM blog_posts WHERE postID = :postID') ;
    $stmt->execute(array(':postID' => $_GET['delpost']));
   

    header('Location: index.php?action=deleted');
    exit;
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Admin</title>
    <script language="JavaScript" type="text/javascript">
        function delpost(id, title)
        {
            if (confirm("Are you sure you want to delete '" + title + "'"))
            {
                window.location.href = 'index.php?delpost=' + id;
            }
        }
    </script>
</head>
<body>
    <?php include('menu.php');?>

    <?php

    if(isset($_GET['action'])){
        echo '<h3>Post '.$_GET['action'].'.</h3>';
    }
    ?>
    <div class="panel-body-default container">
        <div class="panel panel-default">
            <div class="panel-body">
                <table class="table table-striped table-hover">
                    <tr class="active">
                        <th>Title</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
        <?php
        try {

            $stmt = $db->query('SELECT postID, postTitle, postDate FROM blog_posts ORDER BY postID DESC');
            while($row = $stmt->fetch()){

                echo '<tr>';
                echo '<td>'.$row['postTitle'].'</td>';
                echo '<td>'.date('jS M Y', strtotime($row['postDate'])).'</td>';
                ?>

                <td>
                    <a href="edit-post.php?id=<?php echo $row['postID'];?>">Edit</a> |
                    <a href="javascript:delpost('<?php echo $row['postID'];?>','<?php echo $row['postTitle'];?>')">Delete</a>
                </td>

                <?php
                echo '</tr>';

            }

        } catch(PDOException $e) {
            echo $e->getMessage();
        }
        ?>
        </table>

        <a class="btn btn-primary" href='add-post.php'>Add Post</a>

            </div>
        </div>
    </div>
</body>
</html>
