<?php

namespace UtilOil;

class Generate_Scaffold extends \Oil\Generate_Scaffold
{
    public static function forge($args, $subfolder)
    {
        $data = array();

        $subfolder = trim($subfolder, '/');

        if ( ! is_dir(\Package::exists('oil').'views/'.static::$view_subdir.$subfolder))
        {
            throw new Exception('The subfolder for admin templates does not exist or is spelled wrong: '.$subfolder.' ');
        }

        // Go through all arguments after the first and make them into field arrays
        $data['fields'] = array();
        foreach (array_slice($args, 1) as $arg)
        {
            // Parse the argument for each field in a pattern of name:type[constraint]
            preg_match(static::$fields_regex, $arg, $matches);

            if ( ! isset($matches[1]))
            {
                throw new Exception('Unable to determine the field definition for "'.$arg.'". Ensure they are name:type');
            }

            $data['fields'][] = array(
                'name'       => \Str::lower($matches[1]),
                'type'       => isset($matches[2]) ? $matches[2] : 'string',
                'constraint' => isset($matches[4]) ? $matches[4] : null
            );
        }

        $name = array_shift($args);

        // Replace / with _ and classify the rest. DO NOT singularize
        $controller_name = \Inflector::classify(static::$controller_prefix.str_replace(DS, '_', $name), false);

        // Replace / with _ and classify the rest. Singularize
        $model_name = \Inflector::classify(static::$model_prefix.str_replace(DS, '_', $name), ! \Cli::option('singular'));

        // Either foo or folder/foo
        $view_path = $controller_path = str_replace(
            array('_', '-'),
            DS,
            \Str::lower($controller_name)
        );

        // Models are always singular, tough!
        $model_path = str_replace(
            array('_', '-'),
            DS,
            \Str::lower($model_name)
        );

        // uri's have forward slashes, DS is a backslash on Windows
        $uri = str_replace(DS, '/', $controller_path);

        $data['include_timestamps'] = ( ! \Cli::option('no-timestamp', false));

        // If a folder is used, the entity is the last part
        $name_parts = explode(DS, $name);
        $data['singular_name'] = \Cli::option('singular') ? end($name_parts) : \Inflector::singularize(end($name_parts));
        $data['plural_name'] = \Cli::option('singular') ? $data['singular_name'] : \Inflector::pluralize($data['singular_name']);

        $data['table'] = \Inflector::tableize($model_name);
        $data['controller_parent'] = static::$controller_parent;

        /** \Oil\Generate the Migration **/
        $migration_args = $args;

        // add timestamps to the table if needded
        if ($data['include_timestamps'])
        {
            if (\Cli::option('mysql-timestamp', false))
            {
                $migration_args[] = 'created_at:date:null[1]';
                $migration_args[] = 'updated_at:date:null[1]';
            }
            else
            {
                $migration_args[] = 'created_at:int:null[1]';
                $migration_args[] = 'updated_at:int:null[1]';
            }
        }
        $migration_name = \Cli::option('singular') ? \Str::lower($name) : \Inflector::pluralize(\Str::lower($name));
        array_unshift($migration_args, 'create_'.$migration_name);
        \Oil\Generate::migration($migration_args, false);

        // Merge some other data in
        $data = array_merge(compact(array('controller_name', 'model_name', 'model_path', 'view_path', 'uri')), $data);

        /** Generate the Model **/
        $model = \View::forge(static::$view_subdir.$subfolder.'/model', $data);

        \Oil\Generate::create(
            APPPATH.'classes/model/'.$model_path.'.php',
            $model,
            'model'
        );

        /** Generate the Controller **/
        $controller = \View::forge(static::$view_subdir.$subfolder.'/controller', $data);

        $controller->actions = array(
            array(
                'name'   => 'index',
                'params' => '',
                'code'   => \View::forge(static::$view_subdir.$subfolder.'/actions/index', $data),
            ),
            array(
                'name'   => 'view',
                'params' => '$id = null',
                'code'   => \View::forge(static::$view_subdir.$subfolder.'/actions/view', $data),
            ),
            array(
                'name'   => 'create',
                'params' => '',
                'code'   => \View::forge(static::$view_subdir.$subfolder.'/actions/create', $data),
            ),
            array(
                'name'   => 'edit',
                'params' => '$id = null',
                'code'   => \View::forge(static::$view_subdir.$subfolder.'/actions/edit', $data),
            ),
            array(
                'name'   => 'delete',
                'params' => '$id = null',
                'code'   => \View::forge(static::$view_subdir.$subfolder.'/actions/delete', $data),
            ),
        );

        \Oil\Generate::create(
            APPPATH.'classes/controller/'.$controller_path.'.php',
            $controller,
            'controller'
        );

        // do we want csrf protection in our forms?
        $data['csrf'] = \Cli::option('csrf') ? true : false;

        // Create each of the views
        foreach (array('index', 'view', 'create', 'edit', '_form') as $view)
        {
            \Oil\Generate::create(
                APPPATH.'views/'.$controller_path.'/'.$view.'.twig',
                \View::forge(APPPATH.'scaffolding/orm/views/actions/'.$view.'.php', $data),
                'view'
            );
        }

        // Add the default template if it doesnt exist
        if ( ! is_file($app_template = APPPATH.'views/template.php'))
        {
            // check if there's a template in app, and if so, use that
            if (is_file(APPPATH.'views/'.static::$view_subdir.$subfolder.'/views/template.php'))
            {
                \Oil\Generate::create($app_template, file_get_contents(APPPATH.'views/'.static::$view_subdir.$subfolder.'/views/template.php'), 'view');
            }
            else
            {
                \Oil\Generate::create($app_template, file_get_contents(\Package::exists('oil').'views/'.static::$view_subdir.'template.php'), 'view');
            }
        }

        \Oil\Generate::build();
    }

}

/* End of file oil/classes/scaffold.php */
