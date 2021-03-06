<?php

$settingnames = array(
   'trayicon' => array('trayicon',2),
   'extensions' => array('extensions',2),
   'theme' => array('docked',1),
   'color' => array('color1',1)
);

if(!isset($error)){
   $error='';
}
$sql = "select * from cometchat_settings";
if($query = sql_query($sql, array(), 1)){
   $error .= sql_error($dbh);
}

while($oldsettings = sql_fetch_assoc($query)){
   if(array_key_exists($oldsettings['setting_key'], $settingnames)) {
      $settingsarray = array();
      $setting_key = $oldsettings['setting_key'];
      $value = $settingnames[$setting_key][0];
      if($settingnames[$setting_key][1] == 2 && !empty($oldsettings['value'])) {
         $check = 0;
         if($setting_key == 'trayicon' || $setting_key == 'extensions'){
            $settingsarray = unserialize($oldsettings['value']);
            $unsetplugins = array('themechanger','jabber','mobilewebapp');
            foreach ($settingsarray as $key => $value) {
               if(is_array($value)){
                  $search = $key;
               }else{
                  $search = $value;
                  $check = 1;
               }
               if(in_array($search, $unsetplugins)) {
                  unset($settingsarray[$key]);
               }
            }
         }
         if($check){
            $settingsarray = array_values($settingsarray);
         }
         $value = serialize($settingsarray);
      }
      if(!empty($value)) {

         $sql = "WITH upsert AS (UPDATE cometchat_settings SET value = '".$value."', key_type = '".$settingnames[$setting_key][1]."' WHERE setting_key = 'dbversion' RETURNING *) INSERT INTO cometchat_settings (setting_key , value, key_type) SELECT '".$setting_key."', '".$value."', '".$settingnames[$setting_key][1]."' WHERE NOT EXISTS (SELECT * FROM upsert);";
        if(!sql_query($sql, array(), 1)){
            $error .= sql_error($dbh);
         }
      }
   }
}
$sql = ("WITH upsert AS (UPDATE cometchat_settings SET value = '15', key_type = '1' WHERE setting_key = 'dbversion' RETURNING *) INSERT INTO cometchat_settings (setting_key , value, key_type) SELECT 'dbversion', '15', '1' WHERE NOT EXISTS (SELECT * FROM upsert);");
if(!sql_query($sql, array(), 1)){
   $error .= sql_error($dbh);
}
removeCachedSettings($client.'settings');
