<?php
if (isset($is_printed) && $is_printed !== true)
{
    echo '<body onload="window.print();">';
}
else
{
    echo '<body>';
}
?>
<div class="wrapper">

