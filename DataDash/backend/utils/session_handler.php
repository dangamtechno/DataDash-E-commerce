<?php
require_once 'session.php';

function startSession() {
    if (!session_id()) {
        session_start();
    }
}

function destroyCurrentSession() {
    destroySession();
    session_unset();
    session_destroy();
}