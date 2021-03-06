<?php

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$addonfolder = str_replace(DIRECTORY_SEPARATOR.'lang.php','', __FILE__);
$addonarray = explode(DIRECTORY_SEPARATOR, $addonfolder);
$addonname = end($addonarray);
$addontype = rtrim(prev($addonarray),'s');

/* LANGUAGE */

${$addonname.'_language'}['title'] = setLanguageValue('title','Chat',$lang,$addontype,$addonname);
${$addonname.'_language'}['online_users'] = setLanguageValue('online_users','Users Online for Chat',$lang,$addontype,$addonname);
${$addonname.'_language'}['x'] = setLanguageValue('x','X',$lang,$addontype,$addonname);
${$addonname.'_language'}['lobby'] = setLanguageValue('lobby','Lobby',$lang,$addontype,$addonname);
${$addonname.'_language'}['no_users_online'] = setLanguageValue('no_users_online','No users online at the moment.',$lang,$addontype,$addonname);
${$addonname.'_language'}['loggedout'] = setLanguageValue('loggedout','Sorry you have logged out',$lang,$addontype,$addonname);
${$addonname.'_language'}['me'] = setLanguageValue('me','Me',$lang,$addontype,$addonname);
${$addonname.'_language'}['colon'] = setLanguageValue('colon',':  ',$lang,$addontype,$addonname);
${$addonname.'_language'}['close'] = setLanguageValue('close','X',$lang,$addontype,$addonname);
${$addonname.'_language'}['type_message_1'] = setLanguageValue('type_message_1','Type your message',$lang,$addontype,$addonname);
${$addonname.'_language'}['username'] = setLanguageValue('username','Username',$lang,$addontype,$addonname);
${$addonname.'_language'}['password'] = setLanguageValue('password','Password',$lang,$addontype,$addonname);
${$addonname.'_language'}['login'] = setLanguageValue('login','Log in',$lang,$addontype,$addonname);
${$addonname.'_language'}['invalid_login_details'] = setLanguageValue('invalid_login_details','Username and password do not match',$lang,$addontype,$addonname);
${$addonname.'_language'}['blank_user_pass_err'] = setLanguageValue('blank_user_pass_err','Username or password cannot be blank',$lang,$addontype,$addonname);
${$addonname.'_language'}['search_user'] = setLanguageValue('search_user','Search',$lang,$addontype,$addonname);
${$addonname.'_language'}['search_chatroom'] = setLanguageValue('search_chatroom','Search Group',$lang,$addontype,$addonname);
${$addonname.'_language'}['type_message_2'] = setLanguageValue('type_message_2','Type your message',$lang,$addontype,$addonname);
${$addonname.'_language'}['chat'] = setLanguageValue('chat','Chat',$lang,$addontype,$addonname);
${$addonname.'_language'}['chatroom'] = setLanguageValue('chatroom','Group',$lang,$addontype,$addonname);
${$addonname.'_language'}['one_on_one_chat'] = setLanguageValue('one_on_one_chat','One-on-One Chat',$lang,$addontype,$addonname);
${$addonname.'_language'}['chatrooms'] = setLanguageValue('chatrooms','Groups',$lang,$addontype,$addonname);
${$addonname.'_language'}['create_chatroom'] = setLanguageValue('create_chatroom','Create Group',$lang,$addontype,$addonname);
${$addonname.'_language'}['settings'] = setLanguageValue('settings','Settings',$lang,$addontype,$addonname);
${$addonname.'_language'}['check_internet'] = setLanguageValue('check_internet','Unable to connect. Please check your internet connection.',$lang,$addontype,$addonname);
${$addonname.'_language'}['logout'] = setLanguageValue('logout','Logout',$lang,$addontype,$addonname);
${$addonname.'_language'}['edit_status'] = setLanguageValue('edit_status','Edit Status',$lang,$addontype,$addonname);
${$addonname.'_language'}['reason'] = setLanguageValue('reason','Reason',$lang,$addontype,$addonname);
${$addonname.'_language'}['send'] = setLanguageValue('send','Send',$lang,$addontype,$addonname);
${$addonname.'_language'}['confirm_logout'] = setLanguageValue('confirm_logout','Are you sure you want to logout?',$lang,$addontype,$addonname);
${$addonname.'_language'}['joining_chatroom'] = setLanguageValue('joining_chatroom','Joining Group...',$lang,$addontype,$addonname);
${$addonname.'_language'}['report'] = setLanguageValue('report','Report',$lang,$addontype,$addonname);
${$addonname.'_language'}['cancel'] = setLanguageValue('cancel','Cancel',$lang,$addontype,$addonname);
${$addonname.'_language'}['yes'] = setLanguageValue('','Yes',$lang,$addontype,$addonname);
${$addonname.'_language'}['no'] = setLanguageValue('no','No',$lang,$addontype,$addonname);
${$addonname.'_language'}['users_online'] = setLanguageValue('users_online','Users Online',$lang,$addontype,$addonname);
${$addonname.'_language'}['invite'] = setLanguageValue('invite','Invite',$lang,$addontype,$addonname);
${$addonname.'_language'}['today'] = setLanguageValue('today','Today',$lang,$addontype,$addonname);
${$addonname.'_language'}['yesterday'] = setLanguageValue('yesterday','Yesterday',$lang,$addontype,$addonname);
${$addonname.'_language'}['confirm_sending_1'] = setLanguageValue('confirm_sending_1','Confirm Sending',$lang,$addontype,$addonname);
${$addonname.'_language'}['ok'] = setLanguageValue('ok','OK',$lang,$addontype,$addonname);
${$addonname.'_language'}['warning'] = setLanguageValue('warning','Warning',$lang,$addontype,$addonname);
${$addonname.'_language'}['create_1'] = setLanguageValue('create_1','Create',$lang,$addontype,$addonname);
${$addonname.'_language'}['err_creating_cr'] = setLanguageValue('err_creating_cr','Error while creating group',$lang,$addontype,$addonname);
${$addonname.'_language'}['calling'] = setLanguageValue('calling','Calling',$lang,$addontype,$addonname);
${$addonname.'_language'}['incoming_call'] = setLanguageValue('incoming_call','Incoming Call',$lang,$addontype,$addonname);
${$addonname.'_language'}['confirm_sending_2'] = setLanguageValue('confirm_sending_2','Confirm Sending',$lang,$addontype,$addonname);
${$addonname.'_language'}['enter_password_1'] = setLanguageValue('enter_password_1','Please enter a password',$lang,$addontype,$addonname);
${$addonname.'_language'}['blank_username_err'] = setLanguageValue('blank_username_err','Username cannot be blank',$lang,$addontype,$addonname);
${$addonname.'_language'}['invalid_url'] = setLanguageValue('invalid_url','Invalid URL',$lang,$addontype,$addonname);
${$addonname.'_language'}['check_username'] = setLanguageValue('check_username','Check your username',$lang,$addontype,$addonname);
${$addonname.'_language'}['check_password'] = setLanguageValue('check_password','Check your password',$lang,$addontype,$addonname);
${$addonname.'_language'}['check_url'] = setLanguageValue('check_url','Please check the URL',$lang,$addontype,$addonname);
${$addonname.'_language'}['upgrade_message'] = setLanguageValue('upgrade_message','CometChat needs to be upgraded on the site to use this app. In the meanwhile, you can download our Legacy App which is compatible with the version on this site.',$lang,$addontype,$addonname);
${$addonname.'_language'}['register'] = setLanguageValue('register','Register',$lang,$addontype,$addonname);
${$addonname.'_language'}['remember_me'] = setLanguageValue('remember_me','Remember Me',$lang,$addontype,$addonname);
${$addonname.'_language'}['signing_in'] = setLanguageValue('signing_in','Signing in...',$lang,$addontype,$addonname);
${$addonname.'_language'}['could_not_connect'] = setLanguageValue('could_not_connect','Could not connect',$lang,$addontype,$addonname);
${$addonname.'_language'}['no_internet_connection'] = setLanguageValue('no_internet_connection','No internet connection',$lang,$addontype,$addonname);
${$addonname.'_language'}['connection_timeout'] = setLanguageValue('connection_timeout','Connection timeout',$lang,$addontype,$addonname);
${$addonname.'_language'}['done'] = setLanguageValue('done','Done',$lang,$addontype,$addonname);
${$addonname.'_language'}['n_available_on_mobile'] = setLanguageValue('n_available_on_mobile','This feature is not available in Mobile Apps. To use this please login through desktop browser.',$lang,$addontype,$addonname);
${$addonname.'_language'}['home'] = setLanguageValue('home','Home',$lang,$addontype,$addonname);
${$addonname.'_language'}['announcement'] = setLanguageValue('announcement','Announcement',$lang,$addontype,$addonname);
${$addonname.'_language'}['read_more_1'] = setLanguageValue('read_more_1','Read More',$lang,$addontype,$addonname);
${$addonname.'_language'}['read_less_1'] = setLanguageValue('read_less_1','Read Less',$lang,$addontype,$addonname);
${$addonname.'_language'}['file_size_exceeded'] = setLanguageValue('file_size_exceeded','File size limit exceed',$lang,$addontype,$addonname);
${$addonname.'_language'}['msg_received'] = setLanguageValue('msg_received','has sent you a message',$lang,$addontype,$addonname);
${$addonname.'_language'}['select_user'] = setLanguageValue('select_user','Please select atleast one user',$lang,$addontype,$addonname);
${$addonname.'_language'}['invite_your_friends'] = setLanguageValue('invite_your_friends','Invite your friends',$lang,$addontype,$addonname);
${$addonname.'_language'}['block_error_message'] = setLanguageValue('block_error_message','Error while blocking user',$lang,$addontype,$addonname);
${$addonname.'_language'}['unblock_error_message'] = setLanguageValue('unblock_error_message','Error while unblocking user',$lang,$addontype,$addonname);
${$addonname.'_language'}['empty_message'] = setLanguageValue('empty_message','Cannot send empty message',$lang,$addontype,$addonname);
${$addonname.'_language'}['no_contact_selected'] = setLanguageValue('no_contact_selected','No contact selected',$lang,$addontype,$addonname);
${$addonname.'_language'}['no_contacts_found'] = setLanguageValue('no_contacts_found','No contacts found',$lang,$addontype,$addonname);
${$addonname.'_language'}['cannot_send_sms'] = setLanguageValue('cannot_send_sms','This device can not send SMS',$lang,$addontype,$addonname);
${$addonname.'_language'}['vid_already_saved'] = setLanguageValue('vid_already_saved','This video is already saved. Do you want to save it again',$lang,$addontype,$addonname);
${$addonname.'_language'}['sms_ios'] = setLanguageValue('sms_ios',"Hi! I'm using CometChat to talk, share & collaborate with my friends. Join us!\nDownload: http://g.cometchat.com/cometchat-ios",$lang,$addontype,$addonname);
${$addonname.'_language'}['sms_android'] = setLanguageValue('sms_android',"Hi! I'm using CometChat to talk, share & collaborate with my friends. Join us!\nDownload: http://g.cometchat.com/cometchat-android",$lang,$addontype,$addonname);
${$addonname.'_language'}['invite_by_sms'] = setLanguageValue('invite_by_sms','Invite By SMS',$lang,$addontype,$addonname);
${$addonname.'_language'}['to'] = setLanguageValue('to','To',$lang,$addontype,$addonname);
${$addonname.'_language'}['unblock'] = setLanguageValue('unblock','Unblock',$lang,$addontype,$addonname);
${$addonname.'_language'}['set_status_1'] = setLanguageValue('set_status_1','Set status',$lang,$addontype,$addonname);
${$addonname.'_language'}['set_language_1'] = setLanguageValue('set_language_1','Set language',$lang,$addontype,$addonname);
${$addonname.'_language'}['enter_status'] = setLanguageValue('enter_status','Please enter a status',$lang,$addontype,$addonname);
${$addonname.'_language'}['refresh_list'] = setLanguageValue('refresh_list','Refresh list',$lang,$addontype,$addonname);
${$addonname.'_language'}['choose_existing'] = setLanguageValue('choose_existing','Choose Existing',$lang,$addontype,$addonname);
${$addonname.'_language'}['n_headphones_found'] = setLanguageValue('n_headphones_found','No headphones found',$lang,$addontype,$addonname);
${$addonname.'_language'}['switch_camera'] = setLanguageValue('switch_camera','Double-tap to switch camera',$lang,$addontype,$addonname);
${$addonname.'_language'}['share_this_app'] = setLanguageValue('share_this_app','Share This App',$lang,$addontype,$addonname);
${$addonname.'_language'}['you'] = setLanguageValue('you','You',$lang,$addontype,$addonname);
${$addonname.'_language'}['view_member'] = setLanguageValue('view_member','View Member',$lang,$addontype,$addonname);
${$addonname.'_language'}['chatroom_user'] = setLanguageValue('chatroom_user','Users',$lang,$addontype,$addonname);
${$addonname.'_language'}['view_profile'] = setLanguageValue('view_profile','View Profile',$lang,$addontype,$addonname);
${$addonname.'_language'}['video_call'] = setLanguageValue('video_call','Video call',$lang,$addontype,$addonname);
${$addonname.'_language'}['audio_call'] = setLanguageValue('audio_call','Audio call',$lang,$addontype,$addonname);
${$addonname.'_language'}['n_users_available'] = setLanguageValue('n_users_available','No users available in this group',$lang,$addontype,$addonname);
${$addonname.'_language'}['notification_settings'] = setLanguageValue('notification_settings','Notification Settings',$lang,$addontype,$addonname);
${$addonname.'_language'}['show_notifications'] = setLanguageValue('show_notifications','Show notifications',$lang,$addontype,$addonname);
${$addonname.'_language'}['sound'] = setLanguageValue('sound','Sound',$lang,$addontype,$addonname);
${$addonname.'_language'}['vibrate'] = setLanguageValue('vibrate','Vibrate',$lang,$addontype,$addonname);
${$addonname.'_language'}['inapp_sound'] = setLanguageValue('inapp_sound','In-App Sound',$lang,$addontype,$addonname);
${$addonname.'_language'}['inapp_vibrate'] = setLanguageValue('inapp_vibrate','In-App Vibrate',$lang,$addontype,$addonname);
${$addonname.'_language'}['new_announcement'] = setLanguageValue('new_announcement','You have received an announcement',$lang,$addontype,$addonname);
${$addonname.'_language'}['set'] = setLanguageValue('set','Set',$lang,$addontype,$addonname);
${$addonname.'_language'}['complete_action'] = setLanguageValue('complete_action','Complete action using',$lang,$addontype,$addonname);
${$addonname.'_language'}['change_picture'] = setLanguageValue('change_picture','(Tap to change your picture)',$lang,$addontype,$addonname);
${$addonname.'_language'}['edit_status_msg'] = setLanguageValue('edit_status_msg','Status Message',$lang,$addontype,$addonname);
${$addonname.'_language'}['status'] = setLanguageValue('status','Online Status',$lang,$addontype,$addonname);
${$addonname.'_language'}['set_status_msg'] = setLanguageValue('set_status_msg','Set Status message',$lang,$addontype,$addonname);
${$addonname.'_language'}['invite_phone_contacts'] = setLanguageValue('invite_phone_contacts','Invite phone contacts',$lang,$addontype,$addonname);
${$addonname.'_language'}['edit_username'] = setLanguageValue('edit_username','Edit user name',$lang,$addontype,$addonname);
${$addonname.'_language'}['set_username'] = setLanguageValue('set_username','Set user name',$lang,$addontype,$addonname);
${$addonname.'_language'}['set_status_2'] = setLanguageValue('set_status_2','Set status',$lang,$addontype,$addonname);
${$addonname.'_language'}['set_language_2'] = setLanguageValue('set_language_2','Set language',$lang,$addontype,$addonname);
${$addonname.'_language'}['read_more_2'] = setLanguageValue('read_more_2','Read more',$lang,$addontype,$addonname);
${$addonname.'_language'}['read_less_2'] = setLanguageValue('read_less_2','Read less',$lang,$addontype,$addonname);
${$addonname.'_language'}['registering_number'] = setLanguageValue('registering_number','Registering phone number.',$lang,$addontype,$addonname);
${$addonname.'_language'}['website_url'] = setLanguageValue('website_url','Website URL',$lang,$addontype,$addonname);
${$addonname.'_language'}['username_email'] = setLanguageValue('username_email','Username/Email',$lang,$addontype,$addonname);
${$addonname.'_language'}['enter_phone'] = setLanguageValue('enter_phone','Enter Phone',$lang,$addontype,$addonname);
${$addonname.'_language'}['code'] = setLanguageValue('code','Code',$lang,$addontype,$addonname);
${$addonname.'_language'}['register_number'] = setLanguageValue('register_number','Register Number',$lang,$addontype,$addonname);
${$addonname.'_language'}['blank_url_alert'] = setLanguageValue('blank_url_alert','URL cannot be blank.',$lang,$addontype,$addonname);
${$addonname.'_language'}['enter_password_2'] = setLanguageValue('enter_password_2','Please enter your password.',$lang,$addontype,$addonname);
${$addonname.'_language'}['enter_phone_num'] = setLanguageValue('enter_phone_num','Please enter your phone number.',$lang,$addontype,$addonname);
${$addonname.'_language'}['enter_valid_url'] = setLanguageValue('enter_valid_url','Please enter a valid URL. CometChat should be installed at the location.',$lang,$addontype,$addonname);
${$addonname.'_language'}['invalid_username'] = setLanguageValue('invalid_username','The username is invalid',$lang,$addontype,$addonname);
${$addonname.'_language'}['invalid_password'] = setLanguageValue('invalid_password','The password is invalid',$lang,$addontype,$addonname);
${$addonname.'_language'}['invalid_phone_num'] = setLanguageValue('invalid_phone_num','The phone number is invalid',$lang,$addontype,$addonname);
${$addonname.'_language'}['verify_number'] = setLanguageValue('verify_number','Verify your number',$lang,$addontype,$addonname);
${$addonname.'_language'}['verify_phone_num'] = setLanguageValue('verify_phone_num','Verify phone number',$lang,$addontype,$addonname);
${$addonname.'_language'}['enter_number'] = setLanguageValue('enter_number','Enter your number',$lang,$addontype,$addonname);
${$addonname.'_language'}['verify'] = setLanguageValue('verify','Verify',$lang,$addontype,$addonname);
${$addonname.'_language'}['resend_code'] = setLanguageValue('resend_code','Resend Code',$lang,$addontype,$addonname);
${$addonname.'_language'}['wrong_verification_code'] = setLanguageValue('wrong_verification_code','Wrong verification code.',$lang,$addontype,$addonname);
${$addonname.'_language'}['create_profile'] = setLanguageValue('create_profile','Create Profile',$lang,$addontype,$addonname);
${$addonname.'_language'}['creating_profile'] = setLanguageValue('creating_profile','Creating your profile ...',$lang,$addontype,$addonname);
${$addonname.'_language'}['create_2'] = setLanguageValue('create_2','Create',$lang,$addontype,$addonname);
${$addonname.'_language'}['enter_username'] = setLanguageValue('enter_username','Enter your username',$lang,$addontype,$addonname);
${$addonname.'_language'}['username_limit'] = setLanguageValue('username_limit','Username cannot be less than 1 characters.',$lang,$addontype,$addonname);
${$addonname.'_language'}['set_picture'] = setLanguageValue('set_picture','(Tap to set your picture)',$lang,$addontype,$addonname);
${$addonname.'_language'}['invite_contacts'] = setLanguageValue('invite_contacts','Invite Your Contacts',$lang,$addontype,$addonname);
${$addonname.'_language'}['type_a_name'] = setLanguageValue('type_a_name','Type a name.',$lang,$addontype,$addonname);
${$addonname.'_language'}['type_some_msg'] = setLanguageValue('type_some_msg','Type some message..',$lang,$addontype,$addonname);
${$addonname.'_language'}['back'] = setLanguageValue('back','Back',$lang,$addontype,$addonname);
${$addonname.'_language'}['login_with'] = setLanguageValue('login_with','Login with',$lang,$addontype,$addonname);
${$addonname.'_language'}['guest_login'] = setLanguageValue('guest_login','Guest login',$lang,$addontype,$addonname);
${$addonname.'_language'}['login_username_pass'] = setLanguageValue('login_username_pass','Login with username/password',$lang,$addontype,$addonname);
${$addonname.'_language'}['guest_name'] = setLanguageValue('guest_name','Guest Name',$lang,$addontype,$addonname);
${$addonname.'_language'}['guestname_blank_err'] = setLanguageValue('guestname_blank_err','Guest name cannot be blank',$lang,$addontype,$addonname);
${$addonname.'_language'}['use_social_login'] = setLanguageValue('use_social_login','Use Social login',$lang,$addontype,$addonname);
${$addonname.'_language'}['try_a_demo'] = setLanguageValue('try_a_demo','Try a demo',$lang,$addontype,$addonname);
${$addonname.'_language'}['enter_url'] = setLanguageValue('enter_url','Enter url',$lang,$addontype,$addonname);
${$addonname.'_language'}['next'] = setLanguageValue('next','Next',$lang,$addontype,$addonname);
${$addonname.'_language'}['broadcast_message'] = setLanguageValue('broadcast_message','Broadcast Message',$lang,$addontype,$addonname);
${$addonname.'_language'}['select_one_user'] = setLanguageValue('select_one_user','Please select atleast one user before sending.',$lang,$addontype,$addonname);
${$addonname.'_language'}['record_audio_limit'] = setLanguageValue('record_audio_limit','You can not record audio less than 1 sec',$lang,$addontype,$addonname);
${$addonname.'_language'}['typing'] = setLanguageValue('typing','typing...',$lang,$addontype,$addonname);
${$addonname.'_language'}['online'] = setLanguageValue('online','Online',$lang,$addontype,$addonname);
${$addonname.'_language'}['last_seen_at'] = setLanguageValue('last_seen_at','Last seen at',$lang,$addontype,$addonname);
${$addonname.'_language'}['av_broadcast_request'] = setLanguageValue('av_broadcast_request','has sent audio/video broadcast',$lang,$addontype,$addonname);
${$addonname.'_language'}['avcon_request'] = setLanguageValue('avcon_request','has sent audio/video conference',$lang,$addontype,$addonname);
${$addonname.'_language'}['join_av_broadcast'] = setLanguageValue('join_av_broadcast','has invited you to join audio/video broadcast',$lang,$addontype,$addonname);
${$addonname.'_language'}['start_broadcast'] = setLanguageValue('start_broadcast','Start broadcast',$lang,$addontype,$addonname);
${$addonname.'_language'}['start_conference'] = setLanguageValue('start_conference','Start conference',$lang,$addontype,$addonname);
${$addonname.'_language'}['recording'] = setLanguageValue('recording','Recording...',$lang,$addontype,$addonname);
${$addonname.'_language'}['broadcast_ended'] = setLanguageValue('broadcast_ended','This broadcast has ended',$lang,$addontype,$addonname);
${$addonname.'_language'}['read_receipt'] = setLanguageValue('read_receipt','Read receipt',$lang,$addontype,$addonname);
${$addonname.'_language'}['read_receipt_settings'] = setLanguageValue('read_receipt_settings','Read receipt settings',$lang,$addontype,$addonname);
${$addonname.'_language'}['chat_settings'] = setLanguageValue('chat_settings','Chat Settings',$lang,$addontype,$addonname);
${$addonname.'_language'}['last_seen_settings'] = setLanguageValue('last_seen_settings','Last seen settings',$lang,$addontype,$addonname);
${$addonname.'_language'}['last_seen'] = setLanguageValue('last_seen','Last seen',$lang,$addontype,$addonname);
${$addonname.'_language'}['whiteboard_err'] = setLanguageValue('whiteboard_err','Error occured while sharing whiteboard',$lang,$addontype,$addonname);
${$addonname.'_language'}['select_all'] = setLanguageValue('select_all','Select All',$lang,$addontype,$addonname);
${$addonname.'_language'}['deselect_all'] = setLanguageValue('deselect_all','Deselect All',$lang,$addontype,$addonname);
${$addonname.'_language'}['games'] = setLanguageValue('games','Games',$lang,$addontype,$addonname);
${$addonname.'_language'}['single_player_games'] = setLanguageValue('single_player_games','Single Player Games',$lang,$addontype,$addonname);
${$addonname.'_language'}['share_confirmation'] = setLanguageValue('share_confirmation','Do you want to share?',$lang,$addontype,$addonname);
${$addonname.'_language'}['invalid_file_format'] = setLanguageValue('invalid_file_format','File format not supported',$lang,$addontype,$addonname);
${$addonname.'_language'}['today_at'] = setLanguageValue('today_at','today at',$lang,$addontype,$addonname);
${$addonname.'_language'}['rename_chatroom'] = setLanguageValue('rename_chatroom','Rename Group',$lang,$addontype,$addonname);
${$addonname.'_language'}['chatroom_name'] = setLanguageValue('chatroom_name','Group name',$lang,$addontype,$addonname);
${$addonname.'_language'}['renaming'] = setLanguageValue('renaming','Renaming Group',$lang,$addontype,$addonname);
${$addonname.'_language'}['load_more'] = setLanguageValue('renaming','LOAD EARLIER MESSAGES',$lang,$addontype,$addonname);
${$addonname.'_language'}['no_msg'] = setLanguageValue('renaming','No more messages',$lang,$addontype,$addonname);
${$addonname.'_language'}['contact'] = setLanguageValue('contact','CONTACTS',$lang,$addontype,$addonname);
${$addonname.'_language'}['recent'] = setLanguageValue('recent','RECENT',$lang,$addontype,$addonname);
${$addonname.'_language'}['groups'] = setLanguageValue('groups','GROUPS',$lang,$addontype,$addonname);
${$addonname.'_language'}['new_broadcast'] = setLanguageValue('new_broadcast','New Broadcast',$lang,$addontype,$addonname);
${$addonname.'_language'}['new_group'] = setLanguageValue('new_group','New Groups',$lang,$addontype,$addonname);
${$addonname.'_language'}['more'] = setLanguageValue('more','More',$lang,$addontype,$addonname);
${$addonname.'_language'}['update'] = setLanguageValue('update','UPDATE',$lang,$addontype,$addonname);
${$addonname.'_language'}['clear_message_success'] = setLanguageValue('clear_message_success','Successfully Cleared Messages',$lang,$addontype,$addonname);
${$addonname.'_language'}['reported_successfully'] = setLanguageValue('reported_successfully','Reported Successfully',$lang,$addontype,$addonname);
${$addonname.'_language'}['dont_have_an_account'] = setLanguageValue('dont_have_an_account','DON\'T HAVE AN ACCOUNT?',$lang,$addontype,$addonname);

