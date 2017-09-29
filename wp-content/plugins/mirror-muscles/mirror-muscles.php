<?php
/*
Plugin Name: Mirror Muscles Plugin
Description: -/-
Author: sk65cool
Version: 1.0
*/

/*  Copyright 2015  SK65COOL (email : sk65cool {at} gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

function add_cron_file_path()
{
    global $wpdb;
    $settings = array(
                      'mm_cron_file_path'=>plugin_dir_path( __FILE__ ).'transformation_winner_cron.php?key='.md5(NONCE_SALT),
                      'mm_cron_file_url'=>plugin_dir_url( __FILE__ ).'transformation_winner_cron.php?key='.md5(NONCE_SALT)
                    );

                    update_option("mm_cron_file_path", $settings);

}

register_activation_hook(__FILE__, 'add_cron_file_path');



function bfc_results_table_install()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'bfc_results'; // do not forget about tables prefix

    // sql to create your table
    // NOTICE that:
    // 1. each field MUST be in separate line
    // 2. There must be two spaces between PRIMARY KEY and its name
    //    Like this: PRIMARY KEY[space][space](id)
    // otherwise dbDelta will not work
    $sql = "CREATE TABLE " . $table_name . " (
      id int(11) NOT NULL AUTO_INCREMENT,
      user_id int(11) NOT NULL,
      gender varchar(255) NOT NULL,
      age int(11) UNSIGNED NOT NULL,
      weight float(10,2) NOT NULL,
      chest int(11) UNSIGNED NOT NULL,
      axilla int(11) UNSIGNED NOT NULL,
      triceps int(11) UNSIGNED NOT NULL,
      subscapular int(11) UNSIGNED NOT NULL,
      abdominal int(11) UNSIGNED NOT NULL,
      suprailiac int(11) UNSIGNED NOT NULL,
      thigh int(11) UNSIGNED NOT NULL,
      units varchar(255) NOT NULL,
      fatmass float(10,2) NOT NULL,
      leanmass float(10,2) NOT NULL,
      bodyfat float(10,2) NOT NULL,
      category varchar(255) NOT NULL,
      added timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      PRIMARY KEY  (id)
    );";

    // we do not execute sql directly
    // we are calling dbDelta which cant migrate database
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

}

register_activation_hook(__FILE__, 'bfc_results_table_install');



function onerepmax_results_table_install()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'onerepmax_results';
    $sql = "CREATE TABLE " . $table_name . " (
      id int(11) NOT NULL AUTO_INCREMENT,
      user_id int(11) NOT NULL,
      exercise varchar(255) NOT NULL,
      weight varchar(255) NOT NULL,
      repeats int(2) NOT NULL,
      added timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      PRIMARY KEY  (id)
    );";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

}

register_activation_hook(__FILE__, 'onerepmax_results_table_install');




function members_relations_table_install()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'members_connections';

    $sql = "CREATE TABLE ".$table_name." (
        id int(11) NOT NULL AUTO_INCREMENT,
        request_sender_id int(11) NOT NULL,
        request_reciver_id int(11) NOT NULL,
        status text NOT NULL,
        parq int(11) NOT NULL,
        added timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    );";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

}

register_activation_hook(__FILE__, 'members_relations_table_install');


function food_diary_table_install()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'food_diary'; // do not forget about tables prefix

    $sql = "CREATE TABLE " . $table_name . " (
        id int(11) NOT NULL AUTO_INCREMENT,
        diary_uniq_id varchar(255) NOT NULL,
        diary_name varchar(255) NOT NULL,
        user_id int(11) NOT NULL,
        meal_row int(11) NOT NULL,
        ingredient_name varchar(255) NOT NULL,
        ingredient_calories varchar(255) NOT NULL,
        ingredient_protein varchar(255) NOT NULL,
        ingredient_carbs varchar(255) NOT NULL,
        ingredient_fats varchar(255) NOT NULL,
        shared tinyint(1) NOT NULL DEFAULT 0,
        updated timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    );";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

}
register_activation_hook(__FILE__, 'food_diary_table_install');


function custom_ingredients_table_install()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'custom_ingredients'; // do not forget about tables prefix

    $sql = "CREATE TABLE " . $table_name . " (
        id int(11) NOT NULL AUTO_INCREMENT,
        user_id int(11) NOT NULL,
        name varchar(255) NOT NULL,
        number_of_units int(11) NOT NULL,
        measurement_description varchar(255) NOT NULL,
        calories varchar(255) NOT NULL,
        fat varchar(255) NOT NULL,
        saturated_fat varchar(255) NOT NULL,
        polyunsaturated_fat varchar(255) NOT NULL,
        monounsaturated_fat varchar(255) NOT NULL,
        trans_fat varchar(255) NOT NULL,
        cholesterol varchar(255) NOT NULL,
        sodium varchar(255) NOT NULL,
        potassium varchar(255) NOT NULL,
        carbohydrate varchar(255) NOT NULL,
        fiber varchar(255) NOT NULL,
        sugar varchar(255) NOT NULL,
        protein varchar(255) NOT NULL,
        vitamin_a varchar(255) NOT NULL,
        vitamin_c varchar(255) NOT NULL,
        calcium varchar(255) NOT NULL,
        iron varchar(255) NOT NULL,
        updated timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    );";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

}
register_activation_hook(__FILE__, 'custom_ingredients_table_install');


function supplements_diary_table_install()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'supplements_diary'; // do not forget about tables prefix

    $sql = "CREATE TABLE " . $table_name . " (
        id int(11) NOT NULL AUTO_INCREMENT,
        diary_uniq_id varchar(255) NOT NULL,
        user_id int(11) NOT NULL,
        diary_name varchar(255) NOT NULL,
        supplement_row int(11) NOT NULL,
        supplement_name varchar(255) NOT NULL,
        supplement_unit varchar(255) NOT NULL,
        supplement_amount int(11) NOT NULL,
        supplement_per_day int(11) NOT NULL,
        shared tinyint(1) NOT NULL DEFAULT 0,
        created timestamp default '0000-00-00 00:00:00', 
        updated timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    );";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

}
register_activation_hook(__FILE__, 'supplements_diary_table_install');


function calendars_schedules_table_install()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'calendars_schedules'; // do not forget about tables prefix

    $sql = "CREATE TABLE " . $table_name . " (
        id int(11) NOT NULL AUTO_INCREMENT,
        user_id int(11) NOT NULL,
        time_row int(11) NOT NULL,
        person_id int(11) NOT NULL,
        workout varchar(255) NOT NULL,
        status varchar(255) NOT NULL,
        other_name varchar(255) NOT NULL,
        other_email varchar(255) NOT NULL,
        shared tinyint(1) NOT NULL DEFAULT 0,
        calendar_date date default '0000-00-00',
        PRIMARY KEY (id)
    );";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

}
register_activation_hook(__FILE__, 'calendars_schedules_table_install');



function spam_requests_table_install()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'spam_requests';

    $sql = "CREATE TABLE " . $table_name . " (
        id int(11) NOT NULL AUTO_INCREMENT,
        sender_id int(11) NOT NULL,
        reciver_id int(11) NOT NULL,
        qty int(11) NOT NULL,
        PRIMARY KEY  (id)
    );";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

}

register_activation_hook(__FILE__, 'spam_requests_table_install');


function sharing_requests_table_install()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'sharing_requests';

    $sql = "CREATE TABLE " . $table_name . " (
        id int(11) NOT NULL AUTO_INCREMENT,
        client_id int(11) NOT NULL,
        trainer_id int(11) NOT NULL,
        result_id int(11),
        status varchar(255) NOT NULL,
        PRIMARY KEY  (id)
    );";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

}

register_activation_hook(__FILE__, 'sharing_requests_table_install');


function parq_table_install()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'parq';

    $sql = "CREATE TABLE " . $table_name . " (
        id bigint(20) NOT NULL AUTO_INCREMENT,
        trainer_id int(11) NOT NULL,
        client_id int(11) NOT NULL,
        client_name varchar(255) NOT NULL,
        client_dob varchar(255) NOT NULL,
        client_address varchar(255) NOT NULL,
        client_postcode varchar(255) NOT NULL,
        client_email varchar(255) NOT NULL,
        client_mobile varchar(255) NOT NULL,
        client_answers text NOT NULL,
        status varchar(255) NOT NULL,
        updated timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY  (id)
    );";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

}
register_activation_hook(__FILE__, 'parq_table_install');



function workout_muscles_table_install()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'workout_muscles';

    $sql = "CREATE TABLE " . $table_name . " (
        id int(11) NOT NULL AUTO_INCREMENT,
        name varchar(255) NOT NULL,
        is_front tinyint(1) NOT NULL DEFAULT 0,
        PRIMARY KEY  (id)
    );";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

}
register_activation_hook(__FILE__, 'workout_muscles_table_install');


function workout_equipment_table_install()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'workout_equipment';

    $sql = "CREATE TABLE " . $table_name . " (
        id int(11) NOT NULL AUTO_INCREMENT,
        name varchar(255) NOT NULL,
        PRIMARY KEY  (id)
    );";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

}
register_activation_hook(__FILE__, 'workout_equipment_table_install');


function workout_exercisecategory_table_install()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'workout_exercisecategory';

    $sql = "CREATE TABLE " . $table_name . " (
        id int(11) NOT NULL AUTO_INCREMENT,
        name varchar(255) NOT NULL,
        PRIMARY KEY  (id)
    );";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

}
register_activation_hook(__FILE__, 'workout_exercisecategory_table_install');


function workout_exercisecomment_table_install()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'workout_exercisecomment';

    $sql = "CREATE TABLE " . $table_name . " (
        id int(11) NOT NULL AUTO_INCREMENT,
        comment text NOT NULL,
        exercise int(11) NOT NULL,
        PRIMARY KEY  (id)
    );";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

}
register_activation_hook(__FILE__, 'workout_exercisecomment_table_install');


function workout_exerciseimage_table_install()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'workout_exerciseimage';

    $sql = "CREATE TABLE " . $table_name . " (
        id int(11) NOT NULL AUTO_INCREMENT,
        image text NOT NULL,
        exercise int(11) NOT NULL,
        PRIMARY KEY  (id)
    );";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

}
register_activation_hook(__FILE__, 'workout_exerciseimage_table_install');


function workout_exercise_table_install()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'workout_exercise';

    $sql = "CREATE TABLE " . $table_name . " (
        id int(11) NOT NULL AUTO_INCREMENT,
        name varchar(255) NOT NULL,
        description text NOT NULL,
        category int(11) NOT NULL,
        muscles text,
        muscles_secondary text,
        equipment text,
        PRIMARY KEY  (id)
    );";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

}
register_activation_hook(__FILE__, 'workout_exercise_table_install');


function workout_logs_table_install()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'workout_logs';

    $sql = "CREATE TABLE " . $table_name . " (
        id int(11) NOT NULL AUTO_INCREMENT,
        uniq_id varchar(255) NOT NULL,
        name varchar(255) NOT NULL,
        user_id int(11) NOT NULL,
        day int(1) NOT NULL,
        exercise_id int(11) NOT NULL DEFAULT 0,
        exercise_name varchar(255) NOT NULL,
        repeats varchar(255) NOT NULL,
        weights varchar(255) NOT NULL,
        client_id int(11) NOT NULL DEFAULT 0,
        shared tinyint(1) NOT NULL DEFAULT 0,
        added timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY  (id)
    );";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

}
register_activation_hook(__FILE__, 'workout_logs_table_install');

function workout_logs_adv_table_install()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'workout_logs_adv';

    $sql = "CREATE TABLE " . $table_name . " (
        id int(11) NOT NULL AUTO_INCREMENT,
        uniq_id varchar(255) NOT NULL,
        name varchar(255) NOT NULL,
        user_id int(11) NOT NULL,
        week int(1) NOT NULL,
        exercise_order varchar(255) NOT NULL,
        exercise_id int(11) NOT NULL DEFAULT 0,
        exercise_name varchar(255) NOT NULL,
        repeats varchar(255) NOT NULL,
        loads varchar(255) NOT NULL,
        rest varchar(255) NOT NULL,
        tempo int(4) NOT NULL,
        client_id int(11) NOT NULL DEFAULT 0,
        shared tinyint(1) NOT NULL DEFAULT 0,
        added timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY  (id)
    );";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
register_activation_hook(__FILE__, 'workout_logs_adv_table_install');


function timetables_table_install()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'timetables'; // do not forget about tables prefix

   $sql = "CREATE TABLE " . $table_name . " (
      id bigint(20) NOT NULL AUTO_INCREMENT,
      user_id int(11) NOT NULL,
      classname varchar(255) NOT NULL,
      classsize int(11) UNSIGNED NOT NULL,
      specialization varchar(255) NOT NULL,
      trainer_id int(11) UNSIGNED NOT NULL,
      date bigint(20) UNSIGNED NOT NULL,
      time bigint(20) UNSIGNED NOT NULL,
      duration bigint(20) UNSIGNED NOT NULL,
      added timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      PRIMARY KEY  (id)
    );";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

}

register_activation_hook(__FILE__, 'timetables_table_install');












require_once('custom-ingredients.php');

require_once('Workouts.php');
