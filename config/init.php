<?php

spl_autoload_register('require_class');

$control = \kernel\Kernel::getControllersControl();

$site = $control->makeControllerScalable("SiteNavigation");

