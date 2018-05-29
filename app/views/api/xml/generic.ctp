<?php
if (@$debug > 1) {
    echo '<xmp>';
}

echo $restXml->serialize($response);
?>