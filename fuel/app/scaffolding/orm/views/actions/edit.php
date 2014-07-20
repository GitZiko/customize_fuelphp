<?php echo "{% block contents %}\n" ?>
<h2>Editing <span class='muted'><?php echo \Str::ucfirst($singular_name); ?></span></h2>
<br>
<?php echo '{%'; ?> include '<?php echo $view_path; ?>/_form.twig' <?php echo '%}'."\n"; ?>
<p>
    <?php echo '{{'; ?> html_anchor('<?php echo $uri; ?>/view/' ~ <?php echo $singular_name; ?>.id, 'View') <?php echo ' }}'; ?> |
    <?php echo '{{'; ?> html_anchor('<?php echo $uri; ?>', 'Back') <?php echo '}}'."\n"; ?>
</p>
<?php echo "{% endblock %}\n" ?>