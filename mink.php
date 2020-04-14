<?php
/**
 * @file
 * mink.php
 */

use Behat\Mink\Session;
use DMore\ChromeDriver\ChromeDriver;

require __DIR__.'/vendor/autoload.php';

//$driver = new \Behat\Mink\Driver\GoutteDriver();
$driver = new ChromeDriver(
    'http://localhost:9222',
    null,
    null
);

// Think of it as a browser tab
$session = new Session($driver);
$session->start();

$session->visit('http://jurassicpark.wikia.com');

var_dump($session->getStatusCode(), $session->getCurrentUrl());

// Imagine it like JQuery or DOM
$page = $session->getPage();

var_dump(substr($page->getText(), 0, 75));

// Node element, extension of the JQuery DOM similarities
$header = $page->find('css', '.wds-community-header');

$pageTitle = $header->find('css', '.wds-community-header__top-container .wds-community-header__sitename a');
var_dump($pageTitle->getText());

$siteNavigation = $header->find('css', '.wds-community-header__local-navigation .wds-tabs');

// Finding links via css
$linkElementFirst = $siteNavigation->find('css', 'li a');
var_dump($linkElementFirst->getText());

// Finding links via readable text on the page
// Named selectors like below can find: link, fields buttons
$gamesLink = $siteNavigation->findLink('Games');
var_dump($gamesLink->getAttribute('href'));
$gamesLink->click();

var_dump($session->getCurrentUrl());

// <label for="field">Label Text</label>
//$field = $siteNavigation->findField('Label Text');

// <button>Save</button>
// <button>Save something else</button> <-- fuzzy matching
//$button = $siteNavigation->findButton('Save');

$session->stop();
