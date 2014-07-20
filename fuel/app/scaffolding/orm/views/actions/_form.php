<?php echo '{{ form_open({ "class" : "form-horizontal"}) }}' ?>
    <fieldset>
<?php foreach ($fields as $field): ?>
        <div class="form-group">
        <?php echo "{{ form_label('". \Inflector::humanize($field['name']) ."', '{$field['name']}', {'class':'control-label'}) }}\n"; ?>

<?php switch($field['type']):
        case 'text':
            $singular_string = isset($singular_name) ? $singular_name.".".$field['name'] : '';
            echo "\t\t\t\t{{ form_textarea('{$field['name']}', input_post('{$field['name']}', $singular_string), {'class' : 'col-md-8 form-control', 'rows' : 8, 'placeholder' : '".\Inflector::humanize($field['name'])."'}) }}\n";
            break;
        default:
            $singular_string = isset($singular_name) ? $singular_name.".".$field['name'] : '';
            echo "\t\t\t\t{{ form_input('{$field['name']}', input_post('{$field['name']}', $singular_string), {'class' : 'col-md-4 form-control', 'placeholder' : '".\Inflector::humanize($field['name'])."'}) }}\n";
endswitch; ?>
        </div>
<?php endforeach; ?>
        <div class="form-group">
                <label class='control-label'>&nbsp;</label>
                <?php echo '{{'; ?> form_submit('submit', 'Save', {'class' : 'btn btn-primary'}) <?php echo '}}'; ?>
        </div>
    </fieldset>
<?php echo '{{'; ?> form_close() <?php echo '}}'; ?>