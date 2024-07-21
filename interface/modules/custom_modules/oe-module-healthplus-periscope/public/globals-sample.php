<?php

/**
 * Sample HTML page with display of global settings
 *
 * @package   OpenEMR
 * @link      http://www.open-emr.org
 *
 * @author    Stephen Nielson <stephen@nielson.org>
 * @copyright Copyright (c) 2021 Stephen Nielson <stephen@nielson.org>
 * @license   https://github.com/openemr/openemr/blob/master/LICENSE GNU General Public License 3
 */

// we want to have access to events, the autoloader and our module bootstrap so we include globals here
require_once "../../../../globals.php";
use OpenEMR\Modules\HealthplusPeriscope\Bootstrap;

// Note we have to grab the event dispatcher from the globals kernel which is instantiated in globals.php
$bootstrap = new Bootstrap($GLOBALS['kernel']->getEventDispatcher());
$globalsConfig = $bootstrap->getGlobalConfig();

?>
<html>
<body>
<ul>
    <li>ClientID: <?php echo $globalsConfig->getClientId(); ?></li>
</ul>
<a href="sample-index.php">Back to index</a>
</body>
</html>