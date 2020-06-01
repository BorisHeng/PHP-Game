<?php
if($where != ''){
    echo "<script> alert('".$msg."');location.href='$where';</script>";
}
else{
    echo "<script> alert('".$msg."');location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
}
