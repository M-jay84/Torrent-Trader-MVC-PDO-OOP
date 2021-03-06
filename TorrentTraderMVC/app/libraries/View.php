<?php

class View
{

    public static function render($file, $data = [], $page = false)
    {
        if (file_exists('../app/views/' . $file . '.php')) {
            if ($page == 'admin') {
                Style::adminheader('Staff Panel');
                Style::adminnavmenu();
                Style::begin($data['title']);
                require_once "../app/views/" . $file . ".php";
                Style::end();
                Style::adminfooter();
            } elseif ($page == 'user') {
                Style::header($data['title']);
                Style::begin($data['title']);
                require_once "../app/views/" . $file . ".php";
                Style::end();
                Style::footer();
            } else {
                require_once "../app/views/" . $file . ".php";
            }
        } else {
            die('View does not exist');
        }
    }

}