${$addonname.'_language'}['clear_chat'] = setLanguageValue('clear_chat','CLEAR CHAT',$lang,$addontype,$addonname);
${$addonname.'_language'}['block_user'] = setLanguageValue('block_user','BLOCK USER',$lang,$addontype,$addonname);
${$addonname.'_language'}['delete_chatroom'] = setLanguageValue('delete_chatroom','DELETE',$lang,$addontype,$addonname);
${$addonname.'_language'}['rename_chatroom'] = setLanguageValue('rename_chatroom','RENAME',$lang,$addontype,$addonname);
${$addonname.'_language'}['leave_chatroom'] = setLanguageValue('leave_chatroom','LEAVE GROUP',$lang,$addontype,$addonname);
${$addonname.'_language'}['online_status'] = setLanguageValue('online_status','Online Status',$lang,$addontype,$addonname);


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

${$addonname.'_key_mapping'} = array(
	'0'		=>	'title',
	'1'		=>	'online_users',
	'2'		=>	'x',
	'3'		=>	'lobby',
	'4'		=>	'no_users_online',
	'5'		=>	'loggedout',
	'6'		=>	'me',
	'7'		=>	'colon',
	'8'		=>	'close',
	'9'		=>	'type_message_1',
	'10'	=>	'username',
	'11'	=>	'password',
	'12'	=>	'login',
	'13'	=>	'invalid_login_details',
	'14'	=>	'blank_user_pass_err',
	'15'	=>	'search_user',
	'16'	=>	'search_chatroom',
	'17'	=>	'type_message_2',
	'18'	=>	'chat',
	'19'	=>	'chatroom',
	'20'	=>	'one_on_one_chat',
	'21'	=>	'chatrooms',
	'22'	=>	'create_chatroom',
	'23'	=>	'settings',
	'24'	=>	'check_internet',
	'25'	=>	'logout',
	'26'	=>	'edit_status',
	'27'	=>	'reason',
	'28'	=>	'send',
	'29'	=>	'confirm_logout',
	'30'	=>	'joining_chatroom',
	'31'	=>	'report',
	'32'	=>	'cancel',
	'33'	=>	'yes',
	'34'	=>	'no',
	'35'	=>	'users_online',
	'36'	=>	'invite',
	'37'	=>	'today',
	'38'	=>	'confirm_sending_1',
	'39'	=>	'ok',
	'40'	=>	'warning',
	'41'	=>	'create_1',
	'42'	=>	'err_creating_cr',
	'43'	=>	'calling',
	'44'	=>	'incoming_call',
	'45'	=>	'confirm_sending_2',
	'46'	=>	'enter_password_1',
	'47'	=>	'blank_username_err',
	'48'	=>	'invalid_url',
	'49'	=>	'check_username',
	'50'	=>	'check_password',
	'51'	=>	'check_url',
	'52'	=>	'upgrade_message',
	'53'	=>	'register',
	'54'	=>	'remember_me',
	'55'	=>	'signing_in',
	'56'	=>	'could_not_connect',
	'57'	=>	'no_internet_connection',
	'58'	=>	'connection_timeout',
	'59'	=>	'done',
	'60'	=>	'n_available_on_mobile',
	'61'	=>	'home',
	'62'	=>	'announcement',
	'63'	=>	'read_more_1',
	'64'	=>	'read_less_1',
	'65'	=>	'file_size_exceeded',
	'66'	=>	'msg_received',
	'67'	=>	'select_user',
	'68'	=>	'invite_your_friends',
	'69'	=>	'block_error_message',
	'70'	=>	'unblock_error_message',
	'71'	=>	'empty_message',
	'72'	=>	'no_contact_selected',
	'73'	=>	'no_contacts_found',
	'74'	=>	'cannot_send_sms',
	'75'	=>	'vid_already_saved',
	'76'	=>	'sms_ios',
	'77'	=>	'sms_android',
	'78'	=>	'invite_by_sms',
	'79'	=>	'to',
	'80'	=>	'unblock',
	'81'	=>	'set_status_1',
	'82'	=>	'set_language_1',
	'83'	=>	'enter_status',
	'84'	=>	'refresh_list',
	'85'	=>	'choose_existing',
	'86'	=>	'n_headphones_found',
	'87'	=>	'switch_camera',
	'88'	=>	'share_this_app',
	'89'	=>	'you',
	'90'	=>	'view_member',
	'91'	=>	'chatroom_user',
	'92'	=>	'view_profile',
	'93'	=>	'video_call',
	'94'	=>	'audio_call',
	'95'	=>	'n_users_available',
	'96'	=>	'notification_settings',
	'97'	=>	'show_notifications',
	'98'	=>	'sound',
	'99'	=>	'vibrate',
	'100'	=>	'inapp_sound',
	'101'	=>	'inapp_vibrate',
	'102'	=>	'new_announcement',
	'103'	=>	'set',
	'104'	=>	'complete_action',
	'105'	=>	'change_picture',
	'106'	=>	'edit_status_msg',
	'107'	=>	'status',
	'108'	=>	'set_status_msg',
	'109'	=>	'invite_phone_contacts',
	'110'	=>	'edit_username',
	'111'	=>	'set_username',
	'112'	=>	'set_status_2',
	'113'	=>	'set_language_2',
	'114'	=>	'read_more_2',
	'115'	=>	'read_less_2',
	'116'	=>	'registering_number',
	'117'	=>	'website_url',
	'118'	=>	'username_email',
	'119'	=>	'enter_phone',
	'120'	=>	'code',
	'121'	=>	'register_number',
	'122'	=>	'blank_url_alert',
	'123'	=>	'enter_password_2',
	'124'	=>	'enter_phone_num',
	'125'	=>	'enter_valid_url',
	'126'	=>	'invalid_username',
	'127'	=>	'invalid_password',
	'128'	=>	'invalid_phone_num',
	'129'	=>	'verify_number',
	'130'	=>	'verify_phone_num',
	'131'	=>	'enter_number',
	'132'	=>	'verify',
	'133'	=>	'resend_code',
	'134'	=>	'wrong_verification_code',
	'135'	=>	'create_profile',
	'136'	=>	'creating_profile',
	'137'	=>	'create_2',
	'138'	=>	'enter_username',
	'139'	=>	'username_limit',
	'140'	=>	'set_picture',
	'141'	=>	'invite_contacts',
	'142'	=>	'type_a_name',
	'143'	=>	'type_some_msg',
	'144'	=>	'back',
	'145'	=>	'login_with',
	'146'	=>	'guest_login',
	'147'	=>	'login_username_pass',
	'148'	=>	'guest_name',
	'149'	=>	'guestname_blank_err',
	'150'	=>	'use_social_login',
	'151'	=>	'try_a_demo',
	'152'	=>	'enter_url',
	'153'	=>	'next',
	'154'	=>	'broadcast_message',
	'155'	=>	'select_one_user',
	'156'	=>	'record_audio_limit',
	'157'	=>	'typing',
	'158'	=>	'online',
	'159'	=>	'last_seen_at',
	'160'	=>	'av_broadcast_request',
	'161'	=>	'avcon_request',
	'162'	=>	'join_av_broadcast',
	'163'	=>	'start_broadcast',
	'164'	=>	'start_conference',
	'165'	=>	'recording',
	'166'	=>	'broadcast_ended',
	'167'	=>	'read_receipt',
	'168'	=>	'read_receipt_settings',
	'169'	=>	'chat_settings',
	'170'	=>	'last_seen_settings',
	'171'	=>	'last_seen',
	'172'	=>	'whiteboard_err',
	'173'	=>	'select_all',
	'174'	=>	'deselect_all',
	'175'	=>	'games',
	'176'	=>	'single_player_games',
	'177'	=>	'share_confirmation',
	'178'	=>	'invalid_file_format',
	'179'	=>	'today_at',
	'180'	=>	'rename_chatroom',
	'181'	=>	'chatroom_name',
	'182'	=>	'renaming',
	'183'	=>	'load_more',
	'184'	=>	'no_msg',
	'185' 	=>  'yesterday'
);

${$addonname.'_language'} = mapLanguageKeys(${$addonname.'_language'},${$addonname.'_key_mapping'},$addontype,$addonname);
