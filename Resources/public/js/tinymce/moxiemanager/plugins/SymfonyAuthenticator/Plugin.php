<?php

// Пока не работает
/*$loader = require_once $_SERVER['DOCUMENT_ROOT'].'/../app/bootstrap.php.cache';

require_once $_SERVER['DOCUMENT_ROOT'].'/../app/AppKernel.php';

$kernel = new AppKernel('dev', true);
$kernel->loadClassCache();
$kernel->boot();
$container = $kernel->getContainer();
$security = $container->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED');*/

/**
 * This class handles MoxieManager SymfonyAuthenticator.
 *
 * @author Mikhail Bakulin <mikemadweb@gmail.com>
 */
class MOXMAN_SymfonyAuthenticator_Plugin implements MOXMAN_Auth_IAuthenticator
{
    private $session;

    public function __construct($session)
    {
        $this->session = $session;
    }
    public function authenticate(MOXMAN_Auth_User $user)
    {

        // Is the user authenticated ?
        if ($this->session != '') {
            // User is logged in
            return true;
        } else {
            return false;
        }
    }

    public function login(MOXMAN_Auth_User $user) {
        return true;
    }

    public function logout(MOXMAN_Auth_User $user) {
    }
}
$session = '';
if (isset($_SESSION['_sf2_attributes']['_security_main'])) {
    $session = $_SESSION['_sf2_attributes']['_security_main'];
}
MOXMAN::getAuthManager()->add("SymfonyAuthenticator", new MOXMAN_SymfonyAuthenticator_Plugin($session));