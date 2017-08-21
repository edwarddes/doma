<?php
  ini_set('display_errors', 1);
	
  error_reporting(E_ALL & ~E_NOTICE);
  
  include_once(dirname(__FILE__) ."/../config.php");
  include_once(dirname(__FILE__) ."/definitions.php");

  // set character encoding
  header('Content-Type: text/html; charset=utf-8');

  // load session
  session_start();
  
  require_once dirname(__FILE__) . '/../lib/Twig-1.34.4/lib/Twig/Autoloader.php';
  Twig_Autoloader::register();
  
  $twigLoader = new Twig_Loader_Filesystem(dirname(__FILE__) ."/../templates");
  $twig = new Twig_Environment($twigLoader);
  $twig->addFilter('i18n', new Twig_Filter_Function('__'));
  $twig->addFunction('getCurrentUser', new Twig_Function_Function('Helper::GetUser'));
  $twig->addFunction('getLoggedInUser', new Twig_Function_Function('Helper::GetLoggedInUser'));
  $twig->addFunction('selfPath', new Twig_Function_Function('Helper::SelfPath'));
  $twig->addFunction('serverPath', new Twig_Function_Function('Helper::ServerPath'));
  $twig->addFunction('globalPath', new Twig_Function_Function('Helper::GlobalPath'));
  $twig->addFunction('encapsulateLink', new Twig_Function_Function('Helper::EncapsulateLink'));
  $twig->addFunction('isLoggedInAsUser', new Twig_Function_Function('Helper::IsLoggedInAsUser'));
  $twig->addFunction('isLoggedInAsAdmin', new Twig_Function_Function('Helper::IsLoggedInAsAdmin'));
  $twig->addFunction('isMapCurrentlyProtected', new Twig_Function_Function('Helper::IsMapCurrentlyProtected'));
  $twig->addFunction('protectedUntilText', new Twig_Function_Function('Helper::ProtectedUntilText'));
  $twig->addFunction('protectedUntilTimeText', new Twig_Function_Function('Helper::ProtectedUntilTimeText'));
  $twig->addFunction('thumbnailInfoText', new Twig_Function_Function('Helper::ThumbnailInfoText'));
  $twig->addFunction('mapDateText', new Twig_Function_Function('Helper::MapDateText'));
  $twig->addFunction('stringToTime', new Twig_Function_Function('Helper::StringToTime'));
  $twig->addFunction('dateToLongString', new Twig_Function_Function('Helper::DateToLongString'));
  $twig->addFunction('showLanguageMenu', new Twig_Function_Function('Helper::ShowLanguages'));
  $twig->addFunction('convertToTime', new Twig_Function_Function('Helper::ConvertToTime'));
  $twig->addFunction('getThumbnailImage', new Twig_Function_Function('Helper::GetThumbnailImage'));
  $twig->addFunction('loginAsXText', new Twig_Function_Function('Helper::LoginAsXText'));
  $twig->addFunction('getLanguageCode', new Twig_Function_Function('Session::GetLanguageCode'));
  $twig->addGlobal('DOMA_VERSION', DOMA_VERSION);
  $twig->addGlobal('_SITE_TITLE', _SITE_TITLE);
  $twig->addGlobal('_SITE_DESCRIPTION',_SITE_DESCRIPTION);
  $twig->addGlobal('GA_TRACKER', GA_TRACKER);
  $twig->addGlobal('USE_GA', USE_GA);
  $twig->addGlobal('SHOW_LANGUAGES_IN_TOPBAR',SHOW_LANGUAGES_IN_TOPBAR);
  $twig->addGlobal('RERUN_APIKEY', RERUN_APIKEY);
  $twig->addGlobal('RERUN_APIURL', RERUN_APIURL);
  $twig->addGlobal('BASE_URL', BASE_URL);
  $twig->addGlobal('THUMBNAIL_HEIGHT', THUMBNAIL_HEIGHT);
  $twig->addGlobal('THUMBNAIL_WIDTH', THUMBNAIL_WIDTH);
  
  // create database if it does not exist
  if(!Helper::DatabaseVersionIsValid()) Helper::Redirect("create.php?redirectUrl=". urlencode($_SERVER["REQUEST_URI"]));
?>