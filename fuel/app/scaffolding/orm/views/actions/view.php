<h2>Viewing <span class='muted'>#<?php echo '{{'; ?> <?php echo $singular_name; ?>.id <?php echo '}}'; ?></span></h2>

<?php foreach ($fields as $field): ?>
<p>
    <strong><?php echo \Inflector::humanize($field['name']); ?>:</strong>
    <?php echo '{{'; ?><?php echo $singular_name.'.'.$field['name']; ?><?php echo '}}'."\n"; ?>
</p>
<?php endforeach; ?>

<?php echo '{{'; ?> html_anchor('<?php echo $uri ?>/edit/'~<?php echo $singular_name; ?>.id, 'Edit') <?php echo '}}'; ?> |
<?php echo '{{'; ?> html_anchor('<?php echo $uri ?>', 'Back') <?php echo '}}'; ?>
