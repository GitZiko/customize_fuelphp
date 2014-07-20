<?php echo "{% block contents %}\n" ?>
<h2>Listing <span class='muted'><?php echo \Str::ucfirst($plural_name); ?></span></h2>
<br>
<?php echo "{% if {$plural_name} %}"; ?>
<table class="table table-striped">
    <thead>
        <tr>
<?php foreach ($fields as $field): ?>
            <th><?php echo 'ID'; ?></th>
            <th><?php echo \Inflector::humanize($field['name']); ?></th>
<?php endforeach; ?>
            <th>Operation</th>
        </tr>
    </thead>
    <tbody>
<?php echo '{%'; ?> for item in <?php echo $plural_name ?><?php echo ' %}'."\n"; ?>
        <tr>
<?php foreach ($fields as $field): ?>
            <td><?php echo '{{'; ?> item<?php echo '.id'; echo ' }}'; ?></td>
            <td><?php echo '{{'; ?> item<?php echo '.'.$field['name']; echo ' }}'; ?></td>
<?php endforeach; ?>
            <td>
                <div class="btn-toolbar">
                    <div class="btn-group">
                        <?php echo '{{'; ?> html_anchor('<?php echo $uri; ?>/view/' ~ item.id, '<i class="icon-eye-open"></i> View', {'class' : 'btn btn-small btn-primary'}) <?php echo '}}'."\n"; ?>
                        <?php echo '{{'; ?> html_anchor('<?php echo $uri; ?>/edit/' ~ item.id, '<i class="icon-wrench"></i> Edit', {'class' : 'btn btn-small btn-warning'}) <?php echo '}}'."\n"; ?>
                        <?php echo '{{'; ?> html_anchor('<?php echo $uri; ?>/delete/' ~ item.id, '<i class="icon-trash icon-white"></i> Delete', {'class' : 'btn btn-small btn-danger', 'onclick' : "return confirm('Are you sure?')"}) <?php echo '}}'."\n"; ?>
                    </div>
                </div>
            </td>
        </tr>
<?php echo '{% endfor %}'."\n"; ?>
    </tbody>
</table>
<?php echo '{% else %}'; ?>
<p>No <?php echo \Str::ucfirst($plural_name); ?>.</p>
<?php echo '{% endif %}'; ?>
<p><?php echo '{{'; ?> html_anchor('<?php echo $uri; ?>/create', 'Add new <?php echo \Inflector::humanize($singular_name); ?>', {'class' : 'btn btn-success'}) <?php echo '}}'; ?></p>
<?php echo "{% endblock %}\n" ?>