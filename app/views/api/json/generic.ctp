<?php
if (@$debug > 1) {
    echo '<xmp>';
}
echo $restJson->serialize($response);
?>