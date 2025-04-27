<?php
// Get the garage reference from the URL (e.g., ?garage_ref=GAR-00001)
$garage_ref = isset($_GET['garage_ref']) ? sanitize_text_field($_GET['garage_ref']) : null;
// var_dump($garage_ref);

if ($garage_ref) {
  // Query posts of type 'garage' where the ACF field 'garage_ref' matches the URL value
  $args = array(
    'post_type' => 'garage', // Replace with your custom post type if different
    'meta_query' => array(
      array(
        'key' => 'garage_ref', // ACF field storing the reference
        'value' => $garage_ref,
        'compare' => '='
      )
    )
  );

  $garage_query = new WP_Query($args);

  if ($garage_query->have_posts()) {
    while ($garage_query->have_posts()) {
      $garage_query->the_post();
      $garage_post = get_post();

      // For "Ville": Convert Term ID to term name
      $ville_id = get_field('Ville', $garage_post->ID);
      $ville_term = $ville_id ? get_term($ville_id) : null;
      $ville_text = ($ville_term && !is_wp_error($ville_term)) ? $ville_term->name : '';

      // For "domaines": Convert an array of Term IDs to a comma-separated list of term names
      $domaines = get_field('domaines', $garage_post->ID);
      $domaines_names = array();
      if (is_array($domaines)) {
        foreach ($domaines as $term_id) {
          $term = get_term($term_id);
          if ($term && !is_wp_error($term)) {
            $domaines_names[] = $term->name;
          }
        }
        $domaines_display = implode(', ', $domaines_names);
      } else {
        // If not an array, assume it's a single term ID.
        $term = get_term($domaines);
        $domaines_display = ($term && !is_wp_error($term)) ? $term->name : $domaines;
      }

      // For "zone": Convert Term ID to term name
      $zone_id = get_field('zone', $garage_post->ID);
      $zone_term = $zone_id ? get_term($zone_id) : null;
      $zone_text = ($zone_term && !is_wp_error($zone_term)) ? $zone_term->name : $zone_id;

      // Other fields
      $image = get_field('image', $garage_post->ID); // Assuming this is an image field
      $adress = get_field('adresse', $garage_post->ID);
      $permalink = get_permalink($garage_post->ID);
?>
<div class="w-full flex flex-col items-center bg-white border border-gray-200 rounded-lg shadow-sm md:flex-row">
  <?php if ($image) : ?>
  <div class="w-full md:w-64 p-4">
    <a href="<?php echo esc_url($permalink); ?>">
      <img class="w-full h-96 md:h-60 md:w-60 object-cover" src="<?php echo esc_url($image['url']); ?>"
        alt="<?php echo esc_attr($image['alt']); ?>">
    </a>
  </div>
  <?php endif; ?>
  <div class="flex flex-col justify-between p-4 leading-normal">
    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900">
      <a href="<?php echo esc_url($permalink); ?>"><?php echo esc_html($garage_post->post_title); ?></a>
    </h5>
    <p class="mb-3 font-normal text-gray-700">
      <strong>Ville:</strong> <?php echo esc_html($ville_text); ?><br>
      <strong>Domaines:</strong> <?php echo esc_html($domaines_display); ?><br>
      <strong>Adress:</strong> <?php echo esc_html($adress); ?> <br>
      <strong>Zone:</strong> <?php echo esc_html($zone_text); ?>
    </p>
  </div>
</div>
<?php
    }
    wp_reset_postdata();
  } else {
    echo '<p class="error">Garage not found.</p>';
  }
}