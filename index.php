<?php
use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Blob\Models\ListBlobsOptions;
//lets begin with 1000000000000% protection
session_start();
function isLoggedin(){
    if (ISSET($_SESSION['asdlwgqogwegqlejgqienglwras.ndknawbklGIJlvbwRLGREWLBRIJL3BWRILV Lv iv weale vwl....'])) {
        return true;
    } else {
        return false;
    }
}
if(isLoggedin()) {
    ?>
    <h3>Azure blob storage........</h3><br>
    ...... dunno how to use css<br><br>
    <table>
    <tr>
    <td><button onclick="window.location.href = 'logout.php';">logout</button></td>
                    <td> <button onclick="window.location.href = '#upload';">upload</button></td>
</tr>
</table>
    <br><br>
    <h3>List Files :</h3>
    <table border="1">
    <tr>
        <th>no</th>
        <th>filename</th>
        <th>extension</th>
        <th>md5</th>
        <th>time uploaded <br>(gmt+7)</th>
        <th colspan="3">Action</th>
    </tr>
    <?php
    require_once 'vendor/autoload.php';
    $conn_string = "DefaultEndpointsProtocol=https;AccountName=testwebkyblob;AccountKey=V9YWA0gFZy+vbSs60jHKdO9PrcG0UHVuCDTqIBNADO7n3TsXj+5ji+4AQU8zN1jOFrAX1bdxB9vTRMR2JTWiUg==;EndpointSuffix=core.windows.net";
    $container = "testwebkyblobcontainer";
    //blob things..
    $blobClient = BlobRestProxy::createBlobService($conn_string);
    $listBlobsOptions = new ListBlobsOptions();
    $listBlobsOptions->setPrefix("");
    $res = $blobClient->listBlobs($container, $listBlobsOptions);
    $foo = 0;
    do {
        foreach ($res->getBlobs() as $blob) {
            $foo = $foo + 1;
            $name = $blob->getName();
            $prop = $blob->getProperties();
            $date = $prop->getLastModified()->setTimezone(new DateTimeZone('Asia/Jakarta'))->format("Y-m-d H:i:s");

            $n = explode('.', $name);
            ?>
            <tr>
                <td><?php echo $foo ?>.</td>
                <td width="200px"><?php echo $n[0] ?></td>
                <td>
                    <?php echo $pathinfo = pathinfo($blob->getUrl(), PATHINFO_EXTENSION); ?>
                </td>
                <td width="200px">
                    <?php echo $prop->getContentMD5() ?>
                </td>
                <td>
                    <?php echo $date ?>
                </td>
                <td>
                    <form action="" method="post" onsubmit="return confirm('Are you sure want to delete?');">
                        <button name="delete" value="<?php echo $blob->getName() ?>">delete!!!</button>
                    </form>
                </td>

                <td>
                    <form action="analyze.php" method="post">
                        <?php
                        $pathinfo = pathinfo($blob->getUrl(), PATHINFO_EXTENSION);
                        if ($pathinfo === "jpg" OR $pathinfo == "jpeg" OR $pathinfo == "png") {
                            ?>
                            <input type="hidden" name="link" value="<?php echo $blob->getUrl() ?>">
                            <input type="submit" value="Analyze Image">
                            <?php
                        } else {
                            ?>
                            <input type="submit" name="submit" value="Analyze Image" disabled>
                            <?php
                        }
                        ?>
                    </form>
                </td>
                <td>
                    <button onclick="window.location.href = '<?php echo $blob->getUrl() ?>';">DOWNLOAD</button>
            </tr>

            <?php
        }
        $listBlobsOptions->setContinuationToken($res->getContinuationToken());
    } while ($res->getContinuationToken());
    if (sizeof($res->getBlobs()) == 0) {
        echo "<tr>
    <td colspan='4'><font color='red'>no files here</font></td>
</tr>";
    }
    echo "</table>";


    ?>
    <br>
    Total Files : <?php echo sizeof($res->getBlobs()) ?>
    <br>
    <hr>
    <h1 id="upload"><font color="red" >Upload things here... </font></h1>
    <form action="" method="post" enctype="multipart/form-data">
        upload file : <input type="file" name="firefire" id="firefire" required><br><br>
        <input type="submit" name="upload" value="UPLOAD!">
    </form>
    <?php
    if (isset($_POST['upload'])) {
        $fileToUpload = strtolower($_FILES["firefire"]["name"]);
        $content = fopen($_FILES["firefire"]["tmp_name"], "r");
        $blobClient->createBlockBlob($container, $fileToUpload, $content);
        unset($_POST);
        echo "<script>alert('file uploaded')</script>;";
        echo '<meta http-equiv="refresh" content="1">';
    }
    if (isset($_POST['delete'])) {
        $filetodelete = $_POST['delete'];
        $blobClient->deleteBlob($container, $filetodelete);
        echo "<script>alert('File .$filetodelete. deleted');</script>";
        unset($_POST);
        echo "<script>alert('file deleted')</script>;";
        echo '<meta http-equiv="refresh" content="1">';
    }
}
else{
    echo "kamu tidak login";
    ?>
     <form action="" method="post">
     <label for="pw">Password : </label>
                        <input type="password" id="pw" name="pw">
                        <input type="submit" value="login">
                    </form>
        <?php
        if(isset($_POST['pw'])){
            //111000000000000000000002139120312039215180% protection
            if($_POST['pw']=="12345"){
                $_SESSION['asdlwgqogwegqlejgqienglwras.ndknawbklGIJlvbwRLGREWLBRIJL3BWRILV Lv iv weale vwl....'] = true;
                echo '<meta http-equiv="refresh" content="1">';
            }
            else{
                echo "<script>alert('maaf password salah')</script>";
            }
        }
}
?>