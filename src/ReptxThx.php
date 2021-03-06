<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$wgExtensionCredits['other'][] = array(
	'path' => __FILE__,
	'name' => 'ReptxThx',
	'author' => array(
		'Fredy Ugarriza',
                'Charles Espinoza'
	),
	'version'  => '0.0.1',
//	'url' => 'https://www.mediawiki.org/wiki/Extension:ReptxThx',
	'descriptionmsg' => '',
	'license-name' => 'GNU General Public License version 2',
);

/* Setup */

$dir = __DIR__;

$wgExtensionMessagesFiles['ExampleExtension'] = __DIR__ . '/reptxThx.i18n.magic.php';

// Register special page
$wgSpecialPages['ReptxThx'] = 'SpecialReptxThx';

// Register files
$wgAutoloadClasses['SpecialReptxThx'] = $dir . '/SpecialReptxThx.php';
$wgAutoloadClasses['ReptxThxHooks'] = $dir . '/ReptxThx.hooks.php';

$wgAutoloadClasses['DbFactory'] = $dir . '/includes/DbFactory.php';
$wgAutoloadClasses['InteractionMapper'] = $dir . '/includes/mapper/InteractionMapper.php';
$wgAutoloadClasses['AbstractMapper'] = $dir . '/includes/mapper/AbstractMapper.php';
$wgAutoloadClasses['UserMapper'] = $dir . '/includes/mapper/UserMapper.php';
$wgAutoloadClasses['PageMapper'] = $dir . '/includes/mapper/PageMapper.php';
$wgAutoloadClasses['PropertiesMapper'] = $dir . '/includes/mapper/PropertiesMapper.php';

$wgAutoloadClasses['AbstractModelElement'] = $dir . '/model/AbstractModelElement.php';
$wgAutoloadClasses['Interaction'] = $dir . '/model/Interaction.php';
$wgAutoloadClasses['ReptxThxUser'] = $dir . '/model/ReptxThxUser.php';
$wgAutoloadClasses['ReptxThxPage'] = $dir . '/model/ReptxThxPage.php';

$wgAutoloadClasses['ReptxThxAlgorithm'] = $dir . '/includes/algorithm/ReptxThxAlgorithm.php';

$wgAutoloadClasses['ReptxThxAlgorithmJob'] = $dir . '/jobs/ReptxThxAlgorithmJob.php';

$wgAutoloadClasses['ReptxThxProperties'] = $dir . '/includes/ReptxThxProperties.php';
$wgAutoloadClasses['ApiQueryReptxThxUser'] = $dir . '/api/ApiQueryReptxThxUser.php';
$wgAutoloadClasses['ApiQueryReptxThxPage'] = $dir . '/api/ApiQueryReptxThxPage.php';


//// Register hooks
//// See also http://www.mediawiki.org/wiki/Manual:Hooks
$wgHooks['LoadExtensionSchemaUpdates'][] = 'ReptxThxHooks::onLoadExtensionSchemaUpdates';

$wgHooks['PageContentSaveComplete'][] = 'ReptxThxHooks::onPageContentSaveComplete';

//Hook which launches whenever an echo event is created and passes the event object as argument
$wgHooks['EchoEventInsertComplete'][] = 'ReptxThxHooks::onEchoEventInsertComplete';

// Extension initialization
$wgExtensionFunctions[] = 'ReptxThxHooks::initExtension';

$wgJobClasses['executeReptxThxAlgorithm'] = 'ReptxThxAlgorithmJob';

$wgAPIListModules['reptxthxuser'] = 'ApiQueryReptxThxUser';
$wgAPIListModules['reptxthxpage'] = 'ApiQueryReptxThxPage';
$wgHooks['ParserFirstCallInit'][] = 'ReptxThxHooks::onParserSetup';
//$wgHooks['MagicWordwgVariableIDs'][] = 'ReptxThxHooks::onRegisterMagicWords';
//$wgHooks['ParserGetVariableValueSwitch'][] = 'ReptxThxHooks::onParserGetVariableValueSwitch';
//$wgHooks['LoadExtensionSchemaUpdates'][] = 'ReptxThxHooks::onLoadExtensionSchemaUpdates';

$executionMinutes = 1;
$executionInteractionCount = 1;

$giveThankWeight = 0.1;
$receiveThankWeight = 0.8;

$tetaR = 1;
$tetaF = 1;
$phiA = 1;
$phiP = 1;
$roF = 0.5;
$roR = 1;
$lambda = 1;


