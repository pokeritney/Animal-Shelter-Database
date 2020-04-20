<?php

/**
 * Return the user authentication status
 *
 * @return boolean True if a user is logged in, false otherwise
 */
function isLoggedIn()
{
    return isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'];
}

function isAdminUser()
{
    return isset($_SESSION['adminuser']) && $_SESSION['adminuser'];
}

function isEmployee()
{
    return isset($_SESSION['employee']) && $_SESSION['employee'];
}

function isVolunteer()
{
    return isset($_SESSION['volunteer']) && $_SESSION['volunteer'];
}
