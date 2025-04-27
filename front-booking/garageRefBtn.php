<?php
$acf_link = get_field('garage_ref'); // Replace with your actual ACF field name

if (!empty($acf_link)) {
  echo '<a href="https://fixidev.com/RDV?garage_ref=' . $acf_link . '"><button
    style="width: 100%;padding: 10px 20px;margin:auto;">Voir plus d’horaires</button></a>';
